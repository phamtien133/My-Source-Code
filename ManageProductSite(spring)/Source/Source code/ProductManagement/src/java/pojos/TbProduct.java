package pojos;
// Generated Jun 5, 2017 12:08:07 PM by Hibernate Tools 4.3.1



/**
 * TbProduct generated by hbm2java
 */
public class TbProduct  implements java.io.Serializable {


     private Integer productId;
     private String productName;
     private String productDetail;
     private float productPrice;

    public TbProduct() {
    }

    public TbProduct(String productName, String productDetail, float productPrice) {
       this.productName = productName;
       this.productDetail = productDetail;
       this.productPrice = productPrice;
    }
    
    public TbProduct(TbProduct pro) {
       this.productId = pro.productId;
       this.productName = pro.productName;
       this.productDetail = pro.productDetail;
       this.productPrice = pro.productPrice;
    }
   
    public Integer getProductId() {
        return this.productId;
    }
    
    public void setProductId(Integer productId) {
        this.productId = productId;
    }
    public String getProductName() {
        return this.productName;
    }
    
    public void setProductName(String productName) {
        this.productName = productName;
    }
    public String getProductDetail() {
        return this.productDetail;
    }
    
    public void setProductDetail(String productDetail) {
        this.productDetail = productDetail;
    }
    public float getProductPrice() {
        return this.productPrice;
    }
    
    public void setProductPrice(float productPrice) {
        this.productPrice = productPrice;
    }




}


