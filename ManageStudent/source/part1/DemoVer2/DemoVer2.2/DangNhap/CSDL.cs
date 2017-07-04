using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.SqlClient;
using System.Data;

namespace QLHS
{
    class CSDL
    {
        public static string path = "Data Source=.\\SQLEXPRESS;Initial Catalog=QLHOCSINH;Integrated Security=True";
        public static SqlConnection connect()
        {
            SqlConnection con = new SqlConnection( path);
            return con;
        }

        public static DataTable Read(string sql)
        {
            DataTable dt = new DataTable();
            SqlConnection con = connect();
            SqlCommand cmd = new SqlCommand(sql, con);

            SqlDataAdapter adapter = new SqlDataAdapter(cmd);
            adapter.Fill(dt);
            return dt;
        }
    }
}
