using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Data.SqlClient;

namespace DangNhap
{
    public partial class F_TaoTaiKhoan : Form
    {
        public F_TaoTaiKhoan()
        {
            InitializeComponent();
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

        private void pictureBox4_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn quay lại trang chủ?", "Chú ý", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));
            
            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_MainTruongPDT f = new F_MainTruongPDT();
                f.Show();
            }
        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            if (String.IsNullOrEmpty(TB_TenTK.Text) || String.IsNullOrEmpty(TB_MatKhau.Text))
            {
                MessageBox.Show("Vui lòng điền đầy đủ thông tin", "Thông báo");
            }
            else
            {
                if (Test.KtraTrungTK(TB_TenTK.Text.ToString()) == 1)
                {
                    MessageBox.Show("Đã tồn tại tên tài khoản này, vui lòng nhập tên khác", "Thông báo");
                }
                else if(TB_MatKhau.TextLength < 8)
                {
                    MessageBox.Show("Mật khẩu phải có hơn 7 kí tự", "Thông báo");
                }
                else
                {
                    int role = 0;
                    if(CB_VaiTro.Text.ToString() == "Nhân viên PĐT")
                    {
                        role = 2;
                    }
                    else if(CB_VaiTro.Text.ToString() == "Giáo viên chủ nhiệm")
                    {
                        role = 3;
                    }
                    else
                    {
                        role = 4;
                    }
                    Test.InsTaiKhoan(TB_TenTK.Text.ToString(), TB_MatKhau.Text.ToString(), role);
                    MessageBox.Show("Lưu thành công", "Thông báo");
                    TB_TenTK.Text = "";
                    TB_MatKhau.Text = "";
                }
            }
            
        }


        private void TB_TenTK_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar >= 48 && e.KeyChar <= 57 || e.KeyChar >= 97 && e.KeyChar <= 122 || e.KeyChar == 8 || e.KeyChar == 13)
            {
                e.Handled = false;
            }
            else
            {
                MessageBox.Show("Chỉ được nhập chữ cái thường không có dấu, và số");
                e.Handled = true;
            }
        }

        private void TB_MatKhau_KeyPress(object sender, KeyPressEventArgs e)
        {
            if ((e.KeyChar >= 48 && e.KeyChar <= 57) || (e.KeyChar >= 97 && e.KeyChar <= 122) || e.KeyChar == 8 || e.KeyChar == 13)
            {
                e.Handled = false;
            }
            else
            {
                MessageBox.Show("Chỉ được nhập chữ cái thường không có dấu, và số");
                e.Handled = true;
            }
        }

        private void F_TaoTaiKhoan_Load(object sender, EventArgs e)
        {
            CB_VaiTro.Text = "Nhân viên PĐT";
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
        }
    }
}
