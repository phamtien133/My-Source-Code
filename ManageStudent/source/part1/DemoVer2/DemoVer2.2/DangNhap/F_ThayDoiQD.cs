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
    public partial class F_ThayDoiQD : Form
    {
        public F_ThayDoiQD()
        {
            InitializeComponent();
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

        private void PB_Back_Click(object sender, EventArgs e)
        {
            DialogResult ThongBao;
            ThongBao = (MessageBox.Show("Bạn có chắc chắn muốn quay lại trang chủ?", "Chú ý", MessageBoxButtons.YesNoCancel, MessageBoxIcon.Question));
            
            if (ThongBao == DialogResult.Yes)
            {
                this.Hide();
                F_MainTruongPDT f = new F_MainTruongPDT();
                f.Show();
            }
        }

        private void PB_TuoiHocSinh_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_ThayDoiTuoi f = new F_ThayDoiTuoi();
            f.Show();
        }

        private void PB_TTLopHoc_Click(object sender, EventArgs e)
        {
            this.Hide();
            F_ThaydoiLopHoc f = new F_ThaydoiLopHoc();
            f.Show();
        }

        private void PB_TTMonHoc_Click(object sender, EventArgs e)
        {
            MessageBox.Show("Chức năng này chưa có!");
        }

        private void PB_TTDiemSo_Click(object sender, EventArgs e)
        {
            MessageBox.Show("Chức năng này chưa có!");
        }

        
    }
}
