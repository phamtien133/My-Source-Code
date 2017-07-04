namespace GIAOVIEN
{
    partial class frmLapBaoCao
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
            this.bt_LapBD = new System.Windows.Forms.Button();
            this.bt_TKLop = new System.Windows.Forms.Button();
            this.bt_QuayLai = new System.Windows.Forms.Button();
            this.bt_TKMH = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // bt_LapBD
            // 
            this.bt_LapBD.BackColor = System.Drawing.Color.CornflowerBlue;
            this.bt_LapBD.Location = new System.Drawing.Point(146, 46);
            this.bt_LapBD.Name = "bt_LapBD";
            this.bt_LapBD.Size = new System.Drawing.Size(229, 37);
            this.bt_LapBD.TabIndex = 0;
            this.bt_LapBD.Text = "Lập bảng điểm";
            this.bt_LapBD.UseVisualStyleBackColor = false;
            this.bt_LapBD.Click += new System.EventHandler(this.bt_LapBD_Click);
            // 
            // bt_TKLop
            // 
            this.bt_TKLop.BackColor = System.Drawing.Color.CornflowerBlue;
            this.bt_TKLop.Location = new System.Drawing.Point(146, 174);
            this.bt_TKLop.Name = "bt_TKLop";
            this.bt_TKLop.Size = new System.Drawing.Size(229, 37);
            this.bt_TKLop.TabIndex = 2;
            this.bt_TKLop.Text = "Lập báo cáo tổng kết lớp";
            this.bt_TKLop.UseVisualStyleBackColor = false;
            // 
            // bt_QuayLai
            // 
            this.bt_QuayLai.BackColor = System.Drawing.SystemColors.ActiveBorder;
            this.bt_QuayLai.Location = new System.Drawing.Point(409, 239);
            this.bt_QuayLai.Name = "bt_QuayLai";
            this.bt_QuayLai.Size = new System.Drawing.Size(98, 35);
            this.bt_QuayLai.TabIndex = 3;
            this.bt_QuayLai.Text = "Quay Lại";
            this.bt_QuayLai.UseVisualStyleBackColor = false;
            // 
            // bt_TKMH
            // 
            this.bt_TKMH.BackColor = System.Drawing.Color.CornflowerBlue;
            this.bt_TKMH.Location = new System.Drawing.Point(146, 110);
            this.bt_TKMH.Name = "bt_TKMH";
            this.bt_TKMH.Size = new System.Drawing.Size(229, 37);
            this.bt_TKMH.TabIndex = 1;
            this.bt_TKMH.Text = "Lập báo cáo tổng kết môn học";
            this.bt_TKMH.UseVisualStyleBackColor = false;
            // 
            // frmLapBaoCao
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(9F, 19F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(572, 297);
            this.Controls.Add(this.bt_QuayLai);
            this.Controls.Add(this.bt_TKLop);
            this.Controls.Add(this.bt_TKMH);
            this.Controls.Add(this.bt_LapBD);
            this.Font = new System.Drawing.Font("Times New Roman", 12F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.Margin = new System.Windows.Forms.Padding(4);
            this.Name = "frmLapBaoCao";
            this.Text = "Lập báo cáo";
            this.Load += new System.EventHandler(this.frmLapBaoCao_Load);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Button bt_LapBD;
        private System.Windows.Forms.Button bt_TKLop;
        private System.Windows.Forms.Button bt_QuayLai;
        private System.Windows.Forms.Button bt_TKMH;
    }
}