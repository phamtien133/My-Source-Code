/** login */
var loginrefer = '';
function checkLoginStatus()
{
    $('.button_submit_log.bt2').attr('disabled', 'disabled');
    // get param from url
    search = location.search;
    search = trim(search, '?');
    param = search.split('&');
    sParams = '';
    if (!empty(param)) {
        for (i in param) {
            tmp = param[i];
            if (typeof(tmp) == 'function') 
                continue;
            tmp = tmp.split('=');
            sParams += '&val['+tmp[0]+']='+ tmp[1];
        }
    }
    sParams += '&'+ getParam('sGlobalTokenName') + '[call]=user.checkLoginStatus';
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
        } ,
        success: function (logindata) {
            if(logindata.status == 'success') {
                if(isset(logindata.show_message)) {
                    $('.content_err').html(logindata.message);
                    $('.err_list_login').removeClass('hide');
                }
                init_login();
            }
            else {
                $('.content_err').html(logindata.message);
                $('.err_list_login').removeClass('hide');
                initCallLogout();
                // disable button login.
                $('#jForm.form_user').unbind('submit').submit(function(){
                    return false;    
                });
                // disable button register.
                $('')
                $('#js-ql-form').unbind('submit').submit(function(){
                    return false;    
                });
                return false;
            }
        }
    });
}
function init_login()
{
    $('.button_submit_log.bt2').removeAttr('disabled');
    $('#jForm.form_user').unbind('submit').submit(function(){
        $('.err_list_login').addClass('hide');
        /*  Kiểm tra điều kiện đăng nhập */
        if(check_login() == false)
            return false;
        // get refer from url if have.
        var search = location.search;
        refer = '';
        if (!empty(search)) {
            match = search.match('refer=([A-Za-z0-9]+)');
            if (!empty(match))
                refer = match[1]
        }


        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.login';
        sParams += '&val[email]=' + encodeURIComponent($('#ten_truy_cap').val());
        sParams += '&val[passwd]=' + encodeURIComponent($('#mat_khau').val());

        sParams += '&val[refer]='+refer;
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
            } ,
            success: function (logindata) {
                loginStatus(logindata);
            }
        });

        return false;
    });
}
function check_login()
{
    if($('#ten_truy_cap').val() == '') {
        $('.content_err').html('Vui lòng điền tên truy cập');   
        $('#ten_truy_cap').focus();
        $('.err_list_login').removeClass('hide');
        return false;
    }
    if($('#mat_khau').val() == '') {
        $('.content_err').html('Vui lòng điền mật khẩu');   
        $('#mat_khau').focus();
        $('.err_list_login').removeClass('hide');
        return false;
    }
    return true;
}
function loginStatus(data)
{
    if(typeof(data) == 'object' && data.type == 'error') {
        $('.content_err').html('Đã có lỗi xảy ra. Vui lòng thử lại');
        $('.err_list_login').removeClass('hide');
        return false;
    }
    if (data['status'] != 'success'){
        $('.content_err').html(data['message']);
        $('.err_list_login').removeClass('hide');
        // enable button login
        return false;
    }
    data = data.data;
    loginrefer = data.refer;
    //remove cart in local storage to update cart for user afer login.
    if (empty(loginrefer)) {
        loginrefer = '/';
    }
    window.location = loginrefer;
}

$(document).ready(function(e) {
    init_login();
     if (oParams['sController'] == 'core.select') {
         initSelectVendor();
     }
});