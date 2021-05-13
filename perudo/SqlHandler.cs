using System;
using System.Collections.Generic;
using System.Text;
using MySql.Data.MySqlClient;
using System.Threading;

namespace perudo
{
    class SqlHandler
    {
        public static void GetUsernames(ref List<string> names)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
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
                Thread.Sleep(2500);
                GetUsernames(ref names);
            }
            conn.Close();
            Console.WriteLine("GetUsernames done.");
        }
        public static string GetGuess()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            bool ClientTurn = true;
            string guess = "empty";
            try
            {
                conn.Open();
                while (ClientTurn)
                {
                    //Console.WriteLine("Connecting to MySQL...");
                    
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
                        Thread.Sleep(2500);
                        Console.WriteLine("waiting for turn...");
                        continue;
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(5000);
                GetGuess();
            }
            SetCycleTo();
            conn.Close();
            Console.WriteLine("GetGuess done.");
            guess = guess.Replace("'", "");
            return guess;
        }
        private static int GetCycle()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            int a = 1;
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
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
                Thread.Sleep(2500);
                GetCycle();
            }
            conn.Close();
            Console.WriteLine("Getcycle done.");
            return a;
        }
        public static void SetCycleTo()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `cycle`= 1 WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                SetCycleTo();
            }

            conn.Close();
            Console.WriteLine("SetCycle done.");
        }
        public static void NumberUploader(int id, int number)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `numbers`= " + number + " WHERE id =" + id;
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                NumberUploader(id, number);
            }

            conn.Close();
            Console.WriteLine("numberuploader done.");
        }
        public static void CubeUpdater(int id, int cubes)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `cubes`= " + cubes + " WHERE id =" + id;
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                NumberUploader(id, cubes);
            }

            conn.Close();
            Console.WriteLine("numberuploader done.");
        }
        public static void ReportEvent(int i, Player user)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "SELECT orders FROM eventtable ORDER BY orders DESC LIMIT 1";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                MySqlDataReader rdr = cmd.ExecuteReader();
                int a = 0;
                while (rdr.Read())
                {
                    a = Convert.ToInt32(rdr[0]);
                }
                rdr.Close();
                a++;
                sql = "INSERT INTO eventtable (orders, ide, guess, who) VALUES (" + a + ", 0, " +  i + ", " + user.id + ")";
                cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                ReportEvent(i, user);
            }

            conn.Close();
            Console.WriteLine("Done.");
        }
        public static void ReportCPI(int cpi)
        {
            cpi++;
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `cPlayerId`= " + cpi + " WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                ReportCPI(cpi - 1);
            }

            conn.Close();
            Console.WriteLine("cpi upload done.");
        }
        public static void CleanUp()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "TRUNCATE eventtable";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
                sql = "Update game SET playersInGame = 0, cPlayerId = 0 WHERE id = 0";
                cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
                Console.WriteLine("Reset done");
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Console.WriteLine("Cleanup failed");
            }
            conn.Close();
        }
        public static void PIGUpdate(int i)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `numbers`= " + i + " WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                PIGUpdate(i);
            }

            conn.Close();
            Console.WriteLine("Done.");
        }
        public static void Win(int winner)
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                //Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE `game` SET `cycle`= 2, cPlayerId = " + winner + " WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                cmd.ExecuteNonQuery();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
                Thread.Sleep(2500);
                SetCycleTo();
            }

            conn.Close();
            Console.WriteLine("SetCycle done.");
        }
    }
}
