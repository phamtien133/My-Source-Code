
import dao.*;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JOptionPane;
import javax.swing.table.DefaultTableModel;
import pojos.*;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author Pham Tien
 */
public class JFrameStudent_Report extends javax.swing.JFrame {

    /**
     * Creates new form JFrameStudent_Report
     */
    public JFrameStudent_Report() {
        initComponents();
        loadData();
    }

    public void loadData() {
        jTable.setModel(new DefaultTableModel());
        // Model for Table
        DefaultTableModel model = new DefaultTableModel() {
            public Class<?> getColumnClass(int column) {
                switch (column) {
                    case 0:
                        return String.class;
                    case 1:
                        return String.class;
                    case 2:
                        return String.class;
                    case 3:
                        return String.class;
                    case 4:
                        return String.class;
                    case 5:
                        return String.class;
                    case 6:
                        return String.class;
                    case 7:
                        return String.class;
                    case 8:
                        return String.class;
                    case 9:
                        return String.class;
                    case 10:
                        return String.class;
                    case 11:
                        return String.class;
                    case 12:
                        return String.class;
                    case 13:
                        return String.class;
                    case 14:
                        return String.class;
                    case 15:
                        return String.class;
                    case 16:
                        return String.class;

                    default:
                        return String.class;
                }
            }
        };
        jTable.setModel(model);
        // Add Column

        model.addColumn("Môn");
        model.addColumn("W1");
        model.addColumn("W2");
        model.addColumn("W3");
        model.addColumn("W4");
        model.addColumn("W5");
        model.addColumn("W6");
        model.addColumn("W7");
        model.addColumn("W8");
        model.addColumn("W9");
        model.addColumn("W10");
        model.addColumn("W11");
        model.addColumn("W12");
        model.addColumn("W13");
        model.addColumn("W14");
        model.addColumn("W15");
        int row = 0;
        int iCheck = 0;
        StudentSelected stu = new StudentSelected();
        SimpleDateFormat formatter = new SimpleDateFormat("dd/MM/yyyy");
        Date curD;
        curD = new Date();
        try {
            curD = formatter.parse(new SimpleDateFormat("dd/MM/yyyy").format(Calendar.getInstance().getTime()));
        } catch (ParseException ex) {
            Logger.getLogger(JFrameStudent_Report.class.getName()).log(Level.SEVERE, null, ex);
        }
        long curDate = curD.getTime();

        for (TbSubjectsStudents sub_stu : SubjectsStudentsDao.listSubStu()) {
            if (stu.getStudentsId().equals(sub_stu.getTbStudents().getStudentsId())) {
                model.addRow(new Object[row]);
                String startDate = "";
                for (TbSchedule sche : ScheduleDao.listSche()) {
                    String idSubOfSche = sche.getTbSubjects().getSubjectsId();
                    if (sub_stu.getTbSubjects().getSubjectsId().equals(idSubOfSche)) {
                        startDate = sche.getScheduleStartDate();
                    }
                }

                Date attendanceD;
                attendanceD = new Date();
                try {
                    attendanceD = formatter.parse(startDate);
                } catch (ParseException ex) {
                    Logger.getLogger(JFrameStudent_Attendance.class.getName()).log(Level.SEVERE, null, ex);
                }
                long start = attendanceD.getTime();
                model.setValueAt(SubjectsDao.getNameById(sub_stu.getTbSubjects().getSubjectsId()), row, 0); // Checkbox
                start = start/1000;
                curDate = curDate/1000;
//                System.out.println(curDate);
                if ((24 * 60 * 60 * 7 * 0) + start > curDate) {
                    model.setValueAt("NI", row, 1);
                } else {
                    if (sub_stu.getWeek1() == 0) {
                        model.setValueAt("UC", row, 1);
                    } else {
                        model.setValueAt("C", row, 1);
                    }
                }

                if ((24 * 60 * 60 * 7 * 1) + start > curDate) {
                    model.setValueAt("NI", row, 2);
                } else {
                    if (sub_stu.getWeek2() == 0) {
                        model.setValueAt("UC", row, 2);
                    } else {
                        model.setValueAt("C", row, 2);
                    }
                }

                if ((24 * 60 * 60 * 7 * 2) + start > curDate) {
                    model.setValueAt("NI", row, 3);
                } else {
                    if (sub_stu.getWeek3() == 0) {
                        model.setValueAt("UC", row, 3);
                    } else {
                        model.setValueAt("C", row, 3);
                    }
                }

                if ((24 * 60 * 60 * 7 * 3) + start > curDate) {
                    model.setValueAt("NI", row, 4);
                } else {
                    if (sub_stu.getWeek4() == 0) {
                        model.setValueAt("UC", row, 4);
                    } else {
                        model.setValueAt("C", row, 4);
                    }
                }
                long date5 = (long)(24 * 60 * 60 * 7 * 4) + start;
                if (date5  > curDate) {
                    model.setValueAt("NI", row, 5);
                } else {
//                    System.out.println( start);
//                    System.out.println(curDate);
                    if (sub_stu.getWeek5() == 0) {
                        model.setValueAt("UC", row, 5);
                    } else {
                        model.setValueAt("C", row, 5);
                    }
                }

                if ((24 * 60 * 60 * 7 * 5) + start > curDate) {
                    model.setValueAt("NI", row, 6);
                } else {
                    if (sub_stu.getWeek6() == 0) {
                        model.setValueAt("UC", row, 6);
                    } else {
                        model.setValueAt("C", row, 6);
                    }
                }

                if ((24 * 60 * 60 * 7 * 6) + start > curDate) {
                    model.setValueAt("NI", row, 7);
                } else {
                    if (sub_stu.getWeek7() == 0) {
                        model.setValueAt("UC", row, 7);
                    } else {
                        model.setValueAt("C", row, 7);
                    }
                }

                if ((24 * 60 * 60 * 7 * 7) + start > curDate) {
                    model.setValueAt("NI", row, 8);
                } else {
                    if (sub_stu.getWeek8() == 0) {
                        model.setValueAt("UC", row, 8);
                    } else {
                        model.setValueAt("C", row, 8);
                    }
                }

                if ((24 * 60 * 60 * 7 * 8) + start > curDate) {
                    model.setValueAt("NI", row, 9);
                } else {
                    if (sub_stu.getWeek9() == 0) {
                        model.setValueAt("UC", row, 9);
                    } else {
                        model.setValueAt("C", row, 9);
                    }
                }

                if ((24 * 60 * 60 * 7 * 9) + start > curDate) {
                    model.setValueAt("NI", row, 10);
                } else {
                    if (sub_stu.getWeek10() == 0) {
                        model.setValueAt("UC", row, 10);
                    } else {
                        model.setValueAt("C", row, 10);
                    }
                }

                if ((24 * 60 * 60 * 7 * 10) + start > curDate) {
                    model.setValueAt("NI", row, 11);
                } else {
                    if (sub_stu.getWeek11() == 0) {
                        model.setValueAt("UC", row, 11);
                    } else {
                        model.setValueAt("C", row, 11);
                    }
                }

                if ((24 * 60 * 60 * 7 * 11) + start > curDate) {
                    model.setValueAt("NI", row, 12);
                } else {
                    if (sub_stu.getWeek12() == 0) {
                        model.setValueAt("UC", row, 12);
                    } else {
                        model.setValueAt("C", row, 12);
                    }
                }

                if ((24 * 60 * 60 * 7 * 12) + start > curDate) {
                    model.setValueAt("NI", row, 13);
                } else {
                    if (sub_stu.getWeek13() == 0) {
                        model.setValueAt("UC", row, 13);
                    } else {
                        model.setValueAt("C", row, 13);
                    }
                }

                if ((24 * 60 * 60 * 7 * 13) + start > curDate) {
                    model.setValueAt("NI", row, 14);
                } else {
                    if (sub_stu.getWeek14() == 0) {
                        model.setValueAt("UC", row, 14);
                    } else {
                        model.setValueAt("C", row, 14);
                    }
                }

                if ((24 * 60 * 60 * 7 * 14) + start > curDate) {
                    model.setValueAt("NI", row, 15);
                } else {
                    if (sub_stu.getWeek15() == 0) {
                        model.setValueAt("UC", row, 15);
                    } else {
                        model.setValueAt("C", row, 15);
                    }
                }

                row++;
            }
        }
        jTable.setEnabled(false);
    }

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel2 = new javax.swing.JPanel();
        jPanel1 = new javax.swing.JPanel();
        jLabel3 = new javax.swing.JLabel();
        jButton4 = new javax.swing.JButton();
        jLabel2 = new javax.swing.JLabel();
        jScrollPane1 = new javax.swing.JScrollPane();
        jTable = new javax.swing.JTable();
        jPanel3 = new javax.swing.JPanel();
        jLabel4 = new javax.swing.JLabel();
        jLabel5 = new javax.swing.JLabel();
        jLabel1 = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        btnBack = new javax.swing.JButton();
        labelBack = new javax.swing.JLabel();

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 100, Short.MAX_VALUE)
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 100, Short.MAX_VALUE)
        );

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setLocation(new java.awt.Point(400, 120));
        setPreferredSize(new java.awt.Dimension(900, 400));

        jPanel1.setBackground(new java.awt.Color(255, 255, 255));

        jLabel3.setFont(new java.awt.Font("Arial", 1, 18)); // NOI18N
        jLabel3.setText("Kết Quả Điểm Danh");

        jButton4.setFont(new java.awt.Font("Arial", 2, 10)); // NOI18N
        jButton4.setText("Đăng xuất");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });

        jLabel2.setFont(new java.awt.Font("Arial", 2, 14)); // NOI18N
        jLabel2.setIcon(new javax.swing.ImageIcon("D:\\Documents\\K2014 nam 3\\Hoc Ki 2\\Java\\Tuan 4\\Attendance\\pic\\stu_avatar.png")); // NOI18N
        jLabel2.setText("Xin chào Học Sinh");

        jTable.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null},
                {null, null, null, null}
            },
            new String [] {
                "Title 1", "Title 2", "Title 3", "Title 4"
            }
        ));
        jScrollPane1.setViewportView(jTable);

        jPanel3.setBackground(new java.awt.Color(204, 255, 204));

        jLabel4.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        jLabel4.setText("C: checked - đã điểm danh");

        jLabel5.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        jLabel5.setText("NI: no information - chưa có thông tin");

        jLabel1.setFont(new java.awt.Font("Arial", 1, 14)); // NOI18N
        jLabel1.setText("Ghi Chú:");

        jLabel6.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        jLabel6.setText("UC: inchecked - chưa điểm danh");

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addComponent(jLabel1)
                .addGap(0, 0, Short.MAX_VALUE))
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addGap(23, 23, 23)
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jLabel6)
                    .addComponent(jLabel4)
                    .addComponent(jLabel5))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addComponent(jLabel1)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jLabel5)
                .addGap(4, 4, 4)
                .addComponent(jLabel4)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jLabel6))
        );

        btnBack.setIcon(new javax.swing.ImageIcon("D:\\Documents\\K2014 nam 3\\Hoc Ki 2\\Java\\Tuan 4\\Attendance\\pic\\stu_menu_create_back.png")); // NOI18N
        btnBack.setPreferredSize(new java.awt.Dimension(45, 45));
        btnBack.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnBackActionPerformed(evt);
            }
        });

        labelBack.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        labelBack.setText("  Quay Lại");

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                .addGap(0, 0, Short.MAX_VALUE)
                .addComponent(jButton4))
            .addComponent(jScrollPane1)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                .addContainerGap(403, Short.MAX_VALUE)
                .addComponent(jLabel3)
                .addGap(181, 181, 181)
                .addComponent(jLabel2))
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGap(30, 30, 30)
                .addComponent(jPanel3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(labelBack)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGap(11, 11, 11)
                        .addComponent(btnBack, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addGap(19, 19, 19))
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel2)
                    .addComponent(jLabel3))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jButton4)
                .addGap(28, 28, 28)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.PREFERRED_SIZE, 111, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 14, Short.MAX_VALUE)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                        .addComponent(jPanel3, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(34, 34, 34))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                        .addComponent(btnBack, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(labelBack)
                        .addContainerGap())))
        );

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel1, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void jButton4ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton4ActionPerformed
        int reply = JOptionPane.showConfirmDialog(null, "Bạn có thật sự muốn đăng xuất?", "Thông báo", JOptionPane.YES_NO_OPTION);
        if (reply == JOptionPane.YES_OPTION) {
            this.setVisible(false);
            JFrameLogin jf = new JFrameLogin();
            jf.setVisible(true);
        }
    }//GEN-LAST:event_jButton4ActionPerformed

    private void btnBackActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnBackActionPerformed
        this.setVisible(false);
        JFrameStudent jf = new JFrameStudent();
        jf.setVisible(true);
    }//GEN-LAST:event_btnBackActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String args[]) {
        /* Set the Nimbus look and feel */
        //<editor-fold defaultstate="collapsed" desc=" Look and feel setting code (optional) ">
        /* If Nimbus (introduced in Java SE 6) is not available, stay with the default look and feel.
         * For details see http://download.oracle.com/javase/tutorial/uiswing/lookandfeel/plaf.html 
         */
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException ex) {
            java.util.logging.Logger.getLogger(JFrameStudent_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(JFrameStudent_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(JFrameStudent_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(JFrameStudent_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new JFrameStudent_Report().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnBack;
    private javax.swing.JButton jButton4;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JTable jTable;
    private javax.swing.JLabel labelBack;
    // End of variables declaration//GEN-END:variables
}
