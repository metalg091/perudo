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
            cout << name << " lost a cube, and has " << cube << " remaining \n";
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

void debugDisplay(vector<playertype> players, int current, int* allnumber, int cpi){
    for(int i = 0; i<6; ++i){
        cout << *(allnumber + i) << " | ";
    }
    string x;
    cout << "\n";
    cout << "guess: " << current << "\n";
    cout << "current player: " << cpi << "\n";
    cout << "continue?\n";
    cin >> x;
    return;
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

void doubt(vector<playertype>& players, int &current, int* allnumber, int &cpi){
    cout << "Doubt\n";
    debugDisplay(players, current, allnumber, cpi);
    char a = to_string(current).back();
    int prevId;
    if (cpi == 0){
        prevId = size(players) - 1;
    } else {
        prevId = cpi - 1;
    }
    cout << "a: " << a << "\n";
    int real;
    int b = a;
    --b;
    if (b == 0){
        real = *allnumber;
    } else{
        int f = *allnumber;
        cout << "f: " << f << "\n";
        int g = *(allnumber + b); //error here
        cout << "g: " << g << "\n";
        real = f + g;
    }
    cout << "real value " << real << "\n";
    if(current < real){
        players[prevId].loseCube();
        if(players[prevId].readCube() == 0){
            players.erase(players.begin() + prevId);
        }
        cpi = prevId - 1;
    } else {
        players[cpi].loseCube();
        if(players[cpi].readCube() == 0){
            players.erase(players.begin() + prevId);
        }
    }
    current = 10;
    roll(players, allnumber);
}

void equal(vector<playertype>& players, int &current, int* allnumber, int &cpi){
    cout << "Equal\n";
    debugDisplay(players, current, allnumber, cpi);
    char a = to_string(current).back();
    int real;
    if (int(a) == 1){
        real = *allnumber;
    } else{
        real = *allnumber + *(allnumber + (int)a - 1);
    }
    if(current != real){
        players[cpi].loseCube();
        if(players[cpi].readCube() == 0){
            players.erase(players.begin() + cpi);
        }
    } else {
        players[cpi].gainCube();
    }
    current = 10;
    roll(players, allnumber);
    --cpi;
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

void getGuess(vector<playertype>& players, int &lastOne, int* allnumber, int &cpi){
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
                break; //is this right?
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
            lastOne = current;
            break;
        } else{
            cout << "Guess must be bigger than " << lastOne << ", or if its last digit is 1 it mustbe bigger than twice its value, or if your guess is one it must be bigger than half its value! \n";
            continue;
        }
    }
    while (isitstr);
}

void ai(vector<playertype>& players, int &lastOne, int* allnumber, int &cpi){
    char a = to_string(lastOne).back();
    int otherCubesInGame = 0;
    for (int x = 0; x < size(players); ++x){
        otherCubesInGame = otherCubesInGame + players[x].readCube();
    }
    otherCubesInGame = otherCubesInGame - players[cpi].readCube();
    double predict[6] = {0, 0, 0, 0, 0, 0};
    double* ptr = &predict[0];
    predict[0] = otherCubesInGame / 6;
    for(int i = 1; i < 6; ++i){
        predict[i] = 2 * predict[0];
    }
    for(int i = 0; i < players[cpi].readCube(); ++i){
        ++*(ptr + players[cpi].nums[i]);
    }
    int min[6] = {0, 0, 0, 0, 0, 0};
    if(to_string(lastOne).back() == '1'){
        min[0] = (lastOne + 9) / 10;
        for(int i = 1; i < 6; ++i){
            min[i] = ((((lastOne - i) - 1) / 10) * 2) + 1;
        }
    } else {
        min[0] = ((lastOne - 1) / 20) + 1;
        for(int i = 1; i < 6; ++i){
            min[i] = (((lastOne - i) - 1) / 10) + 1;
        }
    }
    double diff[6] = {0, 0, 0, 0, 0, 0};
    double biggestVal = -100;
    int biggestId = 0;
    int nonNegative = 0;
    for(int i = 0; i < 6; ++i){
        diff[i] = predict[i] - min[i];
    }
    for(int i = 0; i<6; ++i){
        if(diff[i] > biggestVal){
            biggestVal = diff[i];
            biggestId = i;
        }
        if(diff[i] > -1){
            ++nonNegative;
        }
    }
    if(nonNegative == 0){
        if(biggestVal == 0){ //chance to equal
            int chances[3]; // 0 -> equal, 1 -> doubt, 2 -> guess
            chances[0] = (players[cpi].readCube() / otherCubesInGame) * 100;
            chances[1] = ((100 - chances[0]) / 4) * 3;
            chances[2] = chances[1] / 3;
            int small = 0;
            int big = 0;
            for(int i = 0; i < 3; ++i){
                if(chances[i] < chances[small]){
                    small = i;
                }
                if(chances[i] > chances[big]){
                    big = i;
                }
            }
            int middle = 0;
            while(middle == small || middle == big){
                ++middle;
            }
            int a = rand() % 101;
            if(a <= chances[small]){
                switch (small)
                    {
                    case 0:
                        equal(players, lastOne, allnumber, cpi);
                        return;
                        break;
                    case 1:
                        doubt(players, lastOne, allnumber, cpi);
                        return;
                        break;
                    case 2:
                        break;
                    }
            } else if(a > chances[small] && a <= chances[middle] + chances[small]) {
                switch (middle)
                    {
                    case 0:
                        equal(players, lastOne, allnumber, cpi);
                        return;
                        break;
                    case 1:
                        doubt(players, lastOne, allnumber, cpi);
                        return;
                        break;
                    case 2:
                        break;
                    }
            } else {
                switch (big)
                    {
                    case 0:
                        equal(players, lastOne, allnumber, cpi);
                        return;
                        break;
                    case 1:
                        doubt(players, lastOne, allnumber, cpi);
                        return;
                        break;
                    case 2:
                        break;
                    }
            }
        } else{ //doubt
            int chances[3] = {15, 80, 5}; // 0 -> equal, 1 -> doubt, 2 -> guess
            int a = rand() % 101;
            if(a > chances[2] + chances[0] && a <= chances[2] + chances[1] + chances[0]){
                doubt(players, lastOne, allnumber, cpi);
                return;
            } else if(a > chances[2] && a <= chances[0] + chances[2]) {
                equal(players, lastOne, allnumber, cpi);
                return;
            }
        }
    } else{ //guess
        if(biggestVal < 5){
            int chances[2]; // 0 -> doubt, 1 -> guess
            chances[1] = biggestVal * 20;
            chances[0] = 100 - chances[1];
            int a = rand() % 101;
            if(chances[0] > a){
                doubt(players, lastOne, allnumber, cpi);
                return;
            }
        }
    }
    //guess mechanic
    int newguess = min[biggestId] * 10 + biggestId + 1;
    bool gut = false;
    do{
        if(isThisBigger(newguess, lastOne)){
            lastOne = newguess;
            gut = true;
            debugDisplay(players, lastOne, allnumber, cpi);
            cout << players[cpi].name << " guessed " << newguess << "\n";
        } else{
            newguess += 10;
        }
    }
    while (!gut);
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
        temp.name = "ai" + to_string(i);
        players.push_back(temp);
    }
    roll(players, allnumber);
    debugDisplay(players, guess, allnumber, 0);
    cout << "\n";
    //Game begin
    while (game){
        for(int i = 0; i < size(players); ++i){
            cout << "cpi: " << i << "\n";
            if(i == 0){
                getGuess(players, guess, allnumber, i);
            } else{
                ai(players, guess, allnumber, i);
            }
        }
        if(size(players) == 1){
            game = false;
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