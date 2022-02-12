#include <iostream>
#include <string>
#include <vector>
#include <stdlib.h>
#include <time.h>

using namespace std;

class playertype {
    private:
        int cube = 5;
    public:
        int id;
        string name;
        bool hasCube = true;
        bool isAi;
        vector<int> nums = {0, 0, 0, 0, 0};
        playertype(int id_n, string name_n, bool isAi_n = true){
            id = id_n;
            name = name_n;
            isAi = isAi_n;
        }
        void loseCube(){
            --cube;
            cout << name << " lost a cube, and has " << cube << "remaining \n";
            nums.erase(nums.end() - 1);
        }
        void gainCube(){
            if(cube != 5){
                ++cube;
                cout << name << " obtained an extra cube, and has " << cube << " now \n";
                nums.push_back(0);
            } else{
                cout << name << " cannot obtain an extra cube \n";
            }
        }
        int readCube(){
            return cube;
        }
};

void doubt(vector<playertype>& players, int current, int* allnumber, int cpi){
    char a = to_string(current).back();
    int prevId;
    if (cpi == 0){
        prevId = size(players) - 1;
    } else {
        prevId = cpi - 1;
    }
    if(current < *(allnumber + (int)a - 1)){
        players[prevId].loseCube();
        roll(players, allnumber);
    } else {
        players[cpi].loseCube();
        roll(players, allnumber);
    }
}

void equal(vector<playertype>& players, int current, int* allnumber, int cpi){
    
}

void roll(vector<playertype>& players, int* allnumber){
    for(int x = 0; x < size(players); ++x){
        for(int y = 0; y < players[x].readCube(); ++y){
            int a = rand() % 6;
            ++*(allnumber + a);
            players[x].nums[y] = a + 1; 
        }
    }
}

bool isThisBigger(int inp, int last){
    if((to_string(last).back() == '1') != (to_string(inp).back() == '1')){
        if(to_string(last).back() == '1'){
            last = last * 2;
        } else{
            last = last / 2;
        }
        return (inp > last);
    } else{
        return (inp > last);
    }
}

void getGuess(vector<playertype>& players, int &lastOne, int* allnumber, int cpi){
    bool isitstr = false;
    do{
        string current_inp;
        cout << "Enter your guess!\n";
        cin >> current_inp;
        cout << "\n";
        if(lastOne != 10){
            if(current_inp == "doubt" || current_inp == "d"){
                doubt(players, lastOne, allnumber, cpi);
                break;
            } else if (current_inp == "equal" || current_inp == "e"){
                equal(players, lastOne, allnumber, cpi);
                break;
            }
        }
        //check if input is a number
        for (int i = 0; i < current_inp.length(); i++) {
            if (isdigit(current_inp[i]) == false) {
                isitstr = false;
                break;
            } else{
                isitstr = true;
            }
        }
        if(!isitstr){
            cout << "Guess must be a number! \n";
            continue;
        }
        if(current_inp.back() == '7' || current_inp.back() == '8' || current_inp.back() == '9' || current_inp.back() == '0'){
            cout << "Guess last number must be within 1 and 6! \n";
            continue;
        }
        int current = stoi(current_inp);
        if(isThisBigger(current, lastOne)){
            //secondlast = lastOne;
            lastOne = current;
            break;
        } else{
            cout << "Guess must be bigger than " << lastOne << ", or if its last digit is 1 it mustbe bigger than twice its value, or if your guess is one it must be bigger than half its value! \n";
            continue;
        }
    }
    while (isitstr);
}

int main() {
    time_t nTime;
    srand((unsigned) time(&nTime)); //to make random numbers really random 
    bool game = true;
    string x;
    int y;
    int guess = 10;
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
            if(i == 0){
                getGuess(players, guess, allnumber, i);
                
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