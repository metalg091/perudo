#include <iostream>
#include <string>
#include <vector>
using namespace std;

class playertype {
    public:
        int id;
        string name;
        int cube = 5;
        bool HasCube = true;
        vector<int> nums = {0, 0, 0, 0, 0};
};

int main() {
    string x;
    int y;
    int currentPlayerId = 0;
    int guess = 10;
    int allnumber[6] = {0, 0, 0, 0, 0, 0};
    cout << "Welcome to perudo c++! \n";
    cout << "Enter your user name \n";
    cin >> x;
    cout << "How many bots?\n";
    cin >> y;
    //setup
    vector<playertype> players;
    playertype temp;
    temp.id = 0;
    temp.name = x;
    players.push_back(temp);
    for(int i = 1; i < y; ++i){
        temp.id = i;
        temp.name = "ai" + i;
        players.push_back(temp);
    }
    //Game begin
    //roll(&player, &allnumbers);
    return 0;
}

void roll(vector<playertype> players, int* allnumber){
    for(int x = 0; x < size(players); ++x){
        //*allnumber[0] = 2;
    }
}