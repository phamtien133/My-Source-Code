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
            this.Hide();
            frmLapBaoCao frm = new frmLapBaoCao();
            frm.ShowDialog();
        }

        private void bt_HanhKiem_Click(object sender, EventArgs e)
        {
            this.Hide();
            frmHanhKiem frm= new frmHanhKiem();
            frm.ShowDialog();
            
        }
    }
}
