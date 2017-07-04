/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package controller;

import org.springframework.stereotype.Controller;
import org.springframework.ui.ModelMap;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import pojos.*;
import service.*;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.ResponseBody;
import javax.servlet.http.*;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.request;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.PathVariable;

/**
 *
 * @author Pham Tien
 */
@Controller
@RequestMapping("/product")
public class AddProductController {

    @RequestMapping("/test")
    public String Test(ModelMap mm, @RequestParam("proId") int proId) {
        mm.put("msg", proId);
        return "pages/test";
    }

    @RequestMapping(value = "/add", method = {RequestMethod.POST, RequestMethod.GET})
    public String add(ModelMap mm, @ModelAttribute("formAddProduct") TbProduct product) {
        ProductService proService = new ProductService();
        mm.put("msg", "");
        boolean msg = proService.addProduct(product);
        if (msg == true) {
            mm.put("msg", "Thêm sản phẩm thành công!");
        }
        return "pages/add";
    }
    int i = 0;

    @RequestMapping(value = "/edit", method = {RequestMethod.POST, RequestMethod.GET})
    public String edit(ModelMap mm, @RequestParam("id") int proId, @ModelAttribute("formEditProduct") TbProduct product) {
        ProductService proService = new ProductService();
        if (i == 0) {
            mm.put("msg", "Chỉnh sửa sản phẩm có mã là: " + proId);
            mm.put("product", proService.getInfoProduct(proId));
            i =1;
            return "pages/edit";
        } else if (i == 1) {
            product.setProductId(proId);
            boolean msg = proService.editProduct(product);
            if (msg == true) {
                i = 0;
                mm.put("msg", "Chỉnh sửa sản phẩm có mã là: " + proId + " thành công!");
                mm.put("product", proService.getInfoProduct(proId));
                return "pages/edit";
            } else {
                mm.put("msg", "Chỉnh sửa sản phẩm có mã là: " + proId);
                mm.put("product", proService.getInfoProduct(proId));
                i = 0;
                return "pages/edit";
            }
        }
        i = 0;
        return "pages/edit";
    }

    public static void main(String[] args) {
        ProductService proService = new ProductService();
        TbProduct pro = new TbProduct();
        pro.setProductName("1234");
        pro.setProductId(1);
        pro.setProductDetail("anh anh");
        pro.setProductPrice(20000);
        TbProduct pro2 = new TbProduct(pro);
        boolean msg = proService.editProduct(pro);
        System.out.println(pro2.getProductId());
        System.out.println(msg);
    }
}
