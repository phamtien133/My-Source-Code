using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace DangNhap
{
    
    public partial class F_MainNVPDT : Form
    {
        
        public F_MainNVPDT()
        {
            InitializeComponent();
            F_Login.Role_Login.Role = 2;
        }
        
        private void pictureBox1_Click(object sender, EventArgs e)
        {

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

        private void pictureBox1_Click_1(object sender, EventArgs e)
        {
            this.Hide();
            F_TiepNhanHocSinh f = new F_TiepNhanHocSinh();
            f.Show();
        }

        private void pictureBox4_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_LapDSLop f = new F_LapDSLop();
            f.Show();
        }

        private void pictureBox5_Click(object sender, EventArgs e)
        {
            
            this.Hide();
            F_Search f = new F_Search();
            f.Show();
        }

        private void PB_XemDSLop_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_XemDSLop f = new F_XemDSLop();
            f.Show();
        }

        private void P_Cancel_Click(object sender, EventArgs e)
        {
            MessageBox.Show("Chức năng này chưa có!!!");
        }


        

    }
}
