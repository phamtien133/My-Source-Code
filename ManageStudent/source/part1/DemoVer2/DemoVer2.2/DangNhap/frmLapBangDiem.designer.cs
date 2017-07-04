namespace QLHS
{
    partial class frmLapBangDiem
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
            this.btLBHK = new System.Windows.Forms.Button();
            this.btLBCTKMH = new System.Windows.Forms.Button();
            this.btQuayLai = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // btLBHK
            // 
            this.btLBHK.Location = new System.Drawing.Point(97, 47);
            this.btLBHK.Name = "btLBHK";
            this.btLBHK.Size = new System.Drawing.Size(180, 23);
            this.btLBHK.TabIndex = 0;
            this.btLBHK.Text = "Lập bảng điểm học kỳ";
            this.btLBHK.UseVisualStyleBackColor = true;
            this.btLBHK.Click += new System.EventHandler(this.btLBHK_Click);
            // 
            // btLBCTKMH
            // 
            this.btLBCTKMH.Location = new System.Drawing.Point(97, 105);
            this.btLBCTKMH.Name = "btLBCTKMH";
            this.btLBCTKMH.Size = new System.Drawing.Size(180, 23);
            this.btLBCTKMH.TabIndex = 1;
            this.btLBCTKMH.Text = "Lập báo cáo tổng kết môn học";
            this.btLBCTKMH.UseVisualStyleBackColor = true;
            // 
            // btQuayLai
            // 
            this.btQuayLai.Location = new System.Drawing.Point(271, 152);
            this.btQuayLai.Name = "btQuayLai";
            this.btQuayLai.Size = new System.Drawing.Size(75, 23);
            this.btQuayLai.TabIndex = 2;
            this.btQuayLai.Text = "Quay lại";
            this.btQuayLai.UseVisualStyleBackColor = true;
            // 
            // frmLapBaoCao
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(428, 208);
            this.Controls.Add(this.btQuayLai);
            this.Controls.Add(this.btLBCTKMH);
            this.Controls.Add(this.btLBHK);
            this.Name = "frmLapBaoCao";
            this.Text = "Lập báo cáo";
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Button btLBHK;
        private System.Windows.Forms.Button btLBCTKMH;
        private System.Windows.Forms.Button btQuayLai;
    }
}