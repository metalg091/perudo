#include <iostream>
#include <string>
#include <vector>
#include <stdlib.h>
#include <time.h>

using namespace std;

class playertype {
    public:
        int id;
        string name;
        int cube = 5;
        bool HasCube = true;
        vector<int> nums = {0, 0, 0, 0, 0};
};

void roll(vector<playertype>& players, int* allnumber){
    for(int x = 0; x < size(players); ++x){
        for(int y = 0; y < 6; ++y){
            int a = rand() % 6;
            ++*(allnumber + a);
            players[x].nums[y] = a + 1; 
        }
    }
}

int main() {
    time_t nTime;
    srand((unsigned) time(&nTime)); //to make random numbers really number 
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
    for(int i = 1; i <= y; ++i){
        temp.id = i;
        temp.name = "ai" + i;
        players.push_back(temp);
    }
    roll(players, allnumber);
    //Game begin
    
    /*for (int y = 0; y < size(players); ++y){
        cout << "player " << y << "cubes are: ";
        for (int x = 0; x < 6; ++x){
            cout << players[y].nums[x] << " ";
        }
        cout << "\n";
    }
    cout << "all the numbers: ";
    for (int x = 0; x < 6; ++x){
        cout << allnumber[x] << " ";
    }*/
    return 0;
}