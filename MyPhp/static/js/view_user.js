$(function(){
    $('.js-edit-user').click(function(){
        var id = $('#js-user-id').val();
        id = id*1;
        if (id > 0) {
            showPopupAddUser(id);
        } 
        
    });
    
    $('.js-recharge').click(function(){
        var id = $('#js-user-id').val();
        id = id*1;
        if (id > 0) {
            showPopupRecharge(id);
        } 
        
    });
    
    $('.js-close-view-user').click(function(){
        var type = $(this).data('type');
        if (typeof(type) !='undefined' && !empty(type)) {
            window.location = '/user/?type=' + type;
        }
    });
});

function showPopupAddUser(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.getBlockUser' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-cancel-user-edit'], '.page-user-edit', true);
            initAddUser();
        }
    });
}

function initAddUser()
{
    initSubmitAddUser();
    
    $('#js-country').change(function(){
        var val = $(this).val();
        loadCities(val);
    });
    
    $('#js-city').change(function(){
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
}

function initSubmitAddUser()
{
    $('#js-submit-add-user').click(function(){
        var action = "$(this).ajaxSiteCall('user.addUser', 'afterAddUser(data)'); return false;";
        $('#frm_add_user').attr('onsubmit', action);
        $('#frm_add_user').submit();
        $('#frm_add_user').removeAttr('onsubmit');
        $('#js-submit-add-user').unbind('click');
        return false;
    });
}

function loadCities(value)
{
    value =value*1;
    
    if (value < 1) {
        return false;
    }
    
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=area.loadCities' + '&val[id]=' + value;
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
            if(isset(result.status) && result.status == 'success') {
                var data = result.data;
                if (typeof(data) != 'undefined') {
                    var html = '<option value="0">Chọn Tỉnh thành</option>';
                    for (i in data) {
                        html += '<option value="'+ data[i]['id'] +'">' + data[i]['name'] +'</option>';
                    }
                    $('#js-city').html(html);
                }
            }
            else {
                var html = '<option value="0">Chọn Tỉnh thành</option>';
                $('#js-city').html(html);
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
        $('#js-district').empty();
        $('#js-district').append(html);
    }
}

function afterAddUser(data)
{
    if (typeof(data) == 'object' && typeof(data.status) != 'undefined') {
        if (data.status == 'success') {
            //reload page
            window.location.reload();
        }
        else {
            if (typeof(data.data.error) != 'undefined' && !empty(data.data.error)) {
                //add notice error
                var html = '';
                html += '<div class="row30 dialog-err">Đã có lỗi xảy ra</div>';
                for(i in data.data.error) {
                    html += '<div class="row30">' + data.data.error[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                initSubmitAddUser();
                return false;
            }
            else {
                alert('Đã có lỗi xảy ra');
                initSubmitAddUser();
            }
        }
    }
    return false;
}

function initRecharge()
{
    initSbmRecharge();
}

function initSbmRecharge()
{
    $('#js-submit-recharge').click(function(){
        var action = "$(this).ajaxSiteCall('user.recharge', 'afterRecharge(data)'); return false;";
        $('#frm_recharge').attr('onsubmit', action);
        $('#frm_recharge').submit();
        $('#frm_recharge').removeAttr('onsubmit');
        $('#js-submit-recharge').unbind('click');
        return false;
    });
}

function showPopupRecharge(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.getBlockRecharge' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-cancel-recharge'], '.page-recharge', true);
            initRecharge();
            ValidateKey();
        }
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

function afterRecharge(data)
{
    if (typeof(data) == 'object' && typeof(data.status) != 'undefined') {
        if (data.status == 'success') {
            //reload page
            alert('Thao tác hành công! Đang chờ duyệt để hoàn tất nạp tiền');
            $('#js-close-recharge').click();
            //window.location.reload();
        }
        else {
            if (typeof(data.message) != 'undefined' && !empty(data.message)) {
                //add notice error
                var html = '';
                html += '<div class="row30 dialog-err">Đã có lỗi xảy ra</div>';
                for(i in data.message) {
                    html += '<div class="row30">' + data.message[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                initSbmRecharge();
                return false;
            }
            else {
                alert('Đã có lỗi xảy ra');
                initSbmRecharge();
            }
        }
    }
    return false;
}