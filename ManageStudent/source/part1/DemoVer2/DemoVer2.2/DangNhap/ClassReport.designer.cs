namespace QLHS
{
    partial class ClassReport
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
            this.btnReport = new System.Windows.Forms.Button();
            this.btnBack = new System.Windows.Forms.Button();
            this.cbClass = new System.Windows.Forms.ComboBox();
            this.cbYear = new System.Windows.Forms.ComboBox();
            this.cbSemester = new System.Windows.Forms.ComboBox();
            this.lbClass = new System.Windows.Forms.Label();
            this.lbYear = new System.Windows.Forms.Label();
            this.lbSemester = new System.Windows.Forms.Label();
            this.SuspendLayout();
            // 
            // btnReport
            // 
            this.btnReport.Location = new System.Drawing.Point(133, 234);
            this.btnReport.Name = "btnReport";
            this.btnReport.Size = new System.Drawing.Size(103, 23);
            this.btnReport.TabIndex = 0;
            this.btnReport.Text = "Lập báo cáo";
            this.btnReport.UseVisualStyleBackColor = true;
            this.btnReport.Click += new System.EventHandler(this.btnReport_Click);
            // 
            // btnBack
            // 
            this.btnBack.Location = new System.Drawing.Point(324, 234);
            this.btnBack.Name = "btnBack";
            this.btnBack.Size = new System.Drawing.Size(75, 23);
            this.btnBack.TabIndex = 1;
            this.btnBack.Text = "Trở về";
            this.btnBack.UseVisualStyleBackColor = true;
            this.btnBack.Click += new System.EventHandler(this.btnBack_Click);
            // 
            // cbClass
            // 
            this.cbClass.FormattingEnabled = true;
            this.cbClass.Location = new System.Drawing.Point(133, 76);
            this.cbClass.Name = "cbClass";
            this.cbClass.Size = new System.Drawing.Size(103, 21);
            this.cbClass.TabIndex = 2;
            this.cbClass.SelectedIndexChanged += new System.EventHandler(this.cbClass_SelectedIndexChanged);
            // 
            // cbYear
            // 
            this.cbYear.FormattingEnabled = true;
            this.cbYear.Location = new System.Drawing.Point(133, 124);
            this.cbYear.Name = "cbYear";
            this.cbYear.Size = new System.Drawing.Size(103, 21);
            this.cbYear.TabIndex = 3;
            // 
            // cbSemester
            // 
            this.cbSemester.FormattingEnabled = true;
            this.cbSemester.Location = new System.Drawing.Point(342, 102);
            this.cbSemester.Name = "cbSemester";
            this.cbSemester.Size = new System.Drawing.Size(72, 21);
            this.cbSemester.TabIndex = 4;
            // 
            // lbClass
            // 
            this.lbClass.AutoSize = true;
            this.lbClass.Location = new System.Drawing.Point(77, 79);
            this.lbClass.Name = "lbClass";
            this.lbClass.Size = new System.Drawing.Size(25, 13);
            this.lbClass.TabIndex = 5;
            this.lbClass.Text = "Lớp";
            // 
            // lbYear
            // 
            this.lbYear.AutoSize = true;
            this.lbYear.Location = new System.Drawing.Point(59, 127);
            this.lbYear.Name = "lbYear";
            this.lbYear.Size = new System.Drawing.Size(56, 13);
            this.lbYear.TabIndex = 5;
            this.lbYear.Text = "Niên khóa";
            // 
            // lbSemester
            // 
            this.lbSemester.AutoSize = true;
            this.lbSemester.Location = new System.Drawing.Point(284, 105);
            this.lbSemester.Name = "lbSemester";
            this.lbSemester.Size = new System.Drawing.Size(41, 13);
            this.lbSemester.TabIndex = 5;
            this.lbSemester.Text = "Học kỳ";
            // 
            // ClassReport
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(543, 272);
            this.Controls.Add(this.lbSemester);
            this.Controls.Add(this.lbYear);
            this.Controls.Add(this.lbClass);
            this.Controls.Add(this.cbSemester);
            this.Controls.Add(this.cbYear);
            this.Controls.Add(this.cbClass);
            this.Controls.Add(this.btnBack);
            this.Controls.Add(this.btnReport);
            this.Name = "ClassReport";
            this.Text = "Báo cáo tổng kết lớp";
            this.Load += new System.EventHandler(this.ClassReport_Load);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button btnReport;
        private System.Windows.Forms.Button btnBack;
        private System.Windows.Forms.ComboBox cbClass;
        private System.Windows.Forms.ComboBox cbYear;
        private System.Windows.Forms.ComboBox cbSemester;
        private System.Windows.Forms.Label lbClass;
        private System.Windows.Forms.Label lbYear;
        private System.Windows.Forms.Label lbSemester;
    }
}