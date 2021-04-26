using System;
using System.Collections.Generic;
using System.Threading;

namespace perudo
{
    class Program
    {
        static void Main(string[] args)
        {
            int ingame = 6;
            Player player1 = new Player(); //registers 6 players, hardcoded
            Player player2 = new Player();
            Player player3 = new Player();
            Player player4 = new Player();
            Player player5 = new Player();
            Player player6 = new Player();
            var pid = new List<string> ();
            var players = new List<Player> ();
            while (Convert.ToBoolean(Console.ReadLine()))
            {
                SqlHandler.RegisteHandler();
            }
            for (int i = 0; i < 6; i++) //gets did (names) of each player
            {
                Console.WriteLine("Enter player " + Convert.ToInt32(i + 1));
                string a = Console.ReadLine();
                if (a == "")
                {
                    pid.Add("ai"); //nameless player automatically an AI
                }
                else
                {
                    pid.Add(a);
                }
            }
            player1.did = pid[0]; //assign names
            player2.did = pid[1];
            player3.did = pid[2];
            player4.did = pid[3];
            player5.did = pid[4];
            player6.did = pid[5];
            player1.id = 1;
            player2.id = 2;
            player3.id = 3;
            player4.id = 4;
            player5.id = 5;
            player6.id = 6;
            players.Add(player1); //Adds players to a list
            players.Add(player2);
            players.Add(player3);
            players.Add(player4);
            players.Add(player5);
            players.Add(player6);
            Player cplayer = players[0];
            int cpc = 0;
            Player lplayer = null;
            foreach (Player f in players) //debug
            {
                for (int i = 0; i < f.cubes; i++)
                {
                    f.nums.Add(0);
                    Console.WriteLine("num has been added to player" + f.id);
                }
                Console.WriteLine("its the after one " + f.nums);
            }
            int cubesingame = player1.cubes * 6;
            while (ingame > 1) //separate first round cuz lack of lastplayer
            {
                int lastguess = 10; //can't guess less the 1 piece of 1
                bool frun = true; //says the next program that its infact the first run
                int n1 = 0; //stores amount of each numbers
                int n2 = 0;
                int n3 = 0;
                int n4 = 0;
                int n5 = 0;
                int n6 = 0;
                roll.Roll(ref player1,
                          ref player2,
                          ref player3,
                          ref player4,
                          ref player5,
                          ref player6,
                          ref n1,
                          ref n2,
                          ref n3,
                          ref n4,
                          ref n5,
                          ref n6);
                int guessround = 0;
                cplayer = players[cpc];
                if (cplayer.did == "ai") //search for ai players
                {
                    Ai.Aihandler(cubesingame, ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6);
                }
                else
                {
                    Interact.UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                }
                lplayer = cplayer;
                frun = false;
                if (cpc + 1 < ingame)
                {
                    cpc++;
                }
                else
                {
                    cpc = 0;
                }
                cplayer = players[cpc];
                while (guessround < 1)
                {
                    int before = cplayer.cubes;
                    int lastbefore = lplayer.cubes;
                    if (cplayer.did == "ai") // search for ai players
                    {
                        Ai.Aihandler(cubesingame, ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        Interact.UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                    }
                    if (lplayer.cubes < 1) //checks if previous player is out of the game
                    {
                        lplayer.HasCube = false;
                        ingame -= 1;
                        players.Remove(lplayer);
                        cpc = players.IndexOf(cplayer);
                        guessround++;
                        Console.WriteLine(lplayer.id + " is out of the game");
                        cubesingame -= 1;

                    }
                    else if (cplayer.cubes < 1) //checks if current player is out of the game
                    {
                        cplayer.HasCube = false;
                        if (cpc + 1 == ingame)
                        {
                            cpc = 0;
                        }
                        ingame -= 1;
                        players.Remove(cplayer);
                        guessround++;
                        Console.WriteLine(cplayer.id + " is out of the game");
                        cubesingame -= 1;
                    }
                    else if (cplayer.cubes < before) //checks for cube loss
                    {
                        cplayer.nums.RemoveAt(cplayer.cubes);
                        guessround++;
                        Console.WriteLine(cplayer.id + " has only " + cplayer.cubes + " cubes remaining");
                        cubesingame -= 1;
                    }
                    else if (lplayer.cubes < lastbefore) //checks for cube loss
                    {
                        lplayer.nums.RemoveAt(lplayer.cubes);
                        cpc = players.IndexOf(lplayer);
                        guessround++;
                        Console.WriteLine(lplayer.id + " has only " + lplayer.cubes + " cubes remaining");
                        cubesingame -= 1;
                    }
                    else if (cplayer.cubes > before)
                    {
                        guessround++;
                        Console.WriteLine(cplayer.id + " own " + cplayer.cubes + " cubes now");
                    }
                    else
                    {
                        lplayer = cplayer;
                        if (cpc + 1 < ingame)
                        {
                            cpc++;
                        }
                        else
                        {
                            cpc = 0;
                        }
                        cplayer = players[cpc];
                    }
                }
            }
            Console.WriteLine(players[0].id + " has won");
        }

    }
}
