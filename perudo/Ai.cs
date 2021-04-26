using System;
using System.Collections.Generic;
using System.Text;

namespace perudo
{
    class Ai
    {
        private static void Aiequal(ref Player cplayer, int lastguess, int n1, int n2, int n3, int n4, int n5, int n6)
        {
            Console.WriteLine("Ai" + cplayer.id + " thinks its equal");
            string lgsrtd = Convert.ToString(lastguess);
            string numd = lgsrtd.Substring(lgsrtd.Length - 1, 1);
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
        private static void Aidoubt(ref Player cplayer, ref Player lplayer, int lastguess, int n1, int n2, int n3, int n4, int n5, int n6)
        {
            Console.WriteLine("Ai" + cplayer.id + " doubt");
            string lgsrtd = Convert.ToString(lastguess);
            string numd = lgsrtd.Substring(lgsrtd.Length - 1, 1);
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
        private static void Aiguess(ref int lastguess, int theo, int a)
        {
            int b;
            var rnd = new Random();
            int before = lastguess;
            while (before >= lastguess)
            {
                b = rnd.Next(-4, 3);
                lastguess = (Convert.ToInt32(theo) + b) * 10 + a;
                Console.WriteLine("guessed " + lastguess + " the original was " + before);
            }
        }
        private static void AiBaseTheory(ref int lastguess, int theo1, int theo2, int theo3, int theo4, int theo5, int theo6, Player me)
        {
            var random = new Random();
            int a = random.Next(1, 7);
            switch (a)
            {
                case 1:
                    Aiguess(ref lastguess, Convert.ToInt32(theo1), a);
                    Console.WriteLine("ai" + me.id + " guessed " + lastguess);
                    break;
                case 2:
                    Aiguess(ref lastguess, Convert.ToInt32(theo2), a);
                    Console.WriteLine("ai" + me.id + " guessed " + lastguess);
                    break;
                case 3:
                    Aiguess(ref lastguess, Convert.ToInt32(theo3), a);
                    Console.WriteLine("ai" + me.id + " guessed " + lastguess);
                    break;
                case 4:
                    Aiguess(ref lastguess, Convert.ToInt32(theo4), a);
                    Console.WriteLine("ai" + me.id + " guessed " + lastguess);
                    break;
                case 5:
                    Aiguess(ref lastguess, Convert.ToInt32(theo5), a);
                    Console.WriteLine("ai" + me.id + " guessed " + lastguess);
                    break;
                case 6:
                    Aiguess(ref lastguess, Convert.ToInt32(theo6), a);
                    Console.WriteLine("ai" + me.id + " guessed " + lastguess);
                    break;
            }
        }
        public static void Aihandler(int cig, ref Player me, ref Player last, ref int lastguess, int n1, int n2, int n3, int n4, int n5, int n6)
        {
            int unkowncubes = cig - me.cubes;
            double theo1 = unkowncubes / 6;
            double theo2 = 0;
            double theo3 = 0;
            double theo4 = 0;
            double theo5 = 0;
            double theo6 = 0;
            for(int i = 0; i < me.cubes - 1; i++)
            {
                switch (me.nums[i])
                {
                    case 1:
                        theo1++;
                        theo2++;
                        theo3++;
                        theo4++;
                        theo5++;
                        theo6++;
                        break;
                    case 2:
                        theo2++;
                        break;
                    case 3:
                        theo3++;
                        break;
                    case 4:
                        theo4++;
                        break;
                    case 5:
                        theo5++;
                        break;
                    case 6:
                        theo6++;
                        break;
                }
            }
            theo2 += unkowncubes / 3;
            theo3 += unkowncubes / 3;
            theo4 += unkowncubes / 3;
            theo5 += unkowncubes / 3;
            theo6 += unkowncubes / 3;
            theo1 = Math.Round(theo1); //makes them convertable to int
            theo2 = Math.Round(theo2);
            theo3 = Math.Round(theo3);
            theo4 = Math.Round(theo4);
            theo5 = Math.Round(theo5);
            theo6 = Math.Round(theo6);
            bool is1 = false;
            string lstgss = Convert.ToString(lastguess);
            //int lastnum = Convert.ToInt32(lstgss.Substring(lstgss.Length - 1, 1));
            var rnd = new Random();
            int randomiser = rnd.Next(-2, 3);
            switch (lstgss.Substring(lstgss.Length - 1, 1))
            {
                case "0":
                    AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    break;
                case "1":
                    is1 = true;
                    if (lastguess > (theo1 + randomiser) * 10 + 1)
                    {
                        Aidoubt(ref me, ref last, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else if (lastguess == (theo1  + randomiser) * 10 + 1)
                    {
                        Aiequal(ref me, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    }
                    break;
                case "2":
                    if (lastguess > (theo2 + randomiser) * 10 + 2)
                    {
                        Aidoubt(ref me, ref last, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else if (lastguess == (theo2 + randomiser) * 10 + 2)
                    {
                        Aiequal(ref me, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    }
                    break;
                case "3":
                    if (lastguess > (theo3 + randomiser) * 10 + 3)
                    {
                        Aidoubt(ref me, ref last, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else if (lastguess == (theo3 + randomiser) * 10 + 3)
                    {
                        Aiequal(ref me, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    }
                    break;
                case "4":
                    if (lastguess > (theo4 + randomiser) * 10 + 4)
                    {
                        Aidoubt(ref me, ref last, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else if (lastguess == (theo4 + randomiser) * 10 + 4)
                    {
                        Aiequal(ref me, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    }
                    break;
                case "5":
                    if (lastguess > (theo5 + randomiser) * 10 + 5)
                    {
                        Aidoubt(ref me, ref last, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else if (lastguess == (theo5 + randomiser) * 10 + 5)
                    {
                        Aiequal(ref me, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    }
                    break;
                case "6":
                    if (lastguess > (theo6 + randomiser) * 10 + 6)
                    {
                        Aidoubt(ref me, ref last, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else if (lastguess == (theo6 + randomiser) * 10 + 6)
                    {
                        Aiequal(ref me, lastguess, n1, n2, n3, n4, n5, n6);
                    }
                    else
                    {
                        AiBaseTheory(ref lastguess, Convert.ToInt32(theo1), Convert.ToInt32(theo2), Convert.ToInt32(theo3), Convert.ToInt32(theo4), Convert.ToInt32(theo5), Convert.ToInt32(theo6), me);
                    }
                    break;
            }
        }
    }
}