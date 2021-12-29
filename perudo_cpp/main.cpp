#include <iostream>
#include <string>
#include <list>
using namespace std;

class playertype {
    public:
        int id;
        string name;
        int cube = 5;
        bool HasCube = true;
        int nums[5];
};

int main() {
    string x;
    int y;
    int currentPlayerId = 0;
    int guess = 10;
    int allnumbers[6] = {0, 0, 0, 0, 0, 0};
    cout << "Welcome to perudo c++! \n";
    cout << "Enter your user name \n";
    cin >> x;
    cout << "How many bots?\n";
    cin >> y;
    //setup
    list<playertype> player;
    list<playertype>::iterator it;
    playertype playerobj[y+1];
    playerobj[0].name = x;
    for (int i = 0; i < y+1; i++){
        playerobj[i].id = i;
        player.push_back(playerobj[i]);
    }
    //delete[] playerobj;
    x.clear();
    it = player.begin();
    cout << it->id;
    it++;
    cout << it->id;
    for (int i = 1; it != player.end(); ++i) {
        it->name = "ai" + i;
        ++it;
    }
    for (it = player.begin(); it != player.end(); ++it) {
        cout << it->name;
        cout << "\n";
    }
    //Game begin
    //roll(&player, &allnumbers);
    return 0;
}
//pointers......
//void roll(playertype* player[], int *allnumbers[6]){

//}