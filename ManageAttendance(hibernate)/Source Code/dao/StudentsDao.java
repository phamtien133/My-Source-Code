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
public class StudentsDao {

    //Lay thong tin hoc sinh

    public static TbStudents getStudentsInfo(String idStu) {
        TbStudents stu = null;

        Session session = HibernateUtil.getSessionFactory().openSession();

        try {
            stu = (TbStudents) session.get(TbStudents.class, idStu);
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return stu;
    }

    //Danh sach hoc sinh

    public static List<TbStudents> listStu() {
        List<TbStudents> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select stu from TbStudents stu";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }

    //Them hoc sinh
    public static boolean addStudents(TbStudents stu) {
        Session session = HibernateUtil.getSessionFactory().openSession();

        if (SubjectsDao.getSubjectsInfo(stu.getStudentsId()) != null) {
            return false;
        }

        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();

            session.save(stu);

            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();
            System.err.println(ex);
        } finally {
            session.close();
        }
        return true;
    }

    public static String getIdByName(String name) {
        List<TbStudents> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select sub from TbStudents sub where studentsName = '" + name + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if (ds != null) {
            return ds.get(0).getStudentsId();
        }
        return null;
    }
}
