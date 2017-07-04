var waitTime = 100;
var oTimer = '';
var aUser = null;
var aCurrentUser = null;
var s_key = 0;

function selectItem()
{
    $('#list_item table tbody tr').click(function(){
        killRequest();
        var key = $(this).attr('rel');
        aCurrentUser = aUser[key];
        
        $('#js-user-id').val(aUser[key].id);
        $('#user_fullname').val(aUser[key].fullname);
        $('#username').val(aUser[key].username);
        $('#email').val(aUser[key].email);
        $('#phone_number').val(aUser[key].phone_number);
        $('#list_item').html('').hide();
        $('#is_pro').val(1);
        initPermission();
    });
}

function initPermission()
{
    var store_id = $('#js-warehouse').val();
    store_id = store_id*1;
    if (aCurrentUser != null) {
        if (store_id > 0 && isset(aCurrentUser.permission[store_id]) && !empty(aCurrentUser.permission[store_id])) {
            var aPermission = aCurrentUser.permission[store_id];
            $('.js-permission').each(function(){
                var flag = 0;
                var key = $(this).data('key');
                for (var i in aPermission) {
                    if (i == key && aPermission[i] == 1) {
                        flag = 1;
                        break;
                    }
                }
                if (flag == 1) {
                    $(this).addClass('md-checked');
                }
                else {
                    $(this).removeClass('md-checked');
                }
            });
            return;
        }
    }
    
    resetPermission();
}

function resetPermission()
{
    $('.js-permission').each(function(){
       $(this).removeClass('md-checked');
    });
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
    aParams['store_permission'] = 1;
    var html = '<div class="row30 mgbt20">Loading ...</div>';
    $('#list_item').html(html).show();
    addRequest(aParams, 'store.searchUser', 'POST', 'json', 'displayResults()');
}

function searchByUserName(value)
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
    aParams['sType'] = 'username';
    aParams['store_permission'] = 1;
    var html = '<div class="row30 mgbt20">Loading ...</div>';
    $('#list_item').html(html).show();
    addRequest(aParams, 'store.searchUser', 'POST', 'json', 'displayResults()');
}

function searchByEmail(value)
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
    aParams['sType'] = 'email';
    aParams['store_permission'] = 1;
    var html = '<div class="row30 mgbt20">Loading ...</div>';
    $('#list_item').html(html).show();
    addRequest(aParams, 'store.searchUser', 'POST', 'json', 'displayResults()');
}

function searchByPhone(value)
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
    aParams['sType'] = 'phone_number';
    aParams['store_permission'] = 1;
    var html = '<div class="row30 mgbt20">Loading ...</div>';
    $('#list_item').html(html).show();
    addRequest(aParams, 'store.searchUser', 'POST', 'json', 'displayResults()');
}
function displayResults()
{
    if(result)
    {
        aUser = result.data;
        var sHtml = '';
        if(aUser.length > 0)
        {
            sHtml += '<table class=\"table table-hover general-table\"><thead><tr><th>Mã thành viên</th><th>Tên thành viên</th><th>Tên đăng nhập</th><th>Hộp thư</th><th>Số điện thoại</th></tr></thead><tbody>';
            for(var key in aUser)
            {
                sHtml += '<tr rel=\"'+key+'\"><td>'+aUser[key]['code']+'</td><td>'+aUser[key]['fullname']+'</td><td>'+aUser[key]['username']+'</td><td>'+aUser[key]['email']+'</td><td>'+aUser[key]['phone_number']+'</td></tr>';  
            }
            sHtml += '</tbody></table>';
            $('#list_item').html(sHtml).show();
            selectItem();
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
    
    //user_fullname
    $('#user_fullname').keyup(function(e){
        clearTimeout(oTimer);
        oTimer = setTimeout(function(){
            var pvalue = $("#user_fullname").val();
            searchByName(pvalue);
        }, waitTime);
    });
    
    //username
    $('#username').keyup(function(e){
        clearTimeout(oTimer);
        oTimer = setTimeout(function(){
            var pvalue = $("#username").val();
            searchByUserName(pvalue);
        }, waitTime);
    });
    
    //email
    $('#email').keyup(function(e){
        clearTimeout(oTimer);
        oTimer = setTimeout(function(){
            var pvalue = $("#email").val();
            searchByEmail(pvalue);
        }, waitTime);
    });
    
    //phone_number
    $('#phone_number').keyup(function(e){
        clearTimeout(oTimer);
        oTimer = setTimeout(function(){
            var pvalue = $("#phone_number").val();
            searchByPhone(pvalue);
        }, waitTime);
    });
    
    //change Store
    $('#js-warehouse').change(function(){
        initPermission();
    });
});