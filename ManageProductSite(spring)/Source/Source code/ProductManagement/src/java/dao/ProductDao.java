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
public class ProductDao {

    //Lấy danh sách sản phẩm
    public static List<TbProduct> listProduct() {
        List<TbProduct> ds = null;
        Session session = HibernateUtil.getSessionFactory().openSession();
        try {
            String hql = "select pro from TbProduct pro";
            Query query = session.createQuery(hql);
            ds = query.list();
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return ds;
    }

    //Lấy thông tin chi tiết sản phẩm
    public static TbProduct getProductInfo(int idPro) {
        TbProduct product = null;

        Session session = HibernateUtil.getSessionFactory().openSession();

        try {
            product = (TbProduct) session.get(TbProduct.class, idPro);
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return product;
    }

    //Thêm sản phẩm
    public static boolean addProduct(TbProduct pro) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            session.save(pro);
            transaction.commit();

        } catch (HibernateException ex) {
            transaction.rollback();
            return false;
        } finally {
            session.close();
        }
        return true;
    }

    //Chỉnh sửa sản phẩm
    public static boolean update(TbProduct pro) {
        Session session = HibernateUtil.getSessionFactory().openSession();
        Transaction transaction = null;
        try {
            transaction = session.beginTransaction();
            session.update(pro);
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

    //Xóa sản phẩm
    public static void deleteProduct(int idPro) {

        SessionFactory sessionFactory = new Configuration().configure().buildSessionFactory();

        Session session = sessionFactory.getCurrentSession();

        int id = idPro;

        try {
            session.beginTransaction();

            TbProduct pro = (TbProduct) session.get(TbProduct.class, id);
            if (pro != null) {
                session.delete(pro);

                session.getTransaction().commit();
            }

        } catch (HibernateException e) {
            e.printStackTrace();
            session.getTransaction().rollback();
        }
    }

    public static void main(String[] args) {
        ProductDao.deleteProduct(3);
    }
}
