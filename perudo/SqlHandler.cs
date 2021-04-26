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
        public static void RegisteHandler()
        {
            string connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn.Open();

                string sql = "UPDATE game SET cycle = 1 WHERE id = 0";
                MySqlCommand cmd = new MySqlCommand(sql, conn);
                MySqlDataReader rdr = cmd.ExecuteReader();

                while (rdr.Read())
                {
                    Console.WriteLine(rdr[0] + "--" + rdr[1]);
                }
                rdr.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            conn.Close();
            Console.WriteLine("SQL connection closed");
            
            connStr = "server=localhost;user=root;database=perudo;port=3306;password=";
            MySqlConnection conn2 = new MySqlConnection(connStr);
            bool waiting = true;
            try
            {
                Console.WriteLine("Connecting to MySQL...");
                conn2.Open();
                while (waiting)
                {
                    string sql = "SELECT cycle FROM game WHERE id = 0";
                    MySqlCommand cmd = new MySqlCommand(sql, conn2);
                    MySqlDataReader rdr = cmd.ExecuteReader();
                    Console.WriteLine("alive");
                    while (rdr.Read())
                    {
                        //Console.WriteLine(rdr[0] + "--" + rdr[0]);
                        if(Convert.ToInt32(rdr[0]) != 1)
                        {
                            waiting = false;
                        }
                        System.Threading.Thread.Sleep(2000);
                    }
                    rdr.Close();
                    
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.ToString());
            }
            conn2.Close();
            Console.WriteLine("SQL connection closed");
        }
    }
}
