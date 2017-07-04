using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace GIAOVIEN
{
    public partial class frmHanhKiemTC : Form
    {
        public frmHanhKiemTC()
        {
            InitializeComponent();
        }
        public frmHanhKiemTC(string L, string HK, string NK)
            : this()
        {

            txtLop.Text = L;
            txtHocKy.Text = HK;
            txtNienKhoa.Text = NK;

        }

        private void frmHanhKiemTC_Load(object sender, EventArgs e)
        {
            gv_DSHK_CellContentClick(sender, e);
        }

        private void gv_DSHK_CellContentClick(object sender, EventArgs e)
        {
            int TaoCot = 0;
            if (TaoCot == 0)
            {
                //Tao from nhap danh sach hanh kiem
                DataTable DS_HanhKiem = new DataTable();

                DataColumn stt = new DataColumn("STT", typeof(int));
                DS_HanhKiem.Columns.Add(stt);

                DataColumn MaHS = new DataColumn("Mã HS", typeof(string));
                DS_HanhKiem.Columns.Add(MaHS);

                // Thêm cột Họ tên
                DataColumn HoTen = new DataColumn("Họ và Tên", typeof(string));
                DS_HanhKiem.Columns.Add(HoTen);

                DataColumn HanhKiem = new DataColumn("Hạnh kiểm", typeof(string));
                DS_HanhKiem.Columns.Add(HanhKiem);

                string sql = "select MAHS FROM KQHT_HK KQ, LOP L WHERE L.NIENKHOA = KQ.NIENKHOA  AND KQ.MALOP = L.MALOP";//, HOCSINH HS WHERE KQ.MAHS = HS.MAHS";
                DataTable dsLop = KetNoiCSDL.Read(sql);
                DataTable Mark;
               for (int i = 0; i < dsLop.Rows.Count; i++)
                  {
                      sql = "SELECT HS.MAHS, HS.HOTENHS FROM HOCSINH HS, KQHT_HK KQ, LOP L "
                          + "WHERE KQ.NIENKHOA = L.NIENKHOA AND KQ.MAHS = HS.MAHS AND KQ.MALOP = L.MALOP "
                     + "and HS.MAHS = '" + dsLop.Rows[i][0].ToString()
                     + "' and L.MALOP = '" + txtLop.Text + "' AND KQ.NIENKHOA = '" 
                     + txtNienKhoa.Text + "' AND KQ.HOCKY = '" + txtHocKy.Text + "'"; 
                      Mark = KetNoiCSDL.Read(sql);
            
                      DataRow row = DS_HanhKiem.NewRow();
                      row["STT"] = i + 1;
                      row["Mã HS"] = Mark.Rows[0][0].ToString();
                      row["Họ và Tên"] = Mark.Rows[0][1].ToString();

                      DS_HanhKiem.Rows.Add(row);
                  }
                DataView DS = new DataView(DS_HanhKiem);
                gv_DSHK.DataSource = DS;
            }
           /* string sql = "SELECT MAHS, HOTENHS FROM HOCSINH HS, KQHT_HK KQ, GV_LOP L"
            +"WHERE HS.MAHS = KQ.MAHS AND HS.NIENKHOA = KQ.NIENKHOA AND L.MALOP = KQ.MALOP AND L.HOCKY = KQ.HOCKY"
            + "KQ.MALOP = '" + txtLop.Text + "'AND KQ.NIENKHOA = '" + txtNienKhoa.Text + "'AND KQ.HOCKY = '" + txtHocKy.Text + "'"; 

            DataTable Mark = KetNoiCSDL.Read(sql);
            DataView DS_HanhKiem = new DataView(Mark);
            gv_DSHK.DataSource = DS_HanhKiem;*/
        }

        private void bt_Luu_Click(object sender, EventArgs e)
        {
            try
            {
                for (int i = 0; i < gv_DSHK.Rows.Count; i++)
                {
                    int sHanhKiem = int.Parse(gv_DSHK.Rows[i].Cells["HanhKiem"].Value.ToString());
                    int sMaHS = int.Parse(gv_DSHK.Rows[i].Cells["MaHS"].Value.ToString());
                    string sInsert = string.Format(@"INSERT INTO KQHT_HK VALUES ({0},{6})", sMaHS, sHanhKiem);
                    KetNoiCSDL.Read(sInsert);

                }
                MessageBox.Show("Lưu thành công", "Thông báo");
            }
            catch (Exception ex)
            {
                MessageBox.Show("Nhập thất bại", "Thông báo");
            }

        }

        private void bt_QuayLai_Click(object sender, EventArgs e)
        {
            this.Hide();
           frmHanhKiem frm = new frmHanhKiem();
            frm.ShowDialog();
        }
    }
}
