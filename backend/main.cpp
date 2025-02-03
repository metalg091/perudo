#include "uWebsockets/src/App.h"
#include <unordered_map>
#include <vector>
#include <map>

struct PerSocketData {
    std::string clientTopic;
    /* Fill with user data */
};

typedef uWS::WebSocket<false, true, PerSocketData> socket;

uWS::App *globalApp;

/*void broadcast(std::string s){
    globalApp->publish("broadcast", s, uWS::OpCode::TEXT, false);
}*/

class room {
public:
    room () {}
    ~room (){}

    bool join(socket* conn, std::string name){
        if(started){
            return false;
        }
        players[conn] = std::pair<std::vector<int>, std::pair<int, std::string> >{std::vector<int>(5, 0), std::pair<int, std::string>{id++, name}};
        broadCast("new" + name + ",id" + std::to_string(getId(conn))+ ',');
        return true;
    }

    int lastGuessValue(){
        if(!started){
            return -1;
        }
        return lastGuess.first;
    }

    std::string getCubes(socket* conn){
        if(!started){
            return "cubes11111,";
        }
        std::string s = "cubes";
        for(auto it = players.at(conn).first.begin(); it != players.at(conn).first.end(); ++it){
            s += std::to_string(*it);
        }
        s += ",";
        return s;
    }

    void setLastGuess(std::pair<int, socket*> &&p) {
        std::string msg = "guess" + std::to_string(p.first) + ",by" + std::to_string(getId(p.second));
        if(p.first % 10 == 7 || p.first % 10 == 8){
            msg += "res";
            int acc = 0;
            for(players_t::iterator it = players.begin(); it != players.end(); ++it){
                acc += getCubeVec(it->first).at(lastGuess.first);
            }
            if(acc > p.first / 10 || (p.first % 10 == 7 && acc == p.first / 10)){
                msg += "c";
            } else if (p.first % 10 == 8 && acc == p.first / 10){
                msg += "e";
                getCubeVec(p.second).push_back(0);
            } else {
                msg += "f";
                getCubeVec(p.second).pop_back();
            }
            msg += ',';
            shake();
        }
        lastGuess = p;
        broadCast(msg);
    }

    void leave(socket* conn){
        broadCast("leave" + std::to_string(getId(conn)) + ',');
        players.erase(conn);
    }

    void start(socket* s){
        if(getId(s) == 0){
            started = true;
            shake();
        } else{
            std::cout << s->getRemoteAddressAsText() << " tried to start the game but is not the host" << std::endl;
            s->send("403 - Not the host", uWS::OpCode::TEXT);
        }
    }

private:
    void broadCast(std::string s){
        for(auto it = players.begin(); it != players.end(); ++it){
            it->first->send(s, uWS::OpCode::TEXT);
        }
    }
    void shake(){
        for(players_t::iterator it = players.begin(); it != players.end(); ++it){
            std::string resp = "cubes";
            for(std::vector<int>::iterator vit = getCubeVec(it->first).begin(); vit != getCubeVec(it->first).end(); ++vit){
                *vit = rand() % 6 + 1;
                resp += std::to_string(*vit);
            }
            resp += ",";
            it->first->send(resp, uWS::OpCode::TEXT);
        }
    }
    bool started = false;
    uint id = 0;
    std::pair<int, socket*> lastGuess; // last guess

    int getId(socket* ws){ return players.at(ws).second.first; }

    std::string getName(socket* ptr) { return players.at(ptr).second.second; }

    std::vector<int>& getCubeVec(socket* ptr) { return players.at(ptr).first; }

    typedef std::map /* player - cube map */ <socket*, std::pair<std::vector<int> /* cubes */, std::pair< int /* id */, std::string> /* names */ > > players_t;
    players_t players;
};

int main() {
    std::unordered_map < socket*, int > players; // player to room map
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
        },
        .message = [&rooms, &players](socket *ws, std::string_view message, uWS::OpCode opCode) {
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
                players[ws] = roomId;
            } else if(players.find(ws) != players.end()){
                int roomId = players.at(ws);
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
                    if(message.find("start")){
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
        .close = [&rooms, &players](socket *ws, int code, std::string_view /*message*/) {
            if(players.find(ws) != players.end()){
                rooms.at(players.at(ws)).leave(ws);
                players.erase(ws);
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
