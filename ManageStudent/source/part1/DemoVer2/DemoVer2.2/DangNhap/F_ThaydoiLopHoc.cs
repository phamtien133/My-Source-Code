using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace DangNhap
{
    public partial class F_ThaydoiLopHoc : Form
    {
        public F_ThaydoiLopHoc()
        {
            InitializeComponent();
        }

        private void PB_Back_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn quay lại trang chủ?", "Chú ý", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));

            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_ThayDoiQD f = new F_ThayDoiQD();
                f.Show();
            }
        }

        private void LB_LogOut_Click(object sender, EventArgs e)
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

        private void pictureBox7_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_ThayDoiLopHoc_SiSo f = new F_ThayDoiLopHoc_SiSo();
            f.Show();
        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_ThayDoiLopHoc_ThemLop f = new F_ThayDoiLopHoc_ThemLop();
            f.Show();
        }

        private void PB_XoaLop_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_ThayDoiLopDoc_XoaLop f = new F_ThayDoiLopDoc_XoaLop();
            f.Show();
        }

        private void PB_SuaLop_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_ThayDoiLopHoc_SuaLop f = new F_ThayDoiLopHoc_SuaLop();
            f.Show();
        }
    }
}
