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
    public partial class F_ThayDoiTuoi : Form
    {
        DangNhap.DataSet1TableAdapters.Login Test = new DataSet1TableAdapters.Login();
        
        public F_ThayDoiTuoi()
        {
            InitializeComponent();
        }

        private void label7_Click(object sender, EventArgs e)
        {

        }

        private void F_ThayDoiTuoi_Load(object sender, EventArgs e)
        {
            CB_TuoiTD.Text = "20";
            CB_TuoiTT.Text = "15";
        }
        
        public void pictureBox1_Click(object sender, EventArgs e)
        {
            Test.UpChangeAge(int.Parse(CB_TuoiTD.Text.ToString()), int.Parse(CB_TuoiTT.Text.ToString()), "admin");
            MessageBox.Show("Thay đổi tuổi thành công!");
        }

        private void pictureBox4_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn quay lại menu thay đổi quy định?", "Chú ý", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));

            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_ThayDoiQD f = new F_ThayDoiQD();
                f.Show();
            }
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
    }
}
