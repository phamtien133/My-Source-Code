using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace QLHS
{
    public partial class ClassReport : Form
    {
        public ClassReport()
        {
            InitializeComponent();
        }

        private void cbClass_SelectedIndexChanged(object sender, EventArgs e)
        {

        }

        private void ClassReport_Load(object sender, EventArgs e)
        {
            this.AcceptButton = btnReport;

            string sql = "select MALOP from LOP ";
            DataTable LOP = CSDL.Read(sql);
            cbClass.DataSource = LOP;
            cbClass.DisplayMember = "MALOP";
            cbClass.ValueMember = "MALOP";

            sql = "select MAHK from HOCKY";
            DataTable HocKy = CSDL.Read(sql);
            cbSemester.DataSource = HocKy;
            cbSemester.DisplayMember = "MAHK";
            cbSemester.ValueMember = "MAHK";

            sql = "select * from NIENKHOA";
            DataTable NienKhoa = CSDL.Read(sql);
            cbYear.DataSource = NienKhoa;
            cbYear.DisplayMember = "TENNK";
            cbYear.ValueMember = "MANK";
        }

        private void btnReport_Click(object sender, EventArgs e)
        {
            string sql = "select MAHS from DIEMTBM where MALOP = '" + cbClass.SelectedValue.ToString()
                        + "' and HOCKY = '" + cbSemester.SelectedValue.ToString()
                        + "' and NIENKHOA = '" + cbYear.SelectedValue.ToString() + "'";
            DataTable report = CSDL.Read(sql);
            if (report.Rows.Count > 0)
            {
                this.Hide();
                SuccessfulClassReport form2 = new SuccessfulClassReport(cbClass.SelectedValue.ToString(), cbSemester.SelectedValue.ToString(), cbYear.SelectedValue.ToString());
                form2.Show();
                //this.Close();
            }
            else
                MessageBox.Show("Thông tin nhập không chính xác.", "Thông báo");
        }

        private void btnBack_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
