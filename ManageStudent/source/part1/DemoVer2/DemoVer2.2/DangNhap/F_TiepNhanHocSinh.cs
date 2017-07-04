using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Data;
using System.Data.SqlClient;

namespace DangNhap
{
    public partial class F_TiepNhanHocSinh : Form
    {
        public F_TiepNhanHocSinh()
        {
            InitializeComponent();
        }
        
        public class AgeOfStu
        {
            
            public static int TuoiTT;
            public static int TuoiTD;
        }
        private void label6_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn đăng xuất hay không?", "THÔNG BÁO", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));
            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_Login f = new F_Login();
                f.Show();
            }
        }

        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void checkBox3_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void button1_Click(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            AgeOfStu.TuoiTD = int.Parse(Test.SelectTuoiTD("admin").ToString());
            AgeOfStu.TuoiTT = int.Parse(Test.SelectTuoiTT("admin").ToString());
            //Lấy ngày sinh để insert vài SQL
            string NgSinh = DTP_NgSinh.Value.ToString().Trim();
            if (NgSinh.Substring(1, 1) == "/")
            {
                if (NgSinh.Substring(3, 1) == "/")
                {
                    NgSinh = NgSinh.Substring(0, 8);
                }
                else
                    NgSinh = NgSinh.Substring(0, 9);
            }
            else
            {
                if (NgSinh.Substring(4, 1) == "/")
                {
                    NgSinh = NgSinh.Substring(0, 9);
                }
                else
                    NgSinh = NgSinh.Substring(0, 10);
            }

            if (String.IsNullOrEmpty(TB_HoTen.Text) || String.IsNullOrEmpty(TB_DiaChi.Text) || String.IsNullOrEmpty(TB_Email.Text))
            {
                MessageBox.Show("Vui lòng nhập đủ thông tin!", "Thông báo");
            }
            else if (Test.KtraTrungTen(TB_HoTen.Text.ToString(), NgSinh) == 1)
            {
                MessageBox.Show("Đã tồn tại học sinh này, vui lòng kiểm tra lại thông tin", "Thông báo");
            }
            else
            {
                //Khai báo lấy ngày tháng năm sinh và hiện tại dd/mm/yyyy
                string NamSinh = DTP_NgSinh.Value.ToString().Trim();
                string NamHTai = DateTime.Now.ToString().Trim();
                if (NamSinh.Substring(1, 1) == "/")
                {
                    if (NamSinh.Substring(3, 1) == "/")
                    {
                        NamSinh = NamSinh.Substring(4, 4);
                    }
                    else
                        NamSinh = NamSinh.Substring(5, 4);
                }
                else
                {
                    if (NamSinh.Substring(4, 1) == "/")
                    {
                        NamSinh = NamSinh.Substring(5, 4);
                    }
                    else
                        NamSinh = NamSinh.Substring(6, 4);
                }
                if (NamHTai.Substring(1, 1) == "/")
                {
                    if (NamHTai.Substring(3, 1) == "/")
                    {
                        NamHTai = NamHTai.Substring(4, 4);
                    }
                    else
                        NamHTai = NamHTai.Substring(5, 4);
                }
                else
                {
                    if (NamHTai.Substring(4, 1) == "/")
                    {
                        NamHTai = NamHTai.Substring(5, 4);
                    }
                    NamHTai = NamHTai.Substring(6, 4);
                }
                int NamBD10 = Int32.Parse(NamHTai.ToString()) - AgeOfStu.TuoiTT;
                int NamKT10 = Int32.Parse(NamHTai.ToString()) - (AgeOfStu.TuoiTD - 2);
                int NamBD11 = Int32.Parse(NamHTai.ToString()) - (AgeOfStu.TuoiTT + 1);
                int NamKT11 = Int32.Parse(NamHTai.ToString()) - (AgeOfStu.TuoiTD - 1);
                int NamBD12 = Int32.Parse(NamHTai.ToString()) - (AgeOfStu.TuoiTT + 2);
                int NamKT12 = Int32.Parse(NamHTai.ToString()) - AgeOfStu.TuoiTD;

                if (RB_GTNam.Checked == true)
                {
                    if (RB_Khoi10.Checked == true)
                    {
                        if ((Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) > (AgeOfStu.TuoiTD - 2)) || (Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) < AgeOfStu.TuoiTT))
                        {
                            MessageBox.Show("Học sinh lớp 10 phải có tuổi lớn hơn " + (AgeOfStu.TuoiTT - 1) + " và bé hơn " + (AgeOfStu.TuoiTD - 1) + " (Năm sinh từ '" + NamKT10 + "' đến năm '" + NamBD10 + "')");
                        }
                        else
                        {
                            Test.InsHoTen(10, TB_HoTen.Text, RB_GTNam.Text, DTP_NgSinh.Text, TB_Email.Text, TB_DiaChi.Text);
                            MessageBox.Show("Lưu thành công!!!", "THÔNG BÁO");
                            TB_DiaChi.Text = "";
                            TB_Email.Text = "";
                            TB_HoTen.Text = "";
                        }
                    }
                    else if (RB_Khoi11.Checked == true)
                    {
                        if ((Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) > (AgeOfStu.TuoiTD - 1)) || (Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) < (AgeOfStu.TuoiTT + 1)))
                        {
                            MessageBox.Show("Học sinh lớp 11 phải có tuổi lớn hơn " + AgeOfStu.TuoiTT+ " và bé hơn "+AgeOfStu.TuoiTD+" (Năm sinh từ '" + NamKT11 + "' đến năm '" + NamBD11 + "')");

                        }
                        else
                        {
                            Test.InsHoTen(11, TB_HoTen.Text, RB_GTNam.Text, DTP_NgSinh.Text, TB_Email.Text, TB_DiaChi.Text);
                            MessageBox.Show("Lưu thành công!!!", "THÔNG BÁO");
                            TB_DiaChi.Text = "";
                            TB_Email.Text = "";
                            TB_HoTen.Text = "";
                        }
                    }
                    else
                    {
                        if ((Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) > AgeOfStu.TuoiTD) || (Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) < (AgeOfStu.TuoiTT + 2)))
                        {
                            MessageBox.Show("Học sinh lớp 12 phải có tuổi lớn hơn "+(AgeOfStu.TuoiTT+1)+" và bé hơn "+(AgeOfStu.TuoiTD+1)+" (Năm sinh từ '" + NamKT12 + "' đến năm '" + NamBD12 + "')");
                        }
                        else
                        {
                            Test.InsHoTen(12, TB_HoTen.Text, RB_GTNam.Text, DTP_NgSinh.Text, TB_Email.Text, TB_DiaChi.Text);
                            MessageBox.Show("Lưu thành công!!!", "THÔNG BÁO");
                            TB_DiaChi.Text = "";
                            TB_Email.Text = "";
                            TB_HoTen.Text = "";
                        }
                    }
                }
                else
                {
                    if (RB_Khoi10.Checked == true)
                    {
                        if ((Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) > (AgeOfStu.TuoiTD - 2)) || (Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) < AgeOfStu.TuoiTT))
                        {
                            MessageBox.Show("Học sinh lớp 10 phải có tuổi lớn hơn " + (AgeOfStu.TuoiTT - 1) + " và bé hơn " + (AgeOfStu.TuoiTD - 1) + " (Năm sinh từ '" + NamKT10 + "' đến năm '" + NamBD10 + "')");
                        }
                        else
                        {
                            Test.InsHoTen(10, TB_HoTen.Text, RB_GTNu.Text, DTP_NgSinh.Text, TB_Email.Text, TB_DiaChi.Text);
                            MessageBox.Show("Lưu thành công!!!", "THÔNG BÁO");
                            TB_DiaChi.Text = "";
                            TB_Email.Text = "";
                            TB_HoTen.Text = "";
                        }
                    }
                    else if (RB_Khoi11.Checked == true)
                    {
                        if ((Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) > (AgeOfStu.TuoiTD - 1)) || (Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) < (AgeOfStu.TuoiTT + 1)))
                        {
                            MessageBox.Show("Học sinh lớp 11 phải có tuổi lớn hơn " + AgeOfStu.TuoiTT + " và bé hơn " + AgeOfStu.TuoiTD + " (Năm sinh từ '" + NamKT11 + "' đến năm '" + NamBD11 + "')");

                        }
                        else
                        {
                            Test.InsHoTen(11, TB_HoTen.Text, RB_GTNu.Text, DTP_NgSinh.Text, TB_Email.Text, TB_DiaChi.Text);
                            MessageBox.Show("Lưu thành công!!!", "THÔNG BÁO");
                            TB_DiaChi.Text = "";
                            TB_Email.Text = "";
                            TB_HoTen.Text = "";
                        }
                    }
                    else
                    {
                        if ((Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) > AgeOfStu.TuoiTD) || (Int32.Parse(NamHTai.ToString()) - Int32.Parse(NamSinh.ToString()) < (AgeOfStu.TuoiTT + 2)))
                        {
                            MessageBox.Show("Học sinh lớp 12 phải có tuổi lớn hơn " + (AgeOfStu.TuoiTT + 1) + " và bé hơn " + (AgeOfStu.TuoiTD + 1) + " (Năm sinh từ '" + NamKT12 + "' đến năm '" + NamBD12 + "')");
                        }
                        else
                        {
                            Test.InsHoTen(12, TB_HoTen.Text, RB_GTNu.Text, DTP_NgSinh.Text, TB_Email.Text, TB_DiaChi.Text);
                            MessageBox.Show("Lưu thành công!!!", "THÔNG BÁO");
                            TB_DiaChi.Text = "";
                            TB_Email.Text = "";
                            TB_HoTen.Text = "";
                        }
                    }
                }
            }
            

        }

        private void pictureBox4_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn quay lại trang chủ?", "Chú ý", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));
            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_MainNVPDT f = new F_MainNVPDT();
                f.Show();
            }
        }

        private void RB_Khoi11_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void F_TiepNhanHocSinh_Load(object sender, EventArgs e)
        {
            RB_Khoi10.Checked = true;
            RB_GTNam.Checked = true;
        }

        

        private void TB_HoTen_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar >= 33 && e.KeyChar <= 64 || e.KeyChar >= 91 && e.KeyChar <= 96 || e.KeyChar >= 123 && e.KeyChar <= 126)
            {
                MessageBox.Show("Chỉ được nhập chữ cái", "Thông báo");
                e.Handled = true;
            }
        }

        private void TB_DiaChi_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar >= 33 && e.KeyChar <= 43 || e.KeyChar >= 45 && e.KeyChar <= 46 || e.KeyChar >= 58 && e.KeyChar <= 64 || e.KeyChar >= 91 && e.KeyChar <= 96 || e.KeyChar >= 123 && e.KeyChar <= 126)
            {
                MessageBox.Show("Không được nhập kí tự đặc biệt", "Thông báo");
                e.Handled = true;
            }
        }

        private void TB_Email_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar >= 97 && e.KeyChar <= 122 || e.KeyChar == 95 || e.KeyChar == 64 || e.KeyChar == 46 || e.KeyChar>=48 && e.KeyChar <= 57 || e.KeyChar == 8)
            {
                e.Handled = false;
            }
            else
            {
                MessageBox.Show("Chỉ được nhập chữ cái thường, dấu ., dấu @ và dấu _", "Thông báo");
                e.Handled = true;
            }
        }
    }
}
