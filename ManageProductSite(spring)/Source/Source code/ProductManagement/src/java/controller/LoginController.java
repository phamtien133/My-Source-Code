/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package controller;

import java.security.Principal;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.security.core.userdetails.User;
import org.springframework.ui.ModelMap;
import pojos.*;
import service.*;

/**
 *
 * @author Pham Tien
 */
@Controller
public class LoginController {

    @RequestMapping(value = "/", method = RequestMethod.GET)
    public String loginPage(Model model) {

        return "/pages/loginPage";
    }

    @RequestMapping(value = "/logoutSuccessful", method = RequestMethod.GET)
    public String logoutSuccessfulPage(Model model) {
        model.addAttribute("title", "Logout");
        return "/pages/logoutSuccessfulPage";
    }

    @RequestMapping(value = "/index", method = RequestMethod.GET)
    public String homeUser(Model model, Principal principal) {

        // Sau khi user login thanh cong se co principal
        String userName = principal.getName();
        ProductService productService = new ProductService();
        model.addAttribute("listProduct", productService.getListProduct());
        LoginService ls = new LoginService();
        int role = ls.getRole(userName);
        if (role == 1) {
            return "/pages/logoutSuccessAdmin";
        } else {
            return "/pages/logoutSuccessStaff";
        }
    }

    @RequestMapping(value = "/info", method = RequestMethod.GET)
    public String userInfo(Model model, Principal principal) {
        // Sau khi user login thanh cong se co principal
        return "/pages/info";
    }

    @RequestMapping(value = "home/info", method = RequestMethod.GET)
    public String userInfo(ModelMap model, Principal principal) {
        // Sau khi user login thanh cong se co principal
        String userName = principal.getName();
        LoginService ls = new LoginService();
        int role = ls.getRole(userName);
        model.put("listInfo", ls.getInfoUser(ls.getId(userName)));
        if (role == 1) {
            return "/pages/info_admin";
        } else {
            return "/pages/info_staff";
        }
    }

    @RequestMapping(value = "/403", method = RequestMethod.GET)
    public String accessDenied(Model model, Principal principal) {
        if (principal != null) {
            model.addAttribute("message", "Hi " + principal.getName()
                    + "<br> You do not have permission to access this page!");
        } else {
            model.addAttribute("msg",
                    "You do not have permission to access this page!");
        }
        return "/pages/403Page";
    }
}
