var waitTime = 100;
var oTimer = '';
var aProduct = null;
var s_key = 0;
function getQuantitative(key)
{
    if(isset(aProduct[key]['quantitative']) && !empty(aProduct[key]['quantitative']))
    {
        s_key = key;
        $('#don_vi_luu_kho').empty();
        $('#has_quantitive').val(1);
        for(var dl in aProduct[key]['quantitative'])
        {
            //'#don_vi_luu_kho').append('<option value="'+dl.id+'">'+dl.dvt_name+'</option>');
            $('#don_vi_luu_kho').append('<option value="'+aProduct[key]['quantitative'][dl].id+'">'+aProduct[key]['quantitative'][dl].dvt_name+'</option>');
        }
    }
    else
    {
        $('#don_vi_luu_kho').empty().append('<option selected="selected" value="'+aProduct[key]['detail']['dvt_id']+'">'+aProduct[key]['detail']['dvt_name']+'</option>');
        $('#has_quantitive').attr('value', 0);
    }
    initSelectQuantitative();
    return false;
}
function selectItem()
{
    $('#list_item table tbody tr').click(function(){
        killRequest();
        var key = $(this).attr('rel');
        getQuantitative(key);
        $('#product_code').val(aProduct[key].sku);
        $('#product_name').val(aProduct[key].detail.name);
        $('#product_unit').val(aProduct[key].detail.dvt_id);
        $('#product_unit_name').val(aProduct[key].detail.dvt_name);
        $('#hidden_price').val(aProduct[key].price_sell);
        $('#product_price').val(aProduct[key].price_sell);
        $('#list_item').html('').hide();
        $('#is_pro').val(1);
    });
}
function initSelectQuantitative()
{
    $('#don_vi_luu_kho').change(function(){
         //$.ajaxCall('store.changeQuantitative', 'qid='+ encodeURIComponent($(this).val()));
         
    });
}
function checkAmount(value)
{
    var price = $('#hidden_price').val();
    if(value == ''){
        $('#product_price').val('');
        $('#product_amount').val('');
    }else{
        if(!IsNumeric(value)){
           $('#product_amount').val(value.substring(0, value.length - 1));
        }
    }
    var new_v = $('#product_amount').val();
    // tính số lượng sản phẩm thực lưu vào kho.
    var has_q = $('#has_quantitive').val();
    var count = 0;
    console.log(has_q);
    if(has_q == 0){
        count = new_v;
        $('#amount_change').val(new_v);
    }else{
        var i_select = $('#don_vi_luu_kho').val();
        var t_index = 0;
        for(var dl in aProduct[s_key]['quantitative']){
            /*
            if(dl.id == i_select){
                t_index = dl;
            } */
            if(aProduct[s_key]['quantitative'][dl].id == i_select){
                t_index = dl;
            }
        }
        count = new_v * aProduct[s_key]['quantitative'][t_index]['']
    }
    // tính lại giá nhập.
    var t_price = count * price;
    $('#product_price').val(t_price);
}
function canculatePrice()
{
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57);
    $('#product_amount').keydown(function(e){
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
    $('#product_amount').keyup(function(e){
        var value = $(this).val();
        checkAmount(value);
    });
}
function searchByCode(value)
{
    if (empty(value))
    {
        hideResults('#list_item');
        killRequest();
        return;
    }
    killRequest();
    
    $('#list_item').show();
    var aParams = new Array();
    aParams['key'] = value;
    aParams['sType'] = 'code';
    var html = '<div class="row30 mgbt20">Loading ...</div>';
    $('#list_item').html(html).show();
    addRequest(aParams, 'store.searchProduct', 'POST', 'json', 'displayResults()');
}
function searchByName(value)
{
    if (empty(value))
    {
        hideResults('#list_item');
        killRequest();
        return;
    }
    killRequest();
    var aParams = new Array();
    aParams['key'] = value;
    aParams['sType'] = 'name';
    var html = '<div class="row30 mgbt20">Loading ...</div>';
    $('#list_item').html(html).show();
    addRequest(aParams, 'store.searchProduct', 'POST', 'json', 'displayResults()');
}
function displayResults()
{
    if(result)
    {
        aProduct = result.data;
        var sHtml = '';
        if(aProduct.length > 0)
        {
            sHtml += '<table class=\"table table-hover general-table\"><thead><tr><th>Mã Vạch</th><th>Mã Hàng</th><th>Tên Hàng</th><th>ĐVT</th><th>Đơn Giá</th></tr></thead><tbody>';
            for(var key in aProduct)
            {
                sHtml += '<tr rel=\"'+key+'\"><td>'+aProduct[key]['sku']+'</td><td>'+aProduct[key]['detail']['code']+'</td><td>'+aProduct[key]['detail']['name']+'</td><td>'+aProduct[key]['detail']['dvt_name']+'</td><td>'+aProduct[key]['price_sell']+'</td></tr>';  
            }
            sHtml += '</tbody></table>';
            $('#list_item').html(sHtml).show();
            selectItem();
            canculatePrice();
        }
        else {
            hideResults('#list_item');
        }
    }
}
function checkValue()
{
    var code = $('#product_code').val();
    if(empty(code)){
        alert('Bạn chưa chọn sản phẩm.');
        return false;
    }
    var amount = $('#product_amount').val();
    if(amount == ''){
        alert('Bạn chưa chọn số lượng.');
        return false;
    }
    return true;
}
function addProduct()
{
    if(checkValue()){
        if($('#is_pro').val() == 1){
            var stt = $('#js-product-list > .box-inner > .row-wh').length + 1;
            //<div class="fa fa-trash icon-wh js-delete-obj"></div> : thẻ xóa row
            $sHtml = '<div class="row30 line-bottom row-wh padtb10">\
                        <input type=\"hidden\" value="'+$('#product_code').val()+'" name="val[product][code][]" />\
                        <input type=\"hidden\" value="'+$('#product_amount').val()+'" name="val[product][amount][]" />\
                        <div class="col-wh-1">'+ stt +'</div>\
                        <div class="col-wh-2">'+ $('#product_code').val() +'</div>\
                        <div class="col-wh-3">'+ $('#product_name').val() +'</div>\
                        <div class="col-wh-4">'+ $('#don_vi_luu_kho option:selected').html() +'</div>\
                        <div class="col-wh-5">'+ $('#product_unit_name').val() +'</div>\
                        <div class="col-wh-6">'+ $('#product_amount').val() +'</div>\
                        <div class="col-wh-7">'+ $('#hidden_price').val() +'</div>\
                        <div class="col-wh-8">'+ $('#product_price').val() +'</div>\
                        <div class="col-wh-9"></div>';
            $('#js-product-list > .box-inner').append($sHtml);
            resetInputForm();
            loadEditItem(); 
            deleteObj();  
        }else{
            alert('Mã sản phẩm không tồn tại trong dữ liệu.');
        }
    }
}
function updateProduct()
{
    if(checkValue()){
        if($('#is_pro').val() == 1){
            $('#product_list table tbody tr').each(function(){
                if($(this).attr('rel') == $('#product_code').val()){
                    var stt = $(this).find("td").eq(1).html();
                    $sHtml = '<td><input type=\"checkbox\" class=\"product_item\" /><input type=\"hidden\" value="'+$('#product_code').val()+'" name="val[product][code][]" /><input type=\"hidden\" value="'+$('#product_amount').val()+'" name="val[product][amount][]" /></td><td>'+stt+'</td><td>'+$('#product_code').val()+'</td><td>'+$('#product_name').val()+'</td><td>'+$('#product_unit').val()+'</td><td>'+$('#product_amount').val()+'</td><td>'+$('#hidden_price').val()+'</td><td>'+$('#product_price').val()+'</td>';
                    $(this).html($sHtml);
                    resetInputForm();
                    loadEditItem();
                }
            });
        }else{
            alert('Mã sản phẩm không tồn tại trong dữ liệu.');
        }
    }
}
function loadEditItem()
{
    $('#product_list table tbody tr').click(function(){
        $('#product_code').val($(this).find("td").eq(2).html());
        $('#product_name').val($(this).find("td").eq(3).html());
        $('#product_unit').val($(this).find("td").eq(4).html());
        $('#product_unit_name').val($(this).find("td").eq(5).html());
        $('#product_amount').val($(this).find("td").eq(6).html());
        $('#hidden_price').val($(this).find("td").eq(6).html());
        $('#product_price').val($(this).find("td").eq(7).html());
        var value = $('#product_amount').val();
        checkAmount(value);
        //$('#add_product').parent().show();
        $('#add_product').show();
        //$('#edit_product').parent().hide();
        $('#edit_product').hide();
    });
}
function resetInputForm()
{
    $('#product_code').val('');
    $('#product_name').val('');
    $('#product_unit').val('');
    $('#product_amount').val('');
    $('#hidden_price').val('');
    $('#product_price').val('');
    $('#amount_change').val('');
    //$('#add_product').parent().show();
    $('#add_product').show();
    //$('#edit_product').parent().hide();
    $('#edit_product').hide();
}

/* ajax call popup add vendor*/
function showPopupAddVendor(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=store.getBlockVendor' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-canel-popup'], '.page-vendor-edit', true);
            initSbmVendor();
        }
    });
}

function afterAddVendor(data)
{
    if (typeof(data.status) != 'undefined')
    {
        if (data.status == 'error') {
            //error
            var i = 0;
            var html = '<div class="row30 padtb10 dialog-err">Đã có lỗi xảy ra</div>';
            for (i in data.message) {
                html += '<div class="row30">' + data.message[i] +'</div>';
            }
            $('#js-notice').html(html);
            initSbmVendor();
            return false;
        }
        else {
            //success
            var html = '<div class="row30 padtb10 dialog-success">Đã tạo thành công</div>';
            $('#js-notice').html(html);
            //add into list vendor
            window.location.reload();
        }
    }
    else {
        //system error
        
    }
}

function initSbmVendor()
{
    $('#js-submit-add-vendor').unbind('click').click(function(){
        var action = "$(this).ajaxSiteCall('store.addVendor', 'afterAddVendor(data)'); return false;";
        $('#js-frm-add').attr('onsubmit', action);
        $('#js-frm-add').submit();
        $('#js-frm-add').removeAttr('onsubmit');
        $('#js-submit-add-vendor').unbind('click');
        return false;
    });
}

function deleteObj()
{
    $('.js-delete-obj').each(function(){
        $(this).click(function(){
            //remove row and re-index table
            console.log('Xóa');
        });
    });
}

$(document).ready(function(e) {
    $('#product_code').keyup(function(e){
        $('#is_pro').val(0);
        clearTimeout(oTimer);
        oTimer = setTimeout(function(){
            var pvalue = $("#product_code").val();
            searchByCode(pvalue);
        }, waitTime);
    });
    $('#product_name').keyup(function(e){
        $('#is_pro').val(0);
        clearTimeout(oTimer);
        oTimer = setTimeout(function(){
            var pvalue = $("#product_name").val();
            searchByName(pvalue);
        }, waitTime);
    });
    $('#add_product').click(function(){
        addProduct();
    });
    $('#edit_product').click(function(){
        updateProduct();
    });
    $('#finish').click(function(){
        $(this).parents().find('form').submit();
    });
    
    $('#js-add-vendor').click(function(){
        showPopupAddVendor(0);
    });
});