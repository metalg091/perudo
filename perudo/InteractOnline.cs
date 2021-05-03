using System;
using System.Collections.Generic;
using System.Text;

namespace perudo
{
    class InteractOnline
    {
        public static void ClientInteract(ref Player cplayer,
                                          ref Player lplayer,
                                          ref int lastguess,
                                          int n1,
                                          int n2,
                                          int n3,
                                          int n4,
                                          int n5,
                                          int n6,
                                          bool frun)
        {
            //.WriteLine("Player " + cplayer.name + " please enter your guess!");
            /*foreach (int g in cplayer.nums)
            {
                Console.WriteLine(g);
            }*/
            //string guess = Console.ReadLine();
            
            string guess = SqlHandler.GetGuess();
            string lgsrtd = Convert.ToString(lastguess);
            string numd = lgsrtd.Substring(lgsrtd.Length - 1, 1);
            if (guess == "doubt")
            {
                switch (Convert.ToInt32(numd))
                {
                    case 1:
                        if (n1 * 10 + 1 > lastguess)
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        else
                        {
                            lplayer.cubes = lplayer.cubes - 1;
                        }
                        break;
                    case 2:
                        if (n2 * 10 + 2 + n1 * 10 > lastguess)
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        else
                        {
                            lplayer.cubes = lplayer.cubes - 1;
                        }
                        break;
                    case 3:
                        if (n3 * 10 + 3 + n1 * 10 > lastguess)
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        else
                        {
                            lplayer.cubes = lplayer.cubes - 1;
                        }
                        break;
                    case 4:
                        if (n4 * 10 + 4 + n1 * 10 > lastguess)
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        else
                        {
                            lplayer.cubes = lplayer.cubes - 1;
                        }
                        break;
                    case 5:
                        if (n5 * 10 + 5 + n1 * 10 > lastguess)
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        else
                        {
                            lplayer.cubes = lplayer.cubes - 1;
                        }
                        break;
                    case 6:
                        if (n6 * 10 + 6 + n1 * 10 > lastguess)
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        else
                        {
                            lplayer.cubes = lplayer.cubes - 1;
                        }
                        break;
                }
            }
            else if (guess == "equal")
            {
                switch (Convert.ToInt32(numd))
                {
                    case 1:
                        if (n1 * 10 + 1 == lastguess)
                        {
                            if (cplayer.cubes == 6)
                            {
                                break;
                            }
                            else
                            {
                                cplayer.cubes = cplayer.cubes + 1;
                                cplayer.nums.Add(0);
                            }
                        }
                        else
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        break;
                    case 2:
                        if (n2 * 10 + 2 + n1 * 10 == lastguess)
                        {
                            if (cplayer.cubes == 6)
                            {
                                break;
                            }
                            else
                            {
                                cplayer.cubes = cplayer.cubes + 1;
                                cplayer.nums.Add(0);
                            }
                        }
                        else
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        break;
                    case 3:
                        if (n3 * 10 + 3 + n1 * 10 == lastguess)
                        {
                            if (cplayer.cubes == 6)
                            {
                                break;
                            }
                            else
                            {
                                cplayer.cubes = cplayer.cubes + 1;
                                cplayer.nums.Add(0);
                            }
                        }
                        else
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        break;
                    case 4:
                        if (n4 * 10 + 4 + n1 * 10 == lastguess)
                        {
                            if (cplayer.cubes == 6)
                            {
                                break;
                            }
                            else
                            {
                                cplayer.cubes = cplayer.cubes + 1;
                                cplayer.nums.Add(0);
                            }
                        }
                        else
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        break;
                    case 5:
                        if (n5 * 10 + 5 + n1 * 10 == lastguess)
                        {
                            if (cplayer.cubes == 6)
                            {
                                break;
                            }
                            else
                            {
                                cplayer.cubes = cplayer.cubes + 1;
                                cplayer.nums.Add(0);
                            }
                        }
                        else
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        break;
                    case 6:
                        if (n6 * 10 + 6 + n1 * 10 == lastguess)
                        {
                            if (cplayer.cubes == 6)
                            {
                                break;
                            }
                            else
                            {
                                cplayer.cubes = cplayer.cubes + 1;
                                cplayer.nums.Add(0);
                            }
                        }
                        else
                        {
                            cplayer.cubes = cplayer.cubes - 1;
                        }
                        break;
                }
            }
        }
    }
}
