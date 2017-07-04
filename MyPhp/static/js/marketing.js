function submitForm()
{
    var apply  = $('#js-select-obj-apply').val()*1;
    var program  = $('#js-select-program').val()*1;
    
    if (program == 1) {
        if ($('#select-list-vendor').val() < 1) {
            alert('Vui lòng nhà cung cấp');
            return false;
        }
    }
    
    var nameMarketing = $('#js-name-marketing').val();
    if (nameMarketing == '') {
        alert('Vui lòng điền tên chương trình');
        return false;
    }
    
    //validate marketing method
    var objMethod = {};
    objMethod['js-method-point'] = 'Vui lòng nhập số điểm thưởng';
    objMethod['js-method-gift'] = 'Vui lòng thông tin quà tặng đính kèm';
    objMethod['js-method-discount'] = 'Vui lòng thông tin giảm giá';
    objMethod['js-delivery-discount'] = 'Vui lòng điền giá vận chuyển được giảm';
    
    for (var sKey in objMethod) {
        var checkboxId = sKey+'-check';
        var hasCheckedTmp = document.getElementById(checkboxId).checked;
        if (hasCheckedTmp) {
            var valueTmp = $('#'+sKey+'-value').val();
            if (empty(valueTmp)) {
                alert(objMethod[sKey]);
                return false;
            }
        }
    }
    
    //validate apply method
    if (apply == 1) {
        var sel_price_apply = document.getElementById('js-order-method-price-apply').checked;
        var sel_price_all = document.getElementById('js-order-method-price-all').checked;
        var sel_vendor_apply = document.getElementById('js-order-method-vendor-apply').checked;
        var sel_vendor_all = document.getElementById('js-order-method-vendor-all').checked;
        
        if (!sel_price_apply && !sel_price_all) {
            alert('Vui lòng chọn hình thức áp dụng mức giá cho đơn hàng');
            return false;
        }
        else {
            if (program == 0) {
                if (!sel_vendor_apply && !sel_vendor_all) {
                    alert('Vui lòng chọn hình thức áp dụng theo nhà cung cấp cho đơn hàng');
                    return false;
                }
                
                if (sel_vendor_apply) {
                    if ($('#js-order-method-vendor-select').val() < 1) {
                            alert('Vui lòng chọn nhà cung cấp áp dụng cho đơn hàng');
                        return false;
                    }
                }
            }
        }
        
        if (sel_price_apply) {
            //validate price
            var objPrice = $('#js-apply-order-price');
            var priceFrom = objPrice.find('.js-price-from').val()*1;
            var priceTo = objPrice.find('.js-price-to').val()*1;
            if (priceTo == 0 && priceFrom == 0) {
                alert('Vui lòng nhập giá cho sản phẩm');
                return false;
            }
            if (priceFrom > priceTo && priceFrom != 0) {
                alert('Giá áp dụng không hợp lệ');
                return false;
            }
        }
    }
    else {
        var total_product = $('#js-has-product').val();
        var sel_price_apply = document.getElementById('js-method-price-apply').checked;
        
        if (total_product < 1 && !sel_price_apply) {
            alert('Vui lòng chọn hình thức áp dụng cho sản phẩm');
            return false;
        }
        
        if (sel_price_apply) {
            //validate price
            var objPrice = $('#js-apply-product-price');
            var priceFrom = objPrice.find('.js-price-from').val()*1;
            var priceTo = objPrice.find('.js-price-to').val()*1;
            if (priceTo == 0 && priceFrom == 0) {
                alert('Vui lòng nhập giá cho sản phẩm');
                return false;
            }
            
            if (priceFrom > priceTo && priceTo != 0) {
                alert('Giá áp dụng không hợp lệ');
                return false;
            }
        }
        
        if (program == 0) {
            var sel_vendor_apply = document.getElementById('js-method-vendor-apply').checked;
            
            if (sel_vendor_apply) {
                if ($('#js-method-vendor-select').val() < 1) {
                    alert('Vui lòng chọn nhà cung cấp áp dụng');
                    return false;
                }
            }
        }
    }
}

function changeDelivery()
{
    $('#js-delivery-free').change(function(){
        if (this.checked == true) {
            //uncheck other
            var other = document.getElementById('js-delivery-discount-check');
            other.checked = false;
        }
    });
    $('#js-delivery-discount-check').change(function(){
        if (this.checked == true) {
            //uncheck other
            var other = document.getElementById('js-delivery-free');
            other.checked = false;
        }
    });
}

function initLoadAdsMarketing()
{
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=marketing.loadAdsByVendor';
    
    //Lọc theo nhà cung cấp (nếu có) - ưu tiên nhà cung cấp phía trên trước rồi đến nhà cung cấp bên dưới điều kiện
    var vendor_id = -1;
    var program  = $('#js-select-program').val()*1;
    var apply  = $('#js-select-obj-apply').val()*1;
    if (program == 1) {
        vendor_id = $('#select-list-vendor').val()*1;
    }
    else {
        var sel_vendor_apply = false;
        if (apply == 1) {
            sel_vendor_apply = document.getElementById('js-order-method-vendor-apply').checked;
            if (sel_vendor_apply) {
                vendor_id = $('#js-order-method-vendor-select').val()*1;
            }
        }
        else {
            sel_vendor_apply = document.getElementById('js-method-vendor-apply').checked;
            if (sel_vendor_apply) {
                vendor_id = $('#js-method-vendor-select').val()*1;
            }
        }
    }
    
    if (vendor_id > 0) {
        sParams += '&val[vendor_id]=' + vendor_id;
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
            alert('Lỗi hệ thống');
            
        },
        success: function (result) {
            var data = {};
            if(isset(result.status) && result.status == 'success') {
                data = result.data
            }
            var html = '';
            html += '<option value="-1">Chọn chương trình</option>';
            for (var i in data) {
                html += '<option value="'+ data[i].id +'">'+ data[i].name +'</option>';
            }
            $('#js-ads-marketing').html(html);
        }
    });
}

function initLoadMarketing()
{
    $('.js-date-time').datetimepicker({
        timepicker:false,
        format:'Y/m/d'
    });
    /*  Thay đổi giữa 2 hình thức: Sản phẩm / Đơn hàng */
    var oSlectObjApply = $('#js-select-obj-apply');
    if (oSlectObjApply.val() == 1) {
        $('#js-product-apply-method').addClass('none');
        $('#js-order-apply-method').removeClass('none');
        $('.js-order-method-apply').removeClass('none');
    }
    else {
        $('.js-order-method-apply').addClass('none');
        $('#js-order-apply-method').addClass('none');
        $('#js-product-apply-method').removeClass('none');
    }
    oSlectObjApply.change(function(){
        initLoadAdsMarketing();
        var val = $(this).val()*1;
        if (val == 1) {
            $('.js-order-method-apply').removeClass('none');
            $('#js-product-apply-method').addClass('none');
            $('#js-order-apply-method').removeClass('none');
        }
        else {
            $('.js-order-method-apply').addClass('none');
            $('#js-order-apply-method').addClass('none');
            $('#js-product-apply-method').removeClass('none');
        }
    })
    
    $('#js-btn-submit').unbind('click').click(function(){
        //$(this).unbind('click');
        $('#frm_add').submit();
    });
    
    if ($('#js-select-program').val() == 1) {
        $('#js-list-vendor').removeClass('none');
        //hide for vendor method
        $('#js-conds-vendor').addClass('none');
        $('#js-conds-order-vendor').addClass('none');
    }
    else {
        $('#js-list-vendor').addClass('none');
        
        $('#js-conds-vendor').removeClass('none');
        $('#js-conds-order-vendor').removeClass('none');
    }
    
    $('#js-select-program').change(function(){
        initLoadAdsMarketing();
        if ($(this).val() == 1) {
            $('#js-list-vendor').removeClass('none');
            //hide for vendor method
            $('#js-conds-vendor').addClass('none');
            $('#js-conds-order-vendor').addClass('none');
        }
        else {
            $('#js-list-vendor').addClass('none');
            $('#js-conds-vendor').removeClass('none');
            $('#js-conds-order-vendor').removeClass('none');
        }
    });
    
    $('#select-list-vendor').change(function(){
        initLoadAdsMarketing();
    });
    $('#js-method-vendor-select').change(function(){
        initLoadAdsMarketing();
    });
    $('#js-order-method-vendor-select').change(function(){
        initLoadAdsMarketing();
    });
    $('#js-method-vendor-apply').change(function(){
        var hasChecked = this.checked;
        var valChecked = $('#js-method-vendor-select').val()*1;
        if (valChecked > 0) {
            initLoadAdsMarketing();
        }
    });
    
    $('#js-order-method-vendor-apply').change(function(){
        var hasChecked = this.checked;
        var valChecked = $('#js-order-method-vendor-select').val()*1;
        if (valChecked > 0) {
            initLoadAdsMarketing();
        }
    });
    
    
    
    changeDelivery();
    
    $('.js-date-time').change(function(){
        var price = $('#js-pos-price').val();
        var id = this.id;
        var bFlag1 = false;
        var bFlag2 = false;
        var start_time = '';
        var end_time = '';
        if (id == 'js-start-time') {
            end_time = $('#js-end-time').val();
            if (end_time != '') {
                start_time = $(this).val();
                bFlag1 = true;
            }
        }
        else if(id == 'js-end-time') {
            start_time = $('#js-start-time').val();
            if (start_time == '') {
                alert('Chọn thời gian bắt đầu trước!');
                $('#js-start-time').focus();
                return false;
            }
            else {
                bFlag2 = true;
                end_time = $(this).val();
            }
        }
        
        
        if (bFlag1) {
            start_time = new Date(start_time);
            end_time = new Date(end_time);
            
            var offset = end_time.getTime() - start_time.getTime();
            if (offset < 0) {
                alert('Thời gian bắt đầu không thể lớn hơn thời gian kết thúc');
                $('#js-start-time').focus();
                return;
            }
            var totalDays = Math.round(offset / 1000 / 60 / 60 / 24) + 1;
            $('#js-total-amount').val(currencyFormat(totalDays*price));
            $('#js-amount-val').val(totalDays*price);
            
        }
        else if (bFlag2) {
            start_time = new Date(start_time);
            end_time = new Date(end_time);
            
            var offset = end_time.getTime() - start_time.getTime();
            if (offset < 0) {
                alert('Thời gian bắt đầu không thể lớn hơn thời gian kết thúc');
                $('#js-end-time').focus();
                return;
            }
            var totalDays = Math.round(offset / 1000 / 60 / 60 / 24) + 1;
            $('#js-total-amount').val(currencyFormat(totalDays*price));
            $('#js-amount-val').val(totalDays*price);
            
        }
    });
}

$(function(){
    /*  Thêm hình thức áp dụng của sản phẩm */
    $('.js-add-apply-product').unbind('click').click(function(){
        /*  Content rỗng */
        var  content = '<div class="container js-popup-apply-product pad20">';
                content += '<div class="content-box panel-shadow">';
                    content += '<div class="box-title">Hình thức áp dụng cho Sản phẩm<span class="fa fa-close js-close-product-method right"></span></div>';
                    content += '<div class="box-inner">';
                        content += '<div class="row30 padbot10 line-bottom">';
                            content += '<div class="col6 padlr10">';
                                content += '<div class="row30">';
                                    content += '<div class="row20 sub-blue-title">Chọn sản phẩm</div>';
                                    content += '<input type="text" class="acr2 js-search-pr-martketing" value="" id="js-keyword-sr-product-mk" data-type="product" placeholder="Nhập tên sản phẩm">';
                                content += '</div>';
                                content += '<div class="row30">';
                                    content += '<div class="row20 sub-blue-title">Danh sách sản phẩm được chọn</div>';
                                    content += '<div id="js-list-result-product">\
                                                    <div class="info-pr mgbt10 js-result-product" id="js-result-sr-row-1">\
                                                        <div class="col3">\
                                                            <input type="hidden" class="js-input-add-pr" data-state="0">\
                                                            <img src="" alt=""  class="info-pr-img"/>\
                                                        </div>\
                                                        <div class="col9">\
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
                                                                <div class="col4">\
                                                                    <span class="sub-black-title">Số lượng</span>\
                                                                </div>\
                                                                <div class="col4">\
                                                                    <input type="text" class="input-quantity" value="1"/>\
                                                                </div>\
                                                                <div class="col4">\
                                                                    <div class="close-pr-detail">\
                                                                        <span data-id="1" class="icon-close fa fa-close js-remove-product-sr" title="Bỏ sản phẩm này"></span>\
                                                                    </div>\
                                                                </div>\
                                                            </div>\
                                                            <input type="hidden" id="js-has-product" value="0"/>\
                                                            <div class="clear"></div>\
                                                        </div>\
                                                        <div class="clear"></div>\
                                                    </div>';
                                    content += '</div>';
                                    content += '<div class="list-result-pr-martketing js-scroll none"></div>';
                                content += '</div>';
                            content += '</div>';
                            content += '<div class="col6 padlr10">';
                                content += '<div class="row30">';
                                    content += '<div class="row20 sub-blue-title">Điều kiện sản phẩm</div>';
                                content += '</div>';
                                content += '<div class="row30">';
                                    content += '<div class="col5 padright10">';
                                        content += '<label class="label-custom">';
                                        content += '<input type="checkbox" name="" id="">Áp dụng cho siệu thị';
                                        content += '</label>';
                                    content += '</div>';
                                    content += '<div class="col5 padright10">';
                                        content += '<select id="" class="">';
                                        content += '<option value="">Big C</option>';
                                        content += '<option value="">Skymart</option>';
                                        content += '<option value="">79Mart</option>';
                                        content += '</select>';
                                    content += '</div>';
                                    content += '';
                                content += '</div>';
                                content += '<div class="">';
                                content += '</div>';
                            content += '</div>';
                        content += '</div>';
                        content += '<div class="">';
                        content += '</div>';
                        
                        content += '<div class="row30 padbot10">';
                            content += '<div class="col4"></div>';
                            content += '<div class="col4">';
                                content += '<div class="col6 padright10">';
                                    content += '<div class="button-default js-close-product-method">Hủy</div>';
                                content += '</div>';
                                content += '<div class="col6 padleft10">';
                                    content += '<div class="button-blue js-apply-product-method">Áp dụng</div>';
                                content += '</div>';
                            content += '</div>';
                            content += '<div class="col4"></div>';
                        content += '</div>';
                    content += '</div>';
                content += '</div>';
            content += '</div>';
        insertPopupCrm(content, ['.js-close-product-method'], '.js-popup-apply-product', true);
        scrollMenu();
        initSearchProductMartketing();
    });

    /*  Thêm hình hình áp dụng của đơn hàng */
    $('.js-add-apply-order').unbind('click').click(function(){
        /*  Content mẫu */
        var content = '<div class="container page-marketing-apply page-marketing-apply-order"><div class="content-box panel-shadow"><div class="box-title title-blue">Áp dụng giá tiền</div><div class="box-inner"><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Giá trị</div><div class="row20"><div class="col4"><select name=""><option value="1">&lt;=</option><option value="2">&gt;=</option></select></div><div class="col8 padleft10"><input type="text"></div></div></div><div class="col6 padleft10"><div class="row20 sub-black-title">Giá trị</div><div class="row20"><div class="col4"><select name=""><option value="1">&lt;=</option><option value="2">&gt;=</option></select></div><div class="col8 padleft10"><input type="text"></div></div></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Tặng tiền</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Tặng tiền</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Giảm giá</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row20 mgbt20"><div class="col6 padright10"><div class="row20 sub-black-title">Phí vận chuyển</div><input type="text"></div><div class="col6 padleft10"><div class="row20 sub-black-title">Đơn vị</div><select name=""><option value="1">Chọn giá trị</option><option value="2">Chọn giá trị</option></select></div></div><div class="row30"><div class="col6"></div><div class="col3 padright10"><div class="button-default js-cancel-apply-order">Hủy</div></div><div class="col3 padleft10"><div class="button-blue js-submit-apply-order">Áp dụng</div></div></div></div></div></div>';
        insertPopupCrm(content, ['.js-cancel-apply-order', '.js-submit-apply-order'], '.page-marketing-apply-order', true);
        $('.page-marketing-apply-order .js-submit-apply-order').click(function(){
            /*  Content mẫu */
            var contentItem = '<div class="item-apply-product padbot10 mgbt10"><div class="row30"><div class="col10"><div class="col4"><div class="sub-black-title row20">Giá trị:</div></div><div class="col8"><div class="sub-black-title row20">>= 1.000.000</div><div class="sub-black-title row20"><= 10.000.000</div></div></div><div class="col2"><span class="fa fa-close right icon-wh js-delete-apply-item"></span></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Tặng tiền</div></div><div class="col6"><input type="text"></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Giảm giá</div></div><div class="col6"><input type="text"></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Voucher</div></div><div class="col6"><input type="text"></div></div><div class="row20"><div class="col6"><div class="sub-black-title">Phí vận chuyển</div></div><div class="col6"><input type="text"></div></div></div>';
            $('.list-apply-product[type="order"]').append(contentItem);
        });
    });
    
    
    initLoadMarketing();
    removeSelectProductMk();
    scrollMenu();
    initSearchProductMartketing()
    initInputApplyMethod();
    initInputMarketing();
});

function initSearchProductMartketing()
{
    $('#js-keyword-sr-product-mk').keyup(function(e) {
            var pvalue = $(this).val();
            if (!empty(pvalue)) {
                $('.list-result-pr-martketing').fadeIn();
                $('.list-result-pr-martketing').addClass('js-button-wait');
            }
            switch (e.keyCode) {
                case 37:
                case 38:
                case 39:
                case 40:
                    break;
                case 13:
                    if(!empty(pvalue)){
                        searchProductMk(pvalue);
                    }
                    break;
                default:
                    if(!empty(pvalue)){
                        clearTimeout(k_oTimer);
                        k_oTimer = setTimeout(function(){
                            searchProductMk(pvalue);
                        }, 500);
                    }
                    break;
            }
            if(empty(pvalue)){
                $('.list-result-pr-martketing').fadeOut();
                $('.list-result-pr-martketing').removeClass('js-button-wait');
            }
        });
}
function searchProductMk(keyword){
    vendor = $('#js-select-market').val();
    category_id = $('#js-select-category').val();
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.search';
    sParams += '&val[q]=' + encodeURIComponent(keyword);
    
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
    
    //Lọc theo nhà cung cấp (nếu có) - ưu tiên nhà cung cấp phía trên trước rồi đến nhà cung cấp bên dưới điều kiện
    var vendor_id = -1;
    var program  = $('#js-select-program').val()*1;
    if (program == 1) {
        vendor_id = $('#select-list-vendor').val()*1;
    }
    else {
        var sel_vendor_apply = document.getElementById('js-method-vendor-apply').checked;
        if (sel_vendor_apply) {
            vendor_id = $('#js-method-vendor-select').val()*1;
        }
    }
    
    if (vendor_id > 0) {
        sParams += '&val[vendor]=' + vendor_id;
    }
    
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
            $('.list-result-pr-martketing').removeClass('js-button-wait');
            if (result.status == 'error') {
                content = '<span class="err">'+result.message+'</span>'; 
                $('.list-result-pr-martketing .mCSB_container').html(content);
                setTimeout(function(){
                    $('.list-result-pr-martketing').fadeOut();
                }, 2000);
                return false;
            }
            else {
                var content = '';
                data = result.data;
                for (var i in data) {
                    content += '<div class="item-sp-sg"\
                        data-id="'          + data[i].id+'"\
                        data-sku="'          + data[i].sku+'"\
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
                $('.list-result-pr-martketing .mCSB_container').html(content);
                initSelectSearchProductMK();
            }
        }
    });
}
function initSelectSearchProductMK(){
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
                                <div class="col3">\
                                    <input type="hidden" class="js-input-add-pr" data-state="0">\
                                    <img src="" alt=""  class="info-pr-img-mk"/>\
                                </div>\
                                <div class="col9">\
                                    <div class="row20">\
                                        <a class="info-pr-name sub-black-title">Tên sản phẩm...</a>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col3">\
                                            <span class="sub-black-title">Siêu thị:</span>\
                                        </div>\
                                        <span class="col9 info-pr-vendor"></span>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col3">\
                                            <span class="sub-black-title">Giá tiền:</span>\
                                        </div>\
                                        <span class="col3 info-pr-price">...</span>\
                                        <div class="col3">\
                                            <span class="sub-black-title">Giá xu:</span>\
                                        </div>\
                                        <span class="col3 info-pr-coin">...</span>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col6">\
                                            <span class="sub-black-title">Số lượng áp dụng</span>\
                                        </div>\
                                        <div class="col4">\
                                            <input type="text" name="val[apply_product]['+hasProduct+'][quantity]" class="input-quantity js-input-number" value="1"/>\
                                        </div>\
                                        <div class="close-pr-detail">\
                                            <span data-id="'+ hasProduct +'" class="icon-close fa fa-close js-remove-product-sr" title="Bỏ sản phẩm này"></span>\
                                        </div>\
                                    </div>\
                                    <div class="clear"></div>\
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
        
        objSelect.find('.info-pr-img-mk').attr('src',                   obj.attr('data-src'));
        objSelect.find('.info-pr-name').attr('href',                 obj.attr('data-href'));
        objSelect.find('.info-pr-name').html(                        obj.attr('data-name'));
        objSelect.find('.info-pr-price').html(                       currencyFormat(obj.attr('data-price')));
        objSelect.find('.info-pr-price').attr('data-money',          obj.attr('data-price'));
        objSelect.find('.info-pr-coin').html(                        currencyFormat(obj.attr('data-coin')));
        objSelect.find('.info-pr-coin').attr('data-money',           obj.attr('data-coin'));
        objSelect.find('.info-pr-vendor').html(                      obj.attr('data-vendor'));
        objSelect.find('.input-net-weight').val(obj.attr('data-unit-value'));
        
        // Tạo thẻ inpunt ẩn gửi dữ liệu lên
        var html_input = '<input type="hidden" name="val[apply_product]['+hasProduct+'][article_id]" value="'+obj.attr('data-id')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][name]" value="'+obj.attr('data-name')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][sku]" value="'+obj.attr('data-sku')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][vendor_id]" value="'+obj.attr('data-vendor-id')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][vendor_name]" value="'+obj.attr('data-vendor')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][price_sell]" value="'+obj.attr('data-price')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][price_coin]" value="'+obj.attr('data-coin')+'">';
        html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][image_path]" value="'+obj.attr('data-src')+'">';
        //html_input += '<input type="hidden" name="val[apply_product]['+hasProduct+'][quantity]" value="1">';
        objSelect.append(html_input);
        
        $('.list-result-pr-martketing').fadeOut();
        $('#js-keyword-sr-product-mk').val('');
        removeSelectProductMk();
        initInputMarketing();
    })
}

function removeSelectProductMk()
{
    $('.js-remove-product-sr').click(function(){
        var id = $(this).data('id')*1;
        if (id > 0) {
            $('#js-result-sr-row-' + id).remove();
            
            //Kiểm tra danh sách sản phẩm có bị xóa hết ko
            var total = $('.js-result-product').length;
            if (total < 1) {
                hasProduct = 1;
                var content = '<div class="info-pr mgbt10 js-result-product" id="js-result-sr-row-'+ hasProduct + '">\
                                <div class="col3">\
                                    <input type="hidden" class="js-input-add-pr" data-state="0">\
                                    <img src="" alt=""  class="info-pr-img-mk"/>\
                                </div>\
                                <div class="col9">\
                                    <div class="row20">\
                                        <a class="info-pr-name sub-black-title">Tên sản phẩm...</a>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col3">\
                                            <span class="sub-black-title">Siêu thị:</span>\
                                        </div>\
                                        <span class="col9 info-pr-vendor"></span>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col3">\
                                            <span class="sub-black-title">Giá tiền:</span>\
                                        </div>\
                                        <span class="col3 info-pr-price">...</span>\
                                        <div class="col3">\
                                            <span class="sub-black-title">Giá xu:</span>\
                                        </div>\
                                        <span class="col3 info-pr-coin">...</span>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col6">\
                                            <span class="sub-black-title">Số lượng áp dụng</span>\
                                        </div>\
                                        <div class="col4">\
                                            <input type="text" name="val[apply_product]['+hasProduct+'][quantity]" class="input-quantity js-input-number" value="1"/>\
                                        </div>\
                                        <div class="close-pr-detail">\
                                            <span data-id="'+ hasProduct +'" class="icon-close fa fa-close js-remove-product-sr" title="Bỏ sản phẩm này"></span>\
                                        </div>\
                                    </div>\
                                    <div class="clear"></div>\
                                </div>\
                                <div class="clear"></div>\
                            </div>';
                $('#js-list-result-product').append(content);
                $('#js-has-product').val(0);
            }
        }
    });
}

function initInputApplyMethod()
{
    //Product method
    if (document.getElementById("js-method-vendor-apply").checked == true) {
        $('#js-method-vendor-select').removeAttr('disabled');
    }
    else {
        $('#js-method-vendor-select').attr('disabled', 'disabled');
    }
    
    if (document.getElementById("js-method-price-apply").checked == true) {
        $('.js-method-price-select').removeAttr('disabled');
    }
    else {
        $('.js-method-price-select').attr('disabled', 'disabled');
    }
    
    $('#js-method-vendor-apply').change(function(){
        if (this.checked == true) {
            $('#js-method-vendor-select').removeAttr('disabled');
        }
        else {
            $('#js-method-vendor-select').attr('disabled', 'disabled');
        }
    });
    
    $('#js-method-price-apply').change(function(){
        if (this.checked == true) {
            $('.js-method-price-select').removeAttr('disabled');
        }
        else {
            $('.js-method-price-select').attr('disabled', 'disabled');
        }
    });
    
    //Order method
    if (document.getElementById("js-order-method-vendor-apply").checked == true) {
        $('#js-order-method-vendor-select').removeAttr('disabled');
    }
    else {
        $('#js-order-method-vendor-select').attr('disabled', 'disabled');
    }
    
    if (document.getElementById("js-order-method-price-apply").checked == true) {
        $('.js-order-method-price-select').removeAttr('disabled');
    }
    else {
        $('.js-order-method-price-select').attr('disabled', 'disabled');
    }
    
    $('#js-order-method-vendor-apply').change(function(){
        if (this.checked == true) {
            var other = document.getElementById('js-order-method-vendor-all');
            other.checked = false;
            $('#js-order-method-vendor-select').removeAttr('disabled');
        }
        else {
            $('#js-order-method-vendor-select').attr('disabled', 'disabled');
        }
    });
    
    $('#js-order-method-price-apply').change(function(){
        if (this.checked == true) {
            var other = document.getElementById('js-order-method-price-all');
            other.checked = false;
            
            $('.js-order-method-price-select').removeAttr('disabled');
        }
        else {
            $('.js-order-method-price-select').attr('disabled', 'disabled');
        }
    });
    
    $('#js-order-method-price-all').change(function(){
        if (this.checked == true) {
            var other = document.getElementById('js-order-method-price-apply');
            other.checked = false;
            $('#js-order-method-price-apply').change();
        }
    });
    
    $('#js-order-method-vendor-all').change(function(){
        if (this.checked == true) {
            var other = document.getElementById('js-order-method-vendor-apply');
            other.checked = false;
            $('#js-order-method-vendor-apply').change();
        }
    });
}

function initInputMarketing()
{
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57);
    $('.js-input-number').keydown(function(e){
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
    
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57,190);
    $('.js-input-float').keydown(function(e){
        var val = $(this).val();
        if (val.indexOf('.') != -1 && e.keyCode == 190) {
            return false;
        }
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
}