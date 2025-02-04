#include "uWebSockets/src/App.h"
#include "game.hpp"
#include "uWebSockets/src/WebSocketProtocol.h"
#include <string>
#include <unordered_map>
#include <vector>


struct PerSocketData {
	int roomId;
};
typedef uWS::WebSocket<false, true, PerSocketData> socket;

uWS::App *globalApp;

void broadcast(std::string roomId, std::string msg){
	std::cout << "broadcasting to " << roomId << " " << msg << std::endl;
	globalApp->publish(roomId, msg, uWS::OpCode::TEXT, false);
}

void message(void* conn /* should be a socket* */, std::string msg){
	try{
		static_cast<socket*>(conn)->send(msg, uWS::OpCode::TEXT, false);
	} catch(std::exception &e){
		std::cout << e.what() << std::endl;
	}
}

std::string getAddr(void* conn){
	return static_cast<std::string>(static_cast<socket*>(conn)->getRemoteAddressAsText());
}

struct BinMsg {
    int target;
    union {
        std::string name;
        int guess;
    };

    BinMsg(int t, const std::string& n) : target(t), name(n) {}
    BinMsg(int t, int g) : target(t), guess(g) {}
    ~BinMsg() {
        /*if (target == 1) {
            name.~basic_string(); // i think this would be a double free
        }*/
    }
};

void invalidMessage(socket* ws,const std::string& msg){
	std::cout << ws->getRemoteAddressAsText() << " " << msg << " " << std::endl;
	ws->send("400 - Invalid message", uWS::OpCode::TEXT);
}

bool isRoomReal(const std::unordered_map<int, room*>& rooms, socket* ws, const std::string&& logmsg){
	if(rooms.find(ws->getUserData()->roomId) == rooms.end()){
		invalidMessage(ws, logmsg);
		return false;
	}
	return true;
}

bool hasRoom(const std::unordered_map<int, room*>& rooms, socket* ws, const std::string&& logmsg){
	if(ws->getUserData()->roomId == -1){
		invalidMessage(ws, logmsg);
		return false;
	}
	return true;
}

int main() {
	std::unordered_map < int, room* > rooms; // room map

	uWS::App app = uWS::App().ws<PerSocketData>("/*", {
		/* Settings */
		.compression = uWS::SHARED_COMPRESSOR,
		.maxPayloadLength = 16 * 1024 * 1024,
		.idleTimeout = 120, // longer timeout is fine
		.maxBackpressure = 1 * 1024 * 1024,
		.closeOnBackpressureLimit = false,
		.resetIdleTimeoutOnSend = false,
		.sendPingsAutomatically = true,
		/* Handlers */
		.upgrade = nullptr,
		.open = [](socket *ws) {
			//ws->subscribe("broadcast");
			ws->getUserData()->roomId = -1;
		},
		.message = [&rooms](socket *ws, std::string_view message, uWS::OpCode opCode) {
			if(opCode == uWS::OpCode::BINARY){
				std::vector<uint8_t> data(message.begin(), message.end());
				if(data.size() < 4){
					invalidMessage(ws, "sent an invalid binmsg (<4)");
					return;
				}
				int target = *reinterpret_cast<const int*>(data.data());
				// must declare all variables before switch
				int roomId, guess;
				std::string* name;
				switch (target) {
					case 0: // start
						if(!hasRoom(rooms, ws, "user tried to start a room before joining one") || !isRoomReal(rooms, ws, "user tried to start not existing room")){
							return;
						}
						rooms.at(ws->getUserData()->roomId)->start(ws);
						break;
					case 1: // join room
						if(data.size() < 9){ // 4 bytes for target, 4 bytes for room id, at least 1 byte for name
							invalidMessage(ws, "sent an invalid binmsg (join room)");
							return;
						}
						if(hasRoom(rooms, ws, "joining room despite being in one")){
							return;
						}
						roomId = *reinterpret_cast<const int*>(data.data() + 4);
						if(rooms.find(roomId) == rooms.end()){
							rooms[roomId] = new room(roomId);
						}
						name = new std::string(reinterpret_cast<const char*>(data.data() + 8), data.size() - 8);
						if(rooms.at(roomId)->join(ws, *name)){
							ws->getUserData()->roomId = roomId;
							ws->subscribe(std::to_string(roomId));
						}
						delete name;
						break;
					case 2: //guess
						if(data.size() < 8){
							invalidMessage(ws, "sent an invalid binmsg (guess)");
							return;
						}
						if(!hasRoom(rooms, ws, "tried to guess without being in a room") || !isRoomReal(rooms, ws, "tried to guess in a non existent room")){
							return;
						}
						guess = *reinterpret_cast<const int*>(data.data() + 4);
						rooms.at(ws->getUserData()->roomId)->setLastGuess(std::pair<int, socket*>(guess, ws));
						break;
					case 3: // getcubes
						if(!hasRoom(rooms, ws, "tried to get cubes without being in a room") || !isRoomReal(rooms, ws, "tried to get cubes in a non existent room")){
							return;
						}
						ws->send(rooms.at(ws->getUserData()->roomId)->getCubes(ws), uWS::OpCode::TEXT);
						break;
					default:
						std::cout << ws->getRemoteAddressAsText() << " sent an invalid binmsg (unknown target) " << message << std::endl;
						ws->send("400 - Invalid message", uWS::OpCode::TEXT);
						break;
				}
			}
		},
		.drain = [](socket */*ws*/) {
			/* Check ws->getBufferedAmount() here */
		},
		.ping = [](socket */*ws*/, std::string_view) {
		},
		.pong = [](socket */*ws*/, std::string_view) {
		},
		.close = [&rooms](socket *ws, int code, std::string_view /*message*/) {
			if(ws->getUserData()->roomId != -1){
				if(rooms.at(ws->getUserData()->roomId)->leave(ws)){
					delete rooms.at(ws->getUserData()->roomId);
					rooms.erase(ws->getUserData()->roomId);
				}
			}
		}
	}).listen(9001, [](auto *listen_socket) {
		if (listen_socket) {
			std::cout << "Listening on port " << 9001 << std::endl;
		}
	});
	globalApp = &app;
	app.run();
}
