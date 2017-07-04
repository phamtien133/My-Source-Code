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

namespace GIAOVIEN
{
    public partial class frmHanhKiem : Form
    {
        public frmHanhKiem()
        {
            InitializeComponent();
        }

        private void frmHanhKiem_Load(object sender, EventArgs e)
        {
            this.AcceptButton = bt_HoanTat;
            string sql = "select MALOP from LOP ";
            DataTable Lop = KetNoiCSDL.Read(sql);
            cb_Lop.DataSource = Lop;
            cb_Lop.DisplayMember = "MALOP";
            cb_Lop.ValueMember = "MALOP";

            sql = "select MAHK from HOCKY";
            DataTable HocKy = KetNoiCSDL.Read(sql);
            cb_HocKy.DataSource = HocKy;
            cb_HocKy.DisplayMember = "MAHK";
            cb_HocKy.ValueMember = "MAHK";

            sql = "select * from NIENKHOA";
            DataTable NienKhoa = KetNoiCSDL.Read(sql);
            cb_NienKhoa.DataSource = NienKhoa;
            cb_NienKhoa.DisplayMember = "TENNK";
            cb_NienKhoa.ValueMember = "MANK";
        }

        private void bt_HoanTat_Click(object sender, EventArgs e)
        {
            
            string sql = "SELECT MAHS FROM  KQHT_HK WHERE NIENKHOA = '" 
            + cb_NienKhoa.SelectedValue.ToString()+ "'AND MALOP = '"
            + cb_Lop.SelectedValue.ToString() + "' AND HOCKY = '"
            + cb_HocKy.SelectedValue.ToString() + "'";
         

            DataTable HanhKiem;
            HanhKiem = KetNoiCSDL.Read(sql);
            if (HanhKiem.Rows.Count > 0)
            {
                this.Hide();
                frmHanhKiemTC form2 = new frmHanhKiemTC(cb_Lop.SelectedValue.ToString(), cb_HocKy.SelectedValue.ToString(), cb_NienKhoa.SelectedValue.ToString());
                form2.Show();
               
            }
            else
                MessageBox.Show("Thông tin nhập không chính xác.", "Thông báo");
        }

        private void bt_QuayLai_Click(object sender, EventArgs e)
        {
            this.Hide();
            frmMainGV frm = new frmMainGV();
            frm.ShowDialog();
        }
    }
}
