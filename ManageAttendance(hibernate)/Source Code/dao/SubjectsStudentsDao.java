/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dao;

import java.util.*;
import pojos.*;
import org.hibernate.*;

/**
 *
 * @author Pham Tien
 */
public class SubjectsStudentsDao {

    //Danh sach
    public static List<TbSubjectsStudents> listSubStu() {
        List<TbSubjectsStudents> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select stu from TbSubjectsStudents stu";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }

    //Them hoc sinh vao mon

    public static boolean addSubjects(TbSubjectsStudents sub_stu) {
        Session session = HibernateUtil.getSessionFactory().openSession();

        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();

            session.save(sub_stu);

            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();
            System.err.println(ex);
        } finally {
            session.close();
        }
        return true;
    }

    public static boolean update(TbSubjectsStudents sub_stu) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            session.update(sub_stu);
            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();
            System.err.println(ex);
        } finally {
            session.close();
        }
        return true;
    }
    
    public static boolean updateBySql(TbSubjectsStudents sub_stu) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            String hql = "update TbSubjectsStudents set week1 = " + sub_stu.getWeek1() 
                    + " week2 = " + sub_stu.getWeek2() + " week3 = " + sub_stu.getWeek3()
                    + " week4 = " + sub_stu.getWeek4() + " week5 = " + sub_stu.getWeek5()
                    + " week6 = " + sub_stu.getWeek6() + " week7 = " + sub_stu.getWeek7()
                    + " week8 = " + sub_stu.getWeek8() + " week9 = " + sub_stu.getWeek9()
                    + " week10 = " + sub_stu.getWeek10() + " week11 = " + sub_stu.getWeek11()
                    + " week12 = " + sub_stu.getWeek12() + " week13 = " + sub_stu.getWeek13()
                    + " week14 = " + sub_stu.getWeek14() + " week15 = " + sub_stu.getWeek15()
                    + " where subjectsStudentsId = " + sub_stu.getSubjectsStudentsId();
            Query query = session.createQuery(hql);
            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();            
            System.err.println(ex);
            return false;
        } finally {
            session.close();
        }
        return true;
    }    
    
}
