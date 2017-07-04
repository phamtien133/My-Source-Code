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
using System.Collections;

namespace DangNhap
{
    public partial class F_LapDSLop : Form
    {
        //private DataTable dt = new DataTable("Danh Sach Hoc Sinh");
        //private SqlDataAdapter da = new SqlDataAdapter();
        DataTable dtHocSinh;
        DataView dvHocSinh;
        SqlDataAdapter Adapter;

        public F_LapDSLop()
        {
            InitializeComponent();
        }

        private void dataGridView1_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void RB_Khoi10_CheckedChanged(object sender, EventArgs e)
        {
            DataTable dt = new DataTable("Danh Sach Hoc Sinh");
            SqlDataAdapter da = new SqlDataAdapter();
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand();
            cm.Connection = conn;
            cm.CommandType = CommandType.Text;

            cm.CommandText = @"select Khoi as N'Khối',
                                HoTen as N'Họ và Tên',
                                GioiTinh as N'Giới tính',
                                NgaySinh as N'Ngày sinh',
                                DiaChi as N'Địa chỉ',
                                Email as N'Email'
                                
                                from DSHocSinh
                                where Khoi = 10 and MaLop is null
                                ";
            da.SelectCommand = cm;
            da.Fill(dt);
            if (DTG_LapDSHS.DataSource != null)
            {
                DTG_LapDSHS.DataSource = null;
                DTG_LapDSHS.Rows.Clear();
                DTG_LapDSHS.Refresh();
            }

            DTG_LapDSHS.DataSource = dt;
        }

        private void RB_Khoi11_CheckedChanged(object sender, EventArgs e)
        {
            DataTable dt = new DataTable("Danh Sach Hoc Sinh");
            SqlDataAdapter da = new SqlDataAdapter();
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand();
            cm.Connection = conn;
            cm.CommandType = CommandType.Text;
            
            cm.CommandText = @"select Khoi as N'Khối',
                                HoTen as N'Họ và Tên',
                                GioiTinh as N'Giới tính',
                                NgaySinh as N'Ngày sinh',
                                DiaChi as N'Địa chỉ',
                                Email as N'Email'
                                
                                from DSHocSinh
                                where Khoi = 11 and MaLop is null
                                ";
            da.SelectCommand = cm;
            da.Fill(dt);
            if (DTG_LapDSHS.DataSource != null)
            {
                DTG_LapDSHS.DataSource = null;
                DTG_LapDSHS.Rows.Clear();
                DTG_LapDSHS.Refresh();
            }
            
            DTG_LapDSHS.DataSource = dt;
        }

        private void RB_Khoi12_CheckedChanged(object sender, EventArgs e)
        {
            DataTable dt = new DataTable("Danh Sach Hoc Sinh");
            SqlDataAdapter da = new SqlDataAdapter();
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand();
            cm.Connection = conn;
            cm.CommandType = CommandType.Text;
            DTG_LapDSHS.DataSource = "";
            cm.CommandText = @"select Khoi as N'Khối',
                                HoTen as N'Họ và Tên',
                                GioiTinh as N'Giới tính',
                                NgaySinh as N'Ngày sinh',
                                DiaChi as N'Địa chỉ',
                                Email as N'Email'
                                
                                from DSHocSinh
                                where Khoi = 12 and MaLop is null
                                ";
            da.SelectCommand = cm;
            da.Fill(dt);
            if (DTG_LapDSHS.DataSource != null)
            {
                DTG_LapDSHS.DataSource = null;
                DTG_LapDSHS.Rows.Clear();
                DTG_LapDSHS.Refresh();
            }
            DTG_LapDSHS.DataSource = dt;
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
        List<ArrayList> DSLop = new List<ArrayList>();
        private void pictureBox1_Click(object sender, EventArgs e)
        {
            DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
            //int Khoi =Int32.Parse( DTG_LapDSHS.Rows[0].Cells[0].Value.ToString());
            //Console.WriteLine(Khoi);
            if (RB_Khoi10.Checked == false && RB_Khoi11.Checked == false && RB_Khoi12.Checked == false)
            {
                MessageBox.Show("Vui lòng chọn khối cần lập danh sách");
            }
            else
            {
                if (DTG_LapDSHS.RowCount == 0)
                {
                    MessageBox.Show("Khối này chưa có sinh viên mơi để lập danh sách!");
                }
                else
                {
                    for (int i = 0; i < DTG_LapDSHS.RowCount; i++)
                    {
                        
                        int Khoi = Int32.Parse(DTG_LapDSHS.Rows[i].Cells[0].Value.ToString());
                        string HoTen = DTG_LapDSHS.Rows[i].Cells[1].Value.ToString();
                        string GTinh = DTG_LapDSHS.Rows[i].Cells[2].Value.ToString();
                        //AddHocSinh(Khoi, HoTen, GTinh);
                        string Lop = Khoi.ToString() + "A1";
                        int T1 = int.Parse(Test.CountLop(Lop).ToString());
                        string Lop2 = Khoi.ToString() + "A2";
                        int T2 = int.Parse(Test.CountLop(Lop2).ToString());
                        string Lop3 = Khoi.ToString() + "A3";
                        int T3 = int.Parse(Test.CountLop(Lop3).ToString());
                        string Lop4 = Khoi.ToString() + "A4";
                        int T4 = int.Parse(Test.CountLop(Lop4).ToString());
                        string Lop5 = Khoi.ToString() + "A5";
                        int T5 = int.Parse(Test.CountLop(Lop5).ToString());
                        string Lop6 = Khoi.ToString() + "A6";
                        int T6 = int.Parse(Test.CountLop(Lop6).ToString());
                        string Lop7 = Khoi.ToString() + "A7";
                        int T7 = int.Parse(Test.CountLop(Lop7).ToString());
                        string Lop8 = Khoi.ToString() + "A8";
                        int T8 = int.Parse(Test.CountLop(Lop8).ToString());
                        string Lop9 = Khoi.ToString() + "A9";
                        //j++;
                        int SiSo = int.Parse((Test.SelectSiSo("admin").ToString()));
                        
                        if ( T1 == SiSo)
                        {
                            Lop = Lop2;
                        }
                        if (T2 == SiSo)
                        {
                            Lop = Lop3;
                        }
                        if (T3 == SiSo)
                        {
                            Lop = Lop4;
                        }
                        if (T4 == SiSo)
                        {
                            Lop = Lop5;
                        }
                        if (T5 == SiSo)
                        {
                            Lop = Lop6;
                        }
                        if (T6 == SiSo)
                        {
                            Lop = Lop7;
                        }
                        if (T7 == SiSo)
                        {
                            Lop = Lop8;
                        }
                        if (T8 == SiSo)
                        {
                            Lop = Lop9;
                        }
                        string Malop = Test.SelectMaLop(Lop).ToString();
                        if (Malop == "")
                        {
                            MessageBox.Show("không tồn tại lớp này!");
                        }
                        else
                        {
                            int MaLop = int.Parse(Malop);

                            //Test.InsDSLop(Khoi, HoTen, GTinh, MaLop);
                            Test.UpLop(MaLop, HoTen, Khoi, GTinh);
                            MessageBox.Show("success");
                        }
                    }
                    
                }
            }
            
            
        }
        private void AddHocSinh(int Khoi, String HoTen, String GTinh)
        {
            ArrayList hs = new ArrayList()
                    {
                         Khoi,HoTen,GTinh
                    };
            DSLop.Add(hs);
        }

        private void F_LapDSLop_Load(object sender, EventArgs e)
        {
            
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QuanLyHocSinh;Integrated Security=TRUE");
            Adapter = new SqlDataAdapter("select * from DSHocSinh where MaLop is null", conn);
            dtHocSinh = new DataTable("DSHocSinh");
            Adapter.Fill(dtHocSinh);
            dvHocSinh = new DataView(dtHocSinh);
            DTG_LapDSHS.DataSource = dvHocSinh;

        }

        private void panel1_Paint(object sender, PaintEventArgs e)
        {

        }

        private void tableLayoutPanel1_Paint(object sender, PaintEventArgs e)
        {

        }

        private void panel2_Paint(object sender, PaintEventArgs e)
        {

        }

        private void pictureBox3_Click(object sender, EventArgs e)
        {

        }

        private void label5_Click(object sender, EventArgs e)
        {

        }

        private void panel3_Paint(object sender, PaintEventArgs e)
        {

        }

        private void DTG_LapDSHS_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {

        }

        private void label8_Click(object sender, EventArgs e)
        {

        }

        private void panel4_Paint(object sender, PaintEventArgs e)
        {

        }

        private void label10_Click(object sender, EventArgs e)
        {

        }

        private void label9_Click(object sender, EventArgs e)
        {

        }

        private void pictureBox2_Click(object sender, EventArgs e)
        {

        }

        private void dataSet1BindingSource_CurrentChanged(object sender, EventArgs e)
        {

        }

        private void dataSet1BindingSource1_CurrentChanged(object sender, EventArgs e)
        {

        }

        private void dSHocSinhTableAdapterBindingSource_CurrentChanged(object sender, EventArgs e)
        {

        }
    }
}


