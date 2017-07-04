/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package controller;

import java.security.Principal;
import org.springframework.security.core.userdetails.User;
import java.io.IOException;
import java.security.Principal;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import pojos.*;
import service.*;

/**
 *
 * @author Pham Tien
 */
@Controller
@RequestMapping(value = "/home")
public class HomeController {

    @RequestMapping(value = "/index")
    public String Index(ModelMap mm, Principal principal) {
        ProductService productService = new ProductService();
        String userName = principal.getName();
        LoginService logginService = new LoginService();
        mm.put("listProduct", productService.getListProduct());
        mm.put("idUser", logginService.getId(userName));
        return "pages/index";
    }

    @RequestMapping(value = "/index_staff")
    public String IndexStaff(ModelMap mm, Principal principal) {
        ProductService productService = new ProductService();
        String userName = principal.getName();
        LoginService logginService = new LoginService();
        mm.put("idUser", logginService.getId(userName));
        mm.put("listProduct", productService.getListProduct());
        return "pages/index_staff";
    }
    
//    @RequestMapping(value = "/info", method = RequestMethod.GET)
//    public String userInfo(ModelMap model, Principal principal, @RequestParam("idUser") int idUser) {
//        // Sau khi user login thanh cong se co principal
//        String userName = principal.getName();
//        LoginService logginService = new LoginService();
//        model.put("listInfo", logginService.getInfoUser(idUser));
//        return "/pages/info_admin";
//    }

//    @RequestMapping(value = "/index2")
//    public void doPost(HttpServletRequest request, HttpServletResponse response) {
//        try {
//            ProductService productService = new ProductService();
//
//            String dsMa[] = request.getParameterValues("id-delete");
//
//            if (request.getParameter("btnDel") != null) {
//                if (dsMa.length > 0) {
//                    for (String id : dsMa) {
//                        if (id != null) {
//                            productService.deleteProduct(Integer.valueOf(id));
//                        }
//                    }
//                }
//            }
//            response.sendRedirect("/ProductManagement/home/index");
//        } catch (IOException ex) {
//            Logger.getLogger(HomeController.class.getName()).log(Level.SEVERE, null, ex);
//        }
//    }
}
