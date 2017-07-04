/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package dao;

import java.io.Serializable;
import java.util.*;
import pojos.*;
import org.hibernate.*;
import org.hibernate.cfg.Configuration;

/**
 *
 * @author Pham Tien
 */
public class LoginDao {
    //Lấy role thông qua username
    public static int getUserRole(String user) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg where loginUser = '" + user + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
            return -1;
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return ds.get(0).getLoginRole();
        }
        return -1;
    }
    
    //Lấy id thông qua username
    public static int getUserId(String user) {
        List<TbLogin> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select lg from TbLogin lg where loginUser = '" + user + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
            return -1;
        } finally {
            session.close();
        }
        if (ds.size() != 0) {
            return ds.get(0).getLoginId();
        }
        return -1;
    }
}
