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
    public partial class F_LapBangDiemHocKy : Form
    {
        public F_LapBangDiemHocKy()
        {
            InitializeComponent();
        }

        private void F_LapBangDiemHocKy_Load(object sender, EventArgs e)
        {
            DangNhap.QLHOCSINHTableAdapters.QLHocSinh Test = new QLHOCSINHTableAdapters.QLHocSinh();
            
            //Kết nối CSDL
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QLHOCSINH;Integrated Security=TRUE");
            conn.Open();
            //Đổ dữ liệu vào combo box lớp
            SqlCommand cm = new SqlCommand("select MaLop, TenLop from Lop", conn);
            SqlDataAdapter da = new SqlDataAdapter(cm);
            DataSet DSLop = new DataSet();
            da.Fill(DSLop, "Lop");
            CB_Lop.DataSource = DSLop.Tables[0];
            CB_Lop.DisplayMember = "TenLop";
            CB_Lop.ValueMember = "MaLop";
            //Đỗ dữ liệu vào Combo box mã học sinh
            string MaLop = Test.selectMaLop(CB_Lop.Text.ToString()).ToString();
            if (CB_MaHS.DataSource != null)
            {
                CB_MaHS.DataSource = null;
                CB_MaHS.Refresh();
            }
            SqlCommand cm2 = new SqlCommand("select MaHS from HocSinh where MaLop = '"+MaLop+"'", conn);
            SqlDataAdapter da2 = new SqlDataAdapter(cm2);
            DataSet DSMaHS = new DataSet();
            da2.Fill(DSMaHS, "HocSinh");
            CB_MaHS.DataSource = DSMaHS.Tables[0];
            CB_MaHS.DisplayMember = "MaHS";
            //Đỗ dữ liệu vào Combo box niên khóa
            SqlCommand cm3 = new SqlCommand("select distinct NienKhoa from Lop", conn);
            SqlDataAdapter da3 = new SqlDataAdapter(cm3);
            DataSet DSNienKhoa = new DataSet();
            da3.Fill(DSNienKhoa, "Lop");
            CB_NienKhoa.DataSource = DSNienKhoa.Tables[0];
            CB_NienKhoa.DisplayMember = "NienKhoa";
            CB_NienKhoa.ValueMember = "NienKhoa";
            //Đỗ dữ liệu vào text box tên học sinh
            
            string HoTen = Test.selectTenHS2(CB_MaHS.Text.ToString(), CB_NienKhoa.Text.ToString(), MaLop);
            TB_TenHS.Text = HoTen;
            //Show combo box
            CB_Lop.Text = "10A1";
            CB_HocKi.Text = "I";
            CB_NienKhoa.Text = "2015";
            //Lưu dữ liệu của các điểm của học sinh xuống csdl
            string SoLuong = Test.DemSoLuongHS(MaLop, CB_NienKhoa.Text.ToString()).ToString();
            if(SoLuong != "")
            {
                int SoLuongHS = int.Parse(SoLuong);
                
                for (int i = 1; i <= SoLuongHS; i++)
                {
                    string HoTen1 = Test.selectTenHS(i, MaLop).ToString();
                    string MaMonHoc = Test.selectMaMH2(i).ToString();
                    string SoLan15 = Test.DemSoLanKT("15", i, MaMonHoc).ToString();
                    string SoLan45 = Test.DemSoLanKT("45", i, MaMonHoc).ToString();
                    string TenMH = Test.selectTenMH(MaMonHoc).ToString();
                    if (SoLan15 == "")
                    {
                        MessageBox.Show("Học sinh này chưa kiểm tra 15 phút lần nào");
                    }
                    else if (SoLan45 == "")
                    {
                        MessageBox.Show("Học sinh này chưa kiểm tra 45 phút lần nào");
                    }
                    else
                    {
                        int SoLanKT15 = int.Parse(SoLan15);
                        int SoLanKT45 = int.Parse(SoLan45);
                        string Diem15 = "";
                        string Diem45 = "";
                        for (int j = 1; j <= SoLanKT15; j++)
                        {
                            string Diem = Test.selectDiem15("15", j, MaMonHoc).ToString();
                            Diem15 += " " + Diem;
                        }
                        for (int j = 1; j <= SoLanKT45; j++)
                        {
                            string Diem = Test.selectDiem15("45", j, MaMonHoc).ToString();
                            Diem45 += " " + Diem;
                        }
                        if (Test.KtraTrungDuLieuBaoCaoMon(i, HoTen1, Diem15, Diem45) == 0)
                        {
                            Test.insertBaoCaoMon2(i, TenMH,HoTen1, Diem15, Diem45);
                        }
                    }
                }
            }
            //Đổ dữ liệu vào datagrid view bảng điểm
            string HoTen2 = Test.selectTenHS2(CB_MaHS.Text.ToString(), CB_NienKhoa.Text.ToString(), MaLop).ToString();
            SqlCommand cm6 = new SqlCommand("select TenMH, Diem15,Diem45 from BaoCaoMon where HoTen = N'"+HoTen2+"'", conn);
            SqlDataAdapter da6 = new SqlDataAdapter(cm6);
            DataSet DSBangDiem = new DataSet();
            da6.Fill(DSBangDiem, "BaoCaoMon");
            DTG_BangDiem.DataSource = DSBangDiem.Tables[0];
            
        }

        private void CB_MaHS_SelectedIndexChanged(object sender, EventArgs e)
        {
            //Kết nối CSDL
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QLHOCSINH;Integrated Security=TRUE");
            DangNhap.QLHOCSINHTableAdapters.QLHocSinh Test = new QLHOCSINHTableAdapters.QLHocSinh();
            
            conn.Open();
            string MaLop = Test.selectMaLop(CB_Lop.Text.ToString()).ToString();
            string HoTen = Test.selectTenHS2(CB_MaHS.Text.ToString(), CB_NienKhoa.Text.ToString(), MaLop);
            TB_TenHS.Text = HoTen;
            //Đổ dữ liệu vào datagrid view bảng điểm
            SqlCommand cm6 = new SqlCommand("select TenMH, Diem15,Diem45 from BaoCaoMon where HoTen = N'" + HoTen+ "'", conn);
            SqlDataAdapter da6 = new SqlDataAdapter(cm6);
            DataSet DSBangDiem = new DataSet();
            da6.Fill(DSBangDiem, "BaoCaoMon");
            DTG_BangDiem.DataSource = DSBangDiem.Tables[0];
        }

        private void CB_Lop_SelectedIndexChanged(object sender, EventArgs e)
        {
            //Kết nối CSDL
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QLHOCSINH;Integrated Security=TRUE");
            conn.Open();
            DangNhap.QLHOCSINHTableAdapters.QLHocSinh Test = new QLHOCSINHTableAdapters.QLHocSinh();
            
            string MaLop = Test.selectMaLop(CB_Lop.Text.ToString()).ToString();
            
            if (CB_MaHS.DataSource != null)
            {
                CB_MaHS.DataSource = null;
                CB_MaHS.Refresh();
            }
            SqlCommand cm2 = new SqlCommand("select MaHS from HocSinh where MaLop = '" + MaLop + "'", conn);
            SqlDataAdapter da2 = new SqlDataAdapter(cm2);
            DataSet DSMaHS = new DataSet();
            da2.Fill(DSMaHS, "HocSinh");
            CB_MaHS.DataSource = DSMaHS.Tables[0];
            CB_MaHS.DisplayMember = "MaHS";
            string HoTen2 = Test.selectTenHS2(CB_MaHS.Text.ToString(), CB_NienKhoa.Text.ToString(), MaLop);
            //Lưu dữ liệu của các điểm của học sinh xuống csdl
            string SoLuong = Test.DemSoLuongHS(MaLop, CB_NienKhoa.Text.ToString()).ToString();
            //string MaMonHoc = Test.SelectMaMH(CB_MonHoc.Text.ToString()).ToString();
            if (SoLuong != "")
            {
                int SoLuongHS = int.Parse(SoLuong);

                for (int i = 1; i <= SoLuongHS; i++)
                {
                    string HoTen1 = Test.selectTenHS(i, MaLop).ToString();
                    string MaMonHoc = Test.selectMaMH2(i).ToString();
                    string SoLan15 = Test.DemSoLanKT("15", i, MaMonHoc).ToString();
                    string SoLan45 = Test.DemSoLanKT("45", i, MaMonHoc).ToString();
                    string TenMH = Test.selectTenMH(MaMonHoc).ToString();
                    if (SoLan15 == "")
                    {
                        MessageBox.Show("Học sinh này chưa kiểm tra 15 phút lần nào");
                    }
                    else if (SoLan45 == "")
                    {
                        MessageBox.Show("Học sinh này chưa kiểm tra 45 phút lần nào");
                    }
                    else
                    {
                        int SoLanKT15 = int.Parse(SoLan15);
                        int SoLanKT45 = int.Parse(SoLan45);
                        string Diem15 = "";
                        string Diem45 = "";
                        for (int j = 1; j <= SoLanKT15; j++)
                        {
                            string Diem = Test.selectDiem15("15", j, MaMonHoc).ToString();
                            Diem15 += " " + Diem;
                        }
                        for (int j = 1; j <= SoLanKT45; j++)
                        {
                            string Diem = Test.selectDiem15("45", j, MaMonHoc).ToString();
                            Diem45 += " " + Diem;
                        }
                        if (Test.KtraTrungDuLieuBaoCaoMon(i, HoTen1, Diem15, Diem45) == 0)
                        {
                            Test.insertBaoCaoMon2(i, TenMH, HoTen1, Diem15, Diem45);
                        }
                    }
                }
            }
            //Đổ dữ liệu vào datagrid view bảng điểm
            
            SqlCommand cm6 = new SqlCommand("select TenMH, Diem15,Diem45 from BaoCaoMon where HoTen = N'" + HoTen2 + "'", conn);
            SqlDataAdapter da6 = new SqlDataAdapter(cm6);
            DataSet DSBangDiem = new DataSet();
            da6.Fill(DSBangDiem, "BaoCaoMon");
            DTG_BangDiem.DataSource = DSBangDiem.Tables[0];
        }
    }
}
