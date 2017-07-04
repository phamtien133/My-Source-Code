var c_status_obj = null;
var c_ustatus_obj = null;
var b_allow_change = 0;
var a_svndorid = [];
var a_svndorname = [];
var allvendor = {};
var allcategory = {};
var valChange = {
        'product': [],
        'obj' : []
    };
var list_product = '';
var convert = 0;

//dành cho map location
var infowindow = null;
var marker = null;
var geocoder =null;

$(function(){
    initListOrder();
})
function initOrderDetail(){
    autoSize(); 
    expandGroup();
    scrollMenu();
    if (b_allow_change) {
        initQuantilyGroup();
        addProductOrder();
        removeProductOrder();
        saveInfo();
        initSetLocationDelivery();
        initPaymentOrder();
    }
      $('#cls-order-dt').unbind('click').click(function(){
        window.location.reload(); 
    });
    // cập nhật trạng thái sản phẩm
    /* tạm thời bỏ chức năng này
    $('.js-set-status-product').each(function(){
        $(this).change(function(){
            var id = $(this).data('id');
            var val = $(this).val();
            if (typeof(id) != 'undefined' && id > 0 && val > 0) {
                sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.updateStatusProduct';
                sParams += '&val[id]='+ id + '&val[status]=' + val;
                $.ajax({
                    crossDomain:true,
                    xhrFields: {
                        withCredentials: true
                    },
                    url: getParam('sJsAjax'),
                    type: "POST",
                    data: sParams,
                    timeout: 15000,
                    cache:false,
                    dataType: 'json',
                    error: function(jqXHR, status, errorThrown){
                    },
                    success: function (result) {
                        if(isset(result.status) && result.status == 'success') {
                            var status = result.data.status;
                            if (typeof(status) != 'undefined') {
                                
                            }
                        }
                        else {
                            if (typeof(result.message) != 'undefined') {
                                alert(result.message);
                            }
                            else {
                                alert('Đã có lỗi xảy ra.');
                            }
                        }
                    }
                });
            }
        });
    });
    */
    // cập nhật trạng thái khách hàng
    $('#js-verify-user').unbind('change').change(function(){
        status = $(this).val();
        // call ajax to update user status.
        uid = $('#js-user-id').val();
        c_ustatus_obj = $('.js-status-state-customer');
        updateUserStatus(uid, status);
    });
    // cập nhật trạng thái đơn hàng
    $('#js-order-status').unbind('change').change(function(){
        status = $(this).val();
        orderid = $('#js-order-id').val();
        current = $('#js-curernt-status').val();
        c_status_obj = $('.js-status-order-state');
        updateOrderStatus(orderid, current, status);
        return false;
    });
    $('#js-print-order').unbind('click').click(function(){
        orderid = $('#block-detail-order').attr('data-id');
        url = oParams['sJsMain'] + 'print/order/'+orderid;
        obj = 'popup_' + Math.floor((Math.random()*100000)+1);
        var left   = (screen.width  - 850)/2;
        var top    = (screen.height - 500)/2;
        var params = 'width='+850+', height='+500;
        params += ', top='+top+', left='+left;
        params += ', directories=no';
        params += ', location=no';
        params += ', menubar=no';
        params += ', resizable=yes';
        params += ', scrollbars=yes';
        params += ', status=no';
        params += ', toolbar=no';
        var newwin = window.open(url, obj, params);
        if(window.focus) {newwin.focus()}
        newwin.document.title = 'In đơn hàng';
    });
    $('#js-view-bill').unbind('click').click(function(){
        orderid = $('#block-detail-order').attr('data-id');
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.showBillInfo';
        sParams += '&order=' + orderid;
        $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        url: getParam('sJsAjax'),
        type: "POST",
        data: sParams,
        timeout: 15000,
        cache:false,
        dataType: 'text',
        error: function(jqXHR, status, errorThrown){
            alert('Error');
        },
        success: function (data) {
            content = data;
            insertPopupCrm(content, ['.js-cancel-popup'], '.order-bill', true);
        }
        });
    });
    $('#address-city').change(function(){
        // load thông tin district.
        id = $(this).val();
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=core.loadDistrict';
        sParams += '&city='+id;
        $.ajax({
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
            },
            success: function (result) {
                showDistrict(result);
            }
        });
    });
    $('#address-district').change(function(){
        // load thông tin district.
        id = $(this).val();
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=core.loadWard';
        sParams += '&district='+id;
        $.ajax({
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
            },
            success: function (result) {
                showWard(result);
            }
        });
    });
    changeEditObj();
    initUpdatePrice();
    
    // tính lại tiền cho tất cả các dòng.
    $('.is-row-product').each(function(){
        calcPriceToDisplay($(this));
    });
    reSumMoney();
}
function initUpdatePrice()
{
    /* edit price */
    $('.is-update-price').click(function(){
        content = '';
        var key = $(this).data('key');
        if (typeof(key) == 'undefined' || key == ''){
            return false;
        }
        // goi ajax
        //sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.showUpdatePrice' + '&val[key]=' + key;

        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.showUpdatePrice' + '&val[key]=' + key;
        sParams += '&val[vendor]=' + $(this).data('vendor');
        sParams += '&val[order]=' + $('#block-detail-order').data('id');
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'text',
            error: function(jqXHR, status, errorThrown){
                alert('Error');
            },
            success: function (data) {
                content = data;
                insertPopupCrm(content, ['.js-cancel-popup'], '.order-update-price', true);
                initSbmUpdatePrice();
            }
        });
    });
}
function initSbmUpdatePrice()
{
    $('#js-submit-update-price').click(function(){
        $(this).addClass('js-button-wait');
        var action = "$(this).ajaxSiteCall('shop.updatePrice', 'afterUpdatePrice(data)'); return false;";
        $('#js-frm-update-price').attr('onsubmit', action);
        $('#js-frm-update-price').submit();
        $('#js-frm-update-price').removeAttr('onsubmit');
    });
}
function afterUpdatePrice(data)
{
    $('#js-submit-update-price').removeClass('js-button-wait');
    if (data.status == 'error') {
        alert(data.message);
        return false;
    }
    else if (data.status == 'success') {
        // cập nhật lại giá sản phẩm.
        product = data.data.product;
        order = data.data.order;
        $('#js-unit-price-'+product.id +' span').html(currencyFormat(product.unit_price - product.price_discount));
        $('#js-unit-price-'+product.id).attr('data-coin', product.coin);
        $('#shop-'+product.id).attr('data-coin', product.coin);
        $('#shop-'+product.id).html(currencyFormat(product.total_price));
        surchage = $('#surcharge_money').data('money');
        $('#total_amount').html(currencyFormat(order.total_amount));
        $('#amount_with_coin').html(currencyFormat(order.total_coin) + ' Xu + '+ currencyFormat(order.total_amount - order.total_coin));
        var amount = order.total_amount - surchage;
        $('#amount').html(currencyFormat(amount));
        $('.js-cancel-popup').click();
        //$('.order-update-price').remove();
    }
}

function printOrder()
{
    //alert(123132);
    return false;
}
function changenetWeightOrder(data){
    var obj = data['obj'].parents('.is-row-product');
    var id  = obj.attr('data-source');
    calcPriceToDisplay(obj);
    
    if (valChange['product'].indexOf(id) < 0) {
        valChange['product'].push(id);
    };
    reSumMoney();
}
function changeQuantityOrder(data){
    var obj = data['obj'].parents('.is-row-product');
    var id  = obj.attr('data-source');
    
    calcPriceToDisplay(obj);
    
    if (valChange['product'].indexOf(id) < 0) {
        valChange['product'].push(id);
    };

    reSumMoney();
}
function reSumMoney(){
    var sumMoney = 0;
    var sumCoin = 0;
    $('.is-row-product').each(function(){
        sumMoney += $(this).find('.is-sum-money').attr('data-money') * 1;
        sumCoin += $(this).find('.is-sum-money').attr('data-coin') * 1;
    });
    
    $('#amount').attr('data-money', sumMoney);
    $('#amount').html(currencyFormat(sumMoney));

    var surchargeMoney  = $('#surcharge_money').attr('data-money') * 1;
    var surchargeCoin  = $('#surcharge_money').attr('data-coin') * 1;
    var reduceMoney     = $('#reduce_money').attr('data-money') * 1;
    
    var marketingMoney = 0;
    var marketingCoin = 0;
    if ($('#marketing_money').length > 0) {
        marketingMoney = $('#marketing_money').attr('data-money') * 1;
        marketingCoin = $('#marketing_money').attr('data-coin') * 1;
    }
    
    var totalAmount     = sumMoney + surchargeMoney - reduceMoney - sumCoin - marketingMoney + marketingCoin;
    sumCoin = sumCoin + surchargeCoin - marketingCoin;
    $('#amount_with_coin').html(sumCoin +' Xu + ' + totalAmount + 'đ' );
    var totalAmount     = sumMoney + surchargeMoney + surchargeCoin - reduceMoney - marketingMoney;
    $('#total_amount').attr('data-money', totalAmount);
    $('#total_amount').html(currencyFormat(totalAmount));
}
function changeEditObj(){
    $('.js-change-obj').change(function(){
        var id = $(this).attr('id');

        if (valChange['obj'].indexOf(id) < 0) {
            valChange['obj'].push(id);
        };
    });
}
function calcPriceToDisplay(rowProduct){
    var umoney       = rowProduct.find('.is-money.ja012').attr('data-money');
    var ucoin       = rowProduct.find('.is-money.ja012').attr('data-coin');
    var netWeight   = rowProduct.find('.js-product-net-weight').val();
    var quantity    = rowProduct.find('.js-product-quantity').val();
    var uvalue = rowProduct.find('.js-product-net-weight').attr('data-value');
    if (uvalue == null || uvalue == 0) {
        uvalue = 1;
    }
    amount = umoney * netWeight * quantity / uvalue;
    coin = ucoin * netWeight * quantity / uvalue;
    
    rowProduct.find('.is-sum-money').attr('data-money', amount);
    rowProduct.find('.is-sum-money').attr('data-coin', coin);
    rowProduct.find('.is-sum-money').html(currencyFormat(amount));
    // tính tổng giá
    calcOrderPrice();
}
function calcOrderPrice()
{
    
}
function updateUserStatus(uid, status, id)
{
    if (typeof(id) == 'undefined') {
        var id = $('#block-detail-order').attr('data-id');
    }
    if (empty(uid)) {
        // báo lỗi và chuyển lại trạng thái khi chưa thay đổi.
        if(status == 1) {
            $('#js-verify-user').val(0);
        }
        else {
            $('#js-verify-user').val(1);
        }
        return false;
    }
    
    addLoadingState(c_ustatus_obj, 'wait');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.updateVerifyStatus';
    sParams += '&uid='+ uid + '&status=' + status;
    sParams += '&oid='+ id;
    $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        url: getParam('sJsAjax'),
        type: "POST",
        data: sParams,
        timeout: 15000,
        cache:false,
        dataType: 'json',
        error: function(jqXHR, status, errorThrown){
        },
        success: function (result) {
            if (result.status == 'error') {
                // thông báo lỗi và chuyển lại trạng thái ban đầu
                alert(result.message);
                if(status == 1) {
                    $('#js-verify-user').val(0);
                }
                else {
                    $('#js-verify-user').val(1);
                }
                addLoadingState(c_ustatus_obj, 'fail');
            }
            else {
                // thành công, không thực hiện thao tác khác.
                removeLoadingState(c_ustatus_obj);
                return true;
            }
        }
    });
}
function updateOrderStatus(orderid, oldid, status)
{
    if (empty(orderid) || empty(status)) {
        // báo lỗi và chuyển lại trạng thái khi chưa thay đổi.
        alert('Có lỗi xảy ra');
        $('#js-order-status').val(oldid);
        return false;
    }
    if (confirm('Sau khi thay đổi, trạng thái đơn hàng sẽ không thể điều chỉnh về các mức thấp hơn. Tiếp tục thao tác? ')) {
        if (status == 'hoan-tra') {
            //Hiển thị popup cho phép chọn sp hoàn trả
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.callPopupReturnProduct' + '&oid=' + orderid;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'text',
                error: function(jqXHR, status, errorThrown){
                    alert('Error');
                },
                success: function (data) {
                    c_status_obj.removeClass('fa-spinner');
                    content = data;
                    insertPopupCrm(content, ['.js-cancel-product-return', '.js-submit-product-return'], '.js-select-product-return', true);
                    initReturnProduct();
                }
            });
            
            return false;
        }
        
        
        addLoadingState(c_status_obj, 'wait');
        // gọi ajax để cập nhật trạng thái đơn hàng.
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.updateOrderStatus';
        sParams += '&val[oid]='+ orderid + '&val[status]=' + status;
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
            },
            success: function (result) {
                if (result.status == 'error') {
                    // thông báo lỗi và chuyển lại trạng thái ban đầu
                    alert(result.message);
                    if(status == 1) {
                        $('#js-verify-user').val(0);
                    }
                    else {
                        $('#js-verify-user').val(1);
                    }
                    addLoadingState(c_status_obj, 'fail');
                }
                else {
                    if (result.status == 'popup') {
                        //call ajax
                        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.callPopupPurchase' + '&oid=' + result.data.id;
                        sParams += '&status=' + result.data.status;
                        $.ajax({
                            crossDomain:true,
                            xhrFields: {
                                withCredentials: true
                            },
                            url: getParam('sJsAjax'),
                            type: "POST",
                            data: sParams,
                            timeout: 15000,
                            cache:false,
                            dataType: 'text',
                            error: function(jqXHR, status, errorThrown){
                                alert('Error');
                            },
                            success: function (data) {
                                c_status_obj.removeClass('fa-spinner');
                                content = data;
                                insertPopupCrm(content, ['.js-cancel-purchase'], '.order-purchase', true);
                                purchase();
                            }
                        });
                    }
                    else {
                        // thành công, không thực hiện thao tác khác.
                        removeLoadingState(c_status_obj);
                        return true;
                    }
                }
            }
        });
    }
    else {
        // chuyển về trạng thái trước đó.
        $('#js-order-status').val(oldid);
        return false;
    }
}
function purchase()
{
    $('#js-submit-purchase').click(function(){
        $('.message').removeClass('dialog-err');
        $('.message').removeClass('dialog-success');
        if ($('#js-select-gateway').val() == '') {
            $('.message').addClass('dialog-err').html('Vui lòng chọn hình thức thanh toán.');
            return false;
        }
        orderid = $('#js-order-id').val();
        if (empty(orderid)) {
            $('.message').addClass('dialog-err').html('Có lỗi xảy ra.');
            return false;
        }
        $(this).addClass('js-button-wait');
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.purchase' + '&val[oid]=' + orderid;
        sParams += '&val[gateway]=' + $('#js-select-gateway').val();
        sParams += '&val[status]=' + $('#js-purchase-status').val();
        if (convert) {
            sParams += '&val[convert]=1';
        }
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
                alert('Error');
            },
            success: function (data) {
                if (data.status == 'error') {
                    $('.message').addClass('dialog-err').html(data.message);
                    $('#js-submit-purchase').removeClass('js-button-wait');
                    if (isset(data.convert) && data.convert == 1) {
                        convert = 1;
                    }
                    else {
                        convert = 0;
                    }
                    return false;
                }
                else {
                    $('.message').addClass('dialog-success').html('Thanh toán thành công.');
                    setTimeout(function(){
                        $('.js-cancel-purchase').click();
                        window.location.reload(false);
                    }, 1000);
                }
            }
        });
    });
}

function initReturnProduct()
{
    document.getElementById("js-return-method-1").checked = true;
    
    $('.js-return-method').change(function(){
        var type = $(this).attr('data-type')*1;
        if (this.checked) {
            if (type == 1) {
                document.getElementById("js-return-method-0").checked = false;
            }
            else {
                document.getElementById("js-return-method-1").checked = false;
            }
        }
        else {
            console.log(2);
            if (type == 1) {
                console.log(3);
                document.getElementById("js-return-method-0").checked = true;
            }
            else {
                console.log(4);
                document.getElementById("js-return-method-1").checked = true;
            }
        }
    });
    
    $('#js-submit-return-product').unbind('click').click(function(){
        var step = $(this).attr('data-step')*1;
        if (step == 2) {
            //Lấy các thông tin xác nhận
            var refund_money = $('#js-refund-money').attr('data-value')*1;
            var refund_coin = $('#js-refund-coin').attr('data-value')*1;
            var refund_order = $('#js-return-order-id').val();
            var order_id = $('#js-select-order-id').val();
            var gateway = $('#js-return-method-pay').val();
            
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.confirmReturnProduct';
            sParams += '&val[roid]=' + refund_order;
            sParams += '&val[oid]=' + order_id;
            sParams += '&val[money]=' + refund_money;
            sParams += '&val[coin]=' + refund_coin;
            sParams += '&val[gateway]=' + gateway;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'json',
                error: function(jqXHR, status, errorThrown){
                    alert('Lỗi hệ thống');
                    $('#js-submit-return-product').removeClass('js-button-wait');
                },
                success: function (result) {
                    $('#js-submit-return-product').removeClass('js-button-wait');
                    if(isset(result.status) && result.status == 'success') {
                        alert('Thao tác thành công');
                        window.location.reload(false);
                        return;
                        //refesh page.
                    }
                    else {
                        var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(messg);
                    }
                }
            });
            return;
        }
        
        var objSubmit = $(this);
        objSubmit.addClass('js-button-wait');
        var action = "$(this).ajaxSiteCall('shop.returnProduct', 'resultReturn(data)'); return false;";
        $('#js-frm-return').attr('onsubmit', action);
        $('#js-frm-return').submit();
        $('#js-frm-return').removeAttr('onsubmit');
        return false;
        
        var return_all = 0;
        $('.js-return-method').each(function(){
            if ($this.checked) {
                var type_tmp = $(this).attr('data-type')*1;
                if (type_tmp == 0) {
                    return_all = 1;
                }
                else {
                    return_all = 0;
                }
                return false;
            }
        });
        
        if (!return_all) {
            //Lấy danh sách tất cả các sản phẩm được check
            
        }
    });
}

function resultReturn(data)
{
    $('#js-submit-return-product').removeClass('js-button-wait');
    if (isset(data.status) ){
        if (data.status == 'success') {
            alert('Thao tác thành công');
            window.location.reload(false);
            return;
            //refresh page
            
        }
        else if (data.status == 'popup') {
            //show
            var html = '<div class="row30 sub-blue-title padlr20" style="border-bottom: 1px solid #eee;">' +
                    '<label>Hoàn tiền cho đơn hàng</label>' +
                '</div>' +
                '<div class="row30 padlr20">' +
                    '<div class="col3">Tổng tiền hoàn trả:</div>' +
                    '<div class="col9 red-text" id="js-refund-money" data-value='+ data.data.total_money +'>'+ currencyFormat(data.data.total_money) +'</div>' +
                '</div>' +
                '<div class="row30 padlr20">' +
                    '<div class="col3">Tổng xu hoàn trả:</div>' +
                    '<div class="col9 red-text" id="js-refund-coin" data-value='+ currencyFormat(data.data.total_coin) +'>'+ data.data.total_coin +'</div>' +
                '</div>' +
                '<input type="hidden" value="'+ data.data.return_id +'" id="js-return-order-id">' +
                '<div class="row30 padlr20">' +
                    '<div class="col3">Hình thức thanh toán:</div>' +
                    '<div class="col9">' +
                        '<select id="js-return-method-pay">' +
                            '<option value="noi-nhan">COD</option>' +
                            '<option value="diem">Tài khoản ĐST</option>' +
                        '</select>' +
                    '</div>' +
                '</div>';
            $('#js-show-next-step').html(html);
            $('#js-show-next-step').removeClass('none');
            $('#js-submit-return-product').attr('data-step', 2);
            $('#js-submit-return-product').html('Xác nhận');
            return;
        }
    }
    var msg = isset(data.message) ? data.message : 'Lỗi hệ thống';
    alert(msg);
}

function addLoadingState(obj, state){
    if (state == 'wait') {
        obj.addClass('fa-spinner').addClass('fa-pulse');
    };
    if (state == 'fail') {
        obj.removeClass('fa-spinner').removeClass('fa-pulse');
        obj.addClass('fa-warning');
    }
}
function removeLoadingState(obj){
    obj.removeClass('fa-spinner').removeClass('fa-pulse');
    obj.addClass('fa-check');
    setTimeout(function(){
        obj.removeClass('fa-check');
    }, 2000);
}
var currenthandle = null;
function initListOrder(){
    if($('.page-list-order').length <= 0){
        return;
    }
    $('.js-handle-order').unbind('click').click(function(){
        $(this).addClass('js-button-wait');
        currenthandle = $(this);
        orderid = $(this).attr('data-id');
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.handle' + '&oid=' + orderid;
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
                currenthandle.removeClass('js-button-wait');
                alert('Error');
            },
            success: function (data) {
                currenthandle.removeClass('js-button-wait');
                if (data.status == 'error') {
                    if (isset(data.display)) {
                        currenthandle.removeClass('button-blue');
                        currenthandle.html(data.display.user_handle_name);
                    }
                    alert(data.message);
                    return false;
                }
                else {
                    alert('Thao tác thành công.');
                    currenthandle.removeClass('button-blue');
                    currenthandle.removeClass('js-handle-order');
                    currenthandle.html(data.data.user_handle_name);
                    return false;
                }
            }
        });
        return false;
    });
    changeStateOrder();
    changeStateCustomer();
}
/* Thay đổi trạng thái đơn hàng */
function changeStateOrder(){
    $('.js-order-state').change(function(){
        var orderid = $(this).attr('data-id');
        current = $(this).attr('data-old');
        status = $(this).val();
        c_status_obj = $('.js-status-list-order-state[data-id="'+orderid+'"]');
        updateOrderStatus(orderid, current, status);
        return false; 
    })
}
/* Thay đổi trạng thái khách hàng */
function changeStateCustomer(){
    $('.js-list-order-customer').change(function(){
        var id = $(this).attr('data-id');
        var uid = $(this).attr('data-user');
        c_ustatus_obj = $('.js-status-list-order-customer[data-id="'+id+'"]');
        var status = $(this).val();
        updateUserStatus(uid, status, id);
        return false;
    })
}
function disableEditOrder(){
    
}
function addProductOrder(){
    $('.js-add-product-order').unbind('click').click(function(){
        var content = '';
        var sub = typeof(subdomain) != 'undefined' ? subdomain : '';
        content += '<section class="search-product-full-popup panel-crm js-add-pr content-box">\
                    <div class="panel-title">Thêm sản phẩm</div>\
                    <div class="panel-content">\
                        <div class="acr1">Chọn siêu thị</div>\
                        <div class="col7">\
                            <select name="" id="js-select-market" class="acr2">';
                            if (sub == 'cms.') {
                                content += '<option value="">Chọn siêu thị...</option>';
                            }
                            $('.js-data-mart').each(function(){
                                a_svndorid.push($(this).attr('data-id'));
                                a_svndorname.push($(this).attr('data-name'));
                                content += '<option value="' + $(this).attr('data-id')+ '">' + $(this).attr('data-name')+ '</option>';
                            });
                content +='</select>\
                        </div>\
                        <div class="col5 padleft10" style="padding-top: 5px">';
                        if (sub == 'cms.') {
                            content += '<input type="checkbox" id="select-all-market" class="js-select-all-market"/>\
                            <label for="select-all-market" title="Chọn để hiển thị tất cả siêu thị">Hiện tất cả Siêu thị</label>';
                        }
            content += '</div>\
                <div class="acr1">Chọn danh mục</div>\
                        <div class="col7">\
                            <select name="" id="js-select-category" class="acr2">';
                                content += '<option value="-1">Chọn danh mục...</option>';
                content +='</select>\
                        </div>\
                        <div class="acr1">Tên sản phẩm</div>\
                        <input type="text" class="acr2 js-input-search-pr" data-state="0" placeholder="Nhập tên sản phẩm">\
                        <div id="js-list-result-product">\
                        <div class="info-pr mgbt10 js-result-product" id="js-result-sr-row-1">\
                            <input type="hidden" class="js-input-add-pr" data-state="0">\
                            <img src="" alt=""  class="info-pr-img"/>\
                            <div class="info-pr-detail">\
                                <div class="row20">\
                                    <a class="info-pr-name sub-black-title">Tên sản phẩm...</a>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Siêu thị:</span>\
                                    </div>\
                                    <span class="col10 info-pr-vendor"></span>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Giá tiền:</span>\
                                    </div>\
                                    <span class="col4 info-pr-price">...</span>';
                                if (sub == 'cms.') {
                                 content +=   '<div class="col2">\
                                        <span class="sub-black-title">Giá xu:</span>\
                                    </div>\
                                    <span class="col4 info-pr-coin">...</span>';
                            } 

                             content += '</div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Khối lượng</span>\
                                    </div>\
                                    <div class="col4">\
                                        <input type="text" class="input-net-weight" value="1"/>\
                                    </div>\
                                    <div class="col2">\
                                        <span class="sub-black-title">Số lượng</span>\
                                    </div>\
                                    <div class="col4">\
                                        <input type="text" class="input-quantity" value="1"/>\
                                    </div>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Tổng tiền:</span>\
                                    </div>\
                                    <span class="col4 info-pr-total-price">...</span>';
                                if (sub == 'cms.') {
                                 content +=  '<div class="col2">\
                                        <span class="sub-black-title">Tổng xu:</span>\
                                    </div>\
                                    <span class="col4 info-pr-total-coin">...</span>';
                                }

                                content += '</div>\
                                <input type="hidden" id="js-has-product" value="0"/>\
                                <div class="clear"></div>\
                            </div>\
                            <div class="close-pr-detail">\
                                <span data-id="1" class="icon-close fa fa-close js-remove-product-sr" title="Bỏ sản phẩm này"></span>\
                            </div>\
                            <div class="clear"></div>\
                        </div>\
                        </div>\
                            <div class="list-sp-sg js-scroll none">\
                            </div>\
                        <div class="acr3 row30">\
                            <div class="button-blue js-submit-add-pr disable-pointer" title="Vui lòng chọn sản phẩm">Thêm</div>\
                            <div class="button-default js-cancel-add-pr">Hủy</div>\
                        </div>\
                    </div>\
                </section>';
        insertPopupCrm(content, ['.js-cancel-add-pr'], '.js-add-pr', true);
        // lấy danh sách các sp đã có trong đơn hàng.
        list_product = '';
        $('.is-row-product').each(function(){
             list_product += $(this).data('product') + ',';
        });
        if (!empty(list_product)) {
            list_product = trim(list_product, ',');
        }
        scrollMenu();
        $('.js-input-search-pr').keyup(function(e) {
            var pvalue = $(this).val();
            if (!empty(pvalue)) {
                $('.list-sp-sg').fadeIn();
                $('.list-sp-sg').addClass('js-button-wait');
            }
            switch (e.keyCode) {
                case 37:
                case 38:
                case 39:
                case 40:
                    break;
                case 13:
                    if(!empty(pvalue)){
                        searchProduct(pvalue);
                    }
                    break;
                default:
                    if(!empty(pvalue)){
                        clearTimeout(k_oTimer);
                        k_oTimer = setTimeout(function(){
                            searchProduct(pvalue);
                        }, 500);
                    }
                    break;
            }
            if(empty(pvalue)){
                $('.list-sp-sg').fadeOut();
                $('.list-sp-sg').removeClass('js-button-wait');
            }
        });
        // tự động gọi ajax để load thông tin tất cả siêu thị 
        if (empty(allvendor)) {
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=vendor.getAll';
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
                dataType: 'json',
                error: function(jqXHR, status, errorThrown){
                },
                success: function (result) {
                    if (result.status == 'error') {
                        alert(result.message);
                        return false;
                    }
                    else {
                        allvendor = result.data;
                        return false;
                    }
                }
            });
        }
        
        initCategory();
        
        $('.js-select-all-market').change(function(){
            var obj = $(this);
            $('#js-select-market').empty();
            option = '<option value="">Chọn siêu thị...</option>';
            if (obj[0]['checked'] == true) {
                // call ajax to get all vendor
                for (var i in allvendor) {
                    option += '<option class="addition-option" value="'+allvendor[i].id+'">'+allvendor[i].name+'</option>';
                }
            }else{
                // chuyển về giá trị cũ.
                $('#js-select-market').empty();
                for (var i in a_svndorid) {
                    option += '<option class="addition-option" value="'+a_svndorid[i]+'">'+a_svndorname[i]+'</option>';
                }
            }
            $('#js-select-market').append(option);
        });
        $('#js-select-market').change(function(){
           var keyword =  $('.js-input-search-pr').val();
           if (!empty(keyword)) {
               //searchProduct(keyword);
               $('.js-input-search-pr').keyup();
           }
        });
        $('#js-select-category').change(function(){
           var keyword =  $('.js-input-search-pr').val();
           if (!empty(keyword)) {
               //searchProduct(keyword);
               $('.js-input-search-pr').keyup();
           }
        });
        $('.js-submit-add-pr').unbind('click').click(function(event) {
            processAddProducts();
        });
    })
}
function processAddProducts(pass_warning) {
    if (typeof(pass_warning) == 'undefined') {
        pass_warning = 0;
    }
    
    /*
        if ($('.js-input-add-pr').attr('data-state') == '0') {
            alert('Vui lòng chọn sản phẩm!');
            event.preventDefault();
            return false;
        };
    */

    //valChange['product'].push($('.js-input-add-pr').attr('data-id'));
    
    // gọi ajax loading.
    oid = $('#block-detail-order').attr('data-id');
    
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.addProductsToOrder';
    sParams += '&val[oid]=' + encodeURIComponent(oid);
    //Lấy danh sách các sản phẩm được thêm vào
    var list = [];
    var cnt = 0;
    $('.js-result-product').each(function(){
        var sku = $(this).find('.js-input-add-pr').attr('data-code');
        var weight = $(this).find('.input-net-weight').val();
        var quantity = $(this).find('.input-quantity').val();
        sParams += '&val[list]['+cnt+'][sku]=' + encodeURIComponent(sku);
        sParams += '&val[list]['+cnt+'][weight]=' + encodeURIComponent(weight);
        sParams += '&val[list]['+cnt+'][quantity]=' + encodeURIComponent(quantity);
        cnt++;
    });
    sParams += '&val[pass_warning]=' + pass_warning;
    $('.js-submit-add-pr').addClass('js-button-wait');
    $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        url: getParam('sJsAjax'),
        type: "POST",
        data: sParams,
        timeout: 15000,
        cache:false,
        dataType: 'json',
        error: function(jqXHR, status, errorThrown){
        },
        success: function (result) {
            
            if (result.status == 'error') {
                $('.js-submit-add-pr').removeClass('js-button-wait');
                alert(result.message);
                return false;
            }
            else if (result.status == 'success') {
                $('.js-cancel-add-pr').click();
                $('#rowOrder' + oid).click();
                //displayProductAfterAdd(result.data);
            }
            else if (result.status == 'warning') {
                var find = '<br />';
                var re = new RegExp(find, 'g');
                msg = result.message.replace(re, '\n');
                if (confirm(msg)) {
                    processAddProducts(1);
                }
                $('.js-submit-add-pr').removeClass('js-button-wait');
                return false;
            }
            //
        }
    });
}

function displayProductAfterAdd(data){
    $('#total_product').html(data.total_product);
    $('#total_amount').html(currencyFormat(data.total_amount));
    $('#amount_with_coin').html(data.total_coin + ' Xu + '+ currencyFormat(data.total_amount));
    $('#surcharge_money').html(currencyFormat(data.total_surcharge));
    $('#surcharge_money').attr('data-money',data.total_surcharge);
    
    var amount = data.total_amount - data.total_surcharge;
    $('#amount').html(currencyFormat(amount));
    if (data.order_status.length > 0) {
        $('#js-order-status').empty();
        option ='';
        for (var i in data.order_status) {
            option += '<option class="addition-option" value="'+i+'">'+data.order_status[i]+'</option>';
        }
        $('#js-order-status').append(option);
    }
    //update display product
    for(var i in data.item) {
    var content = '';
    content += '<div height-expand="69" class="x3 is-expand-panel" data-id="group-mart-1">\
                    <div class="j9 is-row-product" data-source="'+data.item[i].id+'" data-product="'+data.item[i].article_id+'" data-id="product-'+data.item[i].id+'">\
                        <div class="j72">\
                            <a href="'+ oParams['sJsMain']+'article/view/?key='+ data.item[i].code +'" class="j0" title="'+data.item[i].name+'">'+data.item[i].name+'</a>\
                            <div class="ja">\
                                <div class="ja01">\
                                    <div class="ja011">Mã sản phẩm: </div>\
                                    <div class="ja012">'+data.item[i].sku+'</div>\
                                </div>\
                                <div class="ja01">\
                                    <div class="ja011">Đơn vị tính: </div>\
                                    <div class="ja012">'+data.item[i].unit_value + ' ' +data.item[i].unit_name+'</div>\
                                </div>\
                                <div class="ja01">\
                                    <div class="ja011">Đơn giá: </div>\
                                    <div class="j75 is-money ja012" data-money="'+(data.item[i].price_sell - data.item[i].price_discount) +'" data-coin="'+data.item[i].price_coin_buy+'">\
                                        <span>'+currencyFormat(data.item[i].price_sell - data.item[i].price_discount)+'</span>\
                                        <sup itemprop="priceCurrency" content=""> <u>đ</u> </sup>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="j74 jb is-quantity-group" data-min="1" data-step=".5" data-call-back="changenetWeightOrder">\
                            <div class="jd fa fa-minus is-quantity-down"></div>\
                            <input class="is-quantity-value js-product-net-weight" data-value="'+data.item[i].unit_value+'" value="'+ data.item[i].weight +'" type="text">\
                            <div class="jd fa fa-plus is-quantity-up"></div>\
                            <span class="text-quantity">(Khối lượng)</span>\
                        </div>\
                        <div class="j74 jb is-quantity-group" data-min="1" data-step="1" data-call-back="changeQuantityOrder">\
                            <div class="jd fa fa-minus is-quantity-down"></div>\
                            <input class="is-quantity-value js-product-quantity" value="'+ data.item[i].quantity +'" type="text">\
                            <div class="jd fa fa-plus is-quantity-up"></div>\
                            <span class="text-quantity">(Số lượng)</span>\
                        </div>\
                        <div class="j740">\
                            Tổng tiền:\
                        </div>\
                        <div class="j76">\
                            <span class="vl is-sum-money is-money is-money-ajax wait" id="shop-'+data.item[i].article_id+'" data-quantity="1" data-id="'+data.item[i].article_id+'" data-vendor="'+data.item[i].vendor_id+'" data-sku="'+data.item[i].sku+'" data-money="'+(data.item[i].price_sell - data.item[i].price_discount)+'">'+ currencyFormat(data.item[i].price_sell - data.item[i].price_discount)+'</span>\
                            <sup itemprop="priceCurrency" content=""> <u>đ</u> </sup>\
                        </div>\
                        <div class="j77">\
                            <span class=" fa fa-close is-remove-product" title="Bỏ sản phẩm này"></span>\
                            <span class=" fa fa-edit is-update-price" title="Cập nhật giá" data-vendor="'+data.item[i].vendor_id+'" data-key="'+data.item[i].code+'"></span>\
                        </div>\
                        <div class="clear"></div>\
                    </div>\
                </div>';

    var idMart = data.item[i].vendor_id;
    var nameMart = data.item[i].vendor_name;
    var checkExistMark = false;
    $('.js-data-mart').each(function(){
        var obj = $(this);
        if(obj.attr('data-id') == idMart){
            checkExistMark = true;
            $('.js-group-mark-pr[data-mark-id="'+idMart+'"]').append(content);
        }
    });
    if (!checkExistMark) {
        tmp = '<div class="x30 is-expand-group js-group-mark-pr" data-id="group-mart-1" data-mark-id="'+idMart+'">\
                                                <div class="x31">\
                                                    <span class="x32  is-label-check-box js-data-mart" data-id="'+idMart+'" data-name="'+nameMart+'">\
                                                        <i class="fa fa-shopping-cart"></i>'+nameMart+'</span>\
                                                    <span class="fa x320 fa-angle-down is-expand-nav" data-id="group-mart-1"></span>\
                                                    <select id="js-vendor-status" ng-model="select001" placeholder="Chọn trạng thái" class="sele normal js-set-status-vendor">';
        for (var j in data.vendor_status) {
            tmp += '<option value="'+j+'">'+data.vendor_status[j]+'</option>';
        }
        tmp += '</select>\</div>\</div>';
        $('.x3g.is-expand-panel').append(tmp);
        $('.js-group-mark-pr[data-mark-id="'+idMart+'"]').append(content);
    };
       
    }
    $('.js-add-pr').remove();
    expandGroup();
    scrollMenu();
    initOrderDetail();
    initQuantilyGroup();
    removeProductOrder();
    initUpdatePrice();
    // tính lại tiền cho tất cả các dòng.
    $('.is-row-product').each(function(){
        calcPriceToDisplay($(this));
    });
    reSumMoney();
}
function searchProduct(keyword){
    vendor = $('#js-select-market').val();
    category_id = $('#js-select-category').val();
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.search';
    sParams += '&val[q]=' + encodeURIComponent(keyword) + '&val[vendor]=' + vendor + '&val[category_id]=' + category_id;
    
    //Lấy thêm thông tin sản phẩm đã search và được chọn
    var list_tmp = '';
    var list_exit = list_product;
    $('.js-result-product').each(function(){
        var obj = $(this).find('.js-input-add-pr');
        if (typeof(obj.attr('data-id')) != 'undefined' && obj.attr('data-id')*1 > 0) {
            list_tmp += obj.attr('data-id')*1 + ',';
        }
    });
    if (!empty(list_tmp)) {
        list_tmp = trim(list_tmp, ',');
        if (!empty(list_exit)) {
            list_exit = list_exit + ',' + list_tmp;
        }
    }
    
    sParams += '&val[list]=' + encodeURIComponent(list_exit);
    
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
        dataType: 'json',
        error: function(jqXHR, status, errorThrown){
        },
        success: function (result) {
            $('.list-sp-sg').removeClass('js-button-wait');
            if (result.status == 'error') {
                content = '<span class="err">'+result.message+'</span>'; 
                $('.list-sp-sg .mCSB_container').html(content);
                setTimeout(function(){
                    $('.list-sp-sg').fadeOut();
                }, 2000);
                return false;
            }
            else {
                var content = '';
                data = result.data;
                for (var i in data) {
                    content += '<div class="item-sp-sg"\
                        data-id="'          + data[i].id+'"\
                        data-src="'         + data[i].image_path+'"\
                        data-price="'       + data[i].unit_price+'"\
                        data-coin="'        + data[i].price_coin_buy+'"\
                        data-href="'        + data[i].url+'?vendor='+ data[i].vendor_id +'"\
                        data-code="'        + data[i].sku+'"\
                        data-unit="'        + ((data[i].unit == null) ? 'Chưa xác định' : (data[i].unit_value + ' ' +  data[i].unit.name)) +'"\
                        data-unit-value="'        + data[i].unit_value+'"\
                        data-name="'        + data[i].name+'"\
                        data-vendor-id="'   + data[i].vendor_id+'"\
                        data-vendor="'      + data[i].vendor_name+'">\
                        <span class="name">'+ data[i].name+'</span>\
                        <span class="info"> Siêu thị '+ data[i].vendor_name+'\
                        - Giá: '            + data[i].unit_price+' đ\
                        / Đơn vị tính: '                + ((data[i].unit == null) ? 'Chưa xác định' : (data[i].unit_value + ' ' +  data[i].unit.name))+'</span></div>';
                };
                $('.list-sp-sg .mCSB_container').html(content);
                initSelectSearchProduct();
            }
        }
    });
}
function initSelectSearchProduct(){
    $('.item-sp-sg').click(function(){
        $('.js-submit-add-pr').removeClass('disable-pointer').attr('title',  '');
        var obj = $(this);
        //Kiểm tra đã có sản phẩm trước chưa (nếu có thì thêm mới)
        var hasProduct = $('#js-has-product').val()*1;
        var sub = typeof(subdomain) != 'undefined' ? subdomain : '';
        if (hasProduct) {
            //thêm dòng
            hasProduct++;
            $('#js-has-product').val(hasProduct);
            var content = '<div class="info-pr mgbt10 js-result-product" id="js-result-sr-row-'+ hasProduct + '">\
                            <input type="hidden" class="js-input-add-pr" data-state="0">\
                            <img src="" alt=""  class="info-pr-img"/>\
                            <div class="info-pr-detail">\
                                <div class="row20">\
                                    <a class="info-pr-name sub-black-title">Tên sản phẩm...</a>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Siêu thị:</span>\
                                    </div>\
                                    <span class="col10 info-pr-vendor"></span>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Giá tiền:</span>\
                                    </div>\
                                    <span class="col4 info-pr-price">...</span>';
                                if (sub == 'cms.') {
                                    content += '<div class="col2">\
                                        <span class="sub-black-title">Giá xu:</span>\
                                    </div>\
                                    <span class="col4 info-pr-coin">...</span>';
                                }
                                    
                                content += '</div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Khối lượng</span>\
                                    </div>\
                                    <div class="col4">\
                                        <input type="text" class="input-net-weight" value="1"/>\
                                    </div>\
                                    <div class="col2">\
                                        <span class="sub-black-title">Số lượng</span>\
                                    </div>\
                                    <div class="col4">\
                                        <input type="text" class="input-quantity" value="1"/>\
                                    </div>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Tổng tiền:</span>\
                                    </div>\
                                    <span class="col4 info-pr-total-price">...</span>';
                                if (sub == 'cms.') {
                                    content += '<div class="col2">\
                                        <span class="sub-black-title">Tổng xu:</span>\
                                    </div>\
                                    <span class="col4 info-pr-total-coin">...</span>';
                                }
                                content += '</div>\
                                <div class="clear"></div>\
                            </div>\
                            <div class="close-pr-detail">\
                                <span data-id="'+ hasProduct +'" class="icon-close fa fa-close js-remove-product-sr" title="Bỏ sản phẩm này"></span>\
                            </div>\
                            <div class="clear"></div>\
                        </div>';
            $('#js-list-result-product').append(content);
        }
        else {
            hasProduct++;
            $('#js-has-product').val(hasProduct);
            //đưa vào dòng đã có
        }
        /*  Đưa vào thẻ input */
        var objSelect = $('#js-result-sr-row-'+ hasProduct);
        objSelect.find('.js-input-add-pr').val(obj.attr('data-name'));
        objSelect.find('.js-input-add-pr').val(obj.attr('data-name'));
        objSelect.find('.js-input-add-pr').attr('data-state', '1');

        objSelect.find('.js-input-add-pr').attr('data-id',           obj.attr('data-id'));
        objSelect.find('.js-input-add-pr').attr('data-src',          obj.attr('data-src'));
        objSelect.find('.js-input-add-pr').attr('data-price',        obj.attr('data-price'));
        objSelect.find('.js-input-add-pr').attr('data-coin',         obj.attr('data-coin'));
        objSelect.find('.js-input-add-pr').attr('data-href',         obj.attr('data-href'));
        objSelect.find('.js-input-add-pr').attr('data-code',         obj.attr('data-code'));
        objSelect.find('.js-input-add-pr').attr('data-unit',         obj.attr('data-unit'));
        objSelect.find('.js-input-add-pr').attr('data-unitvalue',    obj.attr('data-unit-value'));
        objSelect.find('.js-input-add-pr').attr('data-name',         obj.attr('data-name'));
        objSelect.find('.js-input-add-pr').attr('data-vendor',       obj.attr('data-vendor'));
        objSelect.find('.js-input-add-pr').attr('data-vendor-id',    obj.attr('data-vendor-id'));

        /*  Hiển thị thông tin tóm tắt */
        
        objSelect.find('.info-pr-img').attr('src',                   obj.attr('data-src'));
        objSelect.find('.info-pr-name').attr('href',                 obj.attr('data-href'));
        objSelect.find('.info-pr-name').html(                        obj.attr('data-name'));
        objSelect.find('.info-pr-price').html(                       currencyFormat(obj.attr('data-price')));
        objSelect.find('.info-pr-price').attr('data-money',          obj.attr('data-price'));
        objSelect.find('.info-pr-coin').html(                        currencyFormat(obj.attr('data-coin')));
        objSelect.find('.info-pr-coin').attr('data-money',           obj.attr('data-coin'));
        objSelect.find('.info-pr-vendor').html(                      obj.attr('data-vendor'));
        objSelect.find('.input-net-weight').val(obj.attr('data-unit-value'));

        /* Tính tiền */
        calcPriceAddProduct(objSelect);
        objSelect.find('.input-net-weight').unbind('change').change(function(){
            calcPriceAddProduct(objSelect);
        });
        objSelect.find('.input-quantity').unbind('change').change(function(){
            calcPriceAddProduct(objSelect);
        });

        $('.list-sp-sg').fadeOut();
        $('.js-input-search-pr').val('');
        removeSelectProduct();
    })
}
function calcPriceAddProduct(objSelect){
    var price       = objSelect.find('.info-pr-price').attr('data-money') * 1;
    var coin        = objSelect.find('.info-pr-coin').attr('data-money') * 1;
    var netWeight   = objSelect.find('.input-net-weight').val() * 1;
    var quantity    = objSelect.find('.input-quantity').val() * 1;
    var unitvalue = objSelect.find('.js-input-add-pr').attr('data-unitvalue') * 1;
    if (unitvalue == null || unitvalue == 0) {
        unitvalue = 1;
    }
    price = currencyFormat(price * netWeight * quantity / unitvalue) ;
    coin = currencyFormat(coin * netWeight * quantity / unitvalue);
    
    objSelect.find('.info-pr-total-price').html(price);
    objSelect.find('.info-pr-total-coin').html(coin);
}
function removeProductOrder(){
    $('.is-remove-product').unbind('click').click(function(){
        var obj         = $(this).parents('.x3.is-expand-panel');
        
        var cm          = confirm('Bạn có chắc muốn xóa sản phẩm này?!');
        if (cm == true) {
            processRemoveProduct(obj, 0);
        }
        return false;
    });
}
function processRemoveProduct(obj, pass_warning)
{
    if (typeof(pass_warning) == 'undefined') {
        pass_warning = 0;
    }
    
    // gọi ajax để xóa sản phẩm
    oid = $('#block-detail-order').attr('data-id');
    sku = obj.find('.is-sum-money').attr('data-sku');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.removeProduct';
    sParams += '&val[oid]=' + encodeURIComponent(oid);
    sParams += '&val[sku]=' + encodeURIComponent(sku);
    sParams += '&val[pass_warning]=' + pass_warning;
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
        dataType: 'json',
        error: function(jqXHR, status, errorThrown){
        },
        success: function (result) {
            if (result.status == 'error') {
                alert(result.message);
                return false;
            }
            else if (result.status == 'success') {
                $('#rowOrder' + oid).click();
                /*
                // cập nhật giá trị đơn hàng
                data = result.data;
                $('#total_product').html(data.total_product);
                $('#total_amount').html(currencyFormat(data.total_amount));
                $('#amount_with_coin').html(data.total_coin + ' Xu + '+ currencyFormat(data.total_amount));
                $('#surcharge_money').html(currencyFormat(data.total_surcharge));
                $('#surcharge_money').attr('data-money',data.total_surcharge);
                
                var amount = data.total_amount - data.total_surcharge;
                $('#amount').html(currencyFormat(amount));
                if (data.order_status.length > 0) {
                    $('#js-order-status').empty();
                    option ='';
                    for (var i in data.order_status) {
                        option += '<option class="addition-option" value="'+i+'">'+data.order_status[i]+'</option>';
                    }
                    $('#js-order-status').append(option);
                }
                //bỏ dòng sản phẩm đã chọn xóa.
                $('.is-row-product').each(function(){
                    obj = $(this);
                    var row = obj.find('.is-sum-money');
                    groupMark = obj.parents('.js-group-mark-pr');
                    if (row.attr('data-sku') == data.sku) {
                        obj.animate(function(){
                            opacity: 0
                        }, 1000, function(){
                            obj.parents('.x3.is-expand-panel').remove();
                            if (groupMark.find('.x3.is-expand-panel').length <= 0) {
                                groupMark.fadeOut(function(){
                                    groupMark.remove();
                                })
                            };
                        });
                        return false;
                    }
                });
                reSumMoney();
                */
            }
            else if (result.status == 'warning') {
                var find = '<br />';
                var re = new RegExp(find, 'g');
                msg = result.message.replace(re, '\n');
                if (confirm(msg)) {
                    processRemoveProduct(obj, 1);
                }
                return false;
            }
        }
    });
}
function saveInfo(){
    /*  Lưu thông tin */
    $('.js-save-product-order').unbind('click').click(function(){
        var obj_btn = $(this);
        processSaveInfo(obj_btn);
    });
}

function processSaveInfo(obj_btn, pass_warning)
{
    if (typeof(pass_warning) == 'undefined') {
        pass_warning = 0;
    }
    
    /*
        var content = '<div class="container pad20 order-purchase">';
                content += '<div class="content-box panel-shadow">';
                    content += '<div class="col-md-12">';
                        content += '<div class="box-title title-blue">Cảnh báo!</div>';
                        content += '<div class="row20 padlr20 mgbt10">';
                            content += '<div class="message"></div>';
                            content += '';
                        content += '</div>';
                        content += '<div class="row30 mgbt20">';
                            content += '<div class="col-md-6 padright10"><div class="button-default js-cancel-confirm">Hủy</div></div>';
                            content += '<div class="col-md-6 padleft10"><div class="button-blue col4 js-accept-confirm" id="js-submit-confirm">Đồng ý</div></div>';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';
        content += '</div>';
        
        insertPopupCrm(content, ['.js-cancel-confirm'], '.order-purchase', true);
        */
        if (valChange['product'].length <= 0 && valChange['obj'].length <= 0) {
            alert('Không có chỉnh sửa nào được thực hiện');
            return;
        };
        //$(this).addClass('js-button-wait');
        var oid = $('#block-detail-order').attr('data-id');
        $sParam = '&val[oid]='+ oid;
        aChange = [];
        sProduct = '';
        // duyệt lấy thông tin chỉnh sửa cho product
        if (valChange['product'].length > 0) {
            for (var i in valChange['product']) {
                obj = $('.is-row-product[data-source="'+valChange['product'][i]+'"]');
                weight = obj.find('.js-product-net-weight').val();
                quantity = obj.find('.js-product-quantity').val();
                sku = obj.find('.is-sum-money').attr('data-sku');
                sProduct += '&val[product][sku][]='+encodeURIComponent(sku);
                sProduct += '&val[product][weight][]='+encodeURIComponent(weight);
                sProduct += '&val[product][quantity][]='+encodeURIComponent(quantity);
            }
        }
        if (!empty(sProduct)) {
            aChange.push('product');
            $sParam += sProduct;
        }
        
        // duyệt lấy thông tin chỉnh sửa khác
        if (valChange['obj'].length > 0) {
            for (var i in valChange['obj']) {
                obj = $('.js-change-obj#'+valChange['obj'][i]);
                aChange.push(valChange['obj'][i]);
                $sParam += '&val['+valChange['obj'][i]+']='+ obj.val();
            }
        }
        for (var i in aChange) {
            $sParam += '&val[change][]='+ aChange[i];
        }
        $sParam += '&val[pass_warning]=' + pass_warning;
        $sParam = '&'+ getParam('sGlobalTokenName') + '[call]=shop.updateOrder' + $sParam;
        obj_btn.addClass('js-button-wait');
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: $sParam,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown) {
            },
            success: function (result) {
                $('.js-save-product-order').removeClass('js-button-wait');
                if (result.status == 'error') {
                    var msg = result.message.split('<br />').join('\n');
                    alert(msg);
                    obj_btn.removeClass('js-button-wait');
                    return false;
                }
                else if (result.status == 'warning') {
                    var find = '<br />';
                    var re = new RegExp(find, 'g');
                    msg = result.message.replace(re, '\n');
                    if (confirm(msg)) {
                        processSaveInfo(obj_btn, 1);
                    }
                    obj_btn.removeClass('js-button-wait');
                    return false;
                }
                else {
                    
                    data = result.data;
                    if (isset(data.address) && !empty(data.address)) {
                        $('#full-address').html(data.address);
                    }
                    
                    alert('Lưu thành công.');
                    $('#rowOrder' + oid).click();
                    return false;
                }
            }
        });
}

function showDistrict(data)
{
    if (data.status == 'error') {
        alert(data.message);
        return false;
    }
    else {
        data = data.data;
        var html = '<option value="">Chọn quận / huyện</option>';
        for (var i in data) {
            html += '<option value="'+ data[i].id+'">'+ data[i].name+'</option>';
        }
        $('#address-district').empty();
        $('#address-district').append(html);
    }
    var html = '<option value="">Chọn Phường /Xã</option>';
    $('#address-ward').empty();
    $('#address-ward').append(html);
}

function showWard(data)
{
    if (data.status == 'error') {
        alert(data.message);
        return false;
    }
    else {
        data = data.data;
        var html = '<option value="">Chọn Phường /Xã</option>';
        for (var i in data) {
            html += '<option value="'+ data[i].id+'">'+ data[i].name+'</option>';
        }
        $('#address-ward').empty();
        $('#address-ward').append(html);
    }
}

function removeSelectProduct()
{
    $('.js-remove-product-sr').click(function(){
        var id = $(this).data('id')*1;
        if (id > 0) {
            $('#js-result-sr-row-' + id).remove();
            //document.getElementById('js-result-sr-row-' + id).innerHTML = '';
            //document.getElementById('js-result-sr-row-' + id).style.display = "none";
            
            //Kiểm tra danh sách sản phẩm có bị xóa hết ko
            var total = $('.js-result-product').length;
            if (total < 1) {
                //reset về mặc định
                
                $('.js-submit-add-pr').addClass('disable-pointer').attr('title',  'Vui lòng chọn sản phẩm');
                hasProduct = 1;
                var content = '<div class="info-pr mgbt10 js-result-product" id="js-result-sr-row-'+ hasProduct + '">\
                            <input type="hidden" class="js-input-add-pr" data-state="0">\
                            <img src="" alt=""  class="info-pr-img"/>\
                            <div class="info-pr-detail">\
                                <div class="row20">\
                                    <a class="info-pr-name sub-black-title">Tên sản phẩm...</a>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Siêu thị:</span>\
                                    </div>\
                                    <span class="col10 info-pr-vendor"></span>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Giá tiền:</span>\
                                    </div>\
                                    <span class="col4 info-pr-price">...</span>\
                                    <div class="col2">\
                                        <span class="sub-black-title">Giá xu:</span>\
                                    </div>\
                                    <span class="col4 info-pr-coin">...</span>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Khối lượng</span>\
                                    </div>\
                                    <div class="col4">\
                                        <input type="text" class="input-net-weight" value="1"/>\
                                    </div>\
                                    <div class="col2">\
                                        <span class="sub-black-title">Số lượng</span>\
                                    </div>\
                                    <div class="col4">\
                                        <input type="text" class="input-quantity" value="1"/>\
                                    </div>\
                                </div>\
                                <div class="row20">\
                                    <div class="col2">\
                                        <span class="sub-black-title">Tổng tiền:</span>\
                                    </div>\
                                    <span class="col4 info-pr-total-price">...</span>\
                                    <div class="col2">\
                                        <span class="sub-black-title">Tổng xu:</span>\
                                    </div>\
                                    <span class="col4 info-pr-total-coin">...</span>\
                                </div>\
                                <input type="hidden" id="js-has-product" value="0"/>\
                                <div class="clear"></div>\
                            </div>\
                            <div class="close-pr-detail">\
                                <span data-id="'+ hasProduct +'" class="icon-close fa fa-close js-remove-product-sr" title="Bỏ sản phẩm này"></span>\
                            </div>\
                            <div class="clear"></div>\
                        </div>';
                $('#js-list-result-product').append(content);
            }
        }
    });
}

function initCategory()
{
    // tự động gọi ajax để load thông tin tất cả danh mục
    if (empty(allcategory)) {
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.getProductCategories';
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
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
            },
            success: function (result) {
                if (result.status == 'error') {
                    alert(result.message);
                    return false;
                }
                else {
                    allcategory = result.data;
                    //Show category
                    if (!empty(allcategory)) {
                        showCategory();
                    }
                    return false;
                }
            }
        });
    }
    else {
        showCategory();
    }
}

function showCategory()
{
    if (!empty(allcategory)){
        var content = '<option value="-1">Chọn danh mục...</option>';
        for(var i in allcategory) {
            content += '<option value='+ allcategory[i][0] + '>'+ allcategory[i][1] +'</option>';
        }
        $('#js-select-category').html(content);
    }
}

function initSetLocationDelivery()
{
    $('#js-change-transport').unbind('click').click(function(){
        var objSelect = $(this);
        var id = objSelect.attr('data-id')*1;
        objSelect.addClass('js-button-wait');
        if (id > 0) {
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.changeTransportOrder' + '&val[oid]=' + id;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'json',
                error: function(jqXHR, status, errorThrown){
                    alert('Lỗi hệ thống');
                    objSelect.removeClass('js-button-wait');
                },
                success: function (result) {
                    
                    if(isset(result.status) && result.status == 'success') {
                        alert('Đã cập nhật thành công');
                        objSelect.find('.bp .p').html('Cập nhật vận đơn');
                    }
                    else {
                        var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(messg);
                    }
                    objSelect.removeClass('js-button-wait');
                }
            });
        }
    });
    
    $('#js-confirm-address-delivery').unbind('click').click(function(){
        
        var  id = $(this).attr('data-id')*1;
        if (id < 1) {
            return false;
        }
        var address_delivery = $('#full-address').html();
        var location_lat = $('#js-location-delivery-lat').val();
        var location_lng = $('#js-location-delivery-lng').val();
        var sWarning = '';
        if (empty(location_lat) || empty(location_lng)) {
             var sWarning = '<div class="row30 dialog-warn mgbt10">' +
                'Đơn hàng chưa thiết lập địa điểm giao hàng, vui lòng kiểm tra địa điểm và nhấn nút thiết lập để thiết lập địa điểm cho đơn hàng này.' +
            '</div>';
            
        }
        var html = '<div class="container pad20 set-location-delivery">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Địa điểm giao hàng</div>' +
                            '<div class="box-inner">' +
                                '<form action="#" method="post" name="frm_create" id="frm_location_delivery">' +
                                    '<div class="row30 mgbt10" id="js-notice-map-location">' + sWarning +
                                    '</div>' +
                                    '<input type="text" class="search-address" name="val[address]" value="'+address_delivery+'" id="pac-input" placeholder="Nhập địa chỉ giao hàng">' +
                                    '<div class="row30">' +
                                        '<input type="hidden" name="val[oid]" value="'+id+'"/>' +
                                        '<div class="col9">' +
                                            '<div id="js-map-delivery" class="map-delivery"></div>' +
                                        '</div>' +
                                        '<div class="col3">' +
                                            '<div class="row30 padleft20">' +
                                                '<label for="ten" class="sub-blue-title">' +
                                                    'Vị trí được chọn:' +
                                                '</label>' +
                                            '</div>' +
                                            '<div class="row30 padleft20">' +
                                                'Latitude:' +
                                            '</div>' +
                                            '<div class="row30 padleft20">' +
                                                '<input class="disable-pointer default-input" type="text" name="val[lat]" value="'+location_lat+'" id="js-lat-location">' +
                                            '</div>' +
                                            '<div class="row30 padleft20">' +
                                                'Longtitude:' +
                                            '</div>' +
                                            '<div class="row30 padleft20">' +
                                                '<input class="disable-pointer default-input" type="text" name="val[lng]" value="'+location_lng+'" id="js-lng-location">' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="row30 mgtop20">' +
                                        '<div class="col8"></div>' +
                                        '<div class="col2 padleft10">' +
                                            '<div class="button-blue" id="js-set-location-delivery">Thiết lập</div>' +
                                        '</div>' +
                                        '<div class="col2 padleft10">' +
                                            '<div class="button-default js-cancel-add">Hủy</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</form>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.set-location-delivery', true);
        initLoadLocationDelivery();
        submitSetLocation();
    });
}

function initLoadLocationDelivery()
{
    infowindow = null;
    marker = null;
    geocoder =null;
    
    var map = new google.maps.Map(document.getElementById('js-map-delivery'), {
        center: {
            /* VỊ TRI MẶC ĐỊNH*/
            lat     : 21.014243794,
            lng     : 105.7952894},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );
    geocoder = new google.maps.Geocoder();
    
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });
    
    var markers = [];
    
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        markers.forEach(function(tmp_marker) {
          tmp_marker.setMap(null);
        });
        markers = [];

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            placeMarker(place.geometry.location, map);
            
            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
    
    //show map by lat and long
    var loc_lat = $('#js-lat-location').val();
    var loc_lng = $('#js-lng-location').val();
    if (!empty(loc_lat) && !empty(loc_lng)) {
        var myLatlng = new google.maps.LatLng(loc_lat, loc_lng);
        placeMarker(myLatlng, map);
        if (marker) {
            map.setCenter(marker.getPosition());
        }
    }
    else {
        //show by suggest address
        var address = $('#pac-input').val();
        if (!empty(address)) {
            geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              map.setCenter(results[0].geometry.location);
              placeMarker(results[0].geometry.location, map);
            } else {
                //lỗi
                var html = '<div class="row30 dialog-err mgbt10">' +
                                'Hệ thống không xác định được địa điểm hiện tại' +
                            '</div>';
                $('#js-notice-map-location').append(html);
                //alert('Geocode was not successful for the following reason: ' + status);
            }
          });
        }
    }
    
    //sự kiện click vào map chọn long - lat
    google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng, map);
    });
}

function placeMarker(location, map) {
    $('#js-notice-map-location').find('.dialog-err').remove();    
  if (infowindow) {
      infowindow.close();
  }
  if (marker) {
      marker.setMap(null);
      marker = null;
  }
  marker = new google.maps.Marker({
    position: location,
    map: map,
  });
  infowindow = new google.maps.InfoWindow({
    content: 'Latitude: ' + location.lat() +
    '<br>Longitude: ' + location.lng()
  });
  google.maps.event.addListener(infowindow, 'domready', function(){
    $(".gm-style-iw").next("div").hide();
  });
  
  infowindow.open(map,marker);
  //show info 
  $('#js-lat-location').val(location.lat());
  $('#js-lng-location').val(location.lng());
  
}

function submitSetLocation()
{
    $('#js-set-location-delivery').unbind('click').click(function(){
        //validate
        if (empty($('#js-lat-location').val()) || empty($('#js-lat-location').val())) {
            alert('Vui lòng chọn địa điểm');
            return false;
        }
        $('#js-set-location-delivery').addClass('js-button-wait');
        var action = "$(this).ajaxSiteCall('shop.setLocationDelivery', 'afterSetLocation(data)'); return false;";
        $('#frm_location_delivery').attr('onsubmit', action);
        $('#frm_location_delivery').submit();
        $('#frm_location_delivery').removeAttr('onsubmit');
        return false;
    });
}

function afterSetLocation(data)
{
    $('#js-set-location-delivery').removeClass('js-button-wait');
    if (isset(data.status) &&  data.status == 'success') {
        alert('Cập nhật thành công');
        var loc_lat = $('#js-lat-location').val();
        var loc_lng = $('#js-lng-location').val();
        $('.js-cancel-add').click();
        $('#js-location-delivery-lat').val(loc_lat);
        $('#js-location-delivery-lng').val(loc_lng);
    }
    else {
        var messg = isset(data.message) ? data.message : 'Lỗi hệ thống';
        alert(messg);
    }
}

function initPaymentOrder()
{
    $('#js-refund-order').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        if (id > 0) {
            //call ajax
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.callPopupRefund' + '&oid=' + id;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'text',
                error: function(jqXHR, status, errorThrown){
                    alert('Error');
                },
                success: function (data) {
                    content = data;
                    insertPopupCrm(content, ['.js-cancel-purchase'], '.order-purchase', true);
                    ValidateKey();
                    submitRefundOrder('refund');
                }
            });
        }
        
    });
    
    $('#js-payment-order').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        if (id > 0) {
            //call ajax
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.callPopupPayment' + '&oid=' + id;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'text',
                error: function(jqXHR, status, errorThrown){
                    alert('Error');
                },
                success: function (data) {
                    content = data;
                    insertPopupCrm(content, ['.js-cancel-purchase'], '.order-purchase', true);
                    ValidateKey();
                    submitRefundOrder('payment');
                }
            });
        }
        
    });
}

function submitRefundOrder(type)
{
    $('#js-submit-purchase').unbind('click').click(function(){
        //validate
        var orderid = $('#js-order-id').val();
        if (orderid < 1) {
            alert('Không xác định được đơn hàng');
            return false;
        }
        var pay_gateway = $('#js-select-gateway').val();
        if (empty(pay_gateway))  {
            alert('Chưa chọn hình thức thanh toán');
            return false;
        }
        
        var total_money = $('#js-money-refund').val()*1;
        var max_money = $('#js-money-refund').attr('data-total')*1;
        var total_coin = $('#js-coin-refund').val()*1;
        var max_coin = $('#js-coin-refund').attr('data-total')*1;
        if (type == 'refund') {
            if (total_money  <= 0 && total_coin <= 0) {
                alert('Vui lòng nhập số tiền hoặc xu cần trả lại');
                return false;
            }
            if (total_money > max_money) {
                alert('Số tiền hoàn trả lớn hơn số tiền nhận được');
                return false;
            }
            
            if (pay_gateway != 'diem' && total_coin > 0) {
                alert('Chỉ hoàn trả tiền xu khi hình thức thanh toán là Tài khoản');
                return false;
            }
            
            if (total_coin > max_coin) {
                alert('Số xu hoàn trả lớn hơn số xu nhận được');
                return false;
            }
        }
        else if(type == 'payment') {
            if (total_money  <= 0 && total_coin <= 0) {
                alert('Vui lòng nhập số tiền hoặc xu thanh toán');
                return false;
            }
            if (total_money > max_money) {
                alert('Số tiền lớn hơn số tiền cần thanh toán');
                return false;
            }
            
            if (pay_gateway != 'diem' && total_coin > 0) {
                alert('Chỉ thanh toán tiền xu khi hình thức thanh toán là Tài khoản');
                return false;
            }
            
            if (total_coin > max_coin) {
                alert('Số xu lớn hơn số xu cần thanh toán');
                return false;
            }
            
        }
        
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.paymentOrder' + '&val[oid]=' + orderid;
        sParams += '&val[type]=' + type;
        sParams += '&val[gateway]=' + pay_gateway;
        sParams += '&val[money]=' + total_money;
        sParams += '&val[coin]=' + total_coin;
        
        $('#js-submit-purchase').addClass('js-button-wait');
        $('.message').html('');
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
                alert('Error');
            },
            success: function (data) {
                if (data.status == 'error') {
                    $('.message').addClass('dialog-err').html(data.message);
                    $('#js-submit-purchase').removeClass('js-button-wait');
                    return false;
                }
                else {
                    $('.message').addClass('dialog-success').html('Thanh toán thành công.');
                    setTimeout(function(){
                        $('.js-cancel-purchase').click();
                        window.location.reload(false);
                    }, 1000);
                }
            }
        });
    });
}

function ValidateKey()
{
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57);
    $('.js-input-number').keydown(function(e){
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
}