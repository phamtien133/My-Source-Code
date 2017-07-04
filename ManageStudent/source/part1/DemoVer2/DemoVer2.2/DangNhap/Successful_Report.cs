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
    public partial class Successful_Report : Form
    {
        public Successful_Report()
        {
            InitializeComponent();
        }

        public Successful_Report(string _class, string _semester, string _year, string _subject): this()
        {
            txtSubject.Text = _subject;
            txtClass.Text = _class;
            txtSemester.Text = _semester;
            txtYear.Text = _year;
        }

        private void Successful_Report_Load(object sender, EventArgs e)
        {
            txtNumber_TextChanged(sender, e);
            dgvDiem_CellContentClick(sender, e);
        }

        private void dgvDiem_CellContentClick(object sender, EventArgs e)
        {
            // Tạo form báo cáo
            DataTable baocao = new DataTable();
            // Thêm cột STT
            DataColumn stt = new DataColumn("STT", typeof(int));
            baocao.Columns.Add(stt);
            // Thêm cột Họ tên
            DataColumn HoTen = new DataColumn("Họ và Tên", typeof(string));
            baocao.Columns.Add(HoTen);
             // Thêm cột 15p
            DataColumn Diem15 = new DataColumn("Điểm 15p",typeof(string));
            baocao.Columns.Add(Diem15);
            // Thêm cột 1 tiết
            DataColumn Diem45 = new DataColumn("Điểm 1 tiết",typeof(string));
            baocao.Columns.Add(Diem45);
            // Thêm cột điểm thi cuối kỳ
            DataColumn DiemThi = new DataColumn("Điểm thi");
            baocao.Columns.Add(DiemThi);
            // Thêm cột điểm trung bình
            DataColumn dtb = new DataColumn("ĐTB");
            baocao.Columns.Add(dtb);
            
            string sql = "select distinct hs.MAHS from DIEMKT kt, HOCSINH hs where kt.MAHS = hs.MAHS";
            DataTable dsLop = CSDL.Read(sql);
            DataTable Mark;
            for (int i = 0; i < dsLop.Rows.Count; i++)
            {
                sql = "select HOTENHS, TENKT, DIEM, DTB from KIEMTRA kt, DIEMTBM tbm, DIEMKT dkt, HOCSINH hs"
                           + " where kt.MAKT = dkt.MAKT and tbm.MAHS = dkt.MAHS and tbm.MALOP = dkt.MALOP and dkt.MAHS = hs.MAHS and "
                           + "hs.MAHS = '" + dsLop.Rows[i][0].ToString()
                           + "' and dkt.MALOP = '" + txtClass.Text + "' and dkt.MAMH = '" + txtSubject.Text
                           + "' and dkt.HOCKY = '" + txtSemester.Text + "' and dkt.NIENKHOA = '" + txtYear.Text + "'";
                Mark = CSDL.Read(sql);
                string diem15 = "";
                string diem45 = "";
                string diemthi = "";
                for(int j = 0; j < Mark.Rows.Count; j++)
                {
                    if (Mark.Rows[j][1].ToString() == "KT 15 phút") diem15 += " " + Mark.Rows[j][2].ToString();
                    else if (Mark.Rows[j][1].ToString() == "KT 1 tiết") diem45 += " " + Mark.Rows[j][2].ToString();
                    else if (Mark.Rows[j][1].ToString() == "KT Cuối kỳ") diemthi += " " + Mark.Rows[j][2].ToString();
                }

                // Add điểm học sinh vào datatable baocao
                DataRow row = baocao.NewRow();
                row["STT"] = i + 1;
                row["Họ và Tên"] = Mark.Rows[0][1].ToString();
                row["Điểm 15p"] = diem15;
                row["Điểm 1 tiết"] = diem45;
                row["Điểm thi"] = diemthi;
                row["ĐTB"] = Mark.Rows[0][3].ToString();
                baocao.Rows.Add(row);
            }
           
            DataView Diem = new DataView(baocao);
            dgvDiem.DataSource = Diem;
            
            /*
            dgvDiem.Columns[0].HeaderText = "STT";
            dgvDiem.Columns[1].HeaderText = "Họ Tên";
            dgvDiem.Columns[2].HeaderText = "Điểm 15p";
            dgvDiem.Columns[3].HeaderText = "Điểm 1 tiết";
            dgvDiem.Columns[4].HeaderText = "Điểm thi";
            dgvDiem.Columns[5].HeaderText = "ĐTB"; 
            */
        }

        private void txtNumber_TextChanged(object sender, EventArgs e)
        {
            string sql = "select SISO from LOP where MALOP = '" + txtClass.Text + "' and NIENKHOA = '" + txtYear.Text + "'";
            DataTable NumberOfStudent = CSDL.Read(sql);
            txtNumber.Text = NumberOfStudent.Rows[0][0].ToString();
        }

        private void textBox1_TextChanged(object sender, EventArgs e)
        {
           
        }

        private void label2_Click(object sender, EventArgs e)
        {

        }

        private void btnBack_Click(object sender, EventArgs e)
        {
            this.Close();
            ReportSubject report = new ReportSubject();
            report.ShowDialog();
        }

        private void dgvDiem_CellContentClick(object sender, DataGridViewCellEventArgs e)
        {
           
        }
    }
}
