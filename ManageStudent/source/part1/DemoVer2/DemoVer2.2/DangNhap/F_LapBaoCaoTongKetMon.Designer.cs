namespace DangNhap
{
    partial class F_LapBaoCaoTongKetMon
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(F_LapBaoCaoTongKetMon));
            this.dSHocSinhTableAdapterBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.dSHocSinhBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.quanLyHocSinhDataSet = new DangNhap.QuanLyHocSinhDataSet();
            this.dataSet1BindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.dataSet1 = new DangNhap.DataSet1();
            this.CB_Lop = new System.Windows.Forms.ComboBox();
            this.CB_NienKhoa = new System.Windows.Forms.ComboBox();
            this.label10 = new System.Windows.Forms.Label();
            this.PB_Back = new System.Windows.Forms.PictureBox();
            this.label9 = new System.Windows.Forms.Label();
            this.PB_LapBC = new System.Windows.Forms.PictureBox();
            this.label3 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label7 = new System.Windows.Forms.Label();
            this.DTG_BaoCao = new System.Windows.Forms.DataGridView();
            this.STT = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.HoTen = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.Diem15 = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.Diem45 = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.panel3 = new System.Windows.Forms.Panel();
            this.CB_HocKi = new System.Windows.Forms.ComboBox();
            this.label1 = new System.Windows.Forms.Label();
            this.CB_MonHoc = new System.Windows.Forms.ComboBox();
            this.pictureBox2 = new System.Windows.Forms.PictureBox();
            this.pictureBox3 = new System.Windows.Forms.PictureBox();
            this.LB_LogOut = new System.Windows.Forms.Label();
            this.LB_XinChao = new System.Windows.Forms.Label();
            this.panel2 = new System.Windows.Forms.Panel();
            this.tableLayoutPanel1 = new System.Windows.Forms.TableLayoutPanel();
            this.panel1 = new System.Windows.Forms.Panel();
            this.dSHocSinhTableAdapter = new DangNhap.QuanLyHocSinhDataSetTableAdapters.DSHocSinhTableAdapter();
            ((System.ComponentModel.ISupportInitialize)(this.dSHocSinhTableAdapterBindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.dSHocSinhBindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.quanLyHocSinhDataSet)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.dataSet1BindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.dataSet1)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.PB_Back)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.PB_LapBC)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.DTG_BaoCao)).BeginInit();
            this.panel3.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBox2)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBox3)).BeginInit();
            this.panel2.SuspendLayout();
            this.tableLayoutPanel1.SuspendLayout();
            this.panel1.SuspendLayout();
            this.SuspendLayout();
            // 
            // dSHocSinhTableAdapterBindingSource
            // 
            this.dSHocSinhTableAdapterBindingSource.DataSource = typeof(DangNhap.QuanLyHocSinhDataSetTableAdapters.DSHocSinhTableAdapter);
            // 
            // dSHocSinhBindingSource
            // 
            this.dSHocSinhBindingSource.DataMember = "DSHocSinh";
            this.dSHocSinhBindingSource.DataSource = this.quanLyHocSinhDataSet;
            // 
            // quanLyHocSinhDataSet
            // 
            this.quanLyHocSinhDataSet.DataSetName = "QuanLyHocSinhDataSet";
            this.quanLyHocSinhDataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema;
            // 
            // dataSet1BindingSource
            // 
            this.dataSet1BindingSource.DataSource = this.dataSet1;
            this.dataSet1BindingSource.Position = 0;
            // 
            // dataSet1
            // 
            this.dataSet1.DataSetName = "DataSet1";
            this.dataSet1.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema;
            // 
            // CB_Lop
            // 
            this.CB_Lop.DataBindings.Add(new System.Windows.Forms.Binding("SelectedValue", this.dSHocSinhTableAdapterBindingSource, "ClearBeforeFill", true));
            this.CB_Lop.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.CB_Lop.FormattingEnabled = true;
            this.CB_Lop.Location = new System.Drawing.Point(172, 61);
            this.CB_Lop.Name = "CB_Lop";
            this.CB_Lop.Size = new System.Drawing.Size(121, 21);
            this.CB_Lop.TabIndex = 41;
            // 
            // CB_NienKhoa
            // 
            this.CB_NienKhoa.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.CB_NienKhoa.FormattingEnabled = true;
            this.CB_NienKhoa.Location = new System.Drawing.Point(455, 61);
            this.CB_NienKhoa.Name = "CB_NienKhoa";
            this.CB_NienKhoa.Size = new System.Drawing.Size(121, 21);
            this.CB_NienKhoa.TabIndex = 40;
            // 
            // label10
            // 
            this.label10.AutoSize = true;
            this.label10.BackColor = System.Drawing.Color.White;
            this.label10.Font = new System.Drawing.Font("Arial", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label10.Location = new System.Drawing.Point(542, 253);
            this.label10.Name = "label10";
            this.label10.Size = new System.Drawing.Size(70, 19);
            this.label10.TabIndex = 39;
            this.label10.Text = "Quay lại";
            // 
            // PB_Back
            // 
            this.PB_Back.BackColor = System.Drawing.Color.White;
            this.PB_Back.Image = ((System.Drawing.Image)(resources.GetObject("PB_Back.Image")));
            this.PB_Back.Location = new System.Drawing.Point(542, 182);
            this.PB_Back.Name = "PB_Back";
            this.PB_Back.Size = new System.Drawing.Size(65, 74);
            this.PB_Back.SizeMode = System.Windows.Forms.PictureBoxSizeMode.Zoom;
            this.PB_Back.TabIndex = 38;
            this.PB_Back.TabStop = false;
            // 
            // label9
            // 
            this.label9.AutoSize = true;
            this.label9.BackColor = System.Drawing.Color.White;
            this.label9.Font = new System.Drawing.Font("Arial", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label9.Location = new System.Drawing.Point(520, 133);
            this.label9.Name = "label9";
            this.label9.Size = new System.Drawing.Size(103, 19);
            this.label9.TabIndex = 36;
            this.label9.Text = "Lập báo cáo";
            // 
            // PB_LapBC
            // 
            this.PB_LapBC.BackColor = System.Drawing.Color.White;
            this.PB_LapBC.Image = ((System.Drawing.Image)(resources.GetObject("PB_LapBC.Image")));
            this.PB_LapBC.Location = new System.Drawing.Point(553, 88);
            this.PB_LapBC.Name = "PB_LapBC";
            this.PB_LapBC.Size = new System.Drawing.Size(36, 42);
            this.PB_LapBC.SizeMode = System.Windows.Forms.PictureBoxSizeMode.Zoom;
            this.PB_LapBC.TabIndex = 20;
            this.PB_LapBC.TabStop = false;
            this.PB_LapBC.Click += new System.EventHandler(this.PB_LapBC_Click);
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.BackColor = System.Drawing.Color.White;
            this.label3.Font = new System.Drawing.Font("Arial", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label3.Location = new System.Drawing.Point(345, 63);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(92, 19);
            this.label3.TabIndex = 24;
            this.label3.Text = "Niên khóa:";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.BackColor = System.Drawing.Color.White;
            this.label2.Font = new System.Drawing.Font("Arial", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label2.Location = new System.Drawing.Point(30, 61);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(46, 19);
            this.label2.TabIndex = 23;
            this.label2.Text = "Lớp:";
            // 
            // label7
            // 
            this.label7.AutoSize = true;
            this.label7.BackColor = System.Drawing.Color.White;
            this.label7.Font = new System.Drawing.Font("Arial", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label7.Location = new System.Drawing.Point(30, 27);
            this.label7.Name = "label7";
            this.label7.Size = new System.Drawing.Size(81, 19);
            this.label7.TabIndex = 21;
            this.label7.Text = "Môn học:";
            // 
            // DTG_BaoCao
            // 
            this.DTG_BaoCao.AllowUserToAddRows = false;
            this.DTG_BaoCao.AllowUserToDeleteRows = false;
            this.DTG_BaoCao.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.DTG_BaoCao.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.STT,
            this.HoTen,
            this.Diem15,
            this.Diem45});
            this.DTG_BaoCao.Location = new System.Drawing.Point(34, 109);
            this.DTG_BaoCao.Name = "DTG_BaoCao";
            this.DTG_BaoCao.ReadOnly = true;
            this.DTG_BaoCao.Size = new System.Drawing.Size(487, 128);
            this.DTG_BaoCao.TabIndex = 42;
            // 
            // STT
            // 
            this.STT.DataPropertyName = "STT";
            this.STT.HeaderText = "STT";
            this.STT.Name = "STT";
            this.STT.ReadOnly = true;
            // 
            // HoTen
            // 
            this.HoTen.DataPropertyName = "HoTen";
            this.HoTen.HeaderText = "Họ và tên";
            this.HoTen.Name = "HoTen";
            this.HoTen.ReadOnly = true;
            // 
            // Diem15
            // 
            this.Diem15.DataPropertyName = "Diem15";
            this.Diem15.HeaderText = "Điểm 15 phút";
            this.Diem15.Name = "Diem15";
            this.Diem15.ReadOnly = true;
            // 
            // Diem45
            // 
            this.Diem45.DataPropertyName = "Diem45";
            this.Diem45.HeaderText = "Điểm 45 phút";
            this.Diem45.Name = "Diem45";
            this.Diem45.ReadOnly = true;
            // 
            // panel3
            // 
            this.panel3.Controls.Add(this.CB_HocKi);
            this.panel3.Controls.Add(this.label1);
            this.panel3.Controls.Add(this.CB_MonHoc);
            this.panel3.Controls.Add(this.DTG_BaoCao);
            this.panel3.Controls.Add(this.CB_Lop);
            this.panel3.Controls.Add(this.CB_NienKhoa);
            this.panel3.Controls.Add(this.label10);
            this.panel3.Controls.Add(this.PB_Back);
            this.panel3.Controls.Add(this.label9);
            this.panel3.Controls.Add(this.PB_LapBC);
            this.panel3.Controls.Add(this.label3);
            this.panel3.Controls.Add(this.label2);
            this.panel3.Controls.Add(this.label7);
            this.panel3.Controls.Add(this.pictureBox2);
            this.panel3.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel3.Location = new System.Drawing.Point(3, 74);
            this.panel3.Name = "panel3";
            this.panel3.Size = new System.Drawing.Size(622, 279);
            this.panel3.TabIndex = 1;
            // 
            // CB_HocKi
            // 
            this.CB_HocKi.DataBindings.Add(new System.Windows.Forms.Binding("SelectedValue", this.dSHocSinhTableAdapterBindingSource, "ClearBeforeFill", true));
            this.CB_HocKi.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.CB_HocKi.FormattingEnabled = true;
            this.CB_HocKi.Items.AddRange(new object[] {
            "I",
            "II"});
            this.CB_HocKi.Location = new System.Drawing.Point(455, 27);
            this.CB_HocKi.Name = "CB_HocKi";
            this.CB_HocKi.Size = new System.Drawing.Size(121, 21);
            this.CB_HocKi.TabIndex = 45;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.BackColor = System.Drawing.Color.White;
            this.label1.Font = new System.Drawing.Font("Arial", 12F, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.label1.Location = new System.Drawing.Point(345, 27);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(63, 19);
            this.label1.TabIndex = 44;
            this.label1.Text = "Học kì:";
            // 
            // CB_MonHoc
            // 
            this.CB_MonHoc.DataBindings.Add(new System.Windows.Forms.Binding("SelectedValue", this.dSHocSinhTableAdapterBindingSource, "ClearBeforeFill", true));
            this.CB_MonHoc.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.CB_MonHoc.FormattingEnabled = true;
            this.CB_MonHoc.Location = new System.Drawing.Point(172, 25);
            this.CB_MonHoc.Name = "CB_MonHoc";
            this.CB_MonHoc.Size = new System.Drawing.Size(121, 21);
            this.CB_MonHoc.TabIndex = 43;
            // 
            // pictureBox2
            // 
            this.pictureBox2.BackColor = System.Drawing.Color.White;
            this.pictureBox2.Dock = System.Windows.Forms.DockStyle.Fill;
            this.pictureBox2.Location = new System.Drawing.Point(0, 0);
            this.pictureBox2.Name = "pictureBox2";
            this.pictureBox2.Size = new System.Drawing.Size(622, 279);
            this.pictureBox2.TabIndex = 0;
            this.pictureBox2.TabStop = false;
            // 
            // pictureBox3
            // 
            this.pictureBox3.BackColor = System.Drawing.Color.White;
            this.pictureBox3.Image = ((System.Drawing.Image)(resources.GetObject("pictureBox3.Image")));
            this.pictureBox3.Location = new System.Drawing.Point(560, 0);
            this.pictureBox3.Name = "pictureBox3";
            this.pictureBox3.Size = new System.Drawing.Size(58, 62);
            this.pictureBox3.SizeMode = System.Windows.Forms.PictureBoxSizeMode.Zoom;
            this.pictureBox3.TabIndex = 19;
            this.pictureBox3.TabStop = false;
            // 
            // LB_LogOut
            // 
            this.LB_LogOut.AutoSize = true;
            this.LB_LogOut.BackColor = System.Drawing.Color.WhiteSmoke;
            this.LB_LogOut.Font = new System.Drawing.Font("Times New Roman", 14.25F, ((System.Drawing.FontStyle)((System.Drawing.FontStyle.Italic | System.Drawing.FontStyle.Underline))), System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.LB_LogOut.Location = new System.Drawing.Point(469, 43);
            this.LB_LogOut.Name = "LB_LogOut";
            this.LB_LogOut.Size = new System.Drawing.Size(92, 21);
            this.LB_LogOut.TabIndex = 18;
            this.LB_LogOut.Text = "Đăng xuất";
            // 
            // LB_XinChao
            // 
            this.LB_XinChao.AutoSize = true;
            this.LB_XinChao.BackColor = System.Drawing.Color.WhiteSmoke;
            this.LB_XinChao.Font = new System.Drawing.Font("Times New Roman", 14.25F, System.Drawing.FontStyle.Italic, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.LB_XinChao.Location = new System.Drawing.Point(396, 19);
            this.LB_XinChao.Name = "LB_XinChao";
            this.LB_XinChao.Size = new System.Drawing.Size(168, 21);
            this.LB_XinChao.TabIndex = 16;
            this.LB_XinChao.Text = "Xin chào, Nhân viên";
            // 
            // panel2
            // 
            this.panel2.BackColor = System.Drawing.Color.White;
            this.panel2.BackgroundImage = ((System.Drawing.Image)(resources.GetObject("panel2.BackgroundImage")));
            this.panel2.Controls.Add(this.pictureBox3);
            this.panel2.Controls.Add(this.LB_LogOut);
            this.panel2.Controls.Add(this.LB_XinChao);
            this.panel2.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel2.Location = new System.Drawing.Point(3, 3);
            this.panel2.Name = "panel2";
            this.panel2.Size = new System.Drawing.Size(622, 65);
            this.panel2.TabIndex = 0;
            // 
            // tableLayoutPanel1
            // 
            this.tableLayoutPanel1.ColumnCount = 1;
            this.tableLayoutPanel1.ColumnStyles.Add(new System.Windows.Forms.ColumnStyle(System.Windows.Forms.SizeType.Percent, 100F));
            this.tableLayoutPanel1.Controls.Add(this.panel2, 0, 0);
            this.tableLayoutPanel1.Controls.Add(this.panel3, 0, 1);
            this.tableLayoutPanel1.Location = new System.Drawing.Point(3, 3);
            this.tableLayoutPanel1.Name = "tableLayoutPanel1";
            this.tableLayoutPanel1.RowCount = 2;
            this.tableLayoutPanel1.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Percent, 20F));
            this.tableLayoutPanel1.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Percent, 80F));
            this.tableLayoutPanel1.Size = new System.Drawing.Size(628, 356);
            this.tableLayoutPanel1.TabIndex = 0;
            // 
            // panel1
            // 
            this.panel1.Controls.Add(this.tableLayoutPanel1);
            this.panel1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel1.Location = new System.Drawing.Point(0, 0);
            this.panel1.Name = "panel1";
            this.panel1.Size = new System.Drawing.Size(634, 362);
            this.panel1.TabIndex = 4;
            // 
            // dSHocSinhTableAdapter
            // 
            this.dSHocSinhTableAdapter.ClearBeforeFill = true;
            // 
            // F_LapBaoCaoTongKetMon
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(634, 362);
            this.Controls.Add(this.panel1);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            this.Name = "F_LapBaoCaoTongKetMon";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "Lập báo cáo tổng kết môn học";
            this.Load += new System.EventHandler(this.F_LapBaoCao_Load);
            ((System.ComponentModel.ISupportInitialize)(this.dSHocSinhTableAdapterBindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.dSHocSinhBindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.quanLyHocSinhDataSet)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.dataSet1BindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.dataSet1)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.PB_Back)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.PB_LapBC)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.DTG_BaoCao)).EndInit();
            this.panel3.ResumeLayout(false);
            this.panel3.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBox2)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.pictureBox3)).EndInit();
            this.panel2.ResumeLayout(false);
            this.panel2.PerformLayout();
            this.tableLayoutPanel1.ResumeLayout(false);
            this.panel1.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.BindingSource dSHocSinhTableAdapterBindingSource;
        private System.Windows.Forms.BindingSource dSHocSinhBindingSource;
        private QuanLyHocSinhDataSet quanLyHocSinhDataSet;
        private System.Windows.Forms.BindingSource dataSet1BindingSource;
        private DataSet1 dataSet1;
        private System.Windows.Forms.ComboBox CB_Lop;
        private System.Windows.Forms.ComboBox CB_NienKhoa;
        private System.Windows.Forms.Label label10;
        private System.Windows.Forms.PictureBox PB_Back;
        private System.Windows.Forms.Label label9;
        private System.Windows.Forms.PictureBox PB_LapBC;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label7;
        private System.Windows.Forms.DataGridView DTG_BaoCao;
        private System.Windows.Forms.Panel panel3;
        private System.Windows.Forms.PictureBox pictureBox2;
        private System.Windows.Forms.PictureBox pictureBox3;
        private System.Windows.Forms.Label LB_LogOut;
        private System.Windows.Forms.Label LB_XinChao;
        private System.Windows.Forms.Panel panel2;
        private System.Windows.Forms.TableLayoutPanel tableLayoutPanel1;
        private System.Windows.Forms.Panel panel1;
        private QuanLyHocSinhDataSetTableAdapters.DSHocSinhTableAdapter dSHocSinhTableAdapter;
        private System.Windows.Forms.ComboBox CB_HocKi;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.ComboBox CB_MonHoc;
        private System.Windows.Forms.DataGridViewTextBoxColumn STT;
        private System.Windows.Forms.DataGridViewTextBoxColumn HoTen;
        private System.Windows.Forms.DataGridViewTextBoxColumn Diem15;
        private System.Windows.Forms.DataGridViewTextBoxColumn Diem45;

    }
}