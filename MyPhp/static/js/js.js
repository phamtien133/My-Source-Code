$(function(){
  //heightbox();
  //orderinfo();
  //selebox();
  generalFunc();
});
/******************************/
function generalFunc(){
    autoSize();
    changeTab();
    scrollMenu();
    gioihan();
    initDate();
    initMenu();
    checkBoxCustom();
    expandGroup();
    
    initUserProfile();

    initCrm();

    initAddPost();

    /* menu */
    toggleMenu();

    /*  Nhóm trang marketing */
    initPageMarketing();

    /*  Nhóm trang Payment */
    initPagePayment();    
}
/******************************/

/* init User profile */
function initUserProfile()
{
    $('#js-user-profile').click(function(){
        $('.user-proflie').show();
    });
}

var sh = $(window).height()
function hoverInItem(){
  $('.item-features').mouseenter(function(){
      var item = $(this);
      var img = item.find('.im');
      var hvim = item.find('.imhv');
      var bg = item.find('.hvbg');
      var txt = item.find('.is-txt');
      var hvtxt = item.find('.is-hovertxt');
      img.fadeOut(200);
      if(item.hasClass('inbox') == true) txt.fadeOut(200);
      bg.css('opacity','1');
      setTimeout(function(){
        hvim.fadeIn(200);
        if(item.hasClass('inbox') == true) hvtxt.fadeIn(200);
    }, 201);
      item.mouseleave(function(){
      hvim.fadeOut(200);
      if(item.hasClass('inbox') == true) hvtxt.fadeOut(200);
      setTimeout(function(){
          img.fadeIn(200);
          if(item.hasClass('inbox') == true) txt.fadeIn(200);
          bg.css('opacity','0');
      }, 201);
      });
  });
}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
} 
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
} 
function toggleMenu(){
    if (oParams['sController'] =="user.login") {
        return;
    };
    var valToggleMenu = getCookie('toggleMenu');
    var obj = $('.js-toggle-menu');
    var body = $('body');

    /*
        if (valToggleMenu == null || typeof(valToggleMenu) == 'undefined') {
            localStorage.setItem('toggleMenu', '0');
            valToggleMenu = '0';
        }else{
            if (valToggleMenu == '1') {
                obj.removeClass('fa-angle-double-right');
                obj.addClass('fa-angle-double-left');

                body.addClass('js-pin-menu');
            }else{
                obj.removeClass('fa-angle-double-left');
                obj.addClass('fa-angle-double-right');

                body.removeClass('js-pin-menu');
            }
        }
    */
    obj.click(function(){
        if (valToggleMenu == '0') {
            obj.removeClass('fa-angle-double-right');
            obj.addClass('fa-angle-double-left');

            body.addClass('js-pin-menu');

            valToggleMenu = 1;
            setCookie('toggleMenu', '1', 10);
        }else{
            obj.removeClass('fa-angle-double-left');
            obj.addClass('fa-angle-double-right');

            body.removeClass('js-pin-menu');

            valToggleMenu = 0;
            setCookie('toggleMenu', '0', 10);  
        }
    })
}
function checkToggleMenu(){}
function heightbox(){
  var hbar = $('.features-bar').height();
  var hbox = sh - hbar - 134;
  $('.emb-box').height(hbox);
}
function orderinfo(){
  if ($('.order').length == 0) return;
  var numr = $('.order-list-box').find('.r').length;
  var h = sh - (50 * numr) - 199;
  $('.info-box').height(h);
  $('.info-box').css('margin','30px 0 40px');
}
function selebox(){
  $('.sele').click(function(){
    var bt = $(this);
    var num = bt.find('.row').length;
    alert(num);
  });
}
function initSelect2(){
    $('.select2').select2();
}
function gioihan(){
  $('.or').ellipsis({
    row: 1,
    onlyFullWords: true,
  });
}
/*  Hàm format tiền */
function currencyFormat(num) {
    num *= 1;
    if (typeof(num) != 'number' || num == null || num == 'undefined' || num < 0 || isNaN(num)) {
        num = 0;
    };
    return num.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}
function autoSize(){
    $('.autosize').textareaAutoSize();
}

/*  chagne tab : Hàm dùng cho layout cấu trúc dạng Tab*/
function changeTab(){
    if ($('.js-tab').length <= 0) {
        return;
    };

    $('.js-tab').each(function(){
        var tab         = $(this);
        var callBack    = tab.data('call-back');
        var navTab      = tab.find('.js-nav-tab');
        var contentTab  = tab.find('.js-cnt-tab');
        navTab.unbind('click').click(function(){
            var idTab = $(this).data('id');
            tab.find('.js-nav-tab').removeClass('atv');
            $(this).addClass('atv');
            var id = $(this).data('id');

            tab.find('.js-cnt-tab.atv').removeClass('atv').hide();
            tab.find('.js-cnt-tab[data-id="'+id+'"]').addClass('atv').show();
            if(typeof callBack != 'undefined') {
                window[callBack](idTab);
            };
        })
    });
}
/*  Custom scroll 
-   Nếu chạy trên thiết bị di động thì dùng scroll mặc định
-   Nếu chạy trên desktop thì scroll custom
*/
function scrollMenu(){
    /*if(isiPhone() == true){
        $('html, body').addClass('device');
        return;
    }*/
    setTimeout(function(){
        $('.js-scroll').each(function(){
            if(!$(this).hasClass('done')) {
                var isMn        = $(this);
                var callBack    = isMn.data('call-back');
                isMn.mCustomScrollbar({
                    theme           : "minimal-dark",
                    scrollInertia   : 200,
                    scrollEasing    : "linear",
                    callbacks:{
                        whileScrolling  : function(){
                                
                        }
                    }
                });
                $(this).addClass('done');
            }
        });
    }, 200);
}
/* date */
function initDate(){
    if ($('.js-date-time').length <= 0) {
        return;
    };
    $('.js-date-time').each(function(){
        var id = '#' + $(this).attr('id');
        $(id).datetimepicker({
            timepicker:false,
            format:'d.m.Y',
            scrollMonth:false,
            scrollTime:false,
            scrollInput:false
        });
    });
}
/*  menu */
function initMenu(){
    $('.js-open-menu').click(function(){
        var obj = $(this).parent('.mn1');
        $('#left-menu .mn4').height(0);
        $('#left-menu .mn6.fa-angle-down').removeClass('rotate');
        if (!obj.find('.mn4').hasClass('open')) {
            $('#left-menu .mn4').removeClass('open');
        };
        var oSub = obj.find('.mn4');
        var count = oSub.find('.mn1').length;
        if (oSub.hasClass('open') == false) {
            oSub.addClass('open');
            oSub.height(count * 44);
            obj.find('.mn6').addClass('rotate');
        }else{
            oSub.removeClass('open');
            oSub.height(0);
            obj.find('.mn6').removeClass('rotate');
        }
    });
}
/*  Hàm xử lý check box */
function checkBoxCustom(){
    if($('.is-check-box').length <= 0){
        return;
    }
    $('.is-check-box').unbind('click').click(function(){
        var isCheckBox  = $(this);
        var callBack    = isCheckBox.data('call-back');
        var id          = isCheckBox.data('id');
        var state       = 0;

        if(isCheckBox.hasClass('fa-square-o')){
            isCheckBox.removeClass('fa-square-o');
            isCheckBox.addClass('fa-check-square-o');
            state = 1;
        }else{
            isCheckBox.removeClass('fa-check-square-o');
            isCheckBox.addClass('fa-square-o');
            state = 0;
        }

        if(typeof callBack != 'undefined') {
            window[callBack]({
                'id'        : id,
                'state'     : state
            });
        };
    });

    $('.is-label-check-box').unbind('click').click(function(e){
        if(e['target'].className.indexOf('is-check-box') >= 0){
            return;
        }
        var dataCheckBox        = $(this).data('id')

        $('.is-check-box[data-id="' + dataCheckBox +'"]').trigger('click');
    });
}
/*  Hàm đóng mở Group expand */
function expandGroup(){
    $('.is-expand-group').each(function(){
        var isExpandGroup   = $(this);
        var idGroup         = isExpandGroup.data('id');
        var isExpandNav     = isExpandGroup.find('.is-expand-nav[data-id="' + idGroup + '"]');
        var isExpandPanel   = isExpandGroup.find('.is-expand-panel[data-id="' + idGroup + '"]');
        var outExpandPanel  = isExpandGroup.parents('.is-expand-panel');

        isExpandPanel.attr('height-expand', isExpandPanel.height());
        /*isExpandPanel.height(isExpandPanel.height());*/
        isExpandNav.unbind('click').click(function(){
            if(isExpandNav.hasClass('fa-angle-down')){
                outExpandPanel.removeAttr('style');
                isExpandNav.removeClass('fa-angle-down');
                isExpandNav.addClass('fa-angle-up');
                isExpandPanel.hide();
                setTimeout(function(){
                    outExpandPanel.attr('height-expand', outExpandPanel.height());
                    /*outExpandPanel.height(outExpandPanel.height());*/
                }, 500);
            }else{
                outExpandPanel.removeAttr('style');
                isExpandNav.removeClass('fa-angle-up');
                isExpandNav.addClass('fa-angle-down');
                isExpandPanel.show();
                setTimeout(function(){
                    outExpandPanel.attr('height-expand', outExpandPanel.height());
                    /*outExpandPanel.height(outExpandPanel.height());*/
                }, 500);
            }
        });
    });
}
/*  Hàm thay đổi số lượng của nhóm sản phẩm */
/*  Đây là hàm cũ đã thay đổi bằng hàm mới initQuantilyGroup*/
function changeQuanlityProduct(){
    $('.is-row-product').each(function(){
        var isRowProduct    = $(this);
        var idRow           = isRowProduct.data('id');
        var quanlity        = isRowProduct.find('input').val();
        var changeQuanlity  = isRowProduct.find('.is-change-quanlity');

        var quanlityTimeOut, quanlityInterval;
        var curChangeBtn;
        changeQuanlity.unbind('mousedown').mousedown(function(){
            changeQuanlity.unbind('mouseup').mouseup(function(event) {
                clearTimeout(quanlityTimeOut);
                clearInterval(quanlityInterval);
                return false;
            });
            changeQuanlity.unbind('mouseleave').mouseleave(function(event) {
                clearTimeout(quanlityTimeOut);
                clearInterval(quanlityInterval);
                return false;
            });
            curChangeBtn    = $(this);
            var valChange   = 1;
            if(curChangeBtn.hasClass('fa-minus')){
                valChange = -1;
            }
            quanlity = quanlity * 1 + valChange;
            if(quanlity <= 0){
                quanlity = 1;
                alert('Số lượng tối thiếu là 1!');
                return false;
            }
            isRowProduct.find('input').val(quanlity);
            calcPriceRowProduct(idRow);

            quanlityTimeOut = setTimeout(function(){
                quanlityInterval = setInterval(function(){
                    valChange = 1;
                    if(curChangeBtn.hasClass('fa-minus')){
                        valChange = -1;
                    }
                    quanlity = quanlity * 1 + valChange;
                    if(quanlity <= 0){
                        quanlity = 1;
                        clearTimeout(quanlityTimeOut);
                        clearInterval(quanlityInterval);
                        alert('Số lượng tối thiếu là 1!');
                        return false
                    }
                    isRowProduct.find('input').val(quanlity);
                    calcPriceRowProduct(idRow);
                }, 20);
            }, 500);
            return false;
        });
    });
}
function initQuantilyGroup() {
    if($('.is-quantity-group').length <= 0){
        return;
    }
    $('.is-quantity-group').each(function(){
        var quantityGroup   = $(this);
        var dataMin         = quantityGroup.data('min') * 1;
        var dataMax         = quantityGroup.data('max') * 1;
        var dataStep         = quantityGroup.data('step') * 1;
        var callBack        = quantityGroup.data('call-back');
        var quantityValue   = quantityGroup.find('.is-quantity-value').val();
        var quantityInput   = quantityGroup.find('.is-quantity-value');
        var quantityInputHidden   = quantityGroup.find('.js-quantity');
        var quantityBtn     = quantityGroup.find('.is-quantity-up, .is-quantity-down');
        var quantityTimeOut, quantityInterval, curquantityBtn;

        if (typeof(dataMin) == 'undefined') {
            dataMin = 0;
        };
        if (typeof(dataMax) == 'undefined') {
            dataMin = 9999;
        };

        quantityBtn.unbind('mousedown').mousedown(function(){
            curquantityBtn    = $(this);
            quantityBtn.unbind('mouseup').mouseup(function(event) {
                clearTimeout(quantityTimeOut);
                clearInterval(quantityInterval);
                return false;
            });
            quantityBtn.unbind('mouseleave').mouseleave(function(event) {
                clearTimeout(quantityTimeOut);
                clearInterval(quantityInterval);
                return false;
            });
            
            var valChange   = dataStep;
            if(curquantityBtn.hasClass('is-quantity-down')){
                valChange = dataStep * (-1);
            }
            quantityValue = quantityValue * 1 + valChange;
            if(quantityValue < dataMin){
                quantityValue = dataMin;
                alert('Số lượng tối thiếu 1 là ' + dataMin + '!');
                return false;
            }
            if(quantityValue > dataMax){
                quantityValue = dataMin;
                alert('Số lượng tối đa  qlà ' + dataMax + '!');
                return false;
            }
            quantityInput.val(quantityValue);
            quantityInputHidden.val(quantityValue);
            if(typeof callBack != 'undefined') {
                window[callBack]({
                    'obj'       : quantityGroup,
                    'quantity'  : quantityValue
                });
            }

            quantityTimeOut = setTimeout(function(){
                quantityInterval = setInterval(function(){
                    valChange = dataStep;
                    if(curquantityBtn.hasClass('is-quantity-down')){
                        valChange = dataStep * (-1);
                    }
                    quantityValue = quantityValue * 1 + valChange;
                    if(quantityValue < dataMin){
                        quantityValue = dataMin;
                        clearTimeout(quantityTimeOut);
                        clearInterval(quantityInterval);
                        alert('Số lượng tối thiếu là ' + dataMin + '!');
                        return false;
                    }
                    if(quantityValue > dataMax){
                        quantityValue = dataMax;
                        clearTimeout(quantityTimeOut);
                        clearInterval(quantityInterval);
                        alert('Số lượng tối đa là ' + dataMax + '!');
                        return false;
                    }
                    quantityInput.val(quantityValue);
                    quantityInputHidden.val(quantityValue);
                    if(typeof callBack != 'undefined') {
                        window[callBack]({
                            'obj'       : quantityGroup,
                            'quantity'  : quantityValue
                        });
                    }
                }, 20);
            }, 500);
        });

        
        var currValue = 
        quantityInput.focus(function(){
            currValue = quantityInput.val();
            if (currValue < 0) {
                currValue = 1;
            };
        })

        quantityInput.blur(function(){
            var tempValue = quantityInput.val() * 1;
            if (typeof(tempValue) != 'number' || tempValue <= 0 || isNaN(tempValue)) {
                quantityInput.val(currValue);
            };
            if(typeof callBack != 'undefined') {
                window[callBack]({
                    'obj'       : quantityGroup,
                    'quantity'  : quantityValue
                });
            }
        })
    });
}
function initCrm(){
    changeStateCrm();
    initLoadCrm();
}
function initLoadCrm()
{
    if($('.panel-fixed .crm-wrap').length <= 0){
        var contentCrm = '';
        contentCrm = '<div class="container crm-wrap">\
                            <div class="col4 padright20 fix-left-crm">\
                                <div class="relative" style="height: 100%;">\
                                    <section class="panel is-tab row40">\
                                            <div class="js-nav-tab">Danh sách tương tác</div>\
                                        </section>\
                                    <section class="js-list-customer-crm list-customer js-scroll">\
                                        <section class="icm01 item-customer content-box pad10" id="no-interactive">\
                                            <div class="row20 icm0 mgbt10">\
                                                Hiện tại chưa có tương tác nào\
                                            </div>\
                                    </section>\
                                </div>\
                            </div>\
                            <div class="col8">\
                                <section class="bread-crm mgbt20">\
                                    <div class="brc0" id="last-activity">\
                                        <div class="brc01">Hoạt động gần nhất</div>\
                                    </div>\
                                    <div class="icon-quality-connect" data-value="1">\
                                        <span class="col-st-1"></span>\
                                        <span class="col-st-2"></span>\
                                        <span class="col-st-3"></span>\
                                        <span class="col-st-4"></span>\
                                    </div>\
                                    <div class="brc3">\
                                        <span class="fa-stack">\
                                          <i class="fa fa-circle fa-stack-2x"></i>\
                                          <i class="fa fa-plus fa-stack-1x fa-inverse"></i>\
                                        </span>\
                                        <div class="popup-search-user">\
                                            <div class="panel-crm">\
                                                <div class="panel-title">\
                                                    <input type="text" id="search-user" placeholder="Tìm kiếm..."/>\
                                                </div>\
                                                <div class="panel-content">\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="brc2">\
                                        <span class="fa-stack fa-user">\
                                          <i class="fa fa-circle fa-stack-2x"></i>\
                                          <i class="fa fa-user fa-stack-1x fa-inverse"></i>\
                                        </span>\
                                        <div class="popup-info-ws">\
                                            <div class="panel-crm">\
                                                <div class="panel-title">Thông tin khách hàng</div>\
                                                <div class="panel-content">\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </section>\
                                <section class="js-detail-crm"></section>\
                            </div>\
                        </div>'
        $('.panel-fixed').prepend(contentCrm);
        //loadListUserCrm();
        loadDetailCrm();
    }
}
function changeStateCrm(){
    localStorage.setItem('stateCrm', 'close');
    $('#js-crm-state').click(function(){
        var obj = $(this);
        if(obj.hasClass('has-message')){
            localStorage.setItem('stateCrm', 'open');
            $('.panel-fixed').fadeIn();
            obj.removeClass('has-message');
            obj.removeClass('animate-bounce');
            crmSearchUser();
        }else{
            localStorage.setItem('stateCrm', 'close');
            obj.addClass('has-message');
            //$('.panel-fixed .crm-wrap').fadeOut();
            $('.panel-fixed').fadeOut();
        }
    });
}
k_oTimer = null;
function crmSearchUser()
{
    $('#search-user').unbind('keyup').keyup(function(e) {
        var pvalue = $(this).val();
        switch(e.keyCode)
        {
            case 37:
            case 38:
            case 39:
            case 40:
                break;
            case 13:
                if(!empty(pvalue)){
                    searchUser(pvalue);
                }
                break;
            default:
                if(!empty(pvalue)){
                    clearTimeout(k_oTimer);
                    k_oTimer = setTimeout(function(){
                        searchUser(pvalue);
                    }, 500);
                }
                break;
        }
    });
}
function searchUser(value)
{
    if(empty(value))
        return false;
    var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=crm.searchUser';
    sParams += '&k=' + value;
    $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        url: getParam('sJsAjax'),
        type: "GET",
        data: sParams,
        timeout: 15000,
        cache:false,
        dataType: 'text',
        error: function(jqXHR, status, errorThrown){
        },
        success: function (data) {
            $('.popup-search-user .panel-content').html(data);
            $('.fa-phone').click(function(){
                // disable tất cả các nút phone còn lại.
                
                // thêm user hiện tại vào danh sách tương tác.
                
            });
        }
    });
}
function loadListUserCrm(){
    var contentListCustomer = '';
    for (var i = 0; i < 10; i++) {
        contentListCustomer += '<section class="icm01 item-customer content-box pad10">\
                <div class="row20 icm0 mgbt10">\
                    <span class="fa fa-venus icm1"></span>\
                    <div class="icm2">\
                        Nguyễn Khách hàng\
                    </div>\
                    <span class="fa fa-arrow-circle-up icm3"></span>\
                    <span class="fa fa-envelope icm5"></span>\
                </div>\
                <div class="row20">\
                    <div class="col6 icm61">\
                        0909 090 090\
                    </div>\
                    <div class="col6 icm61">\
                        TP. HCM\
                    </div>\
                </div>\
                <div class="row20 mgbt20">\
                    <div class="col6 icm61">\
                        Nhóm VIP\
                    </div>\
                    <div class="col6 icm61">\
                        Thu nhập: 500Tr\
                    </div>\
                </div>\
                <div class="row20">\
                    <div class="col4 icm62">Truy cập:</div>\
                    <div class="col8 icm63">http://dst.com</div>\
                </div>\
                <div class="row20">\
                    <div class="col4 icm62">Hoạt động</div>\
                    <div class="col8 icm63">Vừa đăng nhập</div>\
                </div>\
                <div class="row20">\
                    <div class="col4 icm62">Đơn hàng</div>\
                    <div class="col8 icm63">#12345</div>\
                </div>\
                <div class="row20">\
                    <div class="col4 icm62">Tiền đã nạp</div>\
                    <div class="col8 icm63">10.000.000</div>\
                </div>\
                <div class="icm4">\
                    <div class="icm5 icm51">\
                        <span class="fa fa-phone"></span>\
                    </div>\
                    <div class="icm5 icm52"></div>\
                    <div class="icm5 icm53">\
                        <span class="fa fa-phone"></span>\
                    </div>\
                </div>\
            </section>';
    };
    $('.js-list-customer-crm').html(contentListCustomer);
}
function loadDetailCrm(){
    var contentDetailCrm = '';
    /*  LOAD ĐƠN HÀNG */
    contentDetailCrm += '<section class="panel-crm mgbt20 panel-crm-order">\
                            <div class="panel-title">\
                                Đơn hàng\
                                <span class="panel-title-sum">\
                                    0\
                                </span>\
                            </div>\
                            <div class="panel-content">\
                                <div class="title">\
                                <div class="ord1 tbod">\
                                    <div class="tbod1">Mã số<span class="ic ic1"></span>\
                                    </div>\
                                    <div class="tbod2">Cập nhật<span class="ic ic2"></span>\
                                    </div>\
                                    <div class="tbod3">Số lượng<span class="ic ic2"></span>\
                                    </div>\
                                    <div class="tbod4">Tổng tiền<span class="ic ic2"></span>\
                                    </div>\
                                    <div class="tbod5">Thanh toán<span class="ic ic2"></span>\
                                    </div>\
                                    <div class="tbod6">\
                                        Trạng thái\
                                        <span class="ic ic2"></span>\
                                    </div>\
                                </div>\
                                </div>\
                                <div class="list-item">\
                                </div>\
                            </div>\
                            </section>';

        /*  LOAD TRUY CẬP - GIỎ HÀNG HIỆN TẠI */
        contentDetailCrm += '<div class="row20 mgbt20">\
                            <div class="col6 padright10">';
        /*  load tin nhắn */
            contentDetailCrm += '<section class="panel-crm panel-crm-message js-tab">\
                                    <div class="panel-title">\
                                        <div class="js-nav-tab iz1 fa fa-comments atv" data-id="ms1"></div>\
                                    </div>\
                                    <div class="panel-content">\
                                        <div class="crm-list js-cnt-tab atv" id="crm-chat" data-id="ms1">\
                                            <div class="msg-list js-scroll"></div>\
                                            <div class="msg-control">\
                                                <form method="post">\
                                                <div>\
                                                    <input type="hidden" id="js-current-chat" name="val[cid]" />\
                                                    <input type="hidden" id="js-current-time" name="val[t]" />\
                                                </div>\
                                                <span class="msg-control-icon fa-stack fa-lg">\
                                                  <i class="fa fa-square fa-stack-2x"></i>\
                                                  <i class="fa fa-align-left fa-stack-1x fa-inverse"></i>\
                                                </span>\
                                                <textarea name="val[content]" type="text" class="msg-input" autocomplete="off" id="txt_chat" placeholder="Nhập nội dung"></textarea>\
                                                <div class="msg-submit">Gửi</div>\
                                                </form>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </section>';
        contentDetailCrm += '</div>\
                            <div class="col6 padleft10">';
        /* load giỏ hàng hiện tại */
            contentDetailCrm += '<section class="panel-crm panel-crm-cart">\
                                    <div class="panel-title">\
                                        Giỏ hàng hiện tại\
                                        <div class="panel-title-icon fa fa-ellipsis-v right"></div>\
                                        <div class="panel-title-icon fa fa-history right"></div>\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                        </div>';
    /*  LOAD VÍ ĐIỆN TỬ & LỊCH SỬ CUỘC GỌI */
    contentDetailCrm += '<div class="row20 mgbt20">\
                            <div class="col6 padright10">';
    /* load ví điện tử */
            contentDetailCrm += '<section class="panel-crm panel-crm-wallet">\
                                    <div class="panel-title">\
                                        Ví diện tử\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                            <div class="col6 padleft10">';
    /* load lịch sử cuộc gọi */
            contentDetailCrm += '<section class="panel-crm panel-crm-calling">\
                                    <div class="panel-title">\
                                        Lịch sử cuộc gọi\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                        </div>';
    /*  LOAD LỊCH SỬ PHẨN HỒI & TIN NHẮN */
    contentDetailCrm += '<div class="row20 mgbt20">\
                            <div class="col6 padright10">';
    /* load lịch sử phản hồi */
            contentDetailCrm += '<section class="panel-crm panel-crm-feed">\
                                    <div class="panel-title">\
                                        Lịch sử phản hồi\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                            <div class="col6 padleft10">';
    /*  load truy cập */
            contentDetailCrm += '<section class="panel-crm panel-crm-visit">\
                                    <div class="panel-title">\
                                        Truy cập\
                                        <span class="panel-title-icon fa fa-clock-o right"></span>\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                    </div>';
    /* LOAD HOẠT ĐỘNG & MỐI QUAN HỆ */
    contentDetailCrm += '<div class="row20 mgbt20">\
                            <div class="col6 padright10">\
                                <section class="panel-crm panel-crm-activity">\
                                    <div class="panel-title">\
                                        Hoạt động\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                            <div class="col6 padleft10">\
                                <section class="panel-crm panel-crm-rel">\
                                    <div class="panel-title">\
                                        Mối quan hệ\
                                        <div class="panel-title-icon fa fa-plus right js-add-rel"></div>\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                        </div>';
        /* BẢNG THÔNG TIN CUỐI */
    contentDetailCrm += '<div class="row20 mgbt20">\
                            <div class="col6 padright10">\
                                <section class="panel-crm js-tab panel-crm-lasttab">\
                                    <div class="panel-title">\
                                        <div class="js-nav-tab iz1 fa fa-eye atv" data-id="tl1"></div>\
                                        <div class="js-nav-tab iz1 fa fa-thumbs-up" data-id="tl2"></div>\
                                        <div class="js-nav-tab iz1 fa fa-heart" data-id="tl3"></div>\
                                        <div class="js-nav-tab iz1 fa fa-comments" data-id="tl4"></div>\
                                        <div class="panel-title-icon fa fa-ellipsis-v right"></div>\
                                    </div>\
                                    <div class="panel-content">\
                                    </div>\
                                </section>\
                            </div>\
                        </div>';
    $('.js-detail-crm').html(contentDetailCrm);
    scrollMenu();
    addCustomerSupport();
    addRelationship();
}
function checkAddCrmUser()
{
    return true;
}
function callInComing(){
    var content = '<section class="notify-customer">\
                        <div class="ns1">\
                            <span class="fa fa-venus"></span>\
                            Nguyễn Khách hàng\
                        </div>\
                        <div class="ns2">\
                            <div class="col6">0909090909</div>\
                            <div class="col6">TP.HCM</div>\
                        </div>\
                        <div class="ns2">\
                            <div class="col6">Nhóm VIP</div>\
                            <div class="col6">Thu nhập: 100Tr</div>\
                        </div>\
                        <div class="ns3">Truy cập: <span>http://gomart.vn</span></div>\
                        <div class="ns3">Hoạt động: <span>Vừa đăng nhập</span></div>\
                        <div class="ns3">Đơn hàng: <span>#123456</span></div>\
                        <div class="ns3">Tiền đã nạp: <span>100 Tr</span></div>\
                        <div class="ns4">\
                            <div class="ns41 fa fa-phone js-reject-call"></div>\
                            <div class="ns42 fa fa-phone js-receive-call"></div>\
                        </div>\
                        <div class="ns5"> \
                            Đang gọi tới Tổng đài A\
                        </div>\
                    </section>';
    insertPopupCrm(content, ['.js-reject-call', '.js-receive-call'], '.notify-customer');
    $('.js-reject-call').click(function(){
        /*  Xử lý từ chối cuộc gọi */       
    });
    $('.js-receive-call').click(function(){
        /*  Xử lý nhận cuộc gọi */
    });
}
function insertPopupCrm(content, listButtonClose, objClose, checkType, sTitle){
    if (typeof(checkType) == 'undefined') {
        checkType = false;
    };
    if (typeof sTitle =='undefined') {
        sTitle = 'Chức năng';
    }
    if (empty(content)) {
        var sPopupClass = trim(objClose, '.');
        var sPopupClass = trim(sPopupClass, '#');
        content = '<div class="container pad20 '+sPopupClass+'"><div class="content-box mgbt20 panel-shadow"><div class="box-title">'+sTitle+'</div><div class="box-inner">Đang tải dữ liệu...</div></div></div>';
    }
    var objContentWait = $('.panel-fixed > *');
    objContentWait.addClass('panel-hidden-wait');
    $('.panel-fixed').append(content);
    $('.panel-fixed').fadeIn();

    $.each(listButtonClose, function(index, objValue) {
        $(objValue).click(function(){
            if (checkType == true) {
                $('.panel-fixed').fadeOut(function(){
                    $('.panel-fixed .panel-hidden-wait').removeClass('panel-hidden-wait');
                });
            }else{
                $('.panel-fixed .panel-hidden-wait').removeClass('panel-hidden-wait');
            }
            $(objClose).fadeOut(function(){
                $(objClose).remove();
            });
        });
    });
}
function addCustomerSupport(){
    $('.js-add-customer-support').unbind('click').click(function(){
        var content = '';
        content += '<section class="add-customer-popup panel-crm js-customer-popup">\
                    <div class="panel-title">Thêm khách hàng hỗ trợ</div>\
                    <div class="panel-content">\
                        <div class="acr1">Tên khách hàng</div>\
                        <input type="text" class="acr2" placeholder="Nhập tên khách hàng">\
                        <div class="acr1">Nhóm khách hàng</div>\
                        <select name="" id="" class="acr2">\
                            <option value="">Chọn nhóm khách hàng</option>\
                            <option value="">Chọn nhóm khách hàng</option>\
                            <option value="">Chọn nhóm khách hàng</option>\
                            <option value="">Chọn nhóm khách hàng</option>\
                            <option value="">Chọn nhóm khách hàng</option>\
                        </select>\
                        <div class="acr1">Thông tin</div>\
                        <input type="text" class="acr2" placeholder="Điền thông tin">\
                        <div class="acr3 row40">\
                            <div class="button-blue js-submit-add-user" >Thêm</div>\
                            <div class="button-default js-cancel-add-user">Hủy</div>\
                        </div>\
                    </div>\
                </section>';
        insertPopupCrm(content, ['.js-submit-add-user', '.js-cancel-add-user'], '.js-customer-popup');
    });
}
function addRelationship(){
    $('.js-add-rel').unbind('click').click(function(){
        var content = '';
        content =   '<section class="add-customer-popup panel-crm js-rel-popup">\
                        <div class="panel-title">Thêm mối quan hệ</div>\
                        <div class="panel-content">\
                            <div class="acr1">Tên khách háng</div>\
                            <input type="text" class="acr2" placeholder="Nhập tên khách hàng">\
                            <div class="acr1">Mối quan hệ</div>\
                            <select name="" id="" class="acr2">\
                                <option value="">Chọn mối quan hệ</option>\
                                <option value="">Chọn mối quan hệ</option>\
                                <option value="">Chọn mối quan hệ</option>\
                                <option value="">Chọn mối quan hệ</option>\
                                <option value="">Chọn mối quan hệ</option>\
                            </select>\
                            <div class="acr3 row40">\
                                <div class="button-blue js-submit-add-rel">Thêm</div>\
                                <div class="button-default js-cancel-add-rel">Hủy</div>\
                            </div>\
                        </div>\
                     </section>';
        insertPopupCrm(content, ['.js-submit-add-rel', '.js-cancel-add-rel'], '.js-rel-popup');
    });
}
function initAddPost(){
    if ($('.addpost-wrap').length <= 0) {
        return false;
    }
    naviAddPost();
}
function naviAddPost(){
    var mainPanel = $('#main-panel');
    var heightMainPanel = -20;
    $('.js-navto-post').each(function(){
        heightMainPanel += $(this).height() + 20;
    });
    $('.js-fixpanel-addpost').css({
        'transform': 'translate(0, ' + mainPanel[0]['scrollTop']+ 'px)',
        '-webkit-transform': 'translate(0, ' + mainPanel[0]['scrollTop']+ 'px)',
        '-moz-transform': 'translate(0, ' + mainPanel[0]['scrollTop']+ 'px)'
    });
    mainPanel.scroll(function(){ 
        if(mainPanel[0]['scrollTop'] >= heightMainPanel - mainPanel.height()){
            return;
        }
    
        var idNavedPost;
        $('.js-navto-post').each(function(){
            if(mainPanel[0]['scrollTop'] -  $(this)[0]['offsetTop'] <= 20){
                idNavedPost = $(this).attr('data-id');
                $('.js-nav-post.atv').removeClass('atv');
                $('.js-nav-post[data-id="'+idNavedPost+'"]').addClass('atv');
            }
        });
    
        $('.js-fixpanel-addpost').css({
            'transform': 'translate(0, ' + mainPanel[0]['scrollTop']+ 'px)',
            '-webkit-transform': 'translate(0, ' + mainPanel[0]['scrollTop']+ 'px)',
            '-moz-transform': 'translate(0, ' + mainPanel[0]['scrollTop']+ 'px)'
        });
    });
    $('.js-nav-post').unbind('click').click(function(){
        heightMainPanel = -20;
        $('.js-navto-post').each(function(){
            heightMainPanel += $(this).height() + 20;
        });
        if (mainPanel.hasClass('lockNav')) {
            return;
        };
        mainPanel.addClass('lockNav');
        var id = $(this).attr('data-id');
        $('.item-navi-post.atv').removeClass('atv');
        $(this).parents('.item-navi-post').addClass('atv');
        mainPanel.animate({
            scrollTop : $('.js-navto-post[data-id="'+id+'"]')[0]['offsetTop'] - 19
        }, 1000, function(){
            mainPanel.removeClass('lockNav');
        });
    })
}

function loadImageExist()
{
    if (typeof(aImageList) != 'object' || $.isEmptyObject(aImageList)) {
        return false;
    }
    //load image
    var count = 0;
    for(i in aImageList['image_path']) {
        var obj = $('#js-img-linked');
        var n = obj.attr('data-max') * 1 + 1;
        obj.attr('data-max', n);
        var content = '<div class="col4 item-img-upload mgbt20" data-id="'+n+'">\
                            <div class="combo-upload">\
                            <div class="img-upload">\
                                <img src="'+ aImageList['image_path'][i] +'" alt="" id="img-upload-'+n+'_img" />\
                                <div class="control-img">\
                                    <span class="fa fa-pencil js-edit-item-upload"></span>\
                                    <span class="fa fa-trash js-del-item-upload"></span>\
                                </div>\
                            </div>\
                            <input type="hidden" name=val[image_link][image_path][] class="js-image-path" value="'+ aImageList['image_path'][i] +'">\
                            <input type="text" name=val[image_link][detail_path][] class="link-upload" value="'+ aImageList['detail_path'][i] +'" placeholder="Nhập Liên kết">\
                            <input type="text" name=val[image_link][caption][] class="link-upload" value="'+ aImageList['caption'][i] +'" placeholder="Nhập mô tả">\
                        </div>\
                    </div>';
        var listImgUpload = $('.js-list-img-upload');
        newdiv = document.createElement( "div" ),
        existingdiv = document.getElementById( "js-img-linked" );
        var addElement = $(content);
        listImgUpload.append(addElement, [newdiv,existingdiv]);
        initEditImgUpload();
    }
}

function initEditImgUpload(){
    $('.js-edit-item-upload').unbind('click').click(function(){
        var obj = $(this);
        var id = obj.parents('.item-img-upload').attr('data-id');
        upHinhMoRong({id: 1, obj: 'img-upload-'+id, width: 0, height: 0});
    });
    $('.js-del-item-upload').unbind('click').click(function(){
        var obj  = $(this).parents('.item-img-upload');
        obj.fadeOut(function(){
            obj.remove();
        });
    });
}
function upHinhMoRong(arr) {
    if(arr == undefined) arr = {};
    if(arr.obj == undefined) arr.obj = '';
    if(arr.type == undefined) arr.type = '1';
    if(arr.width == undefined) arr.width = 0;
    else arr.width *= 1;
    if(arr.height == undefined) arr.height = 0;
    else arr.height *= 1;
    //var imgpath = 'http://www.img.' + global['domain'];
    var imgpath = 'http://img.' + global['domain'] + ':8080';
    function receiveMessage(e) {
        if (e.origin !== imgpath) return;
        window.removeEventListener("message", receiveMessage, false);
        settings = JSON.parse(e.data);
        if($('#' + settings['id']).hasClass('duong_dan_hinh_mo_rong') || settings['id'].indexOf('group_image_extend') > -1){
            /* Trường hợp hình mở rộng
                Gộp chung cho cả trường hợp sửa và thêm mới*/
            cap_nhat_duong_dan_hinh_mo_rong(arr, settings);
        }else{
            /* Trường hợp hình đại diện*/
            var array_data = settings['value'].split(',');
            $('#' + settings['id']).val(array_data[0]);
            $('#' + settings['id']).trigger(settings['trigger']);
            $('#' + settings['id'] + '_img').attr('src',array_data[0]);
        }
        
        $('#div_slide_group_image_extend_' + arr['id_nhom']).sortable('refresh');;
        /*$('.hinh_dai_dien_bai_viet').attr('src',settings['value']);*/
        $.modal.close();
        $.fancybox.close();
    }
    window.addEventListener('message', receiveMessage);
    moPopup(imgpath+'/dialog.php?type=' + arr.type +'+&field_id=' + arr.obj +'&height=' + arr.height + '&width=' + arr.width + '&sid=' + session_id,
        function(){
            if(arr.obj.indexOf('image_path') == -1)
            {
                $('.duong_dan_hinh_mo_rong').change(function(e) {
                    chinh_sua['txt_extend']=true;
                });
            }
        },
        {width: '860px', height:'600px'}
    );
}

/*  Nhóm trang Marketing: trang tạo */
function initPageMarketing(){
    if($('.page-marketing-add').length <= 0){
        return;
    }

    /*  Suggest sản phẩm */
    $('.js-input-suggest').unbind('keydown').keydown(function(e){
        var inputSuggest    = $(this);
        var oListSuggest    = inputSuggest.parent('.suggest-marketing').find('.list-suggest');
        var type            = inputSuggest.data('type');
        var val             = inputSuggest.val();
        oListSuggest.addClass('js-button-wait');
        oListSuggest.fadeIn();
        switch(e.keyCode){
            case 37:
            case 38:
            case 39:
            case 40:
                break;
            case 13:
                if(!val){
                    suggestMarketing({
                        'keyword'   : val,
                        'type'      : type, 
                        'objInput'  : inputSuggest,
                        'objList'   : oListSuggest,
                    });
                }
                break;
            default:
                if(!val){
                    clearTimeout(k_oTimer);
                    k_oTimer = setTimeout(function(){
                        suggestMarketing({
                            'keyword'   : val,
                            'type'      : type, 
                            'objInput'  : inputSuggest,
                            'objList'   : oListSuggest,
                        });
                    }, 500);
                }
                break;
        }
    });

    /*  Thêm hình thức áp dụng của sản phẩm *
    $('.js-add-apply-product').unbind('click').click(function(){
        /*  Content rỗng *
        var  content = '<div class="container js-popup-apply-product pad20"><div class="content-box panel-shadow"><div class="box-title">Form mẫu<span class="fa fa-close js-close-form right"></span></div><div class="box-inner"></div></div></div>';
        insertPopupCrm(content, ['.js-close-form'], '.js-popup-apply-product', true);
    });
    */

    /*  Thêm hình hình áp dụng của đơn hàng *
    $('.js-add-apply-order').unbind('click').click(function(){
        /*  Content mẫu *
        var content = '<div class="container page-marketing-apply page-marketing-apply-order"><div class="content-box panel-shadow"><div class="box-title title-blue">Áp dụng giá tiền</div><div class="box-inner"><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Giá trị</div><div class="row20"><div class="col4"><select name=""><option value="1">&lt;=</option><option value="2">&gt;=</option></select></div><div class="col8 padleft10"><input type="text"></div></div></div><div class="col6 padleft10"><div class="row20 sub-black-title">Giá trị</div><div class="row20"><div class="col4"><select name=""><option value="1">&lt;=</option><option value="2">&gt;=</option></select></div><div class="col8 padleft10"><input type="text"></div></div></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Tặng tiền</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Tặng tiền</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Giảm giá</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Phí vận chuyển</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row30"><div class="col6"></div><div class="col3 padright10"><div class="button-default js-cancel-apply-order">Hủy</div></div><div class="col3 padleft10"><div class="button-blue js-submit-apply-order">Áp dụng</div></div></div></div></div></div>';
        insertPopupCrm(content, ['.js-cancel-apply-order', '.js-submit-apply-order'], '.page-marketing-apply-order', true);
        $('.page-marketing-apply-order .js-submit-apply-order').click(function(){
            /*  Content mẫu *
            var contentItem = '<div class="item-apply-product padbot10 mgbt10"><div class="row30"><div class="col10"><div class="col4"><div class="sub-black-title row20">Giá trị:</div></div><div class="col8"><div class="sub-black-title row20">>= 1.000.000</div><div class="sub-black-title row20"><= 10.000.000</div></div></div><div class="col2"><span class="fa fa-close right icon-wh js-delete-apply-item"></span></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Tặng tiền</div></div><div class="col6"><input type="text"></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Giảm giá</div></div><div class="col6"><input type="text"></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Voucher</div></div><div class="col6"><input type="text"></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Phí vận chuyển</div></div><div class="col6"><input type="text"></div></div></div>';
            $('.list-apply-product[type="order"]').append(contentItem);
        });
    });
    */
    /*  Xóa hình thức áp dụng */
    $('.js-delete-apply-item').unbind('click').click(function(){
        var obj = $(this);
        var cf = confirm('Xác nhận xóa bỏ hình thức áp dụng?!');
        if (cf == true) {
            var itemApply = obj.parents('.item-apply-product');
            itemApply.fadeOut(function(){
                itemApply.remove();
            });
        };
    });
}

/*  Hàm chung trả vể kết quả suggest cho trang marketing */
function suggestMarketing(obj){
    var keyword     = obj['keyword'];
    var type        = obj['type'];
    var objList     = obj['objList'];
    var objInput    = obj['objInput'];

    var content = '';
    /*  Xử lý AJAX xong trả về content */
    for (var i = 0; i < 10; i++) {
        content += '<div class="item-suggest" data-id="'+i+'">Sản phẩm '+i+'</div>';
    };

    /*  Ráp code vào thì xóa đoạn set time out này đi */
    setTimeout(function(){
        objList.removeClass('js-button-wait');
        objList.find('.mCSB_container').html(content);

        objList.find('.item-suggest').unbind('click').click(function(){
            objInput.val($(this).html());
            objInput.attr('data-id', $(this).attr('data-id'));
            objList.fadeOut(function(){
                objList.addClass('none');
            })
        });
    }, 500);
}

/*  Nhóm hàm trang Payment */
function initPagePayment(){
    /*  Select custom dùng chung*/
    initSelect2();

    /*  Dùng riêng: Trang gói tài khoản */
    if($('.page-payment-list-account').length > 0){
        /*  Đổi trạng thái */
        $('.js-change-state').click(function(){
            effectChangeState({
                'obj': $(this)
            }); 
        });

        /* Chỉnh sửa */
        $('.js-edit').click(function(){
            /*  Contet láy từ ajax */
            var content = '<div class="container page-payment page-payment-pack-add pad20"><div class="content-box mgbt20 panel-shadow"><div class="box-title">Tạo gói thanh toán</div><div class="box-inner"><div class="row20 mgbt20"><div class="row20 sub-blue-title">Tên gói</div><input placeholder="Điền thông tin..." type="text"></div><div class="row20 mgbt20"><div class="row20 sub-blue-title">Tên gói</div><input placeholder="Điền thông tin..." type="text"></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-blue-title">Số tiền tối thiếu</div><input placeholder="Điền thông tin..." type="text"></div><div class="col6 padleft10"><div class="row20 sub-blue-title">Số tiền tối đa</div><input placeholder="Điền thông tin..." type="text"></div></div><div class="row30"><div class="col6"></div><div class="col3 padleft10"><div class="button-trans js-cancel">Hủy</div></div><div class="col3 padleft10"><div class="button-blue js-submit">Thêm</div></div></div></div></div></div>';
            insertPopupCrm(content, ['.js-cancel', '.js-submit'], '.page-payment-pack-add', true);
        });

        /*  Xóa */
        $('.js-delete').click(function(){
            var obj = $(this);
            var cf = confirm('Xác nhận xóa gói tài khoản?!');
            if (cf == true) {
                var row = obj.parents('.row-mk');
                row.addClass('before-delete');
                row.animate({
                    opacity: 0,
                    'height': 0,
                    'padding-top': 0,
                    'padding-bottom': 0
                }, 500, function(){
                    row.remove();
                });
            };
        });

        /*  Thêm mới */
        $('.js-pack-add').click(function(){
            /*  Contet láy từ ajax */
            var content = '<div class="container page-payment page-payment-pack-add pad20"><div class="content-box mgbt20 panel-shadow"><div class="box-title">Tạo gói thanh toán</div><div class="box-inner"><div class="row20 mgbt20"><div class="row20 sub-blue-title">Tên gói</div><input placeholder="Điền thông tin..." type="text"></div><div class="row20 mgbt20"><div class="row20 sub-blue-title">Tên gói</div><input placeholder="Điền thông tin..." type="text"></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-blue-title">Số tiền tối thiếu</div><input placeholder="Điền thông tin..." type="text"></div><div class="col6 padleft10"><div class="row20 sub-blue-title">Số tiền tối đa</div><input placeholder="Điền thông tin..." type="text"></div></div><div class="row30"><div class="col6"></div><div class="col3 padleft10"><div class="button-trans js-cancel">Hủy</div></div><div class="col3 padleft10"><div class="button-blue js-submit">Thêm</div></div></div></div></div></div>';
            insertPopupCrm(content, ['.js-cancel', '.js-submit'], '.page-payment-pack-add', true);
        });
    }

    /*  Dùng riêng: Trang giá trị loại tiền */
    if($('.page-payment-type-money').length > 0){
        /* Đổi trạng thái */
        $('.js-change-state').click(function(){
            effectChangeState({
                'obj': $(this)
            });
        });

        /*  Xóa */
        $('.js-delete').click(function(){
            var obj = $(this);
            var cf = confirm('Xác nhận xóa giá trị loại tiền?!');
            if (cf == true) {
                var contentBox = obj.parents('.content-box');
                contentBox.animate({
                    opacity: 0,
                    'height': 0
                }, 1000, function(){
                    contentBox.remove();
                });
            };
        });
    }
}
function effectChangeState(object){
    var obj             = object['obj'];
    var callBackActive  = object['callBackActive'];
    var callBackHidden  = object['callBackHidden'];

    if(obj.hasClass('fa-check')){
        var cf = confirm('Xác nhận thiết lập trạng thái ẩn?!');
        if (cf == true) {
            obj.removeClass('fa-check');
            obj.addClass('fa-close');
            if(typeof callBack != 'undefined') {
                window[callBackHidden](idTab);
            };
        };
    }else{
        var cf = confirm('Xác nhận thiết lập trạng thái kích hoạt?!');
        if (cf == true) {
            obj.removeClass('fa-close');
            obj.addClass('fa-check');
            if(typeof callBack != 'undefined') {
                window[callBackActive](idTab);
            };
        };
    }
    
}
function initTypeNumber()
{
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57);
    $('.js-input-number').keydown(function(e){
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
    
    var numberKey2 = new Array(8,46,48,49,50,51,52,53,54,55,56,57,190);
    $('.js-input-float').keydown(function(e){
        var val = $(this).val();
        if (val.indexOf('.') != -1 && e.keyCode == 190) {
            return false;
        }
        if(numberKey2.indexOf(e.keyCode) == -1){
            return false;
        }
    });
}
