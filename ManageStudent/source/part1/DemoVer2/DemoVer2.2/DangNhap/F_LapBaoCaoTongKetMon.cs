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
    public partial class F_LapBaoCaoTongKetMon : Form
    {
        public F_LapBaoCaoTongKetMon()
        {
            InitializeComponent();
        }

        private void F_LapBaoCao_Load(object sender, EventArgs e)
        {
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
            //Đỗ dữ liệu vào combox môn học
            SqlCommand cm2 = new SqlCommand("select MaMH, TenMH from MonHoc", conn);
            SqlDataAdapter da2 = new SqlDataAdapter(cm2);
            DataSet DSMonHoc = new DataSet();
            da2.Fill(DSMonHoc, "MonHoc");
            CB_MonHoc.DataSource = DSMonHoc.Tables[0];
            CB_MonHoc.DisplayMember = "TenMH";
            CB_MonHoc.ValueMember = "MaMH";
            //Đỗ dữ liệu vào Combo box niên khóa
            SqlCommand cm3 = new SqlCommand("select distinct NienKhoa from Lop", conn);
            SqlDataAdapter da3 = new SqlDataAdapter(cm3);
            DataSet DSNienKhoa = new DataSet();
            da3.Fill(DSNienKhoa, "Lop");
            CB_NienKhoa.DataSource = DSNienKhoa.Tables[0];
            CB_NienKhoa.DisplayMember = "NienKhoa";
            CB_NienKhoa.ValueMember = "NienKhoa";
            //Show combo box
            CB_Lop.Text = "10A1";
            CB_MonHoc.Text = "Toán";
            CB_HocKi.Text = "I";
            CB_NienKhoa.Text = "2015";
        }

        private void panel3_Paint(object sender, PaintEventArgs e)
        {

        }

        private void PB_LapBC_Click(object sender, EventArgs e)
        {
            DangNhap.QLHOCSINHTableAdapters.QLHocSinh Test = new QLHOCSINHTableAdapters.QLHocSinh();
            
            string MaLop = Test.selectMaLop(CB_Lop.Text.ToString()).ToString();
            string SoLuong = Test.DemSoLuongHS(MaLop, CB_NienKhoa.Text.ToString()).ToString();
            string MaMonHoc = Test.SelectMaMH(CB_MonHoc.Text.ToString()).ToString();
            if (SoLuong == "")
            {
                MessageBox.Show("Lớp này chưa có học sinh để lập báo cáo");
            }
            else if(MaMonHoc == "")
            {
                MessageBox.Show("Chưa có môn học này");
            }
            else
            {
                int SoLuongHS = int.Parse(SoLuong);

                for (int i = 1; i <= SoLuongHS; i++)
                {
                    string HoTen = Test.selectTenHS(i, MaLop).ToString();
                    string SoLan15 = Test.DemSoLanKT("15", i, MaMonHoc).ToString();
                    string SoLan45 = Test.DemSoLanKT("45", i, MaMonHoc).ToString();

                    if (SoLan15 == "")
                    {
                        MessageBox.Show("Học sinh này chưa kiểm tra 15 phút lần nào");
                    }
                    else if(SoLan45 == "")
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
                            string Diem = Test.selectDiem15("15",j, MaMonHoc).ToString();
                            Diem15 += " " + Diem;
                        }
                        for (int j = 1; j <= SoLanKT45; j++)
                        {
                            string Diem = Test.selectDiem15("45", j, MaMonHoc).ToString();
                            Diem45 += " " + Diem;
                        }
                        if (Test.KtraTrungDuLieuBaoCaoMon(i, HoTen, Diem15, Diem45) == 1)
                        {
                            MessageBox.Show("Báo cáo này đã có");
                        }
                        else
                        {
                            
                            Test.insertBaoCaoMon(i, HoTen, Diem15, Diem45);
                        }
                    }
                }
            }
            DataTable dt = new DataTable();
            SqlDataAdapter da = new SqlDataAdapter();
            SqlConnection conn = new SqlConnection("Data Source = .\\SQLEXPRESS; Initial Catalog = QLHOCSINH;Integrated Security=TRUE");
            conn.Open();
            SqlCommand cm = new SqlCommand();
            cm.Connection = conn;
            cm.CommandType = CommandType.Text;

            cm.CommandText = @" select *
                                from BaoCaoMon
                                ";
            da.SelectCommand = cm;
            da.Fill(dt);
            if (DTG_BaoCao.DataSource != null)
            {
                DTG_BaoCao.DataSource = null;
                DTG_BaoCao.Rows.Clear();
                DTG_BaoCao.Refresh();
            }
            DTG_BaoCao.DataSource = dt;

        }
    }
}
