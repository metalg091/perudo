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
        bool hasCube = true;
        bool isAi;
        vector<int> nums = {0, 0, 0, 0, 0};
        playertype(int id_n, string name_n, bool isAi_n = true){
            id = id_n;
            name = name_n;
            isAi = isAi_n;
        }
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

bool isThisBigger(int inp, int last){
    if((to_string(last)[-1] == '1') != (to_string(inp)[-1] == '1')){
        if(to_string(last)[-1] == '1'){
            last = last * 2;
        } else{
            last = last / 2;
        }
        return (inp > last);
    } else{
        return (inp > last);
    }
}

int getGuess(int lastOne){
    int current;
    cout << "Enter your guess!\n";
    cin >> current;
    cout << "\n";
    if(isThisBigger(current, lastOne)){
        return current;
    } else{
        cout << "Guess must be bigger than " << lastOne << "\n";
        getGuess(lastOne);
    }
    return -1;
}

int main() {
    time_t nTime;
    srand((unsigned) time(&nTime)); //to make random numbers really random 
    bool game = true;
    string x;
    int y;
    int currentPlayerId = 0;
    int guess = 10;
    int prevGuess;
    int allnumber[6] = {0, 0, 0, 0, 0, 0};
    cout << "Welcome to perudo c++! \n";
    cout << "Enter your user name \n";
    cin >> x;
    cout << "How many bots?\n";
    cin >> y;
    //setup
    vector<playertype> players;
    playertype temp(0, x, false);
    players.push_back(temp);
    temp.isAi = false;
    for(int i = 1; i <= y; ++i){
        temp.id = i;
        temp.name = "ai" + i;
        players.push_back(temp);
    }
    roll(players, allnumber);
    //Game begin
    while (game){
        for(int i = 0; i < size(players); ++i){
            prevGuess = guess;
            if(i == 0){
                guess = getGuess(guess);
                if (guess == -1){
                    cout << "error";
                }
            } else{
                //ai()
            }
        }
    }
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