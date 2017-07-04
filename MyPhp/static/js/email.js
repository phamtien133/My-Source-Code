$(function(){
    initSort();
    
    $('.js-activity').each(function(){
       $(this).unbind('click').click(function(){
            $(this).unbind('click');
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            if (typeof(id) == 'string') {
                id = parseInt(id);
            }
            if (typeof(status) == 'string') {
                status = parseInt(status);
            }
            if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
                hien_thi(id, status);
            }
       });
    });

    $('.js-edit').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                links = '/marketing/email/add/?id='+id;
                window.location = links;
            }
            
       });
    });
    
    $('.js-send-news').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            id = id*1;
            if (id > 0) {
                //call box popup
                showPopupSendNews(id);
            }
            
       });
    });
    
    $('.js-send-sms').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            id = id*1;
            if (id > 0) {
                //call box popup
                showPopupSendSMS(id);
            }
            
       });
    });
    $('.js-send-email').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            id = id*1;
            if (id > 0) {
                //call box popup
                showPopupSendEmail(id);
            }
       });
    });
    
    $('.js-delete').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                xoa_shop_custom(id);
            }
       });
    });

    $('#js-check-all').unbind('click').click(function(){
        var check = $(this).attr('aria-checked');
        if (typeof(check) == 'string') {
            if (check == 'true') {
                //uncheck all
            }
            else {
                //check all
            }
        }
    });
});

/* ajax call popup*/
function showPopupSendNews(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=marketing.getBlockSendNews' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-cancel-send-news'], '.page-send-news', true);
            initSbmSendNews();
        }
    });
}

function initSbmSendNews()
{
    $('#js-submit-send-news').unbind('click').click(function(){
        $('#js-error-popup').hide();
        //validate input
        var action = "$(this).ajaxSiteCall('marketing.sendNews', 'afterSendNews(data)'); return false;";
        $('#frm_send_news').attr('onsubmit', action);
        $('#frm_send_news').submit();
        $('#frm_send_news').removeAttr('onsubmit');
        $('#js-submit-send-news').unbind('click');
        return false;
    });
}

function afterSendNews(data)
{
    if (typeof(data) == 'object' && typeof(data.status) != 'undefined') {
        if (data.status == 'success') {
            //reload page
            alert('Đã gửi thành công!');
            $('#js-close-send-news').click();
        }
        else {
            if (typeof(data.data) != 'undefined' && !empty(data.data)) {
                //add notice error
                var html = '';
                html += '<div class="row30 dialog-err">Đã có lỗi xảy ra</div>';
                for(i in data.data) {
                    html += '<div class="row30">' + data.data[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                $('#js-error-popup').show();
                initSbmSendNews();
                return false;
            }
            else {
                alert('Đã có lỗi xảy ra');
                initSbmSendNews();
            }
        }
    }
    return false;
}

function showPopupSendEmail(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=marketing.getBlockSendEmail' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-cancel-send-email'], '.page-send-email', true);
            initSbmSendEmail();
        }
    });
}

function initSbmSendEmail()
{
    $('#js-submit-send-email').unbind('click').click(function(){
        if ($('#js-send-email-template').val() == '') {
            $('#js-error-popup').html('Vui lòng chọn template.');
            $('#js-error-popup').show();
            return false;
        }
        $(this).addClass('js-button-wait');
        $('#js-error-popup').hide();
        //validate input
        var action = "$(this).ajaxSiteCall('marketing.sendEmail', 'afterSendEmail(data)'); return false;";
        $('#frm_send_email').attr('onsubmit', action);
        $('#frm_send_email').submit();
        $('#frm_send_email').removeAttr('onsubmit');
        //$('#js-submit-send-sms').unbind('click');
        return false;
    });
}

function showPopupSendSMS(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=marketing.getBlockSendSMS' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-cancel-send-sms'], '.page-send-sms', true);
            initSbmSendSMS();
        }
    });
}

function initSbmSendSMS()
{
    $('#js-submit-send-sms').unbind('click').click(function(){
        if ($('#js-send-sms-ctn').val() == '') {
            $('#js-error-popup').html('Vui lòng nhập nội dung gởi sms.');
            $('#js-error-popup').show();
            return false;
        }
        $(this).addClass('js-button-wait');
        $('#js-error-popup').hide();
        //validate input
        var action = "$(this).ajaxSiteCall('marketing.sendSMS', 'afterSendSMS(data)'); return false;";
        $('#frm_send_sms').attr('onsubmit', action);
        $('#frm_send_sms').submit();
        $('#frm_send_sms').removeAttr('onsubmit');
        $('#js-submit-send-sms').unbind('click');
        return false;
    });
}

function afterSendEmail(data)
{
    if (typeof(data) == 'object' && typeof(data.status) != 'undefined') {
        if (data.status == 'success') {
            //reload page
            alert(data.data.message);
            $('#js-close-send-email').click();
        }
        else {
            if (typeof(data.data) != 'undefined' && !empty(data.data)) {
                //add notice error
                var html = '';
                html += '<div class="row30 dialog-err">'+data.message+'</div>';
                for(i in data.data) {
                    html += '<div class="row30">' + data.data[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                $('#js-error-popup').show();
                initSbmSendEmail();
                return false;
            }
            else {
                alert(data.message);
                initSbmSendEmail();
            }
        }
    }
    return false;
}

function afterSendSMS(data)
{
    if (typeof(data) == 'object' && typeof(data.status) != 'undefined') {
        if (data.status == 'success') {
            //reload page
            alert(data.data.message);
            $('#js-close-send-sms').click();
        }
        else {
            if (typeof(data.data) != 'undefined' && !empty(data.data)) {
                //add notice error
                var html = '';
                html += '<div class="row30 dialog-err">Đã có lỗi xảy ra</div>';
                for(i in data.data) {
                    html += '<div class="row30">' + data.data[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                $('#js-error-popup').show();
                initSbmSendNews();
                return false;
            }
            else {
                alert('Đã có lỗi xảy ra');
                initSbmSendSMS();
            }
        }
    }
    return false;
}