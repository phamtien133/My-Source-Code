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
    public partial class F_ThayDoiLopHoc_SuaLop : Form
    {
        public F_ThayDoiLopHoc_SuaLop()
        {
            InitializeComponent();
        }

        private void F_ThayDoiLopHoc_SuaLop_Load(object sender, EventArgs e)
        {
            RB_Khoi10.Checked = true;
        }

        private void RB_Khoi10_CheckedChanged(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand("select Khoi, Lop from DSLop where Khoi = 10", conn);
            SqlDataAdapter da = new SqlDataAdapter(cm);
            DataSet ds = new DataSet();
            da.Fill(ds, "DSLop");
            if (CB_TenLop.DataSource != null)
            {
                CB_TenLop.DataSource = null;
                CB_TenLop.Refresh();
            }
            CB_TenLop.DataSource = ds.Tables[0];
            CB_TenLop.DisplayMember = "Lop";
            CB_TenLop.ValueMember = "Khoi";

            SqlCommand cm2 = new SqlCommand("select Khoi, Lop from LopDuKien where Khoi = 10", conn);
            SqlDataAdapter da2 = new SqlDataAdapter(cm2);
            DataSet ds2 = new DataSet();
            da2.Fill(ds2, "LopDuKien");
            if (CB_TenLopMoi.DataSource != null)
            {
                CB_TenLopMoi.DataSource = null;
                CB_TenLopMoi.Refresh();
            }
            CB_TenLopMoi.DataSource = ds2.Tables[0];
            CB_TenLopMoi.DisplayMember = "Lop";
            CB_TenLopMoi.ValueMember = "Khoi";
        }

        private void RB_Khoi11_CheckedChanged(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand("select Khoi, Lop from DSLop where Khoi = 11", conn);
            SqlDataAdapter da = new SqlDataAdapter(cm);
            DataSet ds = new DataSet();
            da.Fill(ds, "DSLop");
            if (CB_TenLop.DataSource != null)
            {
                CB_TenLop.DataSource = null;
                CB_TenLop.Refresh();
            }
            CB_TenLop.DataSource = ds.Tables[0];
            CB_TenLop.DisplayMember = "Lop";
            CB_TenLop.ValueMember = "Khoi";

            SqlCommand cm2 = new SqlCommand("select Khoi, Lop from LopDuKien where Khoi = 11", conn);
            SqlDataAdapter da2 = new SqlDataAdapter(cm2);
            DataSet ds2 = new DataSet();
            da2.Fill(ds2, "LopDuKien");
            if (CB_TenLopMoi.DataSource != null)
            {
                CB_TenLopMoi.DataSource = null;
                CB_TenLopMoi.Refresh();
            }
            CB_TenLopMoi.DataSource = ds2.Tables[0];
            CB_TenLopMoi.DisplayMember = "Lop";
            CB_TenLopMoi.ValueMember = "Khoi";
        }

        private void RB_Khoi12_CheckedChanged(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand("select Khoi, Lop from DSLop where Khoi = 12", conn);
            SqlDataAdapter da = new SqlDataAdapter(cm);
            DataSet ds = new DataSet();
            da.Fill(ds, "DSLop");
            if (CB_TenLop.DataSource != null)
            {
                CB_TenLop.DataSource = null;
                CB_TenLop.Refresh();
            }
            CB_TenLop.DataSource = ds.Tables[0];
            CB_TenLop.DisplayMember = "Lop";
            CB_TenLop.ValueMember = "Khoi";

            SqlCommand cm2 = new SqlCommand("select Khoi, Lop from LopDuKien where Khoi = 12", conn);
            SqlDataAdapter da2 = new SqlDataAdapter(cm2);
            DataSet ds2 = new DataSet();
            da2.Fill(ds2, "LopDuKien");
            if (CB_TenLopMoi.DataSource != null)
            {
                CB_TenLopMoi.DataSource = null;
                CB_TenLopMoi.Refresh();
            }
            CB_TenLopMoi.DataSource = ds2.Tables[0];
            CB_TenLopMoi.DisplayMember = "Lop";
            CB_TenLopMoi.ValueMember = "Khoi";
        }

        private void PB_Save_Click(object sender, EventArgs e)
        {
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            if (String.IsNullOrEmpty(CB_TenLop.Text.ToString()))
            {
                MessageBox.Show("Không tồn tại lớp nào để sửa", "Thông báo");
            }
            else
            {
                if (Test.KtraTrungLop(CB_TenLopMoi.Text.ToString()) == 1)
                {
                    MessageBox.Show("Tên lớp mới đã tồn tại, vui lòng chọn tên khác", "Thông báo");
                }
                else
                {
                    int MaLop = int.Parse(Test.SelectMaLop(CB_TenLop.Text.ToString()).ToString());
                    Test.CapNhatTenLopDSLop(CB_TenLopMoi.Text.ToString(), MaLop);
                    MessageBox.Show("Sửa tên lớp thành công", "Thông báo");
                }
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
    }
}
