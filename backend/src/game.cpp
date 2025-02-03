#include "game.hpp"

bool room::join(socket* conn, std::string name){
    if(started){
        conn->send("started,", uWS::OpCode::TEXT);
        return false;
    }
    players[conn] = std::pair<std::vector<int>, std::pair<int, std::string> >{std::vector<int>(5, 0), std::pair<int, std::string>{id++, name}};
    broadCast("new" + name + ",id" + std::to_string(getId(conn))+ ',');
    return true;
}

int room::lastGuessValue(){
    if(!started){
        return -1;
    }
    return lastGuess.first;
}

std::string room::getCubes(socket* conn){
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

void room::setLastGuess(std::pair<int, socket*> &&p) {
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

void room::leave(socket* conn){
    broadCast("leave" + std::to_string(getId(conn)) + ',');
    players.erase(conn);
}

void room::start(socket* s){
    if(getId(s) == 0){
        started = true;
        shake();
    } else{
        std::cout << s->getRemoteAddressAsText() << " tried to start the game but is not the host" << std::endl;
        s->send("403 - Not the host", uWS::OpCode::TEXT);
    }
}

void room::broadCast(std::string s){
    for(auto it = players.begin(); it != players.end(); ++it){
        it->first->send(s, uWS::OpCode::TEXT);
    }
}

void room::shake(){
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
