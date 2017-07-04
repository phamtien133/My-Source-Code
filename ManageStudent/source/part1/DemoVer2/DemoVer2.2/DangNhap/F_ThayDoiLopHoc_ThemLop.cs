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
    public partial class F_ThayDoiLopHoc_ThemLop : Form
    {
        public F_ThayDoiLopHoc_ThemLop()
        {
            InitializeComponent();
        }

        private void TB_MaLop_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (!char.IsWhiteSpace(e.KeyChar) && (e.KeyChar >= 48 && e.KeyChar <= 57 || e.KeyChar == 13 || e.KeyChar == 8))
            {
                e.Handled = false;
            }
            else
            {
                MessageBox.Show("Chỉ được nhập số nguyên dương và không có khoảng trắng", "Thông báo");
                e.Handled = true;
            }
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

        private void F_ThayDoiLopHoc_ThemLop_Load(object sender, EventArgs e)
        {
            RB_Khoi10.Checked = true;
            CB_TenLop.Text = "A1";

        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            if (String.IsNullOrEmpty(TB_MaLop.Text.ToString()))
            {
                MessageBox.Show("Vui lòng nhập mã lớp", "Thông báo");
            }
            else
            {
                if (RB_Khoi10.Checked == true)
                {
                    if (Test.KtraTrungMaLop(10, TB_MaLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại mã lớp này, vui lòng nhập mã khác", "Thông báo");
                    }
                    else if (Test.KtraTrungTenLop(10, "10" + CB_TenLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại tên lớp này, vui lòng chọn tên khác", "Thông báo");
                    }
                    else if (Test.KtraTrungMaLop(10, TB_MaLop.Text.ToString()) == 1 && Test.KtraTrungTenLop(10, "10" + CB_TenLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại mã và tên lớp này, vui lòng nhập lại", "Thông báo");
                    }
                    else
                    {
                        Test.InsLop(10, TB_MaLop.Text.ToString(), "10"+CB_TenLop.Text.ToString());
                        MessageBox.Show("Thêm lớp thành công", "Thông báo");
                        TB_MaLop.Text = "";
                    }
                }
                else if (RB_Khoi11.Checked == true)
                {
                    if (Test.KtraTrungMaLop(11, TB_MaLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại mã lớp này, vui lòng nhập mã khác", "Thông báo");
                    }
                    else if (Test.KtraTrungTenLop(11, "11" + CB_TenLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại tên lớp này, vui lòng chọn tên khác", "Thông báo");
                    }
                    else if (Test.KtraTrungMaLop(11, TB_MaLop.Text.ToString()) == 1 && Test.KtraTrungTenLop(11, "11" + CB_TenLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại mã và tên lớp này, vui lòng nhập lại", "Thông báo");
                    }
                    else
                    {
                        Test.InsLop(11, TB_MaLop.Text.ToString(), "11" + CB_TenLop.Text.ToString());
                        MessageBox.Show("Thêm lớp thành công", "Thông báo");
                        TB_MaLop.Text = "";
                    }
                }
                else
                {
                    if (Test.KtraTrungMaLop(12, TB_MaLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại mã lớp này, vui lòng nhập mã khác", "Thông báo");
                    }
                    else if (Test.KtraTrungTenLop(12, "12" + CB_TenLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại tên lớp này, vui lòng chọn tên khác", "Thông báo");
                    }
                    else if (Test.KtraTrungMaLop(12, TB_MaLop.Text.ToString()) == 1 && Test.KtraTrungTenLop(12, "12" + CB_TenLop.Text.ToString()) == 1)
                    {
                        MessageBox.Show("Đã tồn tại mã và tên lớp này, vui lòng nhập lại", "Thông báo");
                    }
                    else
                    {
                        Test.InsLop(12, TB_MaLop.Text.ToString(), "12" + CB_TenLop.Text.ToString());
                        MessageBox.Show("Thêm lớp thành công", "Thông báo");
                        TB_MaLop.Text = "";
                    }
                }
            }
        }
    }
}
