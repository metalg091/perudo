#ifndef GAME_H
#define GAME_H
#include <map>
#include <sys/types.h>
#include <vector>
#include <string>

void broadcast(std::string roomId, std::string msg);
void message(void* conn /* should be a socket* */, std::string msg);
std::string getAddr(void* conn);

class room {
public:
	room (int roomId) : roomId(roomId) {}
	~room (){}

	bool join(void* conn, std::string& name);
	int lastGuessValue();
	std::string getCubes(void* conn);
	void setLastGuess(std::pair<int, void*> &&p);
	bool leave(void* conn); // true if room is empty
	void start(void* s);
	void getAllUser();

private:
	bool started = false;
	uint id = 0;
	int roomId;
	std::pair<int, void*> lastGuess; // last guess

	typedef std::map /* player - cube map */ <void*, std::pair<std::vector<int> /* cubes */, std::pair< int /* id */, std::string> /* names */ > > players_t;
	players_t players;

	void shake();
	inline int getId(void* ws){ return players.at(ws).second.first; }
	inline std::string getName(void* ptr) { return players.at(ptr).second.second; }
	inline std::vector<int>& getCubeVec(void* ptr) { return players.at(ptr).first; }
};
#endif
