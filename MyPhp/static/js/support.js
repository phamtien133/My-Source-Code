var ajaxSupport = [];
var newmessage = [];
ajaxSupport['sending'] = 0;
ajaxSupport['so_lan'] = 0;
ajaxSupport['t_refresh'] = 500;
ajaxSupport['t_time'] = 0;
ajaxSupport['t_chat'] = 0;
ajaxSupport['t_timeUpdate'] = 0;
var currentchat = 0;
function initLoadChatSupport()
{
    // gọi kiểm tra có đang bật hổ trợ trực tuyến hay không.
    var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.initChat';
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
        success: function (data) {
            if (data.status == 'error') {
                alert(data.message);
                return false;
            }
            else if (data.status == 'success') {
                data = data.data;
                if (isset(data.support) && data.support == 1) {
                    // thực hiện gọi ajax để check có caht mới không (chỉ chạy hàm này khi đang có chế độ hổ trợ trực tuyến)
                    receiveNotification();
                    initSendMessage();
                }
                else {
                    // thực hiện tắt khung chat trên khung crm.
                    closeChat();
                }
            }
        }
    });
}
function closeChat()
{
    // remove khung chat. 
    
    // đưa khung dữ liệu khác vào lấp chổ trống.
    
    // xóa các dữ liệu local storage.
    
}
function hasNewMessage()
{
    // chuyển màu icon nếu crm đang đóng
    if($('.back-state.has-message').hasClass('animate-bounce') === false) {
        $('.back-state.has-message').addClass('animate-bounce');
    }
    // hiển thị những khung có chat mới. 
    if (newmessage.length > 0) {
        for (var i in newmessage) {
            $('.item-customer[data-chat='+i+'] .icm5.fa-envelope').addClass('has-chat-message').show();
        }
    }
    
    // hiện thông báo 
    //obj_noi_dung.animate({ scrollTop: obj_noi_dung.prop('scrollHeight')}, 100);
    try {
        chattone.play();
    }
    catch (e) { }
}
function receiveNotification()
{
    if (ajaxSupport['sending']) {
        clearTimeout(ajaxSupport['t_timeUpdate']);
        ajaxSupport['t_timeUpdate'] = setTimeout('receiveNotification()', 1000);
        return false;
    }
       
    var time_sync = localStorage.getItem("cms-chat-time-sync");
    if (!time_sync)
        time_sync = 0;
    // kiểm tra có tin nhắn mới, đồng thời tải nội dung tin nhắn vào bộ phận support.
    ajaxSupport['sending'] = 1;
    var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.receive';
    sParams += '&val[t]=' + time_sync;
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
            clearTimeout(ajaxSupport['t_timeUpdate']);
            ajaxSupport['t_timeUpdate'] = setTimeout('receiveNotification()', 1000);
        },
        success: function (data) {
            if (data.status == 'error') {
                alert(data.message);
                return false;
            }
            else if (data.status == 'success') {
                data = data.data;
                var time = data.time;
                var lists = data.lists;
                var bhasnew = false;
                if (!empty(lists)) {
                    // duyệt dữ liệu để hiển thị chat.
                    var tmp = getLocalStorage('chat-user');
                    if (empty(tmp)) {
                        tmp = {};
                    }
                    var cexit = {};
                    
                    for (var i in lists) {
                        cexit[lists[i]['id']] = 1;
                        // lưu local storage 
                        if (empty(tmp[lists[i]['id']])) {
                            tmp[lists[i]['id']] = lists[i];
                            tmp[lists[i]['id']]['list'] = {};
                        }
                        if (lists[i]['items'].length > 0) {
                            newmessage[lists[i]['id']] = 1;
                            bhasnew = true;
                            for (var j in lists[i]['items']) {
                                tmp[lists[i]['id']]['list'][lists[i]['items'][j]['id']] = lists[i]['items'][j];
                            }
                        }
                    }
                    // thực hiện xóa các item đã hết hạn. 
                    for (var i in tmp) {
                        if (!isset(cexit[i])) {
                            tmp[i] = undefined;
                        }
                    }
                    setLocalStorage('chat-user', tmp);
                }
                // tạo tiếng cho notification
                if (bhasnew) {
                    hasNewMessage();
                }
                // cập nhật lại refresh time trên client 
                ajaxSupport['t_time'] = time;
                localStorage.setItem('cms-chat-time-sync', time);
                displayMessageFromLocal();
                ajaxSupport['sending'] = 0;
                ajaxSupport['t_timeUpdate'] = setTimeout('receiveNotification()', 1000);
            }
        }
    });
}
function displayMessageFromLocal()
{
    var tmp = getLocalStorage('chat-user');
    if (empty(tmp))
        return false;
    for (var i in tmp) {
        if (tmp[i]['user_id'] != -1 ) {
           if ($('.js-list-customer-crm .item-customer[data-user='+tmp[i]['user_id']+']').length > 0) {
                $('.js-list-customer-crm .item-customer[data-user='+tmp[i]['user_id']+']').attr('data-loop', 1);
                continue;
            }
        }
        if ($('.js-list-customer-crm .item-customer[data-chat='+tmp[i]['id']+']').length > 0) {
            $('.js-list-customer-crm .item-customer[data-chat='+tmp[i]['id']+']').attr('data-loop', 1);
            continue;
        }
        insertToList(tmp[i]); 
    }
    $('.item-customer').each(function(){
        var obj = $(this);
        var loop = obj.attr('data-loop');
        if (typeof loop == 'undefined') {
            var call = obj.attr('data-call');
            if (typeof call == 'undefined' || call != 1) {
                obj.remove();
            }
        }
    });
    $('.js-list-customer-crm').css('overflow','none');
    showUserData();
    // hiển thị nội dung chat cập nhật cho user hiện tại
    if (isset(tmp[currentchat]) && isset(tmp[currentchat]['list']) && !empty(tmp[currentchat]['list'])) {
        displayMessage(tmp[currentchat]['list']);
    }
    // thêm hiệu ứng khi click chuột vào khung chat thì cũng ẩn đi icon báo tin nhắn mới
    if (currentchat > 0) {
        obj = $('.item-customer.select');
        if (obj.find('.fa-envelope').hasClass('has-chat-message') !== false) {
            obj.find('.fa-envelope').removeClass('has-chat-message');
            obj.find('.fa-envelope').hide();
        }
    }
    
}
function insertToList(data)
{
    content = '<section class="icm01 item-customer content-box pad10" data-loop="1" data-user="'+data['user_id']+'" data-chat="'+data['id']+'" > \
            <div class="row20 icm0 mgbt10">\
                <span class="fa fa-mars icm1"></span>\
                <div class="icm2">\
                    '+ data['user_fullname'] +'\
                </div>\
                <span class="fa fa-arrow-circle-up icm3"></span>\
                <span class="fa fa-envelope icm5"></span>\
            </div>\
        <div class="row20">\
            <div class="col6 icm61">\
                Số Điện Thoại\
            </div>\
            <div class="col6 icm61">\
                Thành phố\
            </div>\
        </div>\
        <div class="row20 mgbt20">\
            <div class="col6 icm61">\
                Nhóm thành viên\
            </div>\
            <div class="col6 icm61">\
                Thu nhập:\
            </div>\
        </div>\
        <div class="row20">\
            <div class="col4 icm62">Truy cập:</div>\
            <div class="col8 icm63">\
                 Chưa có thông tin\
            </div>\
        </div>\
        <div class="row20">\
            <div class="col4 icm62">Hoạt động</div>\
            <div class="col8 icm63">\
                 \
            </div>\
        </div>\
        <div class="row20">\
            <div class="col4 icm62">Đơn hàng</div>\
            <div class="col8 icm63">\
                 \
            </div>\
        </div>\
        <div class="row20">\
            <div class="col4 icm62">Tiền đã nạp</div>\
            <div class="col8 icm63">\
                \
            </div>\
        </div>\
        <div class="icm4 ">\
            <div class="icm5 icm51">\
                <span class="fa fa-phone js-call-button"></span>\
            </div>\
            <div class="icm5 icm52"></div>\
            <div class="icm5 icm53">\
                <span class="fa fa-phone js-endcall-button"></span>\
            </div>\
        </div>\
    </section>';
    if ($('.js-list-customer-crm .mCSB_container').length == 0) {
        $('.js-list-customer-crm').prepend(content);
    }
    else {
        $('.js-list-customer-crm .mCSB_container').prepend(content);
    }
    console.log(12313);
    scrollMenu();
}
function displayMessage(data) {
    content = '';
    for (var i in data) {
        var uclass = '';
        if (data[i]['is_staff'] > 0) {
            uclass = 'msg-send';
        }
        content += '<div class="msg-item '+uclass+'">\
            <img class="msg-ava mCS_img_loaded" alt="'+data[i]['name']+'" src="//img.disieuthi.vn/styles/web/global/images/noimage/male.png">\
                <div class="msg-panel">\
                    <div class="msg-content">'+data[i]['content']+'</div>\
                </div>\
            </div>';
    }
    $('.panel-crm-message .panel-content #crm-chat .msg-list .mCSB_container').html(content);
    scrollMenu();
    
}
function getCaret(el) 
{ 
  if (el.selectionStart) { 
    return el.selectionStart; 
  } 
  else if (document.selection) { 
    el.focus(); 
    var r = document.selection.createRange(); 
    if (r == null) { 
      return 0; 
    } 

    var re = el.createTextRange(), 
        rc = re.duplicate(); 
    re.moveToBookmark(r.getBookmark()); 
    rc.setEndPoint('EndToStart', re); 

    return rc.text.length; 
  }  
  return 0; 
}
function sendMessage()
{
    var noi_dung = $('#txt_chat').val();
    if (empty(noi_dung)) {
        alert('Vui lòng nhập nội dung');
        return false;
    }
    else {
        $('#js-current-chat').val(currentchat);
        var time_sync = localStorage.getItem("cms-chat-time-sync");
        $('#js-current-time').val(time_sync);
        ajaxSupport['sending'] = 1;
        var action = "$(this).ajaxSiteCall('support.send', 'afterSendMessage(data)'); return false;";
        $('#txt_chat').parents('form').attr('onsubmit', action);
        $('#txt_chat').parents('form').submit();
        $('#txt_chat').parents('form').removeAttr('onsubmit');
        $('#txt_chat').val('');
    }
    return false;
}
function afterSendMessage(data)
{
    if (data.status == 'error') {
        // thông báo lỗi và giữ nguyên nội dung chat.
        alert(data.message);
        return false;
    }
    else if (data.status == 'success') {
        data = data.data;
        $('#txt_chat').val('');
        // đẩy nội dung chat vào localstorage.
        var time = data.time;
        var list = data.lists;
        if (!empty(list)) {
            for (var i in list) {
                // lưu local storage 
                var tmp = getLocalStorage('chat-user');
                if (empty(tmp)) {
                    tmp = {};
                }
                if (empty(tmp[list[i]['id']])) {
                    tmp[list[i]['id']] = list[i];
                    tmp[list[i]['id']]['list'] = {};
                }
                for (var j in list[i]['items']) {
                    tmp[list[i]['id']]['list'][list[i]['items'][j]['id']] = list[i]['items'][j];
                }
                setLocalStorage('chat-user', tmp);
            }
        }
        // thực hiện thêm nội dung chat lên khung.
        var tmp = getLocalStorage('chat-user');
        if (isset(tmp[currentchat]) && isset(tmp[currentchat]['list']) && !empty(tmp[currentchat]['list'])) {
            displayMessage(tmp[currentchat]['list']);
        }
        // cập nhật lại refresh time trên client 
        ajaxSupport['t_time'] = time;
        localStorage.setItem('cms-chat-time-sync', time);
        ajaxSupport['sending'] = 0;
        scrollMenu();
        // đẩy thanh cuộn xuống dưới cùng.
        var height = $('.panel-crm-message .panel-content #crm-chat .msg-list').height();
        $('.panel-crm-message .panel-content #crm-chat .msg-list .mCSB_container').scrollTop(height);
    }
}
function initSendMessage()
{
    $('#txt_chat').keydown(function (event) {
        if (event.keyCode == 13 && event.shiftKey) {
           var content = this.value;
           var caret = getCaret(this);
           this.value = content.substring(0,caret)+"\n"+content.substring(caret,content.length-1);
           event.stopPropagation();
           return false;
        }
        else if(event.keyCode == 13) {
            sendMessage();
            event.stopPropagation();
            return false;
        }
    });
}
function getLocalStorage(dataKey){
    return JSON.parse(localStorage.getItem(dataKey));
}
function setLocalStorage(dataKey, dataValue){
    localStorage.setItem(dataKey, JSON.stringify(dataValue));
}
$(function(){
     initLoadChatSupport();
});