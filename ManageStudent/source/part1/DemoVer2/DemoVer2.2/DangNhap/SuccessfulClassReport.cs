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
    public partial class SuccessfulClassReport : Form
    {
        public SuccessfulClassReport()
        {
            InitializeComponent();
        }

        public SuccessfulClassReport(string _class, string _semester, string _year): this()
        {
            txtClass.Text = _class;
            txtSemester.Text = _semester;
            txtYear.Text = _year;
        }

        private void SuccessfulClassReport_Load(object sender, EventArgs e)
        {
            txtNumber_TextChanged(sender, e);

            txtHLG_TextChanged(sender, e);
            txtHLK_TextChanged(sender, e);
            txtHLTB_TextChanged(sender, e);
            txtHLY_TextChanged(sender, e);
            txtHLKem_TextChanged(sender, e);

            txtHKT_TextChanged(sender, e);
            txtHKK_TextChanged(sender, e);
            txtHKTB_TextChanged(sender, e);
            txtHKY_TextChanged(sender, e);
        }

        private void txtNumber_TextChanged(object sender, EventArgs e)
        {
            string sql = "select SISO from LOP where MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "'";
            DataTable NumberOfStudent = CSDL.Read(sql);
            txtNumber.Text = NumberOfStudent.Rows[0][0].ToString();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            this.Close();
            ClassReport report = new ClassReport();
            report.ShowDialog();
        }

        private void txtHLG_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HOCLUC = 'G'"
                     + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable Gioi = CSDL.Read(sql);
            txtHLG.Text = Gioi.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * Gioi.Rows.Count / number;
            txtTLG.Text = Convert.ToString(percent) + "%";
        }

        private void txtHLK_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HOCLUC = 'K'" 
                        + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable Kha = CSDL.Read(sql);
            txtHLG.Text = Kha.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * Kha.Rows.Count / number;
            txtTLG.Text = Convert.ToString(percent) + "%";
        }

        private void txtHLTB_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HOCLUC = 'TB'"
                     + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable tb = CSDL.Read(sql);
            txtHLG.Text = tb.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * tb.Rows.Count / number;
            txtTLG.Text = Convert.ToString(percent) + "%";
        }

        private void txtHLY_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HOCLUC = 'Y'"
                     + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable yeu = CSDL.Read(sql);
            txtHLG.Text = yeu.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * yeu.Rows.Count / number;
            txtTLG.Text = Convert.ToString(percent) + "%";
        }

        private void txtHLKem_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HOCLUC = 'Kem'"
                     + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable Kem = CSDL.Read(sql);
            txtHLG.Text = Kem.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * Kem.Rows.Count / number;
            txtTLG.Text = Convert.ToString(percent) + "%";
        }

        private void txtHKT_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HANHKIEM = 'T'"
                    + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable Tot = CSDL.Read(sql);
            txtHKT.Text = Tot.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * Tot.Rows.Count / number;
            txtTLTot.Text = Convert.ToString(percent) + "%";
        }

        private void txtHKK_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HANHKIEM = 'K'"
                   + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable Kha = CSDL.Read(sql);
            txtHKT.Text = Kha.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * Kha.Rows.Count / number;
            txtTLTot.Text = Convert.ToString(percent) + "%";
        }

        private void txtHKTB_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HANHKIEM = 'TB'"
                   + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable TB = CSDL.Read(sql);
            txtHKT.Text = TB.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * TB.Rows.Count / number;
            txtTLTot.Text = Convert.ToString(percent) + "%";
        }

        private void txtHKY_TextChanged(object sender, EventArgs e)
        {
            string sql = "select * from KQHT_HK where HANHKIEM = 'T'"
                   + " and MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "' and HOCKY = '" + txtSemester.Text + "'";
            DataTable Yeu = CSDL.Read(sql);
            txtHKT.Text = Yeu.Rows.Count.ToString();
            int number = int.Parse(txtNumber.Text);
            double percent = 100 * Yeu.Rows.Count / number;
            txtTLTot.Text = Convert.ToString(percent) + "%";
        }
    }
}
