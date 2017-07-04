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
    public partial class F_DatLaiMK : Form
    {
        public F_DatLaiMK()
        {
            InitializeComponent();
        }

        private void F_DatLaiMK_Load(object sender, EventArgs e)
        {
            if (F_Login.Role_Login.Role == 1)
            {
                LB_XinChao.Text = "Xin chào, Admin";
            }
            CB_MatKhau.Text = "password123";
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
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
            if(String.IsNullOrEmpty(TB_TenTK.Text.ToString()))
            {
                MessageBox.Show("Vui lòng điền tên tài khoản!", "Thông báo");
            }
            else
            {
                if(Test.KtraTrungTK(TB_TenTK.Text.ToString()) != 1)
                {
                    MessageBox.Show("Tên tài khoản không tồn tại, vui lòng nhập lại", "Thông báo");
                }
                else
                {
                    Test.UpNewPass(CB_MatKhau.Text.ToString(), TB_TenTK.Text.ToString());
                    MessageBox.Show("Mật khẩu mới của tài khoản '"+TB_TenTK.Text+"' là '"+CB_MatKhau.Text+"'", "Thông báo");
                    TB_TenTK.Text = "";
                }
            }
        }
    }
}
