var express = require('express');
var product = require('../models/product');  
var listProduct = require('../models/listProduct'); 
var account = require('../models/account'); 
var fs      = require('fs');
var dateFormat = require('dateformat');
var formatCurrency = require('format-currency'); 
var nodemailer =  require('nodemailer'); // khai báo sử dụng module nodemailer
var transporter =  nodemailer.createTransport({ // config mail server
    service: 'Gmail',
    auth: {
        user: 'phamductien1417@gmail.com',
        pass: 'anhtien1'
    } 
});
var productRoute = express.Router();

productRoute.get('/byCat/:id', function(req, res) {

    // product.loadAllByCat(req.params.id)
    //     .then(function(list) {
    //         res.render('product/byCat', {
    //             layoutModels: res.locals.layoutModels,
    //             products: list,
    //             isEmpty: list.length === 0,
    //             catId: req.params.id
    //         });
    //     });

    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;

    product.loadPageByCat(req.params.id, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameCustomer(data.list[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            if(data.list.length == 1) {
                product.loadNameCustomer(data.list[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            res.render('product/byCat', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
        });
});

productRoute.get('/detail/:id', function(req, res) {
    product.loadDetail(req.params.id)
        .then(function(pro) {
            var id = req.params.id;
            var aProducts= pro;
                // product.loadNameSeller(req.params.id)
                //     .then(function(rowsName) {
                //         aProducts['nameSeller'] = rowsName['HoTen'];
                //         var DiemDanhGia = (rowsName['DiemDGDuong'] - rowsName['DiemDGAm'])/(rowsName['DiemDGDuong'] + rowsName['DiemDGAm']) * 100;
                //         DiemDanhGia = DiemDanhGia.toFixed(1);
                //         aProducts['pointSeller'] = DiemDanhGia;
                //     product.loadNameSellerbyId(req.params.id)
                //         .then(function(rowsName2) {
                //             aProducts['nameCustomer'] = rowsName2['HoTen'];
                //             var DiemDanhGia2 = (rowsName2['DiemDGDuong'] - rowsName2['DiemDGAm'])/(rowsName2['DiemDGDuong'] + rowsName2['DiemDGAm']) * 100;
                //             DiemDanhGia2 = DiemDanhGia2.toFixed(1);
                //             aProducts['pointCustomer'] = DiemDanhGia2;
                //     });
                // });
                product.loadName(id)
                    .then(function(rowsName) {
                        var DiemDanhGia = 0;
                        var DiemDanhGia2 = 0;
                        var GiaDeCu = pro.GiaHienTai + pro.BuocGia;
                        if (rowsName.Seller != null) {
                            aProducts['nameSeller'] = rowsName.Seller['HoTen'];
							aProducts['idSeller'] = rowsName.Seller['KhachHangId'];
                            DiemDanhGia = (rowsName.Seller['DiemDGDuong'] - rowsName.Seller['DiemDGAm'])/(rowsName.Seller['DiemDGDuong'] + rowsName.Seller['DiemDGAm']) * 100;
                            DiemDanhGia = DiemDanhGia.toFixed(1);
                            aProducts['pointSeller'] = DiemDanhGia;
                        } else {
                            aProducts['nameSeller'] = "Không có"; 
                            aProducts['pointSeller'] = "0";
                        }

                        if (rowsName.Customer != null) {
                            aProducts['nameCustomer'] = rowsName.Customer['HoTen'];
							aProducts['idCustomer'] = rowsName.Customer['KhachHangId'];
                            DiemDanhGia2 = (rowsName.Customer['DiemDGDuong'] - rowsName.Customer['DiemDGAm'])/(rowsName.Customer['DiemDGDuong'] + rowsName.Customer['DiemDGAm']) * 100;
                            DiemDanhGia2 = DiemDanhGia2.toFixed(1);
                            aProducts['pointCustomer'] = DiemDanhGia2;
                        } else {
                            aProducts['nameCustomer'] = "Không có"; 
                            aProducts['pointCustomer'] = "0";
                        }
                        aProducts['GiaDeCu'] = GiaDeCu;

                        res.render('product/detail', {
                        layoutModels: res.locals.layoutModels,
                            product: aProducts,
                        });
                });
        });
});

productRoute.get('/like', function(req, res) {
    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;
    console.log(req.session.user.khachhangid);
    product.loadProductLike(req.session.user.khachhangid, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameCustomer(data.list[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            if(data.list.length == 1) {
                product.loadNameCustomer(data.list[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            res.render('product/listLike', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
    });
});

productRoute.get('/bid', function(req, res) {
    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;
    console.log(req.session.user.khachhangid);
    product.loadProductBid(req.session.user.khachhangid, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameCustomer(data.list[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            if(data.list.length == 1) {
                product.loadNameCustomer(data.list[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            for (var i = 0; i < data.list.length; i++)
            {
                if (aProducts[i]['IdKHGiuGia'] == req.session.user.khachhangid) {
                    aProducts[i]['TenSanPham'] = aProducts[i]['TenSanPham'] + "(Đang giữ sản phẩm này)";
                }
            }
            res.render('product/listBid', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
    });
});

productRoute.get('/Won', function(req, res) {
    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;
    console.log(req.session.user.khachhangid);
    product.loadProductWon(req.session.user.khachhangid, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameCustomer(data.list[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            if(data.list.length == 1) {
                product.loadNameCustomer(data.list[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            res.render('product/listWon', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
    });
});
productRoute.get('/searchLike', function(req, res) {
    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;
    product.searchProductLike(req.session.user.khachhangid, req.query.txtKeyword, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameCustomer(data.list[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            if(data.list.length == 1) {
                product.loadNameCustomer(data.list[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            res.render('product/listLike', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
    });
});

productRoute.get('/userDetail/:id', function(req, res) {
    var id = req.params.id;
    var entity = {
        khachhangid: id
    };
    account.loadCustomerInfo(entity)
        .then(function(User) {
            account.loadRatedInfo(entity)
            .then(function(arrRatings) {
                //edit session
                res.render('product/userDetail', {
                    ratings: arrRatings,
                    user: User,
                    layoutModels: res.locals.layoutModels,
                    showError: false
                    //errorMsg: 'Đăng ký thành công.'
                    });

            });
        });
});

productRoute.post('/userDetail/:id/like', function(req, res, next) {
    var id = req.params.id;
    var entity = {
        nguoinhanid: id,//nguoi ban
        nguoidangid: req.session.user.khachhangid,//nguoi mua
        noidung: req.body.nhanxet,
        diemdanhgia:1
    };
    account.checkRatedOrNot(entity)
        .then(function(rows) {
            if(rows == null) {
                account.ratingPlus(entity)
                    .then(function(User) {
                        res.redirect('/home'); 
                        });
            }
            res.redirect('/home');
        });
});

productRoute.post('/userDetail/:id/dislike', function(req, res, next) {
    var id = req.params.id;
    var entity = {
        nguoinhanid: id,
        nguoidangid: req.session.user.khachhangid,
        noidung: req.body.nhanxet,
        diemdanhgia:-1
    };
    account.checkRatedOrNot(entity)
        .then(function(rows) {
            if(rows == null) {
                account.ratingMinus(entity)
                    .then(function(User) {
                        res.redirect('/home'); 
                        });
            }
            else {
                res.redirect('/home');
            }
            });
});

//dat tien
productRoute.post('/setMoney/:id/:giadecu',function(req,res) {
    var SPid = req.params.id;
    var GiaDeCu = req.params.giadecu;
    var intDate = new Date().getTime();
    if(req.session.user.diemdanhgia <= 80) {
        console.log("diemdanhgia <= 80");
    }
    else if(req.body.tiendatcuoc < GiaDeCu) {
        console.log("tiendatcuoc < GiaDeCu");

    }
    else {
        var entity = {
            khachhangid:req.session.user.khachhangid,
            sanphamid:SPid,
            loaidssp:2,
            sotien:req.body.tiendatcuoc,
            thoigiandaugia:intDate
        };

        //them vao ls dau gia
        console.log(req.session.user.khachhangid+"   "+SPid+"   "+req.body.tiendatcuoc+"   "+intDate);
        product.addHistory(entity)
            .then(function(historyId) {
                if(historyId != null) {
                    console.log("Them thanh cong!");
                }
                else {

                    console.log("Them that bai!");
                }
            });
        //Kiểm tra autobid
        product.getMaxAuToBid(SPid)
            .then(function(maxPrice) {
                console.log(maxPrice[0].LargestPrice);
            if (maxPrice[0].LargestPrice > req.body.tiendatcuoc) {
                product.setMoney(entity)
                    .then(function(productId) {
                        res.redirect('/product/detail/'+req.params.id);
                });
            }
            else {
                //ra gia
        product.setMoney(entity)
            .then(function(productId) {
                product.loadDetail(productId)
                    .then(function(product) {
                        //Gửi mail
                        var mainOptions = { // thiết lập đối tượng, nội dung gửi mail
                            from: 'admin of website auction',
                            to: req.session.user.email,
                            subject: 'Thông báo về việc đặt giá',
                            text: 'You recieved message from ',
                            html: '<p>Chúc mừng bạn đặt giá thành công</p>'
                        }

                        transporter.sendMail(mainOptions, function(err, info){
                            if (err) {
                                console.log("err: "+err);
                            } else {
                                console.log('Message sent: ' +  info.response);
                            }
                        });
                        //in ra ls dau gia ra file bid_log.txt
                        var dir = "./public/assests/product/"+productId;
                        var day=dateFormat(new Date(), "dd/mm/yyyy HH:MM");
                        if (!fs.existsSync(dir)){
                            fs.mkdirSync(dir);
                        }
                        var logger = fs.createWriteStream(dir+"/bid_log.txt", {
                          flags: 'a' // 'a' means appending (old data will be preserved)
                        });
                        var hoten = "***"+ req.session.user.hoten.slice(req.session.user.hoten.length-1,req.session.user.hoten.length);
                        logger.write(day+" - "+hoten+" => "+formatCurrency(product.GiaHienTai) + "\n");
                        logger.end();

                        //them vao ds san pham dang tham gia dau gia
                        listProduct.checkExistOrNot(entity)
                            .then(function(rows) {
                                if(rows != null) {
                                    console.log("Sp da co trong ds dang tham gia dau gia");
                                }
                                else {
                                    listProduct.insert(entity)
                                    .then(function(productId) {
                                        console.log("them vao ds dang tham gia dau gia");
                                        });
                                    }
                                });

                        

                res.redirect('/home');

            });
        });
            }
        });

        
    }
    // res.redirect('/home');
});

productRoute.get('/auctioning', function(req, res) {
    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;
    console.log(req.session.user.khachhangid);
    product.loadProductAuctioning(req.session.user.khachhangid, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            console.log(aProducts);
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameCustomer(data.list[i]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[i]['nameCustomer'] = rowsName1[i]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }
            if(data.list.length == 1) {
                product.loadNameCustomer(data.list[0]['IdLoaiDanhMuc'])
                    .then(function(rowsName1) {
                    for (var i = 0; i< rowsName1.length; i++) {
                        aProducts[0]['nameCustomer'] = rowsName1[0]['HoTen'].slice(0,5) +"*****";
                    }
                }); 
            }

            res.render('product/listAuctioning', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
    });
});

productRoute.get('/selled', function(req, res) {
    var rec_per_page = 4;
    var curPage = req.query.page ? req.query.page : 1;
    var offset = (curPage - 1) * rec_per_page;
    product.loadProductSelled(req.session.user.khachhangid, rec_per_page, offset)
        .then(function(data) {

            var number_of_pages = data.total / rec_per_page;
            if (data.total % rec_per_page > 0) {
                number_of_pages++;
            }

            var pages = [];
            for (var i = 1; i <= number_of_pages; i++) {
                pages.push({
                    pageValue: i,
                    isActive: i === +curPage
                });
            }
            var aProducts= data.list;
            for (var i = 0; i < data.list.length; i++)
            {
                product.loadNameUserById(data.list[i]['IdKHGiuGia'])
                    .then(function(rowsName) {
                        console.log(rowsName[0].HoTen);
                        aProducts[i]['nameCustomer'] = rowsName[0].HoTen.slice(0,5) +"*****";
                        console.log(aProducts[i]['nameCustomer']);
                }); 
            }

            if(data.list.length == 1) {
                product.loadNameUserById(data.list[0]['IdKHGiuGia'])
                    .then(function(rowsName) {
                        aProducts[0]['nameCustomer'] = rowsName[0].HoTen.slice(0,5) +"*****";
                        console.log(aProducts[0]['nameCustomer']);
                }); 
            }
            res.render('product/listSelled', {
                layoutModels: res.locals.layoutModels,
                products: aProducts,
                isEmpty: data.total === 0,
                catId: req.params.id,

                pages: pages,
                curPage: curPage,
                prevPage: curPage - 1,
                nextPage: curPage + 1,
                showPrevPage: curPage > 1,
                showNextPage: curPage < number_of_pages - 1,
            });
    });
});

productRoute.get('/setAutoBid/:id', function(req, res) {
    if (req.query.txtPrice > req.query.txtMaxPrice) {
        res.redirect('/product/detail/'+req.params.id); 
    } else {
        product.insertAutoBid(req.params.id, req.session.user.khachhangid, req.query.txtMaxPrice)
            .then(function(pro) {
            res.redirect('/home'); 
        });
    }
});

module.exports = productRoute;