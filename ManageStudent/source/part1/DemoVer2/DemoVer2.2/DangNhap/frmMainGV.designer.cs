﻿namespace GIAOVIEN
{
    partial class frmMainGV
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
            this.bt_NhapDiem = new System.Windows.Forms.Button();
            this.bt_LapBaoCao = new System.Windows.Forms.Button();
            this.bt_HanhKiem = new System.Windows.Forms.Button();
            this.bt_CapNhatDiem = new System.Windows.Forms.Button();
            this.button1 = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // bt_NhapDiem
            // 
            this.bt_NhapDiem.BackColor = System.Drawing.Color.DodgerBlue;
            this.bt_NhapDiem.Location = new System.Drawing.Point(52, 60);
            this.bt_NhapDiem.Name = "bt_NhapDiem";
            this.bt_NhapDiem.Size = new System.Drawing.Size(160, 86);
            this.bt_NhapDiem.TabIndex = 0;
            this.bt_NhapDiem.Text = "Nhập điểm";
            this.bt_NhapDiem.UseVisualStyleBackColor = false;
            // 
            // bt_LapBaoCao
            // 
            this.bt_LapBaoCao.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(255)))), ((int)(((byte)(192)))), ((int)(((byte)(255)))));
            this.bt_LapBaoCao.Location = new System.Drawing.Point(351, 190);
            this.bt_LapBaoCao.Name = "bt_LapBaoCao";
            this.bt_LapBaoCao.Size = new System.Drawing.Size(153, 89);
            this.bt_LapBaoCao.TabIndex = 1;
            this.bt_LapBaoCao.Text = "Lập báo cáo";
            this.bt_LapBaoCao.UseVisualStyleBackColor = false;
            this.bt_LapBaoCao.Click += new System.EventHandler(this.bt_LapBaoCao_Click);
            // 
            // bt_HanhKiem
            // 
            this.bt_HanhKiem.BackColor = System.Drawing.Color.FromArgb(((int)(((byte)(255)))), ((int)(((byte)(224)))), ((int)(((byte)(192)))));
            this.bt_HanhKiem.Location = new System.Drawing.Point(258, 60);
            this.bt_HanhKiem.Name = "bt_HanhKiem";
            this.bt_HanhKiem.Size = new System.Drawing.Size(153, 86);
            this.bt_HanhKiem.TabIndex = 2;
            this.bt_HanhKiem.Text = "Nhập hạnh kiểm";
            this.bt_HanhKiem.UseVisualStyleBackColor = false;
            this.bt_HanhKiem.Click += new System.EventHandler(this.bt_HanhKiem_Click);
            // 
            // bt_CapNhatDiem
            // 
            this.bt_CapNhatDiem.BackColor = System.Drawing.Color.Salmon;
            this.bt_CapNhatDiem.Location = new System.Drawing.Point(139, 190);
            this.bt_CapNhatDiem.Name = "bt_CapNhatDiem";
            this.bt_CapNhatDiem.Size = new System.Drawing.Size(160, 89);
            this.bt_CapNhatDiem.TabIndex = 4;
            this.bt_CapNhatDiem.Text = "Cập nhật điểm";
            this.bt_CapNhatDiem.UseVisualStyleBackColor = false;
            // 
            // button1
            // 
            this.button1.BackColor = System.Drawing.Color.Lime;
            this.button1.Location = new System.Drawing.Point(470, 60);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(153, 86);
            this.button1.TabIndex = 5;
            this.button1.Text = "Tra cứu học sinh";
            this.button1.UseVisualStyleBackColor = false;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // frmMainGV
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 19F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(692, 335);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.bt_CapNhatDiem);
            this.Controls.Add(this.bt_HanhKiem);
            this.Controls.Add(this.bt_LapBaoCao);
            this.Controls.Add(this.bt_NhapDiem);
            this.Font = new System.Drawing.Font("Times New Roman", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Margin = new System.Windows.Forms.Padding(4);
            this.Name = "frmMainGV";
            this.Text = "GIÁO VIÊN";
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Button bt_NhapDiem;
        private System.Windows.Forms.Button bt_LapBaoCao;
        private System.Windows.Forms.Button bt_HanhKiem;
        private System.Windows.Forms.Button bt_CapNhatDiem;
        private System.Windows.Forms.Button button1;
    }
}