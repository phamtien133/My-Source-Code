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
public class ScheduleDao {
    private final SessionFactory sf = HibernateUtil.getSessionFactory();
    //Danh sach
    public static List<TbSchedule> listSche() {
        List<TbSchedule> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select sche from TbSchedule sche";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }
    //Lay thong tin thoi khoa bieu
    public static TbSchedule getScheduleInfo(Integer scheduleId) {
        TbSchedule schedule = null;
        
        Session session = HibernateUtil.getSessionFactory().openSession();
        
        try {
            schedule = (TbSchedule) session.get(TbSchedule.class, scheduleId);
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return schedule;
    }
    
    //Kiem tra trung
    public static int checkExist(Integer id) {
        List<TbSchedule> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select c from TbSchedule c where scheduleId = " + id ;
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        if(ds != null) {
            return 1;
        }
        return 0;
    }
    
    //Them thoi khoa bieu
    public static boolean addSchedule(TbSchedule schedule) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        if (ScheduleDao.checkExist(schedule.getScheduleId()) == 0) {
            return false;
        }
        
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            
            session.save(schedule);
            
            transaction.commit();
        } catch (HibernateException ex) {
            transaction.rollback();
            System.err.println(ex);
        } finally {
            session.close();
        }
        return true;
    }
}
