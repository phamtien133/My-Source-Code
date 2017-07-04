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
    public partial class F_ThayDoiLopHoc_SiSo : Form
    {
        public F_ThayDoiLopHoc_SiSo()
        {
            InitializeComponent();
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

        private void PB_Back_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn quay lại menu thay đổi thông tin lớp học?", "Chú ý", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));

            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_ThaydoiLopHoc f = new F_ThaydoiLopHoc();
                f.Show();
            }
        }

        private void TB_SiSo_KeyPress(object sender, KeyPressEventArgs e)
         {
            if (!char.IsWhiteSpace(e.KeyChar) &&(e.KeyChar >= 48 && e.KeyChar <= 57 || e.KeyChar == 13 || e.KeyChar == 8))
            {
                e.Handled = false;
            }
            else
            {
                MessageBox.Show("Chỉ được nhập số nguyên dương và không có khoảng trắng", "Thông báo");
                e.Handled = true;
            }
        }

        private void PB_Save_Click(object sender, EventArgs e)
        {
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            if (String.IsNullOrEmpty(TB_SiSo.Text.ToString()))
            {
                MessageBox.Show("Vui lòng nhập sĩ số mới", "Thông báo");
            }
            else
            {
                Test.UpSiSo(int.Parse(TB_SiSo.Text.Trim()), "admin");
                MessageBox.Show("Thay đổi sĩ số thành công!", "Thông báo");
                TB_SiSo.Text = "";
            }
        }
    }
}
