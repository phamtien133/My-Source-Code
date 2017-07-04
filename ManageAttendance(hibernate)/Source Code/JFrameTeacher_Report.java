
import dao.StudentsDao;
import dao.SubjectsDao;
import dao.SubjectsStudentsDao;
import javax.swing.table.DefaultTableModel;
import pojos.*;
import dao.*;
import javax.swing.JOptionPane;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author Pham Tien
 */
public class JFrameTeacher_Report extends javax.swing.JFrame {

    /**
     * Creates new form JFrameTeacher_Report
     */
    public JFrameTeacher_Report() {
        initComponents();
        loadNameComBoSubjects();        
    }

    private void loadNameComBoSubjects() {
        for (TbSubjects sub : SubjectsDao.listSub()) {
            jComboSubjects.addItem(sub.getSubjectsName());
        }
    }

    private void loadListStu() {
        jTableStudents.setModel(new DefaultTableModel());
        // Model for Table
        DefaultTableModel model = new DefaultTableModel() {
            public Class<?> getColumnClass(int column) {
                switch (column) {
                    case 0:
                        return String.class;
                    case 1:
                        return Boolean.class;
                    case 2:
                        return Boolean.class;
                    case 3:
                        return Boolean.class;
                    case 4:
                        return Boolean.class;
                    case 5:
                        return Boolean.class;
                    case 6:
                        return Boolean.class;
                    case 7:
                        return Boolean.class;
                    case 8:
                        return Boolean.class;
                    case 9:
                        return Boolean.class;
                    case 10:
                        return Boolean.class;
                    case 11:
                        return Boolean.class;
                    case 12:
                        return Boolean.class;
                    case 13:
                        return Boolean.class;
                    case 14:
                        return Boolean.class;
                    case 15:
                        return Boolean.class;
                    case 16:
                        return Integer.class;

                    default:
                        return String.class;
                }
            }
        };
        jTableStudents.setModel(model);
        // Add Column

        model.addColumn("Tên");
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
        model.addColumn("");  
        jTableStudents.getColumnModel().getColumn(16).setMinWidth(0);
        jTableStudents.getColumnModel().getColumn(16).setMaxWidth(0);
        int row = 0;
        int iCheck = 0;
        for (TbSubjectsStudents sub_stu : SubjectsStudentsDao.listSubStu()) {
            if (SubjectsDao.getNameById(sub_stu.getTbSubjects().getSubjectsId()).equals(jComboSubjects.getSelectedItem().toString())) {
                model.addRow(new Object[row]);

                model.setValueAt(StudentsDao.getStudentsInfo(sub_stu.getTbStudents().getStudentsId()).getStudentsName(), row, 0); // Checkbox

                if (sub_stu.getWeek1() == 0) {
                    model.setValueAt(false, row, 1);
                } else {
                    model.setValueAt(true, row, 1);
                }

                if (sub_stu.getWeek2() == 0) {
                    model.setValueAt(false, row, 2);
                } else {
                    model.setValueAt(true, row, 2);
                }

                if (sub_stu.getWeek3() == 0) {
                    model.setValueAt(false, row, 3);
                } else {
                    model.setValueAt(true, row, 3);
                }

                if (sub_stu.getWeek4() == 0) {
                    model.setValueAt(false, row, 4);
                } else {
                    model.setValueAt(true, row, 4);
                }

                if (sub_stu.getWeek5() == 0) {
                    model.setValueAt(false, row, 5);
                } else {
                    model.setValueAt(true, row, 5);
                }

                if (sub_stu.getWeek6() == 0) {
                    model.setValueAt(false, row, 6);
                } else {
                    model.setValueAt(true, row, 6);
                }

                if (sub_stu.getWeek7() == 0) {
                    model.setValueAt(false, row, 7);
                } else {
                    model.setValueAt(true, row, 7);
                }

                if (sub_stu.getWeek8() == 0) {
                    model.setValueAt(false, row, 8);
                } else {
                    model.setValueAt(true, row, 8);
                }

                if (sub_stu.getWeek9() == 0) {
                    model.setValueAt(false, row, 9);
                } else {
                    model.setValueAt(true, row, 9);
                }

                if (sub_stu.getWeek10() == 0) {
                    model.setValueAt(false, row, 10);
                } else {
                    model.setValueAt(true, row, 10);
                }

                if (sub_stu.getWeek11() == 0) {
                    model.setValueAt(false, row, 11);
                } else {
                    model.setValueAt(true, row, 11);
                }

                if (sub_stu.getWeek12() == 0) {
                    model.setValueAt(false, row, 12);
                } else {
                    model.setValueAt(true, row, 12);
                }

                if (sub_stu.getWeek13() == 0) {
                    model.setValueAt(false, row, 13);
                } else {
                    model.setValueAt(true, row, 13);
                }

                if (sub_stu.getWeek14() == 0) {
                    model.setValueAt(false, row, 14);
                } else {
                    model.setValueAt(true, row, 14);
                }

                if (sub_stu.getWeek15() == 0) {
                    model.setValueAt(false, row, 15);
                } else {
                    model.setValueAt(true, row, 15);
                }
                
                model.setValueAt(sub_stu.getSubjectsStudentsId(), row, 16);
                row++;
            }
        }
    }

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        jPanel1 = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        jTableStudents = new javax.swing.JTable();
        jComboSubjects = new javax.swing.JComboBox();
        jLabel1 = new javax.swing.JLabel();
        jButton1 = new javax.swing.JButton();
        jLabel2 = new javax.swing.JLabel();
        jButton4 = new javax.swing.JButton();
        jLabel5 = new javax.swing.JLabel();
        jLabel4 = new javax.swing.JLabel();
        labelBack = new javax.swing.JLabel();
        btnBack = new javax.swing.JButton();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);
        setLocation(new java.awt.Point(400, 120));

        jPanel1.setBackground(new java.awt.Color(255, 255, 255));

        jTableStudents.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {

            },
            new String [] {
                "Tên", "W1", "W2", "W3", "W4", "W5", "W6", "W7", "W8", "W9", "W10", "W11", "W12", "W13", "W14", "W15"
            }
        ) {
            Class[] types = new Class [] {
                java.lang.String.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class, java.lang.Boolean.class
            };

            public Class getColumnClass(int columnIndex) {
                return types [columnIndex];
            }
        });
        jScrollPane1.setViewportView(jTableStudents);

        jComboSubjects.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        jComboSubjects.addItemListener(new java.awt.event.ItemListener() {
            public void itemStateChanged(java.awt.event.ItemEvent evt) {
                jComboSubjectsItemStateChanged(evt);
            }
        });

        jLabel1.setBackground(new java.awt.Color(255, 255, 255));
        jLabel1.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        jLabel1.setText("Chọn Môn:");

        jButton1.setIcon(new javax.swing.ImageIcon("D:\\Documents\\K2014 nam 3\\Hoc Ki 2\\Java\\Tuan 4\\Attendance\\pic\\save.png")); // NOI18N
        jButton1.setPreferredSize(new java.awt.Dimension(45, 45));
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        jLabel2.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        jLabel2.setText("Lưu");

        jButton4.setFont(new java.awt.Font("Arial", 2, 10)); // NOI18N
        jButton4.setText("Đăng xuất");
        jButton4.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton4ActionPerformed(evt);
            }
        });

        jLabel5.setFont(new java.awt.Font("Arial", 2, 14)); // NOI18N
        jLabel5.setIcon(new javax.swing.ImageIcon("D:\\Documents\\K2014 nam 3\\Hoc Ki 2\\Java\\Tuan 4\\Attendance\\pic\\tea_avatar.png")); // NOI18N
        jLabel5.setText("Xin chào Giáo Viên");

        jLabel4.setFont(new java.awt.Font("Arial", 1, 18)); // NOI18N
        jLabel4.setText("Xem Kết Quá Điểm Danh");

        labelBack.setFont(new java.awt.Font("Arial", 0, 14)); // NOI18N
        labelBack.setText("  Quay Lại");

        btnBack.setIcon(new javax.swing.ImageIcon("D:\\Documents\\K2014 nam 3\\Hoc Ki 2\\Java\\Tuan 4\\Attendance\\pic\\stu_menu_create_back.png")); // NOI18N
        btnBack.setPreferredSize(new java.awt.Dimension(45, 45));
        btnBack.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnBackActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 800, Short.MAX_VALUE)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jLabel1)
                        .addGap(26, 26, 26)
                        .addComponent(jComboSubjects, javax.swing.GroupLayout.PREFERRED_SIZE, 173, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(jButton4))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                        .addGap(0, 0, Short.MAX_VALUE)
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addGroup(jPanel1Layout.createSequentialGroup()
                                        .addGap(10, 10, 10)
                                        .addComponent(jLabel2))
                                    .addComponent(jButton1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                                .addGap(3, 3, 3)
                                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                                    .addComponent(labelBack)
                                    .addGroup(jPanel1Layout.createSequentialGroup()
                                        .addGap(11, 11, 11)
                                        .addComponent(btnBack, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)))
                                .addContainerGap())
                            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                                .addComponent(jLabel4)
                                .addGap(142, 142, 142)
                                .addComponent(jLabel5))))))
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addGap(34, 34, 34)
                        .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(jComboSubjects, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                            .addComponent(jLabel1)
                            .addComponent(jButton4)))
                    .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                        .addComponent(jLabel5)
                        .addComponent(jLabel4)))
                .addGap(18, 18, 18)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.PREFERRED_SIZE, 197, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(18, 18, 18)
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(jButton1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jLabel2))
                    .addGroup(jPanel1Layout.createSequentialGroup()
                        .addComponent(btnBack, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(labelBack)))
                .addContainerGap(36, Short.MAX_VALUE))
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

    private void jComboSubjectsItemStateChanged(java.awt.event.ItemEvent evt) {//GEN-FIRST:event_jComboSubjectsItemStateChanged
        loadListStu();
    }//GEN-LAST:event_jComboSubjectsItemStateChanged

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        int isSuccess = 0;
        for (int count = 0; count < jTableStudents.getRowCount(); count++) {

            TbSubjectsStudents sub_stu = new TbSubjectsStudents();
            sub_stu.setTbStudents(StudentsDao.getStudentsInfo(StudentsDao.getIdByName(jTableStudents.getValueAt(count, 0).toString())));

            sub_stu.setTbSubjects(SubjectsDao.getSubjectsInfo(SubjectsDao.getIdByName(jComboSubjects.getSelectedItem().toString())));

            if (jTableStudents.getValueAt(count, 1).toString().equals("true")) {
                sub_stu.setWeek1(1);
            } else {
                sub_stu.setWeek1(0);
            }

            if (jTableStudents.getValueAt(count, 2).toString().equals("true")) {
                sub_stu.setWeek2(1);
            } else {
                sub_stu.setWeek2(0);
            }

            if (jTableStudents.getValueAt(count, 3).toString().equals("true")) {
                sub_stu.setWeek3(1);
            } else {
                sub_stu.setWeek3(0);
            }

            if (jTableStudents.getValueAt(count, 4).toString().equals("true")) {
                sub_stu.setWeek4(1);
            } else {
                sub_stu.setWeek4(0);
            }

            if (jTableStudents.getValueAt(count, 5).toString().equals("true")) {
                sub_stu.setWeek5(1);
            } else {
                sub_stu.setWeek5(0);
            }

            if (jTableStudents.getValueAt(count, 6).toString().equals("true")) {
                sub_stu.setWeek6(1);
            } else {
                sub_stu.setWeek6(0);
            }

            if (jTableStudents.getValueAt(count, 7).toString().equals("true")) {
                sub_stu.setWeek7(1);
            } else {
                sub_stu.setWeek7(0);
            }

            if (jTableStudents.getValueAt(count, 8).toString().equals("true")) {
                sub_stu.setWeek8(1);
            } else {
                sub_stu.setWeek8(0);
            }

            if (jTableStudents.getValueAt(count, 9).toString().equals("true")) {
                sub_stu.setWeek9(1);
            } else {
                sub_stu.setWeek9(0);
            }

            if (jTableStudents.getValueAt(count, 10).toString().equals("true")) {
                sub_stu.setWeek10(1);
            } else {
                sub_stu.setWeek10(0);
            }

            if (jTableStudents.getValueAt(count, 11).toString().equals("true")) {
                sub_stu.setWeek11(1);
            } else {
                sub_stu.setWeek11(0);
            }

            if (jTableStudents.getValueAt(count, 12).toString().equals("true")) {
                sub_stu.setWeek12(1);
            } else {
                sub_stu.setWeek12(0);
            }

            if (jTableStudents.getValueAt(count, 13).toString().equals("true")) {
                sub_stu.setWeek13(1);
            } else {
                sub_stu.setWeek13(0);
            }

            if (jTableStudents.getValueAt(count, 14).toString().equals("true")) {
                sub_stu.setWeek14(1);
            } else {
                sub_stu.setWeek14(0);
            }

            if (jTableStudents.getValueAt(count, 15).toString().equals("true")) {
                sub_stu.setWeek15(1);
            } else {
                sub_stu.setWeek15(0);
            }
            
            sub_stu.setSubjectsStudentsId(Integer.parseInt((jTableStudents.getValueAt(count, 16).toString())));
            boolean kq = SubjectsStudentsDao.update(sub_stu);

            if (kq == true) {
                isSuccess = 1;
            } else {
                isSuccess = 0;
            }
        }
        if (isSuccess == 1) {
            JOptionPane.showMessageDialog(rootPane, "Chỉnh sửa thành công");
        } else {
            JOptionPane.showMessageDialog(rootPane, "Chỉnh sửa thất bại");
        }
    }//GEN-LAST:event_jButton1ActionPerformed

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
        JFrameTeacher jf = new JFrameTeacher();
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
            java.util.logging.Logger.getLogger(JFrameTeacher_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(JFrameTeacher_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(JFrameTeacher_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(JFrameTeacher_Report.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(new Runnable() {
            public void run() {
                new JFrameTeacher_Report().setVisible(true);
            }
        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnBack;
    private javax.swing.JButton jButton1;
    private javax.swing.JButton jButton4;
    private javax.swing.JComboBox jComboSubjects;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JTable jTableStudents;
    private javax.swing.JLabel labelBack;
    // End of variables declaration//GEN-END:variables
}
