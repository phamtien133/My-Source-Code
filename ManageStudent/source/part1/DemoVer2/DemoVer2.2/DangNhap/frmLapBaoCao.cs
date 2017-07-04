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
    public partial class frmLapBaoCao : Form
    {
        public frmLapBaoCao()
        {
            InitializeComponent();
        }

        private void frmLapBaoCao_Load(object sender, EventArgs e)
        {

        }

        private void bt_LapBD_Click(object sender, EventArgs e)
        {
            frmLapBangDiem frm1 = new frmLapBangDiem();
        }
    }
}
