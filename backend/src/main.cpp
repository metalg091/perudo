#include "uWebSockets/src/App.h"
#include "game.hpp"
#include <unordered_map>

uWS::App *globalApp;

/*void broadcast(std::string s){
    globalApp->publish("broadcast", s, uWS::OpCode::TEXT, false);
}*/

int main() {
    std::unordered_map < int, room > rooms; // room map

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
            if(message.rfind("jr", 0) == 0){
                int roomId;
                try{
                    roomId = std::stoi(static_cast<std::string>(message.substr(2, message.find(',') - 2)));
                }
                catch(std::exception &e){
                    std::cout << ws->getRemoteAddressAsText() << " tried join a room " << " but have not provided valid room id" << std::endl;
                    ws->send("400 - Invalid room id", uWS::OpCode::TEXT);
                    return;
                }
                if( (message.find(',') == std::string::npos) || message.substr(message.find(',')+1).rfind("name", 0) != 0){
                    std::cout << ws->getRemoteAddressAsText() << " tried join room " << roomId << " but have not provided name" << std::endl;
                    ws->send("400 - Name not provided", uWS::OpCode::TEXT);
                    return;
                }
                if(rooms.find(roomId) == rooms.end()){
                    rooms[roomId] = {};
                }
                rooms.at(roomId).join(ws,
                    static_cast<std::string>(message.substr(message.find(',') + 1).substr(4, message.substr(message.find(',')+1).find(',') - 4)));
                ws->getUserData()->roomId = roomId;
            } else if(ws->getUserData()->roomId != -1){
                int roomId = ws->getUserData()->roomId;
                if(rooms.find(roomId) == rooms.end()){
                    ws->send("404 - Room vanished!", uWS::OpCode::TEXT);
                    std::cout << ws->getRemoteAddressAsText() << " got assign a non existent room " << roomId <<  std::endl;
                    return;
                }
                try{
                    if(message.find("guess") != std::string::npos){
                        int guess = std::stoi(static_cast<std::string>(message.substr(message.find("guess") + 5,
                            message.substr(message.find("guess") + 5).find(','))));
                        rooms.at(roomId).setLastGuess(std::pair<int, socket*>(guess, ws));
                    }
                    if(message.find("getcubes") != std::string::npos){
                        ws->send(rooms.at(roomId).getCubes(ws), uWS::OpCode::TEXT);
                    }
                    if(message.find("start") != std::string::npos){
                        rooms.at(roomId).start(ws);
                    }
                } catch(std::exception &e){
                    std::cout << e.what() << std::endl;
                    return;
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
                rooms.at(ws->getUserData()->roomId).leave(ws);
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
