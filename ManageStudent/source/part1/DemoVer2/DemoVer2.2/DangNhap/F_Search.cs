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
    public partial class F_Search : Form
    {
        DataTable dtHocSinh;
        DataView dvHocSinh;
        SqlDataAdapter Adapter;

   
        public F_Search()
        {
            InitializeComponent();
        }

        private void label6_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn đăng xuất hay không?", "THÔNG BÁO", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));
            if (ThongBao == DialogResult.Yes && F_Login.Role_Login.Role == 2)
            {
                this.Hide();
                F_Login f = new F_Login();
                f.Show();
            }
            else if (ThongBao == DialogResult.Yes && F_Login.Role_Login.Role == 1)
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
            if (ThongBao == DialogResult.Yes && F_Login.Role_Login.Role == 2)
            {
                this.Hide();
                F_MainNVPDT f = new F_MainNVPDT();
                f.Show();
            }
            else if (ThongBao == DialogResult.Yes && F_Login.Role_Login.Role == 1)
            {
                this.Hide();
                F_MainTruongPDT f = new F_MainTruongPDT();
                f.Show();
            }
        }

        private void F_Search_Load(object sender, EventArgs e)
        {
            // TODO: This line of code loads data into the 'quanLyHocSinhDataSet.DSHocSinh' table. You can move, or remove it, as needed.
            //this.dSHocSinhTableAdapter.Fill(this.quanLyHocSinhDataSet.DSHocSinh);
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            Adapter = new SqlDataAdapter("select * from DSHocSinh", conn);
            dtHocSinh = new DataTable("DSHocSinh");
            Adapter.Fill(dtHocSinh);
            dvHocSinh = new DataView(dtHocSinh);
            CB_Lop.Text = "10A1";
            CB_NienKhoa.Text = "2015 - 2016";
            if (F_Login.Role_Login.Role == 2)
            {
                LB_XinChao.Text = "Xin chào, Nhân viên";
            }
            else if (F_Login.Role_Login.Role == 1)
            {
                LB_XinChao.Text = "Xin chào, Admin";
            }
            //DTG_Search.DataSource = dvHocSinh;
        }

        private void PB_TraCuu_Click(object sender, EventArgs e)
        {

            string HoTen = TB_HoTen.Text;
            string Lop = CB_Lop.Text;
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            int MaLop = int.Parse(Test.SelectMaLop(Lop).ToString());
            if (Test.KtraCoLopChua() == 0)
            {
                MessageBox.Show("Chưa sắp xếp lớp cho học sinh!");
            }
            else
            {
                dvHocSinh.RowFilter = "HoTen like '%" + HoTen + "%' or MaLop = '" + MaLop + "'";
                DTG_Search.DataSource = dvHocSinh;
                if (DTG_Search.RowCount == 0)
                {
                    MessageBox.Show("Không tồn tại học sinh này, vui lòng kiểm tra lại!");
                }
            }
        }
    }
}
