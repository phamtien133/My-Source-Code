var express = require('express');
var crypto = require('crypto');
var moment = require('moment');
var mkdirp = require('mkdirp');
var multer  =   require('multer');
var fs = require('fs');
var ncp = require('ncp').ncp;
var storage =   multer.diskStorage({

  destination: function (req, file, callback) {
    fs.unlink('1.jpf', function(err) {
    if(err && err.code == 'ENOENT') {
        // file doens't exist
        console.info("File doesn't exist, won't remove it.");
    } else if (err) {
        // maybe we don't have enough permission
        console.error("Error occurred while trying to remove file");
    } else {
        console.info(`removed`);
    }
});
    fs.unlink('2.jgp', function(err) {
    if(err && err.code == 'ENOENT') {
        // file doens't exist
        console.info("File doesn't exist, won't remove it.");
    } else if (err) {
        // maybe we don't have enough permission
        console.error("Error occurred while trying to remove file");
    } else {
        console.info(`removed`);
    }
});
    fs.unlink('3.jpg', function(err) {
    if(err && err.code == 'ENOENT') {
        // file doens't exist
        console.info("File doesn't exist, won't remove it.");
    } else if (err) {
        // maybe we don't have enough permission
        console.error("Error occurred while trying to remove file");
    } else {
        console.info(`removed`);
    }
});
    callback(null, 'public/assests/product/tmp/');
  },
  filename: function (req, file, callback) {
    callback(null, file.originalname);
  }
});
var upload = multer({ storage: storage });

var restrict = require('../middle-wares/restrict');
var seller = require('../models/seller');
var product = require('../models/product');

var sellerRoute = express.Router();


function getPostDate()
{
    var date = new Date();
    date = date.getTime();
    return date;
}
function getState(State){
    if(State == null) return 0;
    else if(State == "1") return 1;
}

sellerRoute.get('/', function(req, res) {
    if (req.session.isLogged === true) {
        res.render('home');
    } else {
        res.render('account/login', {
            layoutModels: res.locals.layoutModels,
            showError: false,
            errorMsg: ''
        });
    }
   // res.render('seller/index');
});

sellerRoute.get('/postProduct', function(req, res) {
    res.render('seller/postProduct');
});

sellerRoute.get('/listSellingProduct/:id', function(req, res) {
    product.loadDetail(req.params.id)
        .then(function(pro) {
            var id = req.params.id;
            var aProducts= pro;
                product.loadName(id)
                    .then(function(rowsName) {
                        var DiemDanhGia = 0;
                        var DiemDanhGia2 = 0;
                        if (rowsName.Seller != null) {
                            aProducts['nameSeller'] = rowsName.Seller['HoTen'];
                            DiemDanhGia = (rowsName.Seller['DiemDGDuong'] - rowsName.Seller['DiemDGAm'])/(rowsName.Seller['DiemDGDuong'] + rowsName.Seller['DiemDGAm']) * 100;
                            DiemDanhGia = DiemDanhGia.toFixed(1);
                            aProducts['pointSeller'] = DiemDanhGia;
                        } else {
                            aProducts['nameSeller'] = "Không có"; 
                            aProducts['pointSeller'] = "0";
                        }

                        if (rowsName.Customer != null) {
                            aProducts['nameCustomer'] = rowsName.Customer['HoTen'];
                            DiemDanhGia2 = (rowsName.Customer['DiemDGDuong'] - rowsName.Customer['DiemDGAm'])/(rowsName.Customer['DiemDGDuong'] + rowsName.Customer['DiemDGAm']) * 100;
                            DiemDanhGia2 = DiemDanhGia2.toFixed(1);
                            aProducts['pointCustomer'] = DiemDanhGia2;
                        } else {
                            aProducts['nameCustomer'] = "Không có"; 
                            aProducts['pointCustomer'] = "0";
                        }

                        res.render('seller/detail', {
                        layoutModels: res.locals.layoutModels,
                            product: aProducts,
                        });
                });
        });
});

sellerRoute.get('/profile', function(req, res) {
     product.loadProfilePage(req.session.user.khachhangid)
        .then(function(rows) {
            console.log(rows);
        var ret = null;

        var aNonExpired = rows.NonExpired;
        var aSoldProduct= rows.SoldProduct;
        for (var i = 0; i < rows.NonExpired.length; i++)
        {
            product.loadNameCustomer(rows.NonExpired[i]['IdLoaiDanhMuc'])
                .then(function(rowsName1) {
                for (var i = 0; i< rowsName1.length; i++) {
                    aNonExpired[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5);
                }
            }); 
        }
        for (var i = 0; i < rows.SoldProduct.length; i++)
        {
            product.loadNameCustomer(rows.SoldProduct[i]['IdLoaiDanhMuc'])
                .then(function(rowsName1) {
                for (var i = 0; i< rowsName1.length; i++) {
                    aSoldProduct[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5);
                }
            }); 
        }
        ret = {
            layoutModels: res.locals.layoutModels,
            products: aNonExpired,
            products2: aSoldProduct,
        }
        res.render('seller/profile', ret);
    });
});

sellerRoute.get('/listSellingProduct', function(req, res) {
    product.loadProductsOfSeller(req.session.user.khachhangid)
        .then(function(rows){
            
            var aProducts= rows;
            for (var i = 0; i < rows.length; i++)
            {
                product.loadNameCustomer(rows[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5);
                    }
                }); 
            }
            if(rows.length == 1) {
                product.loadNameCustomer(rows[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5);
                    }
                }); 
            }
            res.render('seller/listSellingProduct', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: rows.total === 0,
                catId: req.params.id,
            });
        });
});


sellerRoute.post('/listSellingProduct/:id', function(req, res) {

    var today = new Date();
    var entity = {
        ChiTietSanPham: req.body.detail,
        sanphamid:req.params.id
        
    };
    product.update(entity)
        .then(function(user) {
            res.render('seller/detail', {
                layoutModels: res.locals.layoutModels,
                showError: true
                //errorMsg: 'Đăng ký thành công.'
            });
    fs.appendFileSync('desc.txt', "\nEdit " + today.toDateString() + " : " + req.body.detail);

        });
});


sellerRoute.post('/postProduct',upload.fields([{ name: 'img', maxCount: 1 }, { name: 'img2', maxCount: 1 }, { name: 'img3', maxCount: 1 }]),function(req, res, next) {  

    var entity = {
        TenSanPham: req.body.productname,
        GiaHienTai: req.body.startprice,
        GiaMuaNgay: req.body.buyprice,
        ThoiGianBatDau: getPostDate(),
        IdLoaiDanhMuc: req.body.productcart,
        IdKHBan: req.session.user.khachhangid,
        ChiTietSanPham: req.body.detal,
        BuocGia: req.body.pricestep,
        TuDongGiaHan: getState(req.body.isAutoUpdate)
    };

    console.log(req.files);
    // console.log(entity);
    seller.insert(entity)
        .then(function(insertId) {

            mkdirp('public/assests/product/'+ insertId, function (err) {
                if (err) console.error(err)
                else console.log('pow!')
            });
            var srcPath = 'public/assests/product/tmp';
            var destPath = 'public/assests/product/' + insertId;
            ncp(srcPath, destPath, function (err) {
              if (err) {
                return console.error(err);
              }
              console.log('Copying files complete.');
            });

            res.render('seller/postProduct', {
                layoutModels: res.locals.layoutModels,
                showError: true,
                errorMsg: 'Đăng sản phẩm thành công.'
                //produtid = insertId
               
            });
        });
});


module.exports = sellerRoute;