namespace QLHS
{
    partial class ReportSubject
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
            this.label2 = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            this.cb_Year = new System.Windows.Forms.ComboBox();
            this.label1 = new System.Windows.Forms.Label();
            this.cb_Semester = new System.Windows.Forms.ComboBox();
            this.btnReport = new System.Windows.Forms.Button();
            this.label4 = new System.Windows.Forms.Label();
            this.tb_Subject = new System.Windows.Forms.TextBox();
            this.cb_Class = new System.Windows.Forms.ComboBox();
            this.btnBack = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(82, 31);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(25, 13);
            this.label2.TabIndex = 0;
            this.label2.Text = "Lớp";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Cursor = System.Windows.Forms.Cursors.Default;
            this.label3.Location = new System.Drawing.Point(281, 80);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(56, 13);
            this.label3.TabIndex = 0;
            this.label3.Text = "Niên khóa";
            // 
            // cb_Year
            // 
            this.cb_Year.FormattingEnabled = true;
            this.cb_Year.Location = new System.Drawing.Point(356, 77);
            this.cb_Year.Name = "cb_Year";
            this.cb_Year.Size = new System.Drawing.Size(97, 21);
            this.cb_Year.TabIndex = 1;
            this.cb_Year.DropDown += new System.EventHandler(this.comboBox1_SelectedIndexChanged);
            this.cb_Year.SelectedIndexChanged += new System.EventHandler(this.comboBox1_SelectedIndexChanged);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(66, 83);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(41, 13);
            this.label1.TabIndex = 0;
            this.label1.Text = "Học kỳ";
            // 
            // cb_Semester
            // 
            this.cb_Semester.FormattingEnabled = true;
            this.cb_Semester.Location = new System.Drawing.Point(131, 80);
            this.cb_Semester.Name = "cb_Semester";
            this.cb_Semester.Size = new System.Drawing.Size(56, 21);
            this.cb_Semester.TabIndex = 1;
            this.cb_Semester.SelectedIndexChanged += new System.EventHandler(this.comboBox3_SelectedIndexChanged);
            // 
            // btnReport
            // 
            this.btnReport.Location = new System.Drawing.Point(114, 153);
            this.btnReport.Name = "btnReport";
            this.btnReport.Size = new System.Drawing.Size(95, 23);
            this.btnReport.TabIndex = 2;
            this.btnReport.Text = "Lập báo cáo";
            this.btnReport.UseVisualStyleBackColor = true;
            this.btnReport.Click += new System.EventHandler(this.button1_Click);
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(288, 31);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(49, 13);
            this.label4.TabIndex = 0;
            this.label4.Text = "Môn học";
            // 
            // tb_Subject
            // 
            this.tb_Subject.Enabled = false;
            this.tb_Subject.Location = new System.Drawing.Point(356, 28);
            this.tb_Subject.Name = "tb_Subject";
            this.tb_Subject.ReadOnly = true;
            this.tb_Subject.Size = new System.Drawing.Size(97, 20);
            this.tb_Subject.TabIndex = 3;
            this.tb_Subject.TextChanged += new System.EventHandler(this.Form1_Load);
            // 
            // cb_Class
            // 
            this.cb_Class.FormattingEnabled = true;
            this.cb_Class.Location = new System.Drawing.Point(131, 28);
            this.cb_Class.Name = "cb_Class";
            this.cb_Class.Size = new System.Drawing.Size(56, 21);
            this.cb_Class.Sorted = true;
            this.cb_Class.TabIndex = 1;
            this.cb_Class.SelectedIndexChanged += new System.EventHandler(this.comboBox2_SelectedIndexChanged);
            // 
            // btnBack
            // 
            this.btnBack.Location = new System.Drawing.Point(330, 153);
            this.btnBack.Name = "btnBack";
            this.btnBack.Size = new System.Drawing.Size(75, 23);
            this.btnBack.TabIndex = 4;
            this.btnBack.Text = "Trở về";
            this.btnBack.UseVisualStyleBackColor = true;
            this.btnBack.Click += new System.EventHandler(this.button1_Click_1);
            // 
            // ReportSubject
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(532, 230);
            this.Controls.Add(this.btnBack);
            this.Controls.Add(this.tb_Subject);
            this.Controls.Add(this.btnReport);
            this.Controls.Add(this.cb_Semester);
            this.Controls.Add(this.cb_Class);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.cb_Year);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label3);
            this.Name = "ReportSubject";
            this.Text = "Lập báo cáo tổng kết môn học";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.ComboBox cb_Year;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.ComboBox cb_Semester;
        private System.Windows.Forms.Button btnReport;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.TextBox tb_Subject;
        private System.Windows.Forms.ComboBox cb_Class;
        private System.Windows.Forms.Button btnBack;
    }
}

