namespace GIAOVIEN
{
    partial class frmHanhKiemTC
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
            this.groupBox2 = new System.Windows.Forms.GroupBox();
            this.bt_QuayLai = new System.Windows.Forms.Button();
            this.bt_Luu = new System.Windows.Forms.Button();
            this.groupBox1 = new System.Windows.Forms.GroupBox();
            this.txtHocKy = new System.Windows.Forms.TextBox();
            this.txtNienKhoa = new System.Windows.Forms.TextBox();
            this.txtLop = new System.Windows.Forms.TextBox();
            this.label3 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.gv_DSHK = new System.Windows.Forms.DataGridView();
            this.groupBox2.SuspendLayout();
            this.groupBox1.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.gv_DSHK)).BeginInit();
            this.SuspendLayout();
            // 
            // groupBox2
            // 
            this.groupBox2.Controls.Add(this.bt_QuayLai);
            this.groupBox2.Controls.Add(this.bt_Luu);
            this.groupBox2.Controls.Add(this.groupBox1);
            this.groupBox2.Controls.Add(this.gv_DSHK);
            this.groupBox2.Location = new System.Drawing.Point(41, 1);
            this.groupBox2.Name = "groupBox2";
            this.groupBox2.Size = new System.Drawing.Size(596, 419);
            this.groupBox2.TabIndex = 13;
            this.groupBox2.TabStop = false;
            // 
            // bt_QuayLai
            // 
            this.bt_QuayLai.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.bt_QuayLai.Location = new System.Drawing.Point(434, 344);
            this.bt_QuayLai.Name = "bt_QuayLai";
            this.bt_QuayLai.Size = new System.Drawing.Size(116, 32);
            this.bt_QuayLai.TabIndex = 9;
            this.bt_QuayLai.Text = "Quay lại";
            this.bt_QuayLai.UseVisualStyleBackColor = true;
            this.bt_QuayLai.Click += new System.EventHandler(this.bt_QuayLai_Click);
            // 
            // bt_Luu
            // 
            this.bt_Luu.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft;
            this.bt_Luu.Location = new System.Drawing.Point(306, 344);
            this.bt_Luu.Name = "bt_Luu";
            this.bt_Luu.Size = new System.Drawing.Size(93, 32);
            this.bt_Luu.TabIndex = 8;
            this.bt_Luu.Text = "Lưu";
            this.bt_Luu.UseVisualStyleBackColor = true;
            this.bt_Luu.Click += new System.EventHandler(this.bt_Luu_Click);
            // 
            // groupBox1
            // 
            this.groupBox1.Controls.Add(this.txtHocKy);
            this.groupBox1.Controls.Add(this.txtNienKhoa);
            this.groupBox1.Controls.Add(this.txtLop);
            this.groupBox1.Controls.Add(this.label3);
            this.groupBox1.Controls.Add(this.label2);
            this.groupBox1.Controls.Add(this.label1);
            this.groupBox1.Location = new System.Drawing.Point(71, 7);
            this.groupBox1.Name = "groupBox1";
            this.groupBox1.Size = new System.Drawing.Size(456, 102);
            this.groupBox1.TabIndex = 7;
            this.groupBox1.TabStop = false;
            // 
            // txtHocKy
            // 
            this.txtHocKy.Location = new System.Drawing.Point(317, 18);
            this.txtHocKy.Name = "txtHocKy";
            this.txtHocKy.Size = new System.Drawing.Size(100, 26);
            this.txtHocKy.TabIndex = 5;
            // 
            // txtNienKhoa
            // 
            this.txtNienKhoa.Location = new System.Drawing.Point(120, 62);
            this.txtNienKhoa.Name = "txtNienKhoa";
            this.txtNienKhoa.Size = new System.Drawing.Size(100, 26);
            this.txtNienKhoa.TabIndex = 4;
            // 
            // txtLop
            // 
            this.txtLop.Location = new System.Drawing.Point(120, 18);
            this.txtLop.Name = "txtLop";
            this.txtLop.Size = new System.Drawing.Size(100, 26);
            this.txtLop.TabIndex = 3;
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
            // gv_DSHK
            // 
            this.gv_DSHK.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.gv_DSHK.Location = new System.Drawing.Point(71, 124);
            this.gv_DSHK.Name = "gv_DSHK";
            this.gv_DSHK.Size = new System.Drawing.Size(456, 194);
            this.gv_DSHK.TabIndex = 6;
            this.gv_DSHK.CellContentClick += new System.Windows.Forms.DataGridViewCellEventHandler(this.gv_DSHK_CellContentClick);
            // 
            // frmHanhKiemTC
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 19F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(679, 421);
            this.Controls.Add(this.groupBox2);
            this.Font = new System.Drawing.Font("Times New Roman", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Margin = new System.Windows.Forms.Padding(4);
            this.Name = "frmHanhKiemTC";
            this.Text = "NHẬP HẠNH KIỂM HỌC SINH";
            this.Load += new System.EventHandler(this.frmHanhKiemTC_Load);
            this.groupBox2.ResumeLayout(false);
            this.groupBox1.ResumeLayout(false);
            this.groupBox1.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.gv_DSHK)).EndInit();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.GroupBox groupBox2;
        private System.Windows.Forms.Button bt_QuayLai;
        private System.Windows.Forms.Button bt_Luu;
        private System.Windows.Forms.GroupBox groupBox1;
        private System.Windows.Forms.TextBox txtHocKy;
        private System.Windows.Forms.TextBox txtNienKhoa;
        private System.Windows.Forms.TextBox txtLop;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.DataGridView gv_DSHK;
    }
}