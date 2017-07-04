/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package service;

import dao.*;
import java.util.*;
import pojos.*;

/**
 *
 * @author Pham Tien
 */
public class LoginService {

    //Lấy role thông qua user
    public int getRole(String user) {
        int role = LoginDao.getUserRole(user);
        return role;
    }
    
    //Lấy role thông qua user
    public int getId(String user) {
        int id = LoginDao.getUserId(user);
        return id;
    }
    
    //Lấy thông tin chi tiết nhân viên
    public TbUser getInfoUser(int idUser)
    {
        return UserDao.getUserInfo(idUser);
    }
    
    public static void main(String[] args) {
        System.out.println(new LoginService().getInfoUser(1).getUserId());
    }
}