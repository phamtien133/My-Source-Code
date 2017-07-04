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
public class SubjectsDao {

    private final SessionFactory sf = HibernateUtil.getSessionFactory();

    //Lay thong tin mon hoc
    public static TbSubjects getSubjectsInfo(String idSub) {
        TbSubjects sub = null;
        
        Session session = HibernateUtil.getSessionFactory().openSession();
        
        try {
            sub = (TbSubjects) session.get(TbSubjects.class, idSub);
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return sub;
    }

    //Them mon hoc
    public static boolean addSubjects(TbSubjects sub) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        
        if (SubjectsDao.getSubjectsInfo(sub.getSubjectsId()) != null) {
            return false;
        }
        
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            
            session.save(sub);
            
            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();
            System.err.println(ex);
        } finally {
            session.close();
        }
        return true;
    }
    
    public static List<TbSubjects> listSub() {
        List<TbSubjects> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select sub from TbSubjects sub";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }
    
    //
    public static String getIdByName(String name) {
        List<TbSubjects> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select sub from TbSubjects sub where subjectsName = '" + name + "'" ;
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if(ds != null) {
            return ds.get(0).getSubjectsId();
        }
        return null;
    }
    
    public static String getNameById(String id) {
        List<TbSubjects> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select sub from TbSubjects sub where subjectsId = '" + id + "'" ;
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if(ds != null) {
            return ds.get(0).getSubjectsName();
        }
        return null;
    }
//    public static boolean saveInfo(String id, String name) {
//        Session session = HibernateUtil.getSessionFactory()
//                .openSession();
//        try {
//            String hql = "INSERT INTO TbSubjects (subjectsId, subjectsName) "
//                    + "VALUES ('" + id + "', '" + name + "')";
//            Query query = session.createQuery(hql);
//            ds = query.list();
//        } catch (HibernateException ex) {
//            System.err.println(ex);
//        } finally {
//            session.close();
//        }
//        return ds;
//    }
}
