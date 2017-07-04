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
    public partial class frmMainGV : Form
    {
        public frmMainGV()
        {
            InitializeComponent();
        }

        private void bt_LapBaoCao_Click(object sender, EventArgs e)
        {
            frmLapBaoCao frm1 = new frmLapBaoCao();
            frm1.ShowDialog();
        }

        private void bt_HanhKiem_Click(object sender, EventArgs e)
        {
            //frmHanhKiem frm= new frmHanhKiem();
          //  frm.ShowDialog();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_Search f = new F_Search();
            f.Show();

        }
    }
}
