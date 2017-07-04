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
public class ClassroomsDao {
    //Lay thong tin phong
    public static TbClassrooms getClassroomsInfo(int idClassrooms) {
        TbClassrooms classrooms = null;
        
        Session session = HibernateUtil.getSessionFactory().openSession();
        
        try {
            classrooms = (TbClassrooms) session.get(TbClassrooms.class, idClassrooms);
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return classrooms;
    }
    
    //Kiem tra ton tai ten phong
    public static int checkExist(String name) {
        List<TbClassrooms> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select c from TbClassrooms c where classroomsName = '" + name + "'";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if(ds != null) {
            return ds.get(0).getClassroomsId();
        }
        return 0;
    }
    
    public static List<TbClassrooms> listClass() {
        List<TbClassrooms> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select cl from TbClassrooms cl";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }
}
