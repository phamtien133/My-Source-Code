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
public class LoginDao {

    private final SessionFactory sf = HibernateUtil.getSessionFactory();

    public static List<TbLogin> listLogin() {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }

    public static int checkLogin(String user, String pass) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory()
                .openSession();
        try {
            String hql = "select lg from TbLogin lg where loginUser = '" + user
                    + "' and loginPass = '" + pass + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return ds.get(0).getLoginRole();
        } else {
            return 0;
        }
    }

    //

    public static int checkExist(Integer id) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg where loginId = " + id;
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return 1;
        }
        return 0;
    }
    //lay lan dang nhap dau thong qua user
    public static int getFirstLogin(String user) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg where loginUser = '" + user + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return ds.get(0).getLoginFirstLogin();
        }
        return -1;
    }
    //kiem tra lan dang nhap dau tien int loginFirstLogin String loginUser
    public static int checkFirstLoginByUser(String user) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg where loginUser = '" + user + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return ds.get(0).getLoginFirstLogin();
        }
        return 1;
    }

    //Them tai khoan
    public static boolean addLogin(TbLogin lg) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        if (LoginDao.checkExist(lg.getLoginId()) != 0) {
            return false;
        }
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();

            session.save(lg);

            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();
            System.err.println(ex);
        } finally {
            session.close();
        }
        return true;
    }
    //lay id thong qua user
    public static Integer getId(String user) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg where loginUser = '" + user + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return ds.get(0).getLoginId();
        }
        return -1;
    }
    //Doi mat khau
    public static boolean update(TbLogin lg) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            session.update(lg);
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
