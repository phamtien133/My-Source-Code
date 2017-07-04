/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package controller;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.*;
import pojos.*;
import service.*;
import dao.*;
import static java.lang.Compiler.command;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.springframework.web.portlet.ModelAndView;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/**
 *
 * @author Pham Tien
 */
@Controller
@RequestMapping(value = "/ajax")
public class AjaxController {
    @RequestMapping(value ="/index")
    public String Index(ModelMap mm)
    {   
        ProductService productService = new ProductService();
        mm.put("listProduct", productService.getListProduct());
        return "pages/report";
    }
    
    @RequestMapping(value = "/delete/{id}", method = RequestMethod.GET)
    public String delete(ModelMap mm, @PathVariable("id") int id) {
        ProductService proService = new ProductService();
        proService.deleteProduct(id);
        ProductService productService = new ProductService();
        mm.put("listProduct", productService.getListProduct());
        return "Xóa thành công";
    }

//    @RequestMapping(value = "/delete-more")
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
//            Logger.getLogger(AjaxController.class.getName()).log(Level.SEVERE, null, ex);
//        }
//    }
    @RequestMapping(value = "/delete-more")
    protected String doPost(ModelMap mm, HttpServletRequest request, HttpServletResponse response, final RedirectAttributes redirectAttributes)
            throws ServletException, IOException {
        ProductService proService = new ProductService();
        ModelAndView mv = new ModelAndView("/pages/index");
        if (request.getParameterValues("id-delete") == null || request.getParameter("btnDel") == null) {
            response.sendRedirect("/ProductManagement/home/index");
        }
        
        String listIDCheckbox[] = request.getParameterValues("id-delete");
        
        if (request.getParameter("btnDel") != null) {
            if (listIDCheckbox.length > 0) {
                for (String id : listIDCheckbox) {
                    if (id != null) {
                        proService.deleteProduct(Integer.valueOf(id));
                    }
                }
                List<TbProduct> list = proService.getListProduct();
                mv.addObject("listProduct",list);
            }
        }
        List<TbProduct> listt = proService.getListProduct();
        redirectAttributes.addFlashAttribute("listProduct", listt);
        return "redirect:/home/index";
//        response.sendRedirect("/ProductManagement/home/index");
//        request.getRequestDispatcher("/ProductManagement/home/index").forward(request, response);
    }
   
}
