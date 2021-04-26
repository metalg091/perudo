using System;
using System.Collections.Generic;
using System.Text;

namespace perudo
{
    class roll
    {
        public static void Roll(ref Player player1,
                                ref Player player2,
                                ref Player player3,
                                ref Player player4,
                                ref Player player5,
                                ref Player player6,
                                ref int n1,
                                ref int n2,
                                ref int n3,
                                ref int n4,
                                ref int n5,
                                ref int n6)
        {
            for (int i = 0; i < player1.cubes; i++) //generates random numbers for player1, stores and counts them
            {
                var rnd = new Random();
                int a = rnd.Next(1, 7);
                player1.nums[i] = a;
                switch(a)
                {
                    case 1:
                        n1++;
                        break;
                    case 2:
                        n2++;
                        break;
                    case 3:
                        n3++;
                        break;
                    case 4:
                        n4++;
                        break;
                    case 5:
                        n5++;
                        break;
                    case 6:
                        n6++;
                        break;
                }
            }
            for (int i = 0; i < player2.cubes; i++)
            {
                var rnd = new Random();
                int a = rnd.Next(1, 7);
                player2.nums[i] = a;
                switch (a)
                {
                    case 1:
                        n1++;
                        break;
                    case 2:
                        n2++;
                        break;
                    case 3:
                        n3++;
                        break;
                    case 4:
                        n4++;
                        break;
                    case 5:
                        n5++;
                        break;
                    case 6:
                        n6++;
                        break;
                }
            }
            for (int i = 0; i < player3.cubes; i++)
            {
                var rnd = new Random();
                int a = rnd.Next(1, 7);
                player3.nums[i] = a;
                switch (a)
                {
                    case 1:
                        n1++;
                        break;
                    case 2:
                        n2++;
                        break;
                    case 3:
                        n3++;
                        break;
                    case 4:
                        n4++;
                        break;
                    case 5:
                        n5++;
                        break;
                    case 6:
                        n6++;
                        break;
                }
            }
            for (int i = 0; i < player4.cubes; i++)
            {
                var rnd = new Random();
                int a = rnd.Next(1, 7);
                player4.nums[i] = a;
                switch (a)
                {
                    case 1:
                        n1++;
                        break;
                    case 2:
                        n2++;
                        break;
                    case 3:
                        n3++;
                        break;
                    case 4:
                        n4++;
                        break;
                    case 5:
                        n5++;
                        break;
                    case 6:
                        n6++;
                        break;
                }
            }
            for (int i = 0; i < player5.cubes; i++)
            {
                var rnd = new Random();
                int a = rnd.Next(1, 7);
                player5.nums[i] = a;
                switch (a)
                {
                    case 1:
                        n1++;
                        break;
                    case 2:
                        n2++;
                        break;
                    case 3:
                        n3++;
                        break;
                    case 4:
                        n4++;
                        break;
                    case 5:
                        n5++;
                        break;
                    case 6:
                        n6++;
                        break;
                }
            }
            for (int i = 0; i < player6.cubes; i++)
            {
                var rnd = new Random();
                int a = rnd.Next(1, 7);
                player6.nums[i] = a;
                switch (a)
                {
                    case 1:
                        n1++;
                        break;
                    case 2:
                        n2++;
                        break;
                    case 3:
                        n3++;
                        break;
                    case 4:
                        n4++;
                        break;
                    case 5:
                        n5++;
                        break;
                    case 6:
                        n6++;
                        break;
                }
            }
        }
    }
}
