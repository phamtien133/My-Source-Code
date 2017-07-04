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
    public partial class F_Login : Form
    {
        public class Role_Login
        {
            public static int Role;
        }
        public DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
        public F_Login()
        {
            InitializeComponent();
        }

        public void P_Login_Click(object sender, EventArgs e)
        {
            
            if (String.IsNullOrEmpty(TB_Account.Text) || String.IsNullOrEmpty(TB_Pass.Text))
            {
                MessageBox.Show("Vui lòng nhập đủ thông tin!", "Thông báo");
            }
            else
            {
                if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 2)
                {
                    
                    this.Hide();
                    F_MainNVPDT f = new F_MainNVPDT();
                    f.Show();

                    
                }
                else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 1)
                {
                    
                    this.Hide();
                    F_MainTruongPDT f = new F_MainTruongPDT();
                    f.Show();
                    
                    
                }
               /* else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 3)
                {
                    this.Hide();
                    F_LapBaoCaoTongKetMon f = new F_LapBaoCaoTongKetMon();
                    f.Show();
                }
                else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 4)
                {
                    this.Hide();
                    F_LapBangDiemHocKy f = new F_LapBangDiemHocKy();
                    f.Show();
                }*/
                else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 3)
                {
                    this.Hide();
                    frmMain frm = new frmMain();
                    f.Show();
                }
                else
                {
                    MessageBox.Show("Không tồn tại tài khoản này", "Thông báo");
                }
            }
        }

        
        
        private void P_Cancel_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn thoát hay không?", "THÔNG BÁO", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Warning));
            if (ThongBao == DialogResult.Yes)
            {
                MessageBox.Show("Cảm ơn bạn đã sử dụng chương trình!");
                Application.Exit();
                
            }
        }

        private void TB_Pass_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == 13)
            {
                DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();

                if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 2)
                {
                    this.Hide();
                    F_MainNVPDT f = new F_MainNVPDT();
                    f.Show();
                }
                else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 1)
                {
                    this.Hide();
                    F_MainTruongPDT f = new F_MainTruongPDT();
                    f.Show();
                }
                else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 3)
                {
                    this.Hide();
                    F_LapBaoCaoTongKetMon f = new F_LapBaoCaoTongKetMon();
                    f.Show();
                }
                else if (Test.CheckLog(TB_Account.Text, TB_Pass.Text) == 1 && Test.CheckRole(TB_Account.Text, TB_Pass.Text) == 4)
                {
                    this.Hide();
                    F_LapBangDiemHocKy f = new F_LapBangDiemHocKy();
                    f.Show();
                }
                else
                {
                    MessageBox.Show("Không tồn tại tài khoản này", "Thông báo");
                }
            }
        }

        private void panel1_Paint(object sender, PaintEventArgs e)
        {

        }

        private void tableLayoutPanel1_Paint(object sender, PaintEventArgs e)
        {

        }

        private void panel2_Paint(object sender, PaintEventArgs e)
        {

        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {

        }

        private void panel3_Paint(object sender, PaintEventArgs e)
        {

        }

        private void label4_Click(object sender, EventArgs e)
        {

        }

        private void label3_Click(object sender, EventArgs e)
        {

        }

        private void pictureBox2_Click(object sender, EventArgs e)
        {

        }

        private void TB_Pass_TextChanged(object sender, EventArgs e)
        {

        }

        private void label2_Click(object sender, EventArgs e)
        {

        }

        private void TB_Account_TextChanged(object sender, EventArgs e)
        {

        }

        private void label1_Click(object sender, EventArgs e)
        {

        }

        private void bindingSource1_CurrentChanged(object sender, EventArgs e)
        {

        }

        private void F_Login_Load(object sender, EventArgs e)
        {
            
        }

    }
}
