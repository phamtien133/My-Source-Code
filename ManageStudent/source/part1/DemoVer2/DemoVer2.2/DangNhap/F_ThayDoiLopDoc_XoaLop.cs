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
    public partial class F_ThayDoiLopDoc_XoaLop : Form
    {
        public F_ThayDoiLopDoc_XoaLop()
        {
            InitializeComponent();
        }

        private void F_ThayDoiLopDoc_XoaLop_Load(object sender, EventArgs e)
        {
            RB_Khoi10.Checked = true;
        }

        private void RB_Khoi10_CheckedChanged(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand("select MaLop, Lop from DSLop where Khoi = 10", conn);
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
            CB_TenLop.ValueMember = "MaLop";
        }

        private void RB_Khoi11_CheckedChanged(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand("select MaLop, Lop from DSLop where Khoi = 11", conn);
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
            CB_TenLop.ValueMember = "MaLop";
        }

        private void RB_Khoi12_CheckedChanged(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand("select MaLop, Lop from DSLop where Khoi = 12", conn);
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
            CB_TenLop.ValueMember = "MaLop";
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

        private void PB_Save_Click(object sender, EventArgs e)
        {
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            int MaLop = int.Parse(Test.SelectMaLop(CB_TenLop.Text.ToString()).ToString());
            Test.XoaLopDSHocSinh(MaLop);
            Test.XoaLopDSLop(MaLop);
            MessageBox.Show("Xóa lớp thành công", "Thông báo");
        }
    }
}
