using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace QLHS
{
    public partial class ReportSubject : Form
    {
        string MAGV = "'GV001'";
       // public delegate void Send(string Value);
        public ReportSubject()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            this.AcceptButton = btnReport;
            textBox1_TextChanged_1(sender, e);

            string sql = "select MALOP from LOP ";
            DataTable LOP = CSDL.Read(sql);
            cb_Class.DataSource = LOP;
            cb_Class.DisplayMember = "MALOP";
            cb_Class.ValueMember = "MALOP";

            sql = "select MAHK from HOCKY"; 
            DataTable HocKy = CSDL.Read(sql);
            cb_Semester.DataSource = HocKy;
            cb_Semester.DisplayMember = "MAHK";
            cb_Semester.ValueMember = "MAHK";

            sql = "select * from NIENKHOA";
            DataTable NienKhoa = CSDL.Read(sql);
            cb_Year.DataSource = NienKhoa;
            cb_Year.DisplayMember = "TENNK";
            cb_Year.ValueMember = "MANK";
        }

        private void comboBox3_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void comboBox2_SelectedIndexChanged(object sender, EventArgs e)
        {
           
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string sql = "select MAHS from DIEMTBM where MALOP = '" + cb_Class.SelectedValue.ToString() 
                        + "' and HOCKY = '" + cb_Semester.SelectedValue.ToString() 
                        + "' and NIENKHOA = '" + cb_Year.SelectedValue.ToString() 
                        + "' and MAMH = '" + tb_Subject.Text + "'";
            DataTable report;
            report = CSDL.Read(sql);
            if (report.Rows.Count > 0)
            {
                this.Hide();
                Successful_Report form2 = new Successful_Report(cb_Class.SelectedValue.ToString(), cb_Semester.SelectedValue.ToString(), cb_Year.SelectedValue.ToString(), tb_Subject.Text);
                form2.Show();
                //this.Close();
            }
            else
                MessageBox.Show("Thông tin nhập không chính xác.", "Thông báo");
        }

        private void textBox1_TextChanged_1(object sender, EventArgs e)
        {
            string sql = "select MAMH from GIAOVIEN where MAGV = " + MAGV;
            DataTable GVBM;
            GVBM = CSDL.Read(sql);
            tb_Subject.Text = GVBM.Rows[0][0].ToString();
        }

        private void button1_Click_1(object sender, EventArgs e)
        {
            this.Close();
            
        }
       
         
    }
}
