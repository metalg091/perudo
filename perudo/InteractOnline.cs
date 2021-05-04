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
            string guess = SqlHandler.GetGuess();
            string lgsrtd = Convert.ToString(lastguess);
            string numd = lgsrtd.Substring(lgsrtd.Length - 1, 1);
            try
            {
                int gnum = Convert.ToInt32(guess);
                string lgsrt = Convert.ToString(lastguess);
                string num = lgsrt.Substring(lgsrt.Length - 1, 1);
                if (num == "1")
                {
                    num = guess.Substring(guess.Length - 1, 1);
                    if (num == "1")
                    {
                        if (gnum > lastguess)
                        {
                            lastguess = gnum;
                        }
                        else
                        {
                            Console.WriteLine("enter a number bigger than " + lastguess + "userinput error");
                            //UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                        }
                    }
                    else
                    {
                        if (gnum > lastguess * 2)
                        {
                            lastguess = gnum;
                        }
                        else
                        {
                            Console.WriteLine("enter a number bigger than " + 2 * lastguess + "user input error");
                            //UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                        }
                    }
                }
                else
                {
                    num = guess.Substring(guess.Length - 1, 1);
                    if (num == "1")
                    {
                        if (gnum > lastguess / 2)
                        {
                            lastguess = gnum;
                        }
                        else
                        {
                            Console.WriteLine("enter a value biger than " + lastguess / 2 + "user input error!!!");
                            //UserInteract(ref cplayer, ref lplayer, ref lastguess, n1, n2, n3, n4, n5, n6, frun);
                        }
                    }
                    else
                    {
                        if (gnum > lastguess)
                        {
                            lastguess = gnum;
                        }
                        else
                        {
                            Console.WriteLine("enter a number bigger than " + lastguess);
                        }
                    }
                }
            }
            catch
            {
                if (guess == "doubt")
                {
                    switch (Convert.ToInt32(numd))
                    {
                        case 1:
                            if (n1 * 10 + 1 > lastguess)
                            {
                                cplayer.cubes -= 1;
                            }
                            else
                            {
                                lplayer.cubes -= 1;
                            }
                            break;
                        case 2:
                            if (n2 * 10 + 2 + n1 * 10 > lastguess)
                            {
                                cplayer.cubes = cplayer.cubes - 1;
                            }
                            else
                            {
                                lplayer.cubes -= 1;
                            }
                            break;
                        case 3:
                            if (n3 * 10 + 3 + n1 * 10 > lastguess)
                            {
                                cplayer.cubes -= 1;
                            }
                            else
                            {
                                lplayer.cubes -= 1;
                            }
                            break;
                        case 4:
                            if (n4 * 10 + 4 + n1 * 10 > lastguess)
                            {
                                cplayer.cubes -= 1;
                            }
                            else
                            {
                                lplayer.cubes -= 1;
                            }
                            break;
                        case 5:
                            if (n5 * 10 + 5 + n1 * 10 > lastguess)
                            {
                                cplayer.cubes -= 1;
                            }
                            else
                            {
                                lplayer.cubes -= 1;
                            }
                            break;
                        case 6:
                            if (n6 * 10 + 6 + n1 * 10 > lastguess)
                            {
                                cplayer.cubes -= 1;
                            }
                            else
                            {
                                lplayer.cubes -= 1;
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
                                    cplayer.cubes += 1;
                                    cplayer.nums.Add(0);
                                }
                            }
                            else
                            {
                                cplayer.cubes -= 1;
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
                                    cplayer.cubes += 1;
                                    cplayer.nums.Add(0);
                                }
                            }
                            else
                            {
                                cplayer.cubes -= 1;
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
                                    cplayer.cubes += 1;
                                    cplayer.nums.Add(0);
                                }
                            }
                            else
                            {
                                cplayer.cubes -= 1;
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
                                    cplayer.cubes += 1;
                                    cplayer.nums.Add(0);
                                }
                            }
                            else
                            {
                                cplayer.cubes -= 1;
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
                                    cplayer.cubes += 1;
                                    cplayer.nums.Add(0);
                                }
                            }
                            else
                            {
                                cplayer.cubes -= 1;
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
                                    cplayer.cubes += 1;
                                    cplayer.nums.Add(0);
                                }
                            }
                            else
                            {
                                cplayer.cubes -= 1;
                            }
                            break;
                    }
                }
            }
        }
    }
}
