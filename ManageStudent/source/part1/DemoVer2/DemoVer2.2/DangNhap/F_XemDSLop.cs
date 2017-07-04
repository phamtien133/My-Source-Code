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
    public partial class F_XemDSLop : Form
    {
        DataTable dtHocSinh;
        DataView dvHocSinh;
        SqlDataAdapter Adapter;
        public F_XemDSLop()
        {
            InitializeComponent();
        }

        private void label8_Click(object sender, EventArgs e)
        {

        }

        private void label2_Click(object sender, EventArgs e)
        {

        }

        private void label9_Click(object sender, EventArgs e)
        {

        }

        private void PB_XemDS_Click(object sender, EventArgs e)
        {
            string Lop = CB_Lop.Text;
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            int MaLop = int.Parse(Test.SelectMaLop(Lop).ToString());
            dvHocSinh.RowFilter = "MaLop = '" + MaLop + "'";
            DTG_Search.DataSource = dvHocSinh;
            if (DTG_Search.RowCount == 0)
            {
                MessageBox.Show("Lớp này chưa có học sinh!");
            }
        }

        private void F_XemDSLop_Load(object sender, EventArgs e)
        {
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            Adapter = new SqlDataAdapter("select * from DSHocSinh", conn);
            dtHocSinh = new DataTable("DSHocSinh");
            Adapter.Fill(dtHocSinh);
            dvHocSinh = new DataView(dtHocSinh);
            CB_Lop.Text = "10A1";
            CB_NienKhoa.Text = "2015 - 2016";
            //DTG_Search.DataSource = dvHocSinh;
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
                F_MainNVPDT f = new F_MainNVPDT();
                f.Show();
            }
        }
    }
}
