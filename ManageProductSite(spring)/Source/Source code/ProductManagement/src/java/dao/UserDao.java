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
public class UserDao {
    //Lấy thông tin chi tiết nhân viên
    public static TbUser getUserInfo(int idUser) {
        TbUser user = null;

        Session session = HibernateUtil.getSessionFactory().openSession();

        try {
            user = (TbUser) session.get(TbUser.class, idUser);
        } catch (HibernateException ex) {
            System.err.println(ex);
        } finally {
            session.close();
        }
        return user;
    }
}
