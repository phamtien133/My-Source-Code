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
public class ProductService {
    //ProductDao productDao;
    //Lấy danh sách sản phẩm
    public List<TbProduct> getListProduct()
    {
        List<TbProduct> ds = ProductDao.listProduct();
        return ds;
    }
    
    //Thêm sản phẩm
    public boolean addProduct(TbProduct product)
    {
        if(ProductDao.addProduct(product) == true)
        {
            return true;
        }
        return false;
    }
    
    //Lấy chi tiết sản phẩm
    public TbProduct getInfoProduct(int idProduct)
    {
        return ProductDao.getProductInfo(idProduct);
    }
    
    //Chỉnh sửa sản phẩm
    public boolean editProduct(TbProduct product)
    {
        if(ProductDao.update(product) == true)
        {
            return true;
        }
        return false;
    }
    
    //Xóa sản phẩm
    public void deleteProduct(int idProduct)
    {
        ProductDao.deleteProduct(idProduct);
    }
    
    //Hàm test
    public static void main(String[] args) {
        for (TbProduct pro : new ProductService().getListProduct())
        {
            System.out.println(pro.getProductName());
        }
    }
}
