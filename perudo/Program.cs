﻿using System;
using System.Collections.Generic;
using System.Threading;

namespace perudo
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Enter the expected number of players!");
            int ingame = Convert.ToInt32(Console.ReadLine());
            var players = new List<Player>();
            for (int i = 0; i < ingame; i++)
            {
                int u = i + 1;
                players.Add(new Player() { id = u });
            }
            List<string> names = new List<string>();
            SqlHandler.GetUsernames(ref names);
            for (int i = 0; i < ingame; i++) //gets did (names) of each player
            {
                /*Console.WriteLine("Enter player " + Convert.ToInt32(i + 1));
                string a = Console.ReadLine();
                if (a == "")
                {
                    pname.Add("ai"); //nameless player automatically an AI
                }
                else
                {
                    pname.Add(a);
                }*/
                try
                {
                    players[i].name = names[i];
                }
                catch
                {
                    players[i].name = "ai";
                }
            }
            Player cplayer = players[0];
            int cpc = 0;
            Player lplayer = null;
            List<string> numsOfPlayers = new List<string>();
            foreach (Player f in players)
            {
                numsOfPlayers.Add("0");
                for (int i = 0; i < f.cubes; i++)
                {
                    f.nums.Add(0);
                    //Console.WriteLine("num has been added to player" + f.id);
                }
                //Console.WriteLine("its the after one " + f.nums);
            }
            int cubesingame = players[0].cubes * 6; //end of setup
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
                roll.Roll(ref players,
                          ref numsOfPlayers,
                          ref n1,
                          ref n2,
                          ref n3,
                          ref n4,
                          ref n5,
                          ref n6);
                int guessround = 0;
                cplayer = players[cpc];
                if (cplayer.name == "ai") //search for ai players
                {
                    Ai.Aihandler(cubesingame, ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6);
                }
                else
                {
                    //Interact.UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                    InteractOnline.ClientInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
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
                    if (cplayer.name == "ai") // search for ai players
                    {
                        Ai.Aihandler(cubesingame, ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        //Interact.UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                        InteractOnline.ClientInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
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
                        Console.WriteLine(cplayer.name + " has only " + cplayer.cubes + " cubes remaining");
                        cubesingame -= 1;
                    }
                    else if (lplayer.cubes < lastbefore) //checks for cube loss
                    {
                        lplayer.nums.RemoveAt(lplayer.cubes);
                        cpc = players.IndexOf(lplayer);
                        guessround++;
                        Console.WriteLine(lplayer.name + " has only " + lplayer.cubes + " cubes remaining");
                        cubesingame -= 1;
                    }
                    else if (cplayer.cubes > before)
                    {
                        guessround++;
                        Console.WriteLine(cplayer.name + " own " + cplayer.cubes + " cubes now");
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
            Console.WriteLine(players[0].name + " has won");
        }

    }
}
