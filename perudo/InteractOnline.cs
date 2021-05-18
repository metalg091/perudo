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
            Console.WriteLine("got guess " + guess);
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
                    Interact.Doubt(ref cplayer, ref lplayer, lastguess, numd, n1, n2, n3, n4, n5, n6);
                }
                else if (guess == "equal")
                {
                    Interact.Equal(ref cplayer, lastguess, numd, n1, n2, n3, n4, n5, n6);
                }
            }
        }
    }
}
