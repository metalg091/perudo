using System;
using System.Collections.Generic;
using System.Text;
using MySql.Data.MySqlClient;
using System.Threading;

namespace perudo
{
    class SqlHandler
    {
        /*private static void SqlHandler()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE game SET playersInGame = REPLACE(playersInGame, 1, 0) WHERE id LIKE 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                MySqlDataReader rdr = cmd.ExecuteReader();

                while (rdr.Read())
                {
                    Console.WriteLine(rdr[0] + " -- " + rdr[1]);
                }
                rdr.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            conn.Close();
            Console.WriteLine("Done.");
        }*/
        public static List<string> GetUsernames(ref List<string> names)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "SELECT playersInGame FROM game WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                MySqlDataReader rdr = cmd.ExecuteReader();
                int a = 1;
                while (rdr.Read())
                {
                    //Console.WriteLine(rdr[0]);
                    a = Convert.ToInt32(rdr[0]);

                }
                rdr.Close();

                sql = "SELECT name FROM game WHERE id BETWEEN 1 AND " + a;
                cmd = new MySqlCommand(sql, conn);
                rdr = cmd.ExecuteReader();
                while (rdr.Read())
                {
                    //Console.WriteLine(rdr[0]);
                    names.Add(Convert.ToString(rdr[0]));
                }
                rdr.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            /*foreach (var player in names)
            {
                Console.WriteLine(player);
            }*/
            conn.Close();
            Console.WriteLine("Done.");
            return names;
        }
        public static string GetGuess()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            bool ClientTurn = true;
            string guess = "empty";
            try
            {
                while (ClientTurn)
                {
                    Console.WriteLine("Connecting to MySQL...");
                    conn.Open();
                    if (GetCycle() == 0)
                    {
                        ClientTurn = false;
                        string sql = "SELECT guess FROM eventtable ORDER BY orders DESC LIMIT 1";
                        MySqlCommand cmd = new MySqlCommand(sql, conn);
                        MySqlDataReader rdr = cmd.ExecuteReader();
                        while (rdr.Read())
                        {
                            //Console.WriteLine(rdr[0]);
                            guess = Convert.ToString(rdr[0]);

                        }
                        rdr.Close();
                    }
                    else
                    {
                        continue;
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            conn.Close();
            Console.WriteLine("Done.");
            return guess;
        }
        private static int GetCycle()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            int a = 1;
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "SELECT cycle FROM game WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                MySqlDataReader rdr = cmd.ExecuteReader();
                while (rdr.Read())
                {
                    //Console.WriteLine(rdr[0]);
                    a = Convert.ToInt32(rdr[0]);

                }
                rdr.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            conn.Close();
            Console.WriteLine("Done.");
            return a;
        }
        public static void SetCycleTo1()
        {
            string connStr = "server=localhost;user=root;database=world;port=3306;password=******";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `cycle`= 1 WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }

            conn.Close();
            Console.WriteLine("Done.");
        }
        public static void NumberUploader(int id, int number)
        {
            string connStr = "server=localhost;user=root;database=world;port=3306;password=******";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `numbers`= " + number + " WHERE id =" + id;
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }

            conn.Close();
            Console.WriteLine("Done.");
        }
    }
}
