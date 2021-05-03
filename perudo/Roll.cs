using System;
using System.Collections.Generic;
using System.Text;

namespace perudo
{
    class roll
    {
        public static void Roll(ref List<Player> players, 
                                ref List<string> numbers,
                                ref int n1,
                                ref int n2,
                                ref int n3,
                                ref int n4,
                                ref int n5,
                                ref int n6)
        {
            foreach (var user in players)
            {
                for (int i = 0; i < user.cubes; i++)
                {
                    var rnd = new Random();
                    int a = rnd.Next(1, 7);
                    user.nums[i] = a;
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
                int k = 0;
                foreach (var client in players)
                {
                    string number = string.Join("", client.nums);
                    numbers[k] = number;
                    k++;
                    SqlHandler.NumberUploader(client.id, Convert.ToInt32(number));
                }
            }
        }
    }
}
