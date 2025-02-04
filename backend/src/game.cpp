#include "game.hpp"
#include <iostream>
#include <string>

bool room::join(void* conn, std::string& name){
	if(started){
		message(conn, "started");
		return false;
	}
	if(players.find(conn) != players.end()){
		message(conn, "already in room");
		return false;
	}
	getAllUser();
	players[conn] = std::pair<std::vector<int>, std::pair<int, std::string> >{std::vector<int>(5, 0), std::pair<int, std::string>{id++, name}};
	broadcast(std::to_string(roomId), "new" + name + ",id" + std::to_string(getId(conn))+ ';');
	return true;
}

void room::getAllUser(){
	std::string msg = "";
	for(players_t::iterator it = players.begin(); it != players.end(); ++it){
		msg += getName(it->first) + ",id" + std::to_string(getId(it->first)) + ';';
	}
	broadcast(std::to_string(roomId), msg);
}

int room::lastGuessValue(){
	if(!started){
		return -1;
	}
	return lastGuess.first;
}

std::string room::getCubes(void* conn){
	if(!started){
		return "cubes11111;";
	}
	std::string s = "cubes";
	for(auto it = players.at(conn).first.begin(); it != players.at(conn).first.end(); ++it){
		s += std::to_string(*it);
	}
	s += ";";
	return s;
}

void room::setLastGuess(std::pair<int, void*> &&p) {
	if(!started){
		return;
	}
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
		msg += ';';
		shake();
	}
	lastGuess = p;
	broadcast(std::to_string(roomId), msg);
}

bool room::leave(void* conn){
	broadcast(std::to_string(roomId), "leave" + std::to_string(getId(conn)) + ';');
	players.erase(conn);
	return players.size() == 0;
}

void room::start(void* conn){
	if(getId(conn) == 0){
		started = true;
		shake();
	} else{
		std::cout << getAddr(conn) << " tried to start the game but is not the host" << std::endl;
		message(conn, "403 - Not the host");
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
		message(it->first, resp);
	}
}
