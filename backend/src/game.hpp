#ifndef GAME_H
#define GAME_H
#include "uWebSockets/src/App.h"
#include "iosfwd"
#include <map>

struct PerSocketData {
    int roomId;
};


typedef uWS::WebSocket<false, true, PerSocketData> socket;

class room {
public:
    room () {}
    ~room (){}

    bool join(socket* conn, std::string name);
    int lastGuessValue();
    std::string getCubes(socket* conn);
    void setLastGuess(std::pair<int, socket*> &&p);
    void leave(socket* conn);
    void start(socket* s);

private:
    void broadCast(std::string s);
    void shake();
    bool started = false;
    uint id = 0;
    std::pair<int, socket*> lastGuess; // last guess

    inline int getId(socket* ws){ return players.at(ws).second.first; }

    inline std::string getName(socket* ptr) { return players.at(ptr).second.second; }

    inline std::vector<int>& getCubeVec(socket* ptr) { return players.at(ptr).first; }

    typedef std::map /* player - cube map */ <socket*, std::pair<std::vector<int> /* cubes */, std::pair< int /* id */, std::string> /* names */ > > players_t;
    players_t players;
};
#endif
