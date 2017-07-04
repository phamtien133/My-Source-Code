namespace GIAOVIEN
{
    partial class frmHanhKiem
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
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.cb_NienKhoa = new System.Windows.Forms.ComboBox();
            this.cb_HocKy = new System.Windows.Forms.ComboBox();
            this.cb_Lop = new System.Windows.Forms.ComboBox();
            this.label3 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.bt_QuayLai = new System.Windows.Forms.Button();
            this.bt_HoanTat = new System.Windows.Forms.Button();
            this.groupBox1.SuspendLayout();
            this.SuspendLayout();
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.cb_NienKhoa);
            this.groupBox1.Controls.Add(this.cb_HocKy);
            this.groupBox1.Controls.Add(this.cb_Lop);
            this.groupBox1.Controls.Add(this.label3);
            this.groupBox1.Controls.Add(this.label2);
            this.groupBox1.Controls.Add(this.label1);
            this.groupBox1.Location = new System.Drawing.Point(127, 38);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(456, 102);
            this.groupBox1.TabIndex = 10;
            this.groupBox1.TabStop = false;
            // 
            // cb_NienKhoa
            // 
            this.cb_NienKhoa.FormattingEnabled = true;
            this.cb_NienKhoa.Items.AddRange(new object[] {
            ""});
            this.cb_NienKhoa.Location = new System.Drawing.Point(105, 62);
            this.cb_NienKhoa.Name = "cb_NienKhoa";
            this.cb_NienKhoa.Size = new System.Drawing.Size(121, 27);
            this.cb_NienKhoa.TabIndex = 5;
            // 
            // cb_HocKy
            // 
            this.cb_HocKy.FormattingEnabled = true;
            this.cb_HocKy.Items.AddRange(new object[] {
            "1",
            "2"});
            this.cb_HocKy.Location = new System.Drawing.Point(317, 18);
            this.cb_HocKy.Name = "cb_HocKy";
            this.cb_HocKy.Size = new System.Drawing.Size(121, 27);
            this.cb_HocKy.TabIndex = 4;
            // 
            // cb_Lop
            // 
            this.cb_Lop.FormattingEnabled = true;
            this.cb_Lop.Location = new System.Drawing.Point(105, 18);
            this.cb_Lop.Name = "cb_Lop";
            this.cb_Lop.RightToLeft = System.Windows.Forms.RightToLeft.No;
            this.cb_Lop.Size = new System.Drawing.Size(121, 27);
            this.cb_Lop.TabIndex = 3;
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(27, 65);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(72, 19);
            this.label3.TabIndex = 2;
            this.label3.Text = "Niên khóa";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(257, 21);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(54, 19);
            this.label2.TabIndex = 1;
            this.label2.Text = "Học kỳ";
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(27, 21);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(34, 19);
            this.label1.TabIndex = 0;
            this.label1.Text = "Lớp";
            // 
            // bt_QuayLai
            // 
            this.bt_QuayLai.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.bt_QuayLai.Location = new System.Drawing.Point(542, 180);
            this.bt_QuayLai.Name = "bt_QuayLai";
            this.bt_QuayLai.Size = new System.Drawing.Size(116, 32);
            this.bt_QuayLai.TabIndex = 12;
            this.bt_QuayLai.Text = "Quay lại";
            this.bt_QuayLai.UseVisualStyleBackColor = true;
            this.bt_QuayLai.Click += new System.EventHandler(this.bt_QuayLai_Click);
            // 
            // bt_HoanTat
            // 
            this.bt_HoanTat.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.bt_HoanTat.Location = new System.Drawing.Point(419, 180);
            this.bt_HoanTat.Name = "bt_HoanTat";
            this.bt_HoanTat.Size = new System.Drawing.Size(93, 32);
            this.bt_HoanTat.TabIndex = 11;
            this.bt_HoanTat.Text = "Hoàn tất";
            this.bt_HoanTat.UseVisualStyleBackColor = true;
            this.bt_HoanTat.Click += new System.EventHandler(this.bt_HoanTat_Click);
            // 
            // frmHanhKiem
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 19F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(734, 257);
            this.Controls.Add(this.groupBox1);
            this.Controls.Add(this.bt_QuayLai);
            this.Controls.Add(this.bt_HoanTat);
            this.Font = new System.Drawing.Font("Times New Roman", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Margin = new System.Windows.Forms.Padding(4, 4, 4, 4);
            this.Name = "frmHanhKiem";
            this.Text = "HẠNH KIỂM";
            this.Load += new System.EventHandler(this.frmHanhKiem_Load);
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.ComboBox cb_NienKhoa;
        private System.Windows.Forms.ComboBox cb_HocKy;
        private System.Windows.Forms.ComboBox cb_Lop;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Button bt_QuayLai;
        private System.Windows.Forms.Button bt_HoanTat;
    }
}