$(function() {
if (typeof('addEventListener') == 'undefined') {
 function addEventListener(e, callback)
 {
  return this.attachEvent('on' + e, callback);
 }
 function removeEventListener(e, callback, useCapture)
 {
  return this.detachEvent('on' + e, callback);
 }
}
});
function setupPath(subdomain)
{
//	return document.location.protocol + '//' + window.location.hostname;
	var path = window.location.hostname;
	
	if(window.location.port == '8080')
	{
		subdomain = '';
		path += ':' + window.location.port;
	}
	if(subdomain != '')
	{
		subdomain = subdomain + '.';
		if(subdomain == path.substring(0, subdomain.length)) subdomain = '';
	}
	path = document.location.protocol + '//' + subdomain + path;
	return path;
};
function setupPathCode(path)
{
	if(path.indexOf('?') == -1) path += '?';
	path += '&code=' + readCookie('code');
	return path;
}

/* ajax */
var xhrAjax = {};
$(window).bind('beforeunload', function (e) {
	/*remove ajax*/
	for(i in xhrAjax)
	{
		if(typeof(xhrAjax[i]) == 'undefined') continue;
		xhrAjax[i].abort();
    }
	/*document.write('<img src="http://img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" title="Waiting" />');*/
});

$.fn.ajaxCall = function(arr)
{
	/* default data */
	var tmps = {
		obj: 'default',
		url: '',
		type: 'GET',
		timeout: 15000,
		cache: false,
		async: true,
		dataType: 'json',
		data: {},
		callback: '',
	};
	/* --- end --- */
	
	if(typeof(arr) == 'undefined') arr = tmps;
	for(i in tmps)
		if(typeof(arr[i]) == 'undefined') arr[i] = tmps[i];
	tmps = {};
	
	if(typeof(xhrAjax[arr['obj']]) != 'undefined') xhrAjax[arr['obj']].abort();
    
	//if(arr['url'].substr(0, 1) == '/' && arr['url'].indexOf('://') == -1) arr['url'] = setupPathCode(setupPath('s') + arr['url']);
	var sParams = '&' + getParam('sGlobalTokenName') + '[ajax]=true';
    if (!sParams.match(/\[security_token\]/i)) {
       sParams += '&' + getParam('sGlobalTokenName') + '[security_token]=' + oCore['log.security_token'];
    }
    
    arr['data'] += sParams;
	var callback = arr['callback'];
	xhrAjax[arr['obj']] = $.ajax({
		crossDomain:true,
		xhrFields: {
			withCredentials: true
		},
		type: arr['type'],
		url: getParam('sJsAjax'),
		timeout: arr['timeout'],
		cache: arr['cache'],
		dataType: arr['dataType'],
		data: arr['data'],
        error: function(request, status, err) {
			var data = {
				err: err,
				status: status,
				type: 'error'
			};
            callback(data);
        },
        success: function(data){
            callback(data);
        }
	});
    return xhrAjax[arr['obj']];
};

$.ajaxCall = function(arr)
{
    return $.fn.ajaxCall(arr);
};

/* end ajax */

/* support online */
var ajaxSupport = [];

ajaxSupport['sending'] = 0;
ajaxSupport['so_lan'] = 0;
ajaxSupport['t_refresh'] = 500;
ajaxSupport['t_time'] = 0;
ajaxSupport['t_chat'] = 0;
ajaxSupport['t_timeUpdate'] = 0;
var focusTimer = null;
var show = ['New Messages', window.document.title];
function thu_nho_chat(phong_to)
{
    var chat_state = 0; // inactive
    if ($('#div_chat').hasClass('state-active'))
        chat_state = 1;
    if (chat_state == 1) {
        // kiểm tra user có login không, nếu có thì lấy full name của user
        var userinfo = getLocalStorage('userinfo');
        if (isset(userinfo.uid) && userinfo.uid > 0) {
            $('#div-chat-content').show();
        }
        else {
            var chatinfo = getLocalStorage('chat-info');
            if (!empty(chatinfo) && isset(chatinfo.name) && !empty(chatinfo.name)) {
                $('#div_chat').removeClass('input-info');
                $('#div_chat').removeClass('input-name');
                $('#chat-online').hide();
                $('#div-chat-content').show();
            }
            else {
                localStorage.removeItem('chat-info');
                $('#div_chat').addClass('input-name');
                $('#div_chat').removeClass('input-info');
                $('#chat-online').show();
                startChatNotLogin();
            }
            $('#send-message-offline').hide();
        }
    }
    else {
        $('#div_chat').addClass('input-info');
        $('#div_chat').removeClass('input-name');
        $('#chat-online').hide();
        $('#send-message-offline').show();
    }
	if($('#div_chat').hasClass('close')){
        $('.div_quang_cao_dong').show();
		$('#div_chat').removeClass('close').addClass('open');
	}
	else{
        $('.div_quang_cao_dong').hide();
		$('#div_chat').removeClass('open').addClass('close');
	}
	return;
	/*
	if(phong_to == undefined) phong_to = 0;
	var obj = $('#div_chat_thu_nho');
	var obj_noi_dung = $('#div_chat_noi_dung');
	
	if(obj.html() == '^' || phong_to == 1)
	{
		obj_noi_dung.css('display', 'block');
		
		// cuon noi dung chat
		obj_noi_dung.scrollTop(obj_noi_dung.scrollHeight);
		
		$('#div_chat').css('background-color', "#FFF");
	}
	else
	{
		obj_noi_dung.css('display', 'none');
	}
	$('#div_chat').css('background-color', "transparent");
	return false;
	*/
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
function tat_chat()
{
	$('#div_chat').animate({'bottom':'-400px'}
		,500
		,function(){
		}
	);
    clearTimeout(ajaxSupport['t_timeUpdate']);
	return false;

	sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.closeChat';
	
	$.ajaxCall({
		obj: 'chat',
		data: sParams
	});
	return false;
}
function luu_chat()
{
	var noi_dung = $('#txt_chat').val();
	if(noi_dung == '') return false;
	$('#txt_chat').val('');
    var name = '';
	var chatinfo = getLocalStorage('chat-info');
    if (!empty(chatinfo) && isset(chatinfo.name) && !empty(chatinfo.name)) {
        name = chatinfo.name;
    }
	var sParams = '&' + getParam('sGlobalTokenName') + '[call]=support.saveMessage';
    sParams += '&val[name]='+name+'&val[t]=' + ajaxSupport['t_time'] + '&val[content]=' + unescape(noi_dung);
    ajaxSupport['sending'] = 1;
	$.ajaxCall({
		data: sParams,
		async:false,
		timeout: 8000,
		callback: function(data){
			if(typeof(data) == 'object' && data.type == 'error') {
				$('#txt_chat').val(noi_dung);
				luu_chat();
			}
			else {
                if (data.status == 'error') {
                    message = '<div class="row-poup center">'+data.message+'</div>';
                    showSmartAlert(message, 'Lỗi thao tác', 'error');
                    ajaxSupport['sending'] = 0;
                    return false;
                }
                else if(data.status == 'success') {
                    data = data.data;
                    ajaxSupport['t_refresh'] = 500;
                    ajaxSupport['so_lan'] = 0;
                    cap_nhat_noi_dung_chat(data);
                    // bật chức năng kiểm tra, nếu quá 30 giây thì thông báo
                    if(ajaxSupport['t_chat'] != 2) {
                        ajaxSupport['t_chat'] = 2;
                        $('#div_chat_noi_dung').append($('#div_chat_thong_bao_lan_dau').html());
                    }
                }
			}
		},
    });
	return false;
}
function doi_mau_chat(level)
{
	if(level == undefined) level = 0;
	var obj = $('#div_chat_thu_nho');
	if(obj.html() == '^') // nếu đang thu nhỏ cửa sổ, đổi màu liên tục
	{
		if(level == 0)
		{
			level = 1;
			$('#div_chat').css('background-color', "#F00");
		}
		else
		{
			level = 0;
			$('#div_chat').css('background-color', "#FFF");
		}
		setTimeout("doi_mau_chat(" + level + ")", 500);
	}
}
function stopNotificationTitle() {
  clearInterval(focusTimer);
  window.document.title = show[1];
}
function co_tin_nhan_moi()
{
	var obj = $('#div_chat_thu_nho');
	var obj_noi_dung = $('#div_chat_noi_dung');
	if(obj.html() == '^') // nếu đang thu nhỏ cửa sổ, đổi màu liên tục
	{
		doi_mau_chat();
	}
	else obj_noi_dung.css('display', 'block');
	
	obj_noi_dung.animate({ scrollTop: obj_noi_dung.prop('scrollHeight')}, 100);
    var i = 0
    
    focusTimer = setInterval(function() { 
      if (window.closed) {
		clearInterval(focusTimer);
		return ;
      }
      window.document.title = show[i++ % 2];
    }, 1000);
	/*
	

// If the user agreed to get notified
    if (Notification && Notification.permission === "granted") {
      var n = new Notification("Hi!");
    }

    // If the user hasn't told if he wants to be notified or not
    // Note: because of Chrome, we are not sure the permission property
    // is set, therefore it's unsafe to check for the "default" value.
    else if (Notification && Notification.permission !== "denied") {
      Notification.requestPermission(function (status) {
        if (Notification.permission !== status) {
          Notification.permission = status;
        }

        // If the user said okay
        if (status === "granted") {
          var n = new Notification("Hi!");
			n.ondisplay = function() { alert('display'); };
    		n.onclose = function() { window.focus(); };
        }

        // Otherwise, we can fallback to a regular modal alert
        else {
          alert("Hi!");
        }
      });
    }

    // If the user refuses to get notified
    else {
      // We can fallback to a regular modal alert
      alert("Hi!");
    }
	*/
}
function chat_cap_nhat_lap()
{
	ajaxSupport['t_timeUpdate'] = setTimeout(
		function(){
			chat_cap_nhat();
		},
		ajaxSupport['t_refresh']
	);
}
function chat_cap_nhat()
{
	if(ajaxSupport['t_timeUpdate'] == 0) {
		$('#txt_chat').removeAttr('disabled');
	}
	if (ajaxSupport['sending']) {
        clearTimeout(ajaxSupport['t_timeUpdate']);
		chat_cap_nhat_lap();
		return false;
	}
	
	ajaxSupport['sending'] = 1;
	ajaxSupport['t_time'] = localStorage.getItem("chat-time-sync");
	var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.receiveChat';
    sParams += '&t=' + ajaxSupport['t_time'];
	clearTimeout(ajaxSupport['t_timeUpdate']);
    
    $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        type: "GET",
        url: getParam('sJsAjax'),
        timeout: 5000,
        cache: false,
        dataType: 'json',
        data: sParams,
        error: function(request, status, err) {
            ajaxSupport['sending'] = 0;
            //if (data.status == 'timeout') {
//                alert("Kết nối hệ thống bị lỗi.\nVui lòng thử lại.");
//            }
            chat_cap_nhat_lap();
        },
        success: function(data){
            if (data.status == 'error') {
                chat_cap_nhat_lap();
            }
            else if(data.status == 'success') {
                data = data.data;
                cap_nhat_noi_dung_chat(data);
            }
        }
    });
}
function cap_nhat_noi_dung_chat(data, onload)
{
	var i = 0, du_lieu = '';
	if(ajaxSupport['t_time'] == 0 && ajaxSupport['t_chat'] == 0) {
		ajaxSupport['t_chat'] = 1;
		if(typeof data.list == 'undefined' && $('#div_chat_loi_chao').html() != '') {
			$('#div_chat_noi_dung').append($('#div_chat_loi_chao').html());
			// phóng to
			thu_nho_chat(1);
		}
	}
    var time = 0;
    var chats = {};
    var bhasnew = false;
    var isonload = false;
    var tname = 'items';
    if (typeof onload != 'undefined' && onload == 1) {
        isonload = true;
        tname = 'list';
        chats = data;
        time = localStorage.getItem("chat-time-sync");
    }
    else {
         chats = data.lists;
         time = data.time;
    }
    ajaxSupport['t_time'] = time;
    localStorage.setItem('chat-time-sync', time);
    ajaxSupport['sending'] = 0;
    // kiểm tra có tồn tại id chat không, nếu không thì empty localstorage
    if (!empty(chats)) {
        if (!isset(chats['id']) || chats['id'] < 0) {
            localStorage.removeItem('datachat');
            chat_cap_nhat_lap(); // gọi lại hàm để cập nhật tin nhắn
            return false; // không có dữ liệu nên không hiển thị.
        }
        // duyệt dữ liệu để hiển thị chat.
        var tmp = getLocalStorage('datachat');
        if (empty(tmp)) {
            tmp = {};
        }
        
        if (!isset(tmp[chats['id']])) {
            // không có dữ liệu của người chat trong phiên làm việc, xóa hết các localstorage cũ.
            localStorage.removeItem('datachat');
        }
        
        if (empty(tmp[chats['id']])) {
            tmp[chats['id']] = chats;
            tmp[chats['id']]['list'] = {};
        }
        setLocalStorage('current-chat', chats['id']);
        
        if (Object.keys(chats[tname]).length > 0) {
            ajaxSupport['so_lan'] = 0;
            for (var j in chats[tname]) {
                tmp[chats['id']]['list'][chats[tname][j]['id']] = chats[tname][j];
                if (chats[tname][j]['is_staff'] == 0) {
                    du_lieu += '<div class="item-message message-user">' +
                        '<img class="avatar" src="'+oParams['sJsImage']+'/styles/web/global/images/user01.png" alt=" "/>' + 
                        '<div class="message-detail">' +
                            '<div class="message-info">' +
                                '<div class="message-username">'+chats[tname][j].name+'</div>' +
                                '<div class="message-time">'+chats[tname][j].display_time+'</div>' + 
                            '</div>' +
                            '<div class="message-content">' + 
                                chats[tname][j].content +
                            '</div>' +
                        '</div>' +
                        '<span class="clear"></span>' +
                    '</div>';
                }
                else {
                    if (!isonload)
                        bhasnew = true;
                    du_lieu += '<div class="item-message message-admin">' +
                        '<img class="avatar" src="'+oParams['sJsImage']+'/styles/web/global/images/user01.png" alt=" "/>' + 
                        '<div class="message-detail">' +
                            '<div class="message-info">' +
                                '<div class="message-username">'+chats[tname][j].name+'</div>' +
                                '<div class="message-time">'+chats[tname][j].display_time+'</div>' + 
                            '</div>' +
                            '<div class="message-content">' + 
                                chats[tname][j].content +
                            '</div>' +
                        '</div>' +
                        '<span class="clear"></span>' +
                    '</div>';
                }
            }
        }
        
        if ($('#div_chat_noi_dung .mCSB_container').length == 0) {
            $('#div_chat_noi_dung').append(du_lieu);
        }
        else {
            $('#div_chat_noi_dung .mCSB_container').append(du_lieu);
        }
        
        if (bhasnew) {
            $('#div_chat').removeClass('input-info');
            co_tin_nhan_moi();
        }
        if (!isonload) {
            setLocalStorage('datachat', tmp);
        }
  	}
    ajaxSupport['so_lan']++;
	if(ajaxSupport['so_lan'] > 30) {
		if(ajaxSupport['t_time'] > 0)
			ajaxSupport['t_refresh'] = 20000;
		else
			ajaxSupport['t_refresh'] = 30000;
	}
	else if(ajaxSupport['so_lan'] > 20) {
		ajaxSupport['t_refresh'] = 10000;
	}
	else if(ajaxSupport['so_lan'] > 10) {
		ajaxSupport['t_refresh'] = 5000;
	}
	else if(ajaxSupport['so_lan'] > 5) {
		ajaxSupport['t_refresh'] = 2000;
	}
	else {
		ajaxSupport['t_refresh'] = 1000;
	}
	chat_cap_nhat_lap();
}
function sendOfflineMessage()
{
    $("#send-offmsg-button").unbind("click").click(function(){
        var name = $('#js-offmsg-name').val();
        if (empty(name)) {
            message = '<div class="row-poup center">Vui lòng nhập họ tên của bạn.</div>';
            showSmartAlert(message, 'Lỗi thao tác', 'error');
            return false;
        }
        var email = $('#js-offmsg-email').val();
        if (empty(email)) {
            message = '<div class="row-poup center">Vui lòng nhập email của bạn.</div>';
            showSmartAlert(message, 'Lỗi thao tác', 'error');
            return false;
        }
        var content = $('#js-offmsg-content').val();
        if (empty(content)) {
            message = '<div class="row-poup center">Vui lòng nhập nội dung cần hổ trợ.</div>';
            showSmartAlert(message, 'Lỗi thao tác', 'error');
            return false;
        }
        showAjaxLoading($(this), 'button');
        // lưu thông tin 
        var action = "$(this).ajaxSiteCall('support.sendOfflineMessage', 'sendOffMsgCallback(data)'); return false;";
        $('#js-send-off-msg-form').attr('onsubmit', action);
        $('#js-send-off-msg-form').submit();
        $('#js-send-off-msg-form').removeAttr('onsubmit');
        return false;
    });
}
function sendOffMsgCallback(data)
{
    closeAjaxLoading($('#send-offmsg-button'));
    if (data.status == 'error') {
        message = '<div class="row-poup center">'+ data.message+'.</div>';
        showSmartAlert(message, 'Lỗi thao tác', 'error');
        return false;
    }
    else {
        data = data.data;
        content = '<div class="item-message message-user">' +
                '<img class="avatar" src="'+oParams['sJsImage']+'/styles/web/global/images/user01.png" alt=" "/>' + 
                '<div class="message-detail">' +
                    '<div class="message-info">' +
                        '<div class="message-username">'+data.name+'</div>' +
                        '<div class="message-time">'+data.time+'</div>' + 
                    '</div>' +
                    '<div class="message-content">' + 
                        data.content +
                    '</div>' +
                '</div>' +
                '<span class="clear"></span>' +
            '</div>';
        $('#div_chat_noi_dung .mCSB_container').append(content);
        localStorage.setItem('offline-msg', JSON.stringify(data));
        $('#div_chat').removeClass('input-info');
        $('#send-message-offline').hide();
        $('#div-chat-content').show();
    }
}
function startChatNotLogin()
{
    $('#js-start-chat').unbind('click').click(function(){
         var name = $('#js-chat-name').val();
         if (empty(name)) {
             message = '<div class="row-poup center">Vui lòng nhập tên của bạn.</div>';
             showSmartAlert(message, 'Lỗi thao tác', 'error');
             return false;
         }
         var tmp = {};
         tmp['name'] = name;
         localStorage.setItem('chat-info', JSON.stringify(tmp));
         $('#div_chat').removeClass('input-info');
         $('#div_chat').removeClass('input-name');
         $('#chat-online').hide();
         $('#div-chat-content').show();
    });
}
function checkLocalStorage(dataKey){
    var data = localStorage.getItem(dataKey);
    if(data === null) {
        return false;
    }
    return true;
}
function getLocalStorage(dataKey){
    return JSON.parse(localStorage.getItem(dataKey));
}
function setLocalStorage(dataKey, dataValue){
    localStorage.setItem(dataKey, JSON.stringify(dataValue));
}
$(function() {
	$('#txt_chat').focus(function(e) {
        stopNotificationTitle();
		$('#div_chat').css('background-color', "#FFF");
	});
    $('.div_quang_cao_tieu_de').unbind('click').click(function(){
         thu_nho_chat();
    });
    $('#txt_chat').keydown(function (event) {
        if (event.keyCode == 13 && event.shiftKey) {
           var content = this.value;
           var caret = getCaret(this);
           this.value = content.substring(0,caret)+"\n"+content.substring(caret,content.length-1);
           event.stopPropagation();
           return false;
        }
        else if(event.keyCode == 13) {
          $(this).parents('form').submit();
          event.stopPropagation();
          return false;
        }
    });
	/* Lấy dữ liệu từ cache trước khi thực thi */
	if (ajaxSupport['t_timeUpdate'] == 0) {
		var data = getLocalStorage("datachat");
        var time_sync = localStorage.getItem("chat-time-sync");
        ajaxSupport['t_time'] = time_sync;
        if(data == undefined || data == '') {
            
        }
        else {
            // tạm dừng nút chat vì có thể hết giờ hỗ trợ
            /*$('#txt_chat').attr('disabled', 'disabled');*/
            var currentchat = getLocalStorage('current-chat');
            if (isset(data[currentchat]))
                cap_nhat_noi_dung_chat(data[currentchat], 1);
        }
	} 
});
// mo popup
var popup;

function moPopup(url, callback, settings) {
	if(url == '') return false;
	
	if(settings == undefined) settings = {};
	if(callback == undefined) callback  = function(){};
	
	if(settings['width'] == undefined) settings['width']  = 290;
	if(settings['height'] == undefined) settings['height'] = 600;
	
	if(settings['type'] == undefined) settings['type'] == 'iframe';
	
	var newwin;
	
	if(settings['type'] == 'popup')
	{
		
		obj = 'popup_' + Math.floor((Math.random()*100000)+1);
		var left   = (screen.width  - settings['width'])/2;
		var top    = (screen.height - settings['height'])/2;
		var params = 'width='+settings['width']+', height='+settings['height'];
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
		
		newwin.onload = function(){
			//$('#' + newwin).height($('#' + newwin).contents().find('body').height()+30);
			popup = newwin;
			if(!popup && typeof(popup) != 'object')
			{
				alert('fail');
				return false;
			}
        };
		callback();
		newwin.document.title = settings['title'];
		return newwin;
	}
	
	$.fancybox({
		'href'			: url,
		'width'				: settings['width'],
		'height'				: settings['height'],
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe',
		'iframe' : {
			preload: false
		},
		'beforeShow': function(){
			newwin = $('.fancybox-iframe').attr('id');
			newwin = document.getElementById(newwin);
			newwin.onload  = function(){
				//$('#' + newwin).height($('#' + newwin).contents().find('body').height()+30);
				popup = (newwin.contentWindow || newwin.contentDocument);
				if(!popup && typeof(popup) != 'object')
				{
					alert('fail');
					return false;
				}
				callback();
			}
		},
	});
	
	return false;
}
// end

/// search 
String.prototype.trim = function() { //Trim ambas direcciones
    return this.replace(/^[ ]+|[ ]+$/g, "");
}

String.prototype.stripSpace = function() { //Trim ambas direcciones
	tmp = this.replace(/(\s\s+)/g, " ");
	tmp = tmp.replace(/ /g, "-");
    return tmp;
}

String.prototype.stripExtra = function() { //Trim ambas direcciones
    re = /\$|,|@|#|~|`|\%|\*|\^|\&|\(|\)|\+|\=|\[|\-|\_|\]|\[|\}|\{|\;|\:|\'|\"|\<|\>|\?|\||\\|\!|\$|\.|\//g;
    return this.replace(re, " ");
}

String.prototype.stripViet = function() {
    var replaceChr = String.prototype.stripViet.arguments[0];
    var stripped_str = this;
    var viet = [];
    i = 0;
    viet[i++] = new Array('a', "/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/g");
    viet[i++] = new Array('o', "/ó|ò|ỏ|õ|ọ|ơ|ớ|ờ|ở|ỡ|ợ|ô|ố|ồ|ổ|ỗ|ộ/g");
    viet[i++] = new Array('e', "/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/g");
    viet[i++] = new Array('u', "/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/g");
    viet[i++] = new Array('i', "/í|ì|ỉ|ĩ|ị/g");
    viet[i++] = new Array('y', "/ý|ỳ|ỷ|ỹ|ỵ/g");
    viet[i++] = new Array('d', "/đ/g");
    for (var i = 0; i < viet.length; i++) {
        stripped_str = stripped_str.replace(eval(viet[i][1]), viet[i][0]);
        stripped_str = stripped_str.replace(eval(viet[i][1].toUpperCase().replace('G', 'g')), viet[i][0].toUpperCase());
    }
    if (replaceChr) {
        return stripped_str.replace(/[\W]|_/g, replaceChr).replace(/\s/g, replaceChr).replace(/^\-+|\-+$/g, replaceChr);
    } else {
        return stripped_str;
    }
};
function CheckSearch(obj) {
    var length = obj.tim_kiem_tu_khoa.value.trim().length;
    if (length < 2) {
        alert("Từ khóa phải có chiều dài từ 2 kí tự trở lên!");
        if (length < 1)
            obj.tim_kiem_tu_khoa.value = '';
        obj.tim_kiem_tu_khoa.focus();
    }
    else
	{
		obj.tim_kiem_tu_khoa.value = obj.tim_kiem_tu_khoa.value.stripExtra().trim().stripSpace();
		window.location = '/search/' + obj.tim_kiem_tu_khoa.value;
	}
    return false;
}
function CheckSearchAdvanded(obj) {
    var length = obj.tim_kiem_tu_khoa.value.trim().length;
    if (length < 2) {
        alert("Từ khóa phải có chiều dài từ 2 kí tự trở lên!");
        if (length < 1)
            obj.tim_kiem_tu_khoa.value = '';
        obj.tim_kiem_tu_khoa.focus();
    }
    else
	{
		obj.tim_kiem_tu_khoa.value = obj.tim_kiem_tu_khoa.value.stripExtra().trim();
		if(obj.catid.value == '')
		{
			window.location = '/search/' + obj.tim_kiem_tu_khoa.value;
		}
		else
		{
			window.location = obj.catid.value + '?q=' + obj.tim_kiem_tu_khoa.value;
		}
	}
    return false;
}

// http.js
function createObject() {
var XMLHttp = null;
if (window.XMLHttpRequest) {
	XMLHttp = new XMLHttpRequest();
}
else
{
	   //  active x internetexplorer ---------------------------------------------------------
      try
      {
           XMLHttp = new ActiveXObject('MSXML2.XMLHTTP.3.0'); // ie7
      }
      catch (e)
      {
         try
         {
               XMLHttp = new ActiveXObject("Msxml2.XMLHTTP"); //ie 6
         }
         catch(e)
         {
            try
            {
               XMLHttp = new ActiveXObject("Microsoft.XMLHTTP");//ie older versions
            }
            catch(e)
            {
            }
         }
      }
   //  active x internetexplorer ---------------------------------------------------------
}
return XMLHttp;
}
var http = createObject();

function htmlEntities(str) {
    var e = document.createElement('div');
  e.innerHTML = str;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

/* gmap */
var gmap_infowindow = [], gmap_trang_thai = 0;
function da_tai_ban_do()
{
	if(gmap_trang_thai != 0) return ;
	gmap_trang_thai = 1;
	// if not download api js, download
	if(typeof google == 'undefined')
	{
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.async = false;
		script.src = "//maps.google.com/maps/api/js?sensor=false&callback=da_tai_ban_do";
		if(window.attachEvent && document.all){
			script.onreadystatechange = function()
			{
				if( this.readyState == 'complete' )
				{
					gmap_trang_thai = 2;
				}
			}
		}
		else
		{
			script.onload = function(){
				gmap_trang_thai = 2;
			};
		}
		document.getElementsByTagName("head")[0].appendChild(script);
	}
}
function cai_dat_ban_do(obj, markers)
{
	if(gmap_trang_thai != 2 || (typeof(google.maps.LatLng) == "undefined"))
	{
		if(gmap_trang_thai == 0) da_tai_ban_do();
		setTimeout(function()
		{
			cai_dat_ban_do(obj, markers);
		}, 1000);
		return ;
	}
	
	var centerMap = new google.maps.LatLng(markers[0][1], markers[0][2]);

	var myOptions = {
		zoom: 15,
		center: centerMap,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	var map = new google.maps.Map(document.getElementById(obj), myOptions);

	
	for (var i = 0; i < markers.length; i++) {
		var sites = markers[i];
		var siteLatLng = new google.maps.LatLng(sites[1], sites[2]);
		var marker = new google.maps.Marker({
			position: siteLatLng,
			map: map,
			title: sites[0],
			html: sites[3]
		});

		google.maps.event.addListener(marker, "click", function () {
			gmap_infowindow[obj].setContent(this.html);
			gmap_infowindow[obj].open(map, this);
		});
	}
	gmap_infowindow[obj] = new google.maps.InfoWindow({
			content: "loading..."
		});

	var bikeLayer = new google.maps.BicyclingLayer();
	bikeLayer.setMap(map);
}

/* Quang cao */
var t_thu_nho_quang_cao = [];
function thu_nho_quang_cao(id, val)
{
	clearTimeout(t_thu_nho_quang_cao[id]);
	
	var obj = document.getElementById('div_quang_cao_thu_nho_' + id), obj_noi_dung = null;
	
	// if popup
	if(obj == undefined)
	{
		return false;
	}
	
	for(var i in document.getElementById('div_quang_cao_' + id))
	{
		if(document.getElementById('div_quang_cao_' + id)[i] != null && document.getElementById('div_quang_cao_' + id)[i].id == 'div_quang_cao_noi_dung')
		{
			obj_noi_dung = document.getElementById('div_quang_cao_' + id)[i];
		}
	}
	if(obj_noi_dung == null) return ;
	
	if(obj == undefined || obj_noi_dung == undefined)
	{
		alert('loi' + id);
		return ;
	}
	if(val == undefined) val = 0;
	if(val == 1)
	{
		obj_noi_dung.style.display = 'block';
		obj_noi_dung.innerHTML = htmlEntities(obj_noi_dung.innerHTML);
		
		obj.innerHTML = '-';
		obj.onclick = function()
		{
			thu_nho_quang_cao(id);
		}
	}
	else
	{
		obj_noi_dung.style.display = 'none';
		obj_noi_dung.textContent = obj_noi_dung.innerHTML
		
		obj.innerHTML = '^';
		obj.onclick = function()
		{
			thu_nho_quang_cao(id, 1);
		}
		
		if (document.getElementById('div_quang_cao_' + id).className.indexOf('vi_tri_9') > -1) {
			document.getElementById('div_quang_cao_' + id).style.display = 'none';
			document.body.style.overflow = "inherit";
			if(document.getElementById('dShadow') != undefined) document.getElementById('dShadow').style.display = "none";
		}
	}
	return false;
}
function tat_quang_cao(id, val)
{
	if(val == undefined) val = 0;
	var obj = document.getElementById('div_quang_cao_' + id);
	
	// if popup
	if(obj == undefined)
	{
		if(popup_quang_cao[id] != undefined) popup_quang_cao[id]['obj'].close();
		return false;
	}
	obj.style.display = 'none';

	if (obj.className.indexOf('vi_tri_9') > -1) {
		document.body.style.overflow = "inherit";
		if(document.getElementById('dShadow') != undefined) document.getElementById('dShadow').style.display = "none";
	}
	
	sParams = '&'+ getParam('sGlobalTokenName') + '[call]=ads.close';
    sParams += '&id=' + id;

	if(val == 0)
	{
		$.ajaxCall({
			data: sParams,
			timeout: 8000
		});
	}
	return false;
}

function setPopUpCenter(a) {
    var b;
    var c;
    if (typeof window.innerWidth != 'undefined') {
        b = window.innerWidth, c = window.innerHeight
    } else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
        b = document.documentElement.clientWidth, c = document.documentElement.clientHeight
    } else {
        b = document.getElementsByTagName('body')[0].clientWidth, c = document.getElementsByTagName('body')[0].clientHeight
    }
    var d = getScrollXY();
    var e = document.getElementById(a).offsetHeight;
    var f = document.getElementById(a).offsetWidth;
    var g = c > e ? (((c - e) / 2) + d[1]) : 0;
    var h = b > f ? (((b - f) / 2) + d[0]) : 0;
    var i = "scroll Left: " + d[0] + "\n" + "scroll Top : " + d[1] + "\n Left : " + h + "\n Top : " + g;
    document.getElementById(a).style.top = g + "px";
    document.getElementById(a).style.position = "absolute";
	document.getElementById(a).style.left = (h - 9) + "px";
}
function getScrollXY() {
    var a = 0,
        scrOfY = 0;
    if (typeof (window.pageYOffset) == 'number') {
        scrOfY = window.pageYOffset;
        a = window.pageXOffset
    } else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
        scrOfY = document.body.scrollTop;
        a = document.body.scrollLeft
    } else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
        scrOfY = document.documentElement.scrollTop;
        a = document.documentElement.scrollLeft
    }
    return [a, scrOfY]
}

function hien_thi_quang_cao(id)
{
	var obj_noi_dung = null;
	
	for(var i in document.getElementById('div_quang_cao_' + id))
	{
		if(document.getElementById('div_quang_cao_' + id)[i] != null && document.getElementById('div_quang_cao_' + id)[i].id == 'div_quang_cao_noi_dung')
		{
			obj_noi_dung = document.getElementById('div_quang_cao_' + id)[i];
		}
	}
	
	if(obj_noi_dung == null) return ;
	
	noi_dung = obj_noi_dung.innerHTML;
	noi_dung = htmlEntities(noi_dung);
	obj_noi_dung.innerHTML = noi_dung;
	
	if(noi_dung.indexOf('div_newsletter'))
	{
		if(readCookie('newsletter') != 1)
		{
			$('.div_newsletter').fadeIn('fast');	
		}
		else
		{
			$('.vi_tri_9').css('display', 'none');
		}
	}
	document.getElementById('div_quang_cao_' + id).style.display = 'block';
	
	if(time_thu_nho[id] > 0) t_thu_nho_quang_cao[id] = setTimeout('thu_nho_quang_cao(' + id + ')', time_thu_nho[id])
	
	if(time_tat_quang_cao[id] > 0)
	{
		setTimeout('tat_quang_cao(' + id + ', 1)', time_tat_quang_cao[id])
	}

	document.getElementById('div_quang_cao_' + id).onmouseover = function()
	{
		clearTimeout(t_thu_nho_quang_cao[this.id.replace('div_quang_cao_' , '')]);
	}
	if (document.getElementById('div_quang_cao_' + id).className.indexOf('vi_tri_9') > -1) {
		
		// disable scroll
		document.body.style.overflow = "hidden";
		if(document.getElementById('dShadow') != undefined) document.getElementById('dShadow').style.display = "block";
		
		window.onresize = function () {
			setPopUpCenter('div_quang_cao_' + id);
		};
		setPopUpCenter('div_quang_cao_' + id);
	}
	if (document.getElementById('div_quang_cao_' + id).className.indexOf('vi_tri_10') > -1) {
		popup_quang_cao[id]['obj'] = moPopup('', 'popup_' + id, popup_quang_cao[id]['width'], popup_quang_cao[id]['height']);
		if(popup_quang_cao[id]['obj'] == false)
		{
			window.onclick = function()
			{
				popup_quang_cao[id]['obj'] = moPopup('', 'popup_' + id, popup_quang_cao[id]['width'], popup_quang_cao[id]['height']);
				if(popup_quang_cao[id]['obj'] != false)
				{
					popup_quang_cao[id]['obj'].document.body.innerHTML = popup_quang_cao[id]['content'];
					popup_quang_cao[id]['obj'].hide();
					window.onclick = function(){};
				}
			}
		}
		else
		{
			popup_quang_cao[id]['obj'].document.body.innerHTML = popup_quang_cao[id]['content'];
			popup_quang_cao[id]['obj'].hide();
		}
	}
}
(function(){
	if(sessionStorage.getItem('time') === null)
	{
		sessionStorage.setItem('time', 1);
		// update session after include shopcart (string) and userinfo (json)
		
		var data = [];
		data = {
			'shopcart' : localStorage.getItem('shopcart'),
			'userinfo' : localStorage.getItem('userinfo'),
		};
		localStorage.clear();
		// send data
		var i = 0; query = '';
		for(i in data)
		{
			query += i + '=' + encodeURIComponent(data[i]) + '&';
		}
	}
})();
var t_capNhatGioHang;
function capNhatGioHang(arr){
	var objremoveajaxwait = arr['objremoveajaxwait'];
	var callBackJsDatMua = arr['callBackJsDatMua'];
	/*
	arr
	{auto: 1}
	*/
	if(arr == undefined) arr = {
		callback: 1,
		auto: 0
	};

	var contentLang = {};
	function xu_ky_cap_nhat_gio_hang(data)
	{
			$('#div_cap_nhat_gio_hang').html(contentLang.update);
			
			if(localStorage) t_capNhatGioHang = setTimeout('capNhatGioHang({auto: 1})', 1500);
			
			$('#div_gio_hang_so_luong').html(data[0]);
			$('#div_gio_hang_tong_tien').html(data[1]);
			
			data['auto'] = arr.auto;
			if(typeof(capNhatGioHang_callback) == 'function') capNhatGioHang_callback(data);
	}
	var data = localStorage.getItem('shopcart');
	if(data !== null) {
		data = JSON.parse(data);
		showProdcutCartPopup(data);
		return false;
	}
	
	clearTimeout(t_capNhatGioHang);
	var query = '';
	if(typeof(configGlobal['getShopTotal']) != 'undefined' && configGlobal['getShopTotal'] != 0) 
        query = '&val[n]=' + configGlobal['getShopTotal'];
	
	contentLang.update = $('#div_cap_nhat_gio_hang').html();
	$('#div_cap_nhat_gio_hang').html('<a href="javascript:void(this);" onclick=""><img src="http://img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" title="Waiting" /></a>');

	sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.update';
    sParams += query;

	$.ajaxCall({
		data: sParams,
		obj: 'shop_gio_hang',
		dataType: 'json',
		callback: function(data){
			if(typeof(data) == 'object' && data.type == 'error') {
				setTimeout(
					function(){
						capNhatGioHang();
					},
					10000
				);
				$('#div_cap_nhat_gio_hang').html(contentLang.update);
			}
			else {
                if(data.status == 'success') {
                    insertProductCartPopup(data.data);
                    if(typeof(showpage) != 'undefined' && showpage == 1) {
                        showCart(data.data);
                    }
                }
                if (data.status == 'error') {
                    // format cart to default.
                    $('#div_gio_hang_so_luong').html(0);
                    $('#div_gio_hang_so_luong').removeClass('is-button-wait');
                    $('#div_gio_hang_tong_tien').html(0);
                    $('#div_gio_hang_tong_tien').removeClass('is-button-wait');
                    $('.js-notify-cart').html(0);
                    $('.is-content-cart').html('');
                    if(typeof(showpage) != 'undefined' && showpage == 1) {
                        $('#js_list_cart').addClass('empty-content');
                        $('#js_list_cart').html(data.message);
                    }
                }
                /*return false;
				if(data == null) data = '';
				// convert to string
				localStorage.setItem('shopcart', JSON.stringify(data));
				xu_ky_cap_nhat_gio_hang(data);*/
			}
			if (typeof(objremoveajaxwait) != 'undefined') {
            	closeAjaxLoading(objremoveajaxwait);
			};
			if (typeof(callBackJsDatMua)!='undefined' || callBackJsDatMua !=null) {
            	if(data.status == 'success') {
            		callBackJsDatMua();
            	}else{
            		callBackJsDatMua(false);
            	}
            }
		}
	});
	return false;
}

if(typeof(link_bai_viet) == 'undefined') var link_bai_viet = [];
		$('#overlay').onclick = function(){
			dong_hinh();
		};
		
$(function() {
	t_capNhatGioHang = setTimeout('capNhatGioHang({auto: 0})', 1600);

	$(".btn_xem_nhanh").each(function(index, element) {
	
		// fancybox
		var obj = $(this).attr('href');
		
		if(obj == '' || $(obj).length != 0) return true;
		
		$("body").append('<div id="' + obj.replace('#', '') + '" class="none"></div>');
	});

	$(".btn_xem_nhanh").fancybox({
		'titlePosition'		: 'inside',
		'transitionIn'		: 'none',
		'width'				: 980,
		'height'			: 376,
		'autoSize'			: false,
		'autoDimensions'	: true,
		'transitionOut'		: 'none',
		'beforeShow'	: function() {

// fancybox
var obj = $(this).attr('href'), id;

id = obj.substr( obj.lastIndexOf('-') + 1 );

if($(obj).html() != '')
{
	//tracking
	if(typeof(_gaq) != 'undefined') _gaq.push(['_trackPageview', link_bai_viet[id]]);
	return ;
}
if(id < 1)
{
	alert('Can not define ID of Product');
	setTimeout(function(){$.fancybox.close()}, 700);
	return false;
}

// gọi ajax tải nội dung
$.ajax({
		crossDomain:true,
		xhrFields: {
			withCredentials: true
		},
		crossDomain:true,
		xhrFields: {
			withCredentials: true
		},
	url: setupPathCode(setupPath('s') + "/tools/thong_tin_bai_viet.php?field=tieu_de,noi_dung,hinh_mo_rong,gia_ban,giam_gia,khuyen_mai,mo_rong_ghi_chu,duong_dan_chi_tiet,duong_dan_hinh,trich_loc&list=" + id),
	type: "GET",
	timeout: 10000,
	cache:true,
	dataType: 'json',
	beforeSend: function ( xhr ) {
		$(obj).html('<img src="http://img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" /> Đang tải thông tin sản phẩm');
	},
	error: function(jqXHR, status, errorThrown){
		if(status == 'timeout')
		{
			alert("Kết nối hệ thống bị lỗi.\nVui lòng thử lại.");
		}
		$(obj).html('');
	} ,
	success: function (data) {
		if(data == null)
		{
			$(obj).html('');
			alert("Không có dữ liệu liên kết.\nVui lòng thử lại.");
			return;
		}
		var content = '', thanh_tien = 0, phan_tram = 0, i = 0;
		
		if(data['giam_gia'] == undefined) data['giam_gia'] = [];
		if(data['gia_ban'] == undefined) data['gia_ban'] = [];
		if(data['mo_rong_ghi_chu'] == undefined) data['mo_rong_ghi_chu'] = [];
		if(data['hinh_mo_rong'] == undefined) data['hinh_mo_rong'] = [];
		
		if(data['giam_gia'][i] == undefined) data['giam_gia'][i] = 0;
		if(data['gia_ban'][i] == undefined) data['gia_ban'][i] = 0;
		if(data['mo_rong_ghi_chu'][i] == undefined) data['mo_rong_ghi_chu'][i] = '';
		if(data['hinh_mo_rong'][i] == undefined) data['hinh_mo_rong'][i] = [];
		if(data['hinh_mo_rong'][i]['hinh-slide-lon'] == undefined) data['hinh_mo_rong'][i]['hinh-slide-lon'] = [];
		
		
		thanh_tien = numberFormat(data['gia_ban'][i] - data['giam_gia'][i]);
		
		link_bai_viet[id] = data['duong_dan_chi_tiet'][i];
		content = build_temp_buy_quick({
				'thanh_tien': thanh_tien,
				'id': id,
			}, data
		);
		$(obj).html(content);
		
		$('.AddCartBT_' + id).click(function(e) {
		var tmps = {
			san_pham: {
				id: [id],
				sku: [$('input#sku').val()],
				so_luong: [$('select#so_luong').val()],
				name: [data['tieu_de']],
				avatar: [data['duong_dan_hinh']],
			},
			doi_tuong_trich_loc_co_dinh: '.trich_loc_co_dinh_quick_' + id,
			doi_tuong_trich_loc_khong_co_dinh: '.trich_loc_co_dinh_quick_' + id,
			mua_nhanh: 1
		};
		js_dat_mua(tmps);
		e.preventDefault();
		return false;
        });
		$("a[rel=thumbsImg]").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none'
		});
		//tracking
		if(typeof(_gaq) != 'undefined') _gaq.push(['_trackPageview', link_bai_viet[id]]);
	}
});
	}
	});
});
var ajax = [];
var showpage = 0;
function js_dat_mua(arr)
{
	var callBackJsDatMua = arr.callback;
    /* default data */
    var tmps = {
        thanh_toan_ngay: 0,
        san_pham: {
            id: [],
            sku: [],
            so_luong: [],
            name: [],
            avatar: [],
            obj: [],
        },
        doi_tuong_trich_loc_co_dinh: '.trich_loc_co_dinh',
        doi_tuong_trich_loc_khong_co_dinh: '.trich_loc_khong_co_dinh',
        mua_nhanh: 0,
    };
    if(typeof(langsys) == 'undefined') {
        var langsys = {};
        langsys['buy-success'] = 'Đã thêm vào giỏ hàng thành công';
    }
    
    if(typeof(arr) == 'undefined') arr = tmps;
    for(i in tmps)
        if(typeof(arr[i]) == 'undefined') arr[i] = tmps[i];
    tmps = {};
    
    //if(typeof(arr.san_pham.obj) == 'undefined' || arr.san_pham.id.obj < 1 || arr.san_pham.obj[0] = '') return;
    
    if(typeof(arr.san_pham.id) == 'undefined' || arr.san_pham.id.length < 1 || arr.san_pham.id[0] < 1) return;
    
    if(typeof(arr.san_pham.sku) == 'undefined' || arr.san_pham.sku.length < 1 || arr.san_pham.sku[0] == '') return;
    
    if(typeof(arr.san_pham.so_luong) == 'undefined' || arr.san_pham.so_luong.length < 1) return;
    // bỏ phần này để cho phép gởi số lượng < 0 (giảm số lượng)
    //for(i in arr.san_pham.so_luong) {
//        if(arr.san_pham.so_luong[i] > 0) continue;
//        
//        delete arr.san_pham.sku[i];
//        delete arr.san_pham.id[i];
//    }
    
    if(arr.san_pham.id.length != arr.san_pham.sku.length || arr.san_pham.so_luong.length != arr.san_pham.sku.length) return ;
    
    if(ajax['datmua']) {
        if(confirm("Waiting session or cancel it?")) ajax['datmua'].abort();
        else return;
    }
    var query = '', obj, n = 0;
    sku = arr.san_pham.sku.join(',');
    query += '&val[sku]=' + sku;
    
    // tinh so luong
    n = arr.san_pham.so_luong.join(',');
    query += '&val[n]=' + n;
    if (typeof(arr.san_pham.obj) != 'undefined') {
       var objremoveajaxwait = arr.san_pham.obj;
    }
    if (typeof(arr.san_pham.shop) != 'undefined') {
       if(arr.san_pham.shop)
            showpage = 1;
    }
    $(arr.doi_tuong_trich_loc_co_dinh).each(function(index, element) {
        var val = 0, stt = $(this).attr('name').replace('cs[', '').replace(']', '') * 1;
        if($(this).is("input") && $(this).attr('type') == 'radio')
        {
            var elements = document.getElementsByName($(this).attr('name'));
            for (i=0;i<elements.length;i++) {
                if(elements[i].checked != true) continue;
                val = elements[i].value;
                break;
            }
            /* check if exists */
            if(query.indexOf('&val[cs][' + stt + ']=' + val) > 0) return ;
        }
        else val = $(this).val();
        
        if(val == 0)
        {
            alert('Please choose: "' + $('#name_cs_' + stt).html() + '"');
            $('#name_' + $(this).attr('id')).click();
            query = '';
            return false;
        }
        query += '&val[cs][' + stt + ']=' + val;
    });
    
    if(query == '') return false;
    /* lấy trích lọc ko cố định */
    
    $(arr.doi_tuong_trich_loc_khong_co_dinh).each(function(index, element) {
        var val = 0, stt = $(this).attr('name').replace('cs[', '').replace(']', '') * 1;
        if($(this).is("input") && $(this).attr('type') == 'radio') {
            var elements = document.getElementsByName($(this).attr('name'));
            for (i=0;i<elements.length;i++) {
                if(elements[i].checked != true) continue;
                val = elements[i].value;
                break;
            }
        }
        else val = $(this).val();
        
        if(val == '') {
            if(!confirm('Do you want to skip "' + $('#name_cs_' + stt).html() + '"')) {
                $('#name_' + $(this).attr('id')).click();
                query = '';
                return false;
            }
        }
        if(val != '') query += '&val[csn][' + stt + ']=' + escape(val);
    });
    if(query == '') return false;
    $('#MooDialog').css('visibility', 'visible');
    $('#overlay').css('visibility', 'visible');
    $('#div_dang_mua').html('<a href="javascript:void(this);" onclick=""><img src="http://img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" title="Waiting" /></a>');

    tmps['id'] = arr.san_pham.id.join(',');
    
    /* lay khuyen_mai va bo_san_pham */
    var arrobj = ['khuyen_mai', 'bo_san_pham'];
    for(n in arrobj) {
        tmp = '';
        if(typeof(arr[arrobj[n]]) == 'undefined') continue;
        if(typeof(arr[arrobj[n]]['id']) == 'undefined') continue;
        for(i in arr[arrobj[n]]['id']) {
            tmp += arr[arrobj[n]]['id'][i] + ',';
        }
        if(tmp == '') continue;
        tmp = tmp.substr(0, tmp.length - 1);
        query += '&val[' + arrobj[n] + ']=' + tmp;
    }
    
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.saveCart';
    sParams += query;
    sParams += '&val[list]=' + tmps['id'];
    
    $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        url: getParam('sJsAjax'),
        type: "POST",
        timeout: 10000,
        cache:false,
        data: sParams,
        dataType: 'json',
        beforeSend: function ( xhr ) {
            ajax['datmua'] = xhr;
            $('#div_dang_mua').html('<a href="javascript:void(this);" onclick=""><img src="//img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" title="Waiting" /></a>');
        },
        error: function(jqXHR, status, errorThrown){
            ajax['datmua'] = null;
            if(status == 'timeout')
            {
                alert('Connect server fail.Please try again later.');
            }
            if (typeof(callBackJsDatMua) != 'undefined') {
            	callBackJsDatMua(false);
            };

            closeAjaxLoading(objremoveajaxwait);
        } ,
        success: function (data) {
        	ajax['datmua'] = null;
            if(data == null) {
                $(obj).html('');
                if (typeof(callBackJsDatMua) != 'undefined') {
	            	callBackJsDatMua(false);
	            };
	            closeAjaxLoading(objremoveajaxwait);
                return;
            }
            
            if(data.status == 'error') {
                $('#div_dang_mua').html('<a href="javascript:void(this);" onclick="return js_dat_mua(' + arr + ')"><img src="//img.' + window.location.hostname + '/styles/web/global/images/status_warning.png" title="Error: ' + data[1] + '" /></a>');
                setTimeout('dong_hinh()', 4000);
                if (typeof(callBackJsDatMua) != 'undefined') {
	            	callBackJsDatMua(false);
	            };
                closeAjaxLoading(objremoveajaxwait);
            } else {
                var buycart = localStorage.getItem('save_cart');
                var savingcart = localStorage.getItem('is_buy_cart');
                if (typeof(savingcart) != 'undefined' && savingcart > 0) {
                    localStorage.setItem('is_buy_cart', 0);
                    // chuyển trang khi thực hiện mua giỏ hàng
                    window.location = oParams['sJsHome'] + 'shop_chi_tiet.html';
                    // làm trống giỏ hàng hiện tại để câp nhật mới
                    localStorage.removeItem('shopcart');
                    return false;
                }
                clearTimeout(t_capNhatGioHang);
                /* update store cache */
                localStorage.removeItem('shopcart');
                $('#div_dang_mua').html('<img src="http://img.' + window.location.hostname + '/styles/web/global/images/trich_loc_da_chon.gif" /> ' + langsys['buy-success']);
                if(arr.thanh_toan_ngay)
                {
                    window.location = '/shop_chi_tiet.html';
                    return false;
                }
                setTimeout('dong_hinh()', 500);
                t_capNhatGioHang = setTimeout(function(){
                	capNhatGioHang({
                		'auto'				: 0, 
                		'callBackJsDatMua'	:callBackJsDatMua,
                		'objremoveajaxwait'	:objremoveajaxwait
                	})
                }, 500);
                if(arr.mua_nhanh) $.fancybox.close();
                //Cập nhật localstorage và các tags liên quan
                localStorage.setItem('save_cart', 0);
                $('#js-save-cart').find('.js-icon').removeClass('fa-check');
                $('#js-save-cart').find('.js-icon').addClass('fa-download');
                $('#js-save-cart').find('.js-btn-save').html('Lưu giỏ');
                $('#js-save-cart').addClass('atv');
                
                $('#js-post-cart').addClass('atv');
            }
            $('#overlay').css('visibility', 'hidden');
        }
    });
};
function dong_hinh()
{
	$('#MooDialog').css('visibility', 'hidden');
	$('#overlay').css('visibility', 'hidden');
	$('#showHinh').css('visibility', 'hidden');
	window.onkeydown = null;
	return false;
};
$('#overlay').click(function(e) {
	dong_hinh();
	e.preventDefault();
	return false;
});
/* tao ham, de khi login thanh cong thi goi ham */
/* Cap nhat thong ke */
function xu_ly_thong_ke(data)
{
    obj = document.getElementById('div_dang_truc_tuyen');
    if(obj != undefined) obj.innerHTML = data['visitol'];
    
    obj = document.getElementById('div_tong_luot_truy_cap');
    if(obj != undefined) obj.innerHTML = data['visittotal'];
    if (data['uid'] > 0) {
        obj = document.getElementById('div_user_not_log');
        if(obj != undefined) {
            document.getElementById('div_user_not_log').style.display = 'none';
            document.getElementById('div_user_log').style.display = 'block';
            $('#div_user_log').find('b').html(data['uname']);
        }
    }
    // chat
    if (isset(data['display_chat']) && data['display_chat'] == 1) {
        if (data['chat'] == 1) {
            // support time
            $('#div_chat').addClass('state-active');
            $('#div_chat .text-header').html('Tôi sẵn sàng hỗ trợ bạn!.');
        }
        else {
            // not support time
            $('#div_chat').addClass('state-inactive');
            $('#div_chat .text-header').html('Bạn cần tôi hỗ trợ?');
            sendOfflineMessage();
        }
        t_chat_cap_nhat = setTimeout('chat_cap_nhat()', 500);
        $('#div_chat').show();
    }
    else if(data['chat'] == 1) {
        t_chat_cap_nhat = setTimeout('chat_cap_nhat()', 500);
        $('#div_chat').show();
        $('#div_chat').addClass('state-active');
    }
    // quang cao
    for(var i in data['quang_cao']) {
        hien_thi_quang_cao(data['quang_cao'][i]);
    }
    if(typeof(callbackUserInfo) == 'function'){
        callbackUserInfo(data);  
    } 
    if(typeof(callback) != 'undefined') callback(data);
}
function cap_nhat_thong_ke(callback)
{
	var data = localStorage.getItem('userinfo');
	if (typeof(data) != 'undefined') {
        if(data !== null)
        {
            data = JSON.parse(data);
            xu_ly_thong_ke(data);
            return false;
        }
    }

    var sParams = '&'+ getParam('sGlobalTokenName') +'[call]=user.getUserStatus';
    sParams += '&val[type]=' + generalPage['type'] + '&val[id]=' + generalPage['id'];
    sParams += '&val[refer]=' + escape(document.referrer);
    
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
	beforeSend: function ( xhr ) {
		$('#div_dang_truc_tuyen').html('<img src="http://img.' + window.location.hostname + '/styles/web/global/images/waiting.gif" />');
	},
	error: function(jqXHR, status, errorThrown){
		if(status == 'timeout')
		{
		}
		setTimeout(
			function(){
				cap_nhat_thong_ke();
			},
			10000
		);
	} ,
	success: function (data) {
		if(data == null) data = '';
		/*	tắt chức năng cache, vì cần gửi info liên tục với server, nhằm tracking link*/
        if(data.status != 'success')
            return false;
        data = data.data;
		localStorage.setItem('userinfo', JSON.stringify(data));
		xu_ly_thong_ke(data);
	}
});
}

function thong_ke_truy_cap()
{
	var timeOnSite = new Date();
	var data = '';
	// get data type
	data += '&val[type]=' + generalPage['type'] + '&val[id]=' + generalPage['id'] + '&val[pid]=' + generalPage['pid'] + '&val[vid]=' + generalPage['vid'];
	data += '&val[refer]=' + escape(document.referrer);

	var sParams = '&'+ getParam('sGlobalTokenName') +'[call]=user.statisticsAccess';
    sParams += data;

	$.ajaxCall({
		obj: 'thong_ke_truy_cap',
		data: sParams,
		cache:false,
		callback: function(data){
			if(typeof(data) == 'object' && data.type == 'error') {
				setTimeout(
					function(){
						thong_ke_truy_cap();
					},
					10000
				);
				return ;
			}
			if(data == null) data = [];
			
			for(i in data['delete']) {
				eraseCookie(data['delete'][i]);
			}
			
			log_truy_cap_chi_tiet_stt = data['lid'];
			// đưa cookie lên 1, để khi đóng trình duyệt sẽ đc cập nhật cookie
			createCookie('logdId-' + log_truy_cap_chi_tiet_stt, 1, 7);
			if (typeof(data.login) != 'undefined') {
                usr = localStorage.getItem('userinfo');
                if (typeof(usr) != 'undefined') {
                    
                }
            }
            if (typeof(data.message) != 'undefined' && !empty(data.message)) {
                var code = '';
                if (typeof(data.code) != 'undefined' && !empty(data.code)) {
                    code = data.code;
                }
                if (code == 'exist-email') {
                    content = '<div class="row-poup center">Hộp thư đã tồn tại trên hệ thống. Quý khách vui lòng đăng nhập hoặc lấy lại mật khẩu. Liên hệ 1900 2075 nếu quý khách cần hổ trợ.</div>';
                    content += '<div class="footer-popup align-center"><a class="button-popup button-green" href="/dang_nhap.html">Đăng nhập</a><a class="button-popup button-green" href="/quen_mat_khau.html">Quên mật khẩu</a></div>';
                }
                else {
                    content = '<div class="row-poup center">'+data.message+'</div>';
                }
                showSmartAlert(content, 'Thông báo', 'error');
            }
			$(window).bind('beforeunload', function (e) {
				var endTime = new Date();        //Get the current time.
				timeOnSite = Math.ceil((endTime - timeOnSite)/1000);
				createCookie('logdId-' + log_truy_cap_chi_tiet_stt, timeOnSite, 7);
			});
		},
	});
}
$(function() {
	cap_nhat_thong_ke();
	thong_ke_truy_cap();
});

function createCookie(name,value,days, domain) {
	if(typeof(domain) == 'undefined') domain = ';domain=' + window.location.host;
	else
	{
		domain = ';domain=' + domain;
	}
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+domain+";path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
function onLinkClick(link)
{
    if (isIE)	
    {	
        //Mozilla and FireFox don't support non-standard attribute
	    window.open(link.href, '', +
	    'toolbar=' + link.toolbar +
	    ',location=' + link.location +
	    ',status=' + link.statusbar +
	    ',menubar=' + link.menubar +
	    ',scrollbars=' + link.scrollbars +
	    ',resizable=' + link.resizable +
	    ',width=' + link.width +
	    ',height=' + link.height +
	    ',top=' + link.top + 
	    ',left=' + link.left);
	    return false;
    }
    else
    {
	    window.open(link.href, 'name', 'height=800, width=1024, left=0, top=0, resizable=yes, scrollbars=yes, toolbar=no, status=no');
	    return false;
    }
}

function dem_so_ky_tu(len, type, obj)
{
	var mang_min=Array(25, 25, 165, 205, 1000);
	var mang_max=Array(65, 65, 205, 256, 6000);
	obj += '_goi_y_so_ky_tu';
	if(len == 0)
	{
		document.getElementById(obj).innerHTML = '';
	}
	else if(len < mang_min[type])
	{
		document.getElementById(obj).innerHTML = 'Nên thêm <strong>' + (mang_min[type]-len) + '</strong> ký tự';
	}
	else if(len > mang_max[type])
	{
		document.getElementById(obj).innerHTML = 'Nên bỏ bớt <strong>' + (len-mang_max[type]) + '</strong> ký tự';
	}
	else if(len == mang_max[type])
	{
		document.getElementById(obj).innerHTML = 'Đã đạt, bạn có thể bỏ <strong>' + (len-mang_min[type]) + '</strong> ký tự';
	}
	else
	{
		document.getElementById(obj).innerHTML = 'Đã đạt, và vẫn có thể thêm <strong>' + (mang_max[type]-len) + '</strong> ký tự';
	}
}
function remove_chuoi(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};


var t_dem_nguoc = 0;
var giay_dem=1;
function tat_dem_nguoc()
{
	document.getElementById('div_dem_nguoc').style.display = 'none';
	clearTimeout(t_dem_nguoc);
	giay_dem = 1;
}
function numberFormat(nStr){
	  nStr += '';
	  x = nStr.split('.');
	  x1 = x[0];
	  x2 = x.length > 1 ? ',' + x[1] : '';
	  var rgx = /(\d+)(\d{3})/;
	  while (rgx.test(x1))
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	  return x1 + x2;
}
function dem_nguoc(duong_dan)
{
	if(giay_dem==8)
	{
		clearTimeout(t_dem_nguoc);
		document.getElementById('div_dem_nguoc').style.display = 'none';
		giay_dem = 1;
		window.location = duong_dan;
	}
	else
	{
		if(giay_dem>2)
		{
			document.getElementById('div_dem_nguoc').style.display = 'block';
			document.getElementById('div_dem_nguoc').innerHTML = giay_dem-2;
		}
		t_dem_nguoc = setTimeout('dem_nguoc(\'' + duong_dan+'\')', 1000);
	}
	giay_dem++;
}
function cai_dat()
{
	var newdiv = document.createElement('div');
	newdiv.setAttribute('id', "div_dem_nguoc");
	newdiv.style.position = "absolute";
	newdiv.style.fontSize = "72px";
	newdiv.style.display = "none";
	newdiv.style.fontWeight= "bold"; 
	newdiv.style.zIndex = 999;
	document.body.appendChild(newdiv);
	var x=y=0;
	var firefox=document.getElementById&&!document.all;
	if(document.getElementsByClassName && firefox){
		var obj = document.getElementsByClassName("hieu_ung");		
		for(var i = 0;i<obj.length;i++)
		{
			obj[i].onmouseover = function(e) {
				so_them = 72;
				if(e.clientY < so_them) so_them *= -1;
				x = e.clientX + window.scrollX;
				y = e.clientY + window.scrollY - so_them;
				document.getElementById('div_dem_nguoc').style.left = x+'px';
				document.getElementById('div_dem_nguoc').style.top = y+'px';
				dem_nguoc(this.href);
			}
			obj[i].onmouseout= tat_dem_nguoc;
		}
	}
	else
	{
		var obj = document.getElementsByTagName("a");
		for(var i = 0;i<obj.length;i++)
		{
			if(obj[i].className.indexOf("hieu_ung")!=-1)
			{
				obj[i].onmouseover = function(e) {
					so_them = 72;
					if(event.clientY < so_them) so_them *= -1;
					x = event.clientX  + document.documentElement.scrollLeft + document.body.scrollLeft;
					y = event.clientY + document.documentElement.scrollTop + document.body.scrollTop - so_them;
					document.getElementById('div_dem_nguoc').style.left = x+'px';
					document.getElementById('div_dem_nguoc').style.top = y+'px';
					dem_nguoc(this.href);
				}
				obj[i].onmouseout= tat_dem_nguoc;
			}
		}
	}
}

function cai_dat_mouse(doi_tuong)
{	var firefox=document.getElementById&&!document.all;
	if(document.getElementsByClassName && firefox){
		var obj = document.getElementsByClassName(doi_tuong);
		for(var i = 0;i<obj.length;i++)
		{
			obj[i].onmouseover = function(e) {
				this.style.backgroundColor='#E1EAFE'
			}
			obj[i].onmouseout= function() {
				this.style.backgroundColor='transparent';
			}
		}
	}
	else
	{
		var obj = document.getElementsByTagName("div");
		for(var i = 0;i<obj.length;i++)
		{
			if(obj[i].className.indexOf(doi_tuong)!=-1)
			{
				obj[i].onmouseover = function(e) {
					this.style.backgroundColor='#E1EAFE';
				}
				obj[i].onmouseout = function() {
					this.style.backgroundColor='transparent';
				}
			}
		}
	}
};

/* COMPARE */
var tFindQueue = [];
function findQueue(e, obj, txt_id)
{
	var charcode = !e.charCode ? e.which : e.charCode;
	if(charcode == 13)
	{
		/* 	Nếu là eneter thì dừng lại */
		return true;
	}
	else
	{
		/*
		var regex = new RegExp("^[a-zA-Z0-9]+$");
		var str = String.fromCharCode(charcode);
		if (!regex.test(str)) return false;
		*/
	}
	/*	Clear hàng đợi */
	clearTimeout(tFindQueue[obj]);
	
	/*	Định nghĩa back tương ứng với từng trường hợp */
	var callback;
	if(obj == 'compare')
	{
		callback = 'showSearchCompare';
	}
	else if(obj == 'search')
	{
		callback = 'showSearchCat';
	}
	else if(obj == 'check_user')
	{
		callback = 'showCheckUser';
	}
	/*	callback loading là function thực thi khi chờ */
	if(typeof(eval(callback + 'Loading')) == 'function')
	{
		setTimeout(
			callback + 'Loading(0)',
			1
		);
	}
	
	tFindQueue[obj] = setTimeout(
		function(){
			findTopic(obj, txt_id)
		},
		340
	);
	return true;
}
var tu_dang_tim = [], dataSearchProduct = [];
function findTopic(obj, txt_id)
{
	var field = '', callback, keyword = '', catSearch = 0;
	/*	Khởi tạo các trường thông tin tương ứng ở mỗi trường hợp */
	if(obj == 'compare')
	{
		keyword = $('#' + txt_id).val();
		field = 'id,link,title,store_no,pricemin,pricemax,img_link,thumbnail,note';
		callback = 'showSearchCompare';
	}
	else if(obj == 'search')
	{
		keyword = $('#' + txt_id).val();
		field = 'id,link,title,store_no,pricemin,pricemax,img_link,thumbnail,note';
		callback = 'showSearchCat';
		
		catSearch = $('#searchCatfield option:selected').attr('data-id');
	}
	else if(obj == 'check_user')
	{
		keyword = $('#' + txt_id).val();
		callback = 'showCheckUser';
	}
	/*	keyword là từ khóa đưa vào */
	keyword = $.trim(keyword);
	catSearch *= 1;
	if(!$.isNumeric(catSearch)) catSearch = 0;
	
	if(tu_dang_tim[obj] == undefined) tu_dang_tim[obj] = [];
	if(tu_dang_tim[obj][catSearch] == undefined) tu_dang_tim[obj][catSearch] = '';
	
	if(keyword.length < 3 || tu_dang_tim[obj][catSearch] == keyword)
	{
		// restore last result.
		$('#catsearchResult .message').fadeOut('fast');
		
		if(keyword.length < 3) $('#catsearchResult .content').fadeOut('fast');
		else $('#catsearchResult .content').fadeIn('fast');
		
		return false;
	}
	
	/*	dataSearchProduct là biến toàn cục lưu kết quả từ khóa 
		Nghĩa là Cache kết quả vào biến cục bộ
	*/
	if(dataSearchProduct[obj] == undefined) dataSearchProduct[obj] = [];
	if(dataSearchProduct[obj][catSearch] == undefined) dataSearchProduct[obj][catSearch] = [];
	
	/*	Nếu đã có lưu trong Cache thì đọc từ cache ra*/
	
	if(dataSearchProduct[obj][catSearch][keyword] != undefined)
	{
		tu_dang_tim[obj][catSearch] = keyword;
		
		if(typeof(eval(callback + 'Loading')) == 'function')
		{
			setTimeout(
				callback + 'Loading(1)',
				1
			);
		}
		/*	Cùng 1 hàm callback nếu như truyền đối số nghĩa là tìm giá trị trong kết quả đã có */
		
		setTimeout(
			callback + '("' + txt_id + '")',
			1
		);
		return false;
	}
	
	/*	Nếu kết quả chưa lưu trong cache thì gửi ajax */
	if(xhrAjax[obj] != undefined) xhrAjax[obj].abort();
	var url_ajax = '', data_ajax = '', type_ajax;
	/*	Khởi tạo ajax cho từng trường hợp */
	if(obj == 'check_user')
	{
		url_ajax = setupPathCode(setupPath('s') + "/tools/api.php?type=check_user");
		type_ajax = "POST";
		data_ajax = {'ten_truy_cap': keyword};
	}
	else if(obj == 'compare' || obj == 'search')
	{
		url_ajax = setupPathCode(setupPath('s') + "/tools/tim_san_pham.php?cat=" + catSearch + "&key=" + escape(keyword) + "&field=" + field);
		type_ajax = "GET";
	}
	
	xhrAjax[obj] = $.ajax({
	  	crossDomain:true,
	  	xhrFields: {
	   		withCredentials: true
	  	},
		url: url_ajax,
		type: type_ajax,
		data: data_ajax,
		timeout:5000,
		async:true,
		cache:true,
		beforeSend: function ( xhr ) {
			tu_dang_tim[obj][catSearch] = '';
			if(typeof(eval(callback + 'Loading')) == 'function')
			{
				setTimeout(
					callback + 'Loading(0)',
					1
				);
			}
		},
		dataType: 'json',
		error: function(request, status, err) {
			if(status == 'abort') return ;
			alert('Can not connect Server');
			tu_dang_tim[obj][catSearch] = '';
			if(typeof(eval(callback + 'Loading')) == 'function')
			{
				setTimeout(
					callback + 'Loading(1)',
					1
				);
			}
		},
		success: function (data) {
			/* Khi thành công đưa từ khóa / kết quả vừa tìm kiếm vào Cache */
			tu_dang_tim[obj][catSearch] = keyword;
			dataSearchProduct[obj][catSearch][keyword] = data;
			
			if(typeof(eval(callback + 'Loading')) == 'function')
			{
				setTimeout(
					callback + 'Loading(1)',
					1
				);
			}
			setTimeout(
				callback + '()',
				1
			);
		}
	});
}
var datacompareproduct = '';
function downCompareList(callback)
{
	if(datacompareproduct == 'loading')
	{
		setTimeout(
			function(){
				downCompareList(callback)
			},
			2000
		);
		return ;
	}
	if(datacompareproduct != '')
	{
		setTimeout(
			callback + '()',
			1
		);
		return ;
	}
	$.ajax({
		crossDomain:true,
		xhrFields: {
			withCredentials: true
		},
		
	url: setupPathCode(setupPath('s') + "/tools/so_sanh_san_pham.php"),
	type: "GET",
	timeout:8000,
	async:true,
	beforeSend: function ( xhr ) {
		datacompareproduct = 'loading';
		if(typeof(eval(callback + 'Loading')) == 'function')
		{
			setTimeout(
				callback + 'Loading(0)',
				1
			);
		}
	},
	dataType: 'json',
	error: function(request, status, err) {
		if(status == 'abort') return ;
		alert('Can not connect Server');
		datacompareproduct = '';
		if(typeof(eval(callback + 'Loading')) == 'function')
		{
			setTimeout(
				callback + 'Loading(1)',
				0
			);
		}
	},
	success: function (data) {
		datacompareproduct = data;
		if(typeof(eval(callback + 'Loading')) == 'function')
		{
			eval(callback + 'Loading(1)');
		}
		eval(callback + '()');
	}
	});
}
var dataSearchResult = [];

function showSearchCatLoading(act)
{
	if(act == 0)
	{
		var obj = 'search';
		var catSearch = $('#searchCatfield option:selected').attr('data-id');
		catSearch *= 1;
		if(!$.isNumeric(catSearch)) catSearch = 0;
		if(tu_dang_tim[obj] == undefined) tu_dang_tim[obj] = [];
		tu_dang_tim[obj][catSearch] = '';
		$('#catsearchResult').fadeIn('fast');
		$('#catsearchResult .content').fadeOut('fast');
		$('#catsearchResult .message').fadeIn('fast');
	}
	else
	{
		$('#catsearchResult .message').fadeOut('fast');
		$('#catsearchResult .content').fadeIn('fast');
		$('#catsearchResult').fadeOut('fast');
	}	
}
function showSearchCat(txt_id)
{
	var tmps = [], content = '';
	var obj = 'search';
	var catSearch = $('#searchCatfield option:selected').attr('data-id');
	catSearch *= 1;
	if(!$.isNumeric(catSearch)) catSearch = 0;
	var keyword = tu_dang_tim[obj][catSearch];
	if(dataSearchResult[obj] == undefined) dataSearchResult[obj] = [];
	if(dataSearchProduct[obj][catSearch] == undefined || dataSearchProduct[obj][catSearch][keyword] == undefined) return false;
	
	if(dataSearchProduct[obj][catSearch][keyword]['gia_ban'] == undefined) dataSearchProduct[obj][catSearch][keyword]['gia_ban'] = [];
	if(dataSearchProduct[obj][catSearch][keyword]['thanh_tien'] == undefined) dataSearchProduct[obj][catSearch][keyword]['thanh_tien'] = [];
	
	for(i = 0; i < dataSearchProduct[obj][catSearch][keyword]['stt'].length; i++)
	{
		tmps = dataSearchProduct[obj][catSearch][keyword];
		
		con = 0;
		$('#compareproduct li').each(function(index, element) {
			if(tmps['stt'][i] == $(this).attr('data-id'))
			{
				con = -1;
				return false;
			}
		});
		if(con == -1) continue;
		
		if(tmps['thanh_tien'][i] == undefined) tmps['thanh_tien'][i] = 0;
		
		dataSearchResult[obj][tmps['stt'][i]] = {
			'id' : tmps['stt'][i],
			'link' : tmps['duong_dan'][i],
			'title' : tmps['ten'][i],
			'price_min' : tmps['thanh_tien'][i],
			'price_max' : tmps['gia_ban'][i],
			'img_link' : tmps['duong_dan_hinh'][i],
			'thumbnail' : [],
		};
		content += '<div data-id="' + tmps['stt'][i] + '"><div class="fleft"><img src="' + tmps['duong_dan_hinh'][i] + '" /></div><div class="fleft"><div>' + tmps['ten'][i] + '</div><div>Giá: <span>' + numberFormat(tmps['gia_ban'][i]) + '</span></div></div></div>';
	}
	if(content != '')
	{
		$('#catsearchResult .content').html(content);
		$('#catsearchResult').fadeIn('fast');
		$('#catsearchResult .content').fadeIn('fast');
		$('#catsearchResult .content > div').unbind('click').click(function(e) {
			var arr = dataSearchResult['search'][$(this).attr('data-id')];
			
			$('#' + txt_id).val('');
			$('#catsearchResult').fadeOut('fast');
			
			window.location = arr['link'];
        });
	}
}
function showSearchCompareLoading(act)
{
	if(act == 0)
	{
		var obj = 'compare';
		var catSearch = 0;
		if(tu_dang_tim[obj] == undefined) tu_dang_tim[obj] = [];
		tu_dang_tim[obj][catSearch] = '';
		$('#compareloading').fadeIn('fast');
	}
	else
	{
		$('#compareloading').fadeOut('fast');
		$('#comparesearchResult').fadeOut('fast');
	}	
}
function showSearchCompare(txt_id)
{
	var tmps = [], content = '';
	var obj = 'compare';
	var catSearch = 0;
	var keyword = tu_dang_tim[obj][catSearch];
	
	if(dataSearchResult[obj] == undefined) dataSearchResult[obj] = [];
	if(dataSearchProduct[obj][catSearch][keyword] == undefined) return false;
	
	var con = 0;
	for(i = 0; i < dataSearchProduct[obj][catSearch][keyword]['stt'].length; i++)
	{
		tmps = dataSearchProduct[obj][catSearch][keyword];
		
		con = 0;
		$('#compareproduct li').each(function(index, element) {
			if(tmps['stt'][i] == $(this).attr('data-id'))
			{
				con = -1;
				return false;
			}
		});
		if(con == -1) continue;
		
		if(tmps['thanh_tien'][i] == undefined) tmps['thanh_tien'][i] = 0;
		
		dataSearchResult[obj][tmps['stt'][i]] = {
			'id' : tmps['stt'][i],
			'link' : tmps['duong_dan'][i],
			'title' : tmps['ten'][i],
			'store_no' : tmps['so_cua_hang'][i],
			'price_min' : tmps['thanh_tien'][i],
			'price_max' : tmps['gia_ban'][i],
			'img_link' : tmps['duong_dan_hinh'][i],
			'thumbnail' : [],
			'note' : tmps['mo_rong_ghi_chu'][i],
		};
		
		content += '<div data-id="' + tmps['stt'][i] + '"><div class="fleft"><img src="' + tmps['duong_dan_hinh'][i] + '" /></div><div class="fleft"><div>' + tmps['ten'][i] + '</div><div>Giá từ: <span>' + numberFormat(tmps['thanh_tien'][i]) + '</span></div></div></div>';
	}
	if(content != '')
	{
		$('#comparesearchResult').html(content);
		$('#comparesearchResult').fadeIn('fast');
		$('#comparesearchResult > div').unbind('click');
		$('#comparesearchResult > div').click(function(e) {
			var arr = dataSearchResult['compare'][$(this).attr('data-id')];
			
			tu_dang_tim[obj][catSearch] = '';
			$('#' + txt_id).val('');
			$('#comparesearchResult').fadeOut('fast');
			
			addcompareproduct(arr);
			$.ajax({
		crossDomain:true,
		xhrFields: {
			withCredentials: true
		},
			url: setupPathCode(setupPath('s') + "/tools/so_sanh_san_pham.php?act=save&list=" + arr['id']),
			type: "GET",
			timeout:8000,
			beforeSend: function ( xhr ) {
			},
			dataType: 'json',
			error: function() {
				alert('Can not connect Server');
			},
			success: function (data) {
				
			}
			});
        });
	}
}
function showpopupcompareLoading(act)
{
	if(act == 0)
	{
		$('#compareloading').fadeIn('fast');
	}
	else
	{
		$('#compareloading').fadeOut('fast');
	}	
}
function showpopupcompare()
{
	var arr;
	if(datacompareproduct == undefined) return false;
	for(i = 0; i < datacompareproduct['stt'].length; i++)
	{
		arr = {
			'id' : datacompareproduct['stt'][i],
			'link' : datacompareproduct['duong_dan'][i],
			'title' : datacompareproduct['ten'][i],
			'store_no' : datacompareproduct['so_cua_hang'][i],
			'price_min' : datacompareproduct['thanh_tien'][i],
			'price_max' : datacompareproduct['gia_ban'][i],
			'img_link' : datacompareproduct['duong_dan_hinh'][i],
			'thumbnail' : [],
			'note' : datacompareproduct['mo_rong_ghi_chu'][i],
		};
		addcompareproduct(arr);
	}
}
function addcompareproduct(arr)
{
	var content = '', i = 0;
	$('#compareproduct li').each(function(index, element) {
        if(arr['id'] == $(this).attr('data-id'))
		{
			i = -1;
			return false;
		}
    });
	if(i == -1) return false;
	
	arr['price_min'] = numberFormat(arr['price_min']);
	arr['price_max'] = numberFormat(arr['price_max']);
	
	arr['title_html'] = arr['title'];
	
	content = '<li data-id="' + arr['id'] + '"><div class="hot"></div>';
	
	if(!arr['thumbnail'].length > 0)
	{
		content += '<ul class="circlegallery">';
		for(i = 0; i < arr['thumbnail'].length; i++)
			content += '<li>' + arr['thumbnail'][i] + '</li>';
    	content += '</ul>';
	}
	content += '<div class="productImage"><a href="' + arr['link'] + '" title="' + arr['title_html'] + '"><img src="' + arr['img_link'] + '" alt="' + arr['title_html'] + '"></a></div><div class="productName"><a href="' + arr['link'] + '" title="' + arr['title_html'] + '"><span>' + arr['title'] + '</span></a></div><div class="productRating rating"><span class="rating-45"></span></div><div class="productFeature">' + arr['note'] + '</div><div><div class="productCountry"><span>Giá tại Việt Nam</span></div><div class="priceRange"><span><div class="lowestPrice amountPrice"><span class="priceTag">Từ</span>' + arr['price_min'] + '</div><span class="priceSeperator">-</span><div class="highestPrice amountPrice">';
	if(arr['price_min'] != arr['price_max'])
		content += '<span class="priceTag">Đến</span>' + arr['price_max'];
	else
		content += '<small>Không có giá cạnh tranh</small>';
	content += '</div></span></div><div class="productCount"><a href="' + arr['link'] + '" title="' + arr['title_html'] + '"><span>So giá ' + arr['store_no'] + ' điểm bán</span></a></div></div></li>';
	$('#compareproduct').append(content);
}
/* COMPARE */

/* zoom image */

 (function($){
	var
	props = ['Width', 'Height'],
	prop;

	while (prop = props.pop()) {
	(function (natural, prop) {
	  $.fn[natural] = (natural in new Image()) ? 
	  function () {
	  return this[0][natural];
	  } : 
	  function () {
	  var 
	  node = this[0],
	  img,
	  value;

	  if (node.tagName.toLowerCase() === 'img') {
		img = new Image();
		img.src = node.src,
		value = img[prop];
	  }
	  return value;
	  };
	}('natural' + prop, prop.toLowerCase()));
	}
  }(jQuery));
function zoomImageContent(obj)
{
	$(obj).imagesLoaded(function() {
		$(obj + ' img').each(function(){
			if($(this).naturalWidth() <= $(this).width() && $(this).naturalHeight() <= $(this).height()) return true;

			//$(this).parent().addClass('zoomImageContent');
			$(this).css('cursor', 'move');
			$(this).click(function(e) {
				e.preventDefault();
				$.fancybox(
					{
						'autoDimensions'    : false,
						'width'             : 350,
						'height'            : 'auto',
						'transitionIn'      : 'elastic',
						'transitionOut'     : 'elastic',
						'speedIn'			:	600, 
						'speedOut'			:	200, 
						'overlayShow'		:	false,
						'href'				: $(this).attr('src'),
						'title'				:	$(this).attr('alt'),
					}
				);
				return false;
            });
		});
	});
}
function get_hash_tag(){
	var data = {};
	$.each(window.location.hash.replace("#", "").split("&"), function (i, value) {
        value = value.split("=");
        data[value[0]]= value[1]; 
    });
    return data; 
}
/*	Nhóm function đăng nhập popup 
$(function(){
	$('body').append('<div id="popup_dang_nhap">\
	    <div class="container">\
	    	<div class="header_control" style="height: 24px">\
	    		<a class="dang_nhap" href="#"><i class="icon_log eff_transition"></i> Đăng nhập</a>\
	    		<a class="dang_ky" href="#"><i class="icon_reg eff_transition"></i> Đăng ký</a>\
	    		<a class="close_popup" href="#"><i class="icon_close eff_transition"></i> Đóng</a>\
	    		<div class="clear"></div>\
	    	</div>\
	    	<div class="outer_content_popup">\
	    		<div class="content_popup"></div>\
	    	</div>\
	    </div>\
	</div>');
	open_popup();
	close_popup();
	close_err();
	yeu_cau_dang_nhap();
});
*/
/*	Biến state popup dùng để xác định đăng nhập / đăng ký có chạy popup hay không */
/*	hàm mở popup */
function open_popup(){
	/*	1. popup sẽ được mở khi có sự kiện click vào class="pop_up_dang_nhap"
		2. tại class pop_up_dang_nhap sẽ có thêm class dang_nhap / dang_ky / quen_mat_khau để cho biết loại popup được mở
	*/
	$('.pop_up_dang_nhap').unbind('click').click(function(){
		/*	Kiểm tra loại popup được mở */
		var type = '';
		if($(this).hasClass('dang_nhap')) type = 'dang_nhap';
		else if($(this).hasClass('dang_ky')) type = 'dang_ky';
		else if($(this).hasClass('quen_mat_khau')) type = 'quen_mat_khau';

		change_type_popup(type);
		return false;
	});
	$('.header_control .dang_nhap').unbind('click').click(function(){
		change_type_popup('dang_nhap');
	});
	$('.header_control .dang_ky').unbind('click').click(function(){
		change_type_popup('dang_ky');
	});
}
/*	Hàm quy định nội dung được hiển thị trong content popup
	Tham số truyển vào là type cho biết loại dang_nhap / dang_ky / quen_mat_khau
	Dựa vào type để callAjax tương ứng và có kèm theo noinclude
*/
function change_type_popup(type){
	/*	Mở popup */
	$('#popup_dang_nhap').addClass('open');
	/*	Ẩn tất header control dang_nhap / dang_ky
		Sẽ được hiển thị lại ở loại type tương ứng 
	*/
	$('.header_control .dang_nhap, .header_control .dang_ky').hide();
	if (type == 'dang_nhap') {
		$('.header_control .dang_ky').show();

        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.callLoginForm';
    	sParams += '&act=noinclude';

        $.ajaxCall({
			data: sParams,
            type: 'POST',
            timeout: 5000,
            dataType: 'html',
            callback: function(data){
            	if(typeof(data) == 'object' && data.type == 'error')
                {
                	/*	Trường hợp lỗi ko lấy được data hay trục trặc nào đó...*/
                    $('.content_popup').html('<div class="wrap_login">\
										    <div class="err_list_login">\
										        <div class="content_err">Đã có lỗi xảy ra. Vui lòng thử lại</div>\
										        <div class="close_err_login"></div>\
										    </div>\
										</div>');
					return false;
                }
                /*	Data lấy được sẽ đẩy vào .content_popup 
                	Sau đó gọi các hàm khởi tạo*/
            	var string_content = data;
                $('.content_popup').html(string_content);
				
				$('.row_login_acc .quen_mat_khau').unbind('click').click(function(){
					change_type_popup('quen_mat_khau');
				});
				close_err();
            }
        });
        return false;
	};
	if (type == 'dang_ky') {
		$('.header_control .dang_nhap').show();


        sParams = '&'+getParam('sGlobalTokenName') + '[call]=user.callRegisterForm';
   		sParams += '&act=noinclude';

        $.ajaxCall({
			data: sParams,
            type: 'POST',
            timeout: 5000,
            dataType: 'html',
            callback: function(data){
            	if(typeof(data) == 'object' && data.type == 'error')
                {
                    $('.content_popup').html('<div class="wrap_login">\
										    <div class="err_list_login">\
										        <div class="content_err">Đã có lỗi xảy ra. Vui lòng thử lại</div>\
										        <div class="close_err_login"></div>\
										    </div>\
										</div>');
					return false;
                }
                var string_content = data;
                $('.content_popup').html(string_content);
				/*check_dong_y_dieu_khoan();
	        	change_step();*/
	        	close_err();
            }
        });
        return false;
	};
	if (type == 'quen_mat_khau') {
		$('.header_control .dang_ky, .header_control .dang_nhap').show();
		var url = '/quen_mat_khau.html?act=noinclude';
        url = setupPathCode(setupPath('s') + url);

        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.callForgotForm';
    	sParams += '&act=noinclude';

        $.ajaxCall({
			data: sParams,
            type: 'POST',
            timeout: 5000,
            dataType: 'html',
            callback: function(data){
            	if(typeof(data) == 'object' && data.type == 'error')
                {
                    $('.content_popup').html('<div class="wrap_login">\
										    <div class="err_list_login">\
										        <div class="content_err">Đã có lỗi xảy ra. Vui lòng thử lại</div>\
										        <div class="close_err_login"></div>\
										    </div>\
										</div>');
					return false;
                }
                var string_content = data;
                $('.content_popup').html(string_content);
				$('.content_popup').html(string_content);
				/*init_qmk();*/
				close_err();
            }
        });
	};
};

/*	hàm đóng popup : remove class open để đóng*/
function close_popup(){
	$('.close_popup').unbind('click').click(function(){
		$('#popup_dang_nhap').removeClass('open');
	});
}

/*	Dùng để close danh sách lỗi của đăng nhập / đăng xuất / đăng ký
	Khi add class hide thì danh sách lỗi sẽ ẩn đi*/
function close_err(){
	$(".close_err_login").unbind('click').click(function(){
		$(this).parent('.err_list_login').addClass('hide');
	})
}

/*	Hàm thay đổi trạng thái check điều khoản 
	Class check / un_check quy định background checkbox*/
function check_dong_y_dieu_khoan(){
    $('.dong_y_dieu_khoan').unbind('click').click(function(){
        if($(this).hasClass('check')){
            $(this).removeClass('check');
            $(this).addClass('un_check');
        }else{
            $(this).removeClass('un_check');
            $(this).addClass('check');
        }
    });
}
/*	Complete dùng khi hoàn thành thao tác ở đăng nhập / đăng xuất
	Dùng biến $('#popup_dang_nhap').hasClass('open') để xác định là 
	đăng nhập / đăng xuất ở popup hay bình thường
	- true 		: 		Dùng popup 		-> 		Close popup
	- false		: 		Bình thường		->		Chạy progress bar
*/
function complete_task(){
	if ($('#popup_dang_nhap').hasClass('open') == true) {
		/*	Trước khi đóng popup sẽ lấy thông tin người dùng để trả về call back */

		var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.getUserStatus';

		$.ajaxCall({
			data: sParams,
            type: 'POST',
            timeout: 8000,
            callback: function(data){
            	if(typeof(callback_close_popup) == 'function') callback_close_popup(data);
            }
        });
        /*	Giả lập sự kiện click vào điểm đóng popup */
		$('.close_popup').trigger('click');
	}else{
		setTimeout('progress_bar_login()', 1000);
	}
}
/*	Xử lý trường hợp những chỗ cần đăng nhập để xử lý thao tác nào đó
	Dùng class "yeu_cau_dang_nhap" để xử lý để gọi popup
	Khi đã đăng nhập thì class yeu_cau_dang_nhap sẽ được remove
*/
function yeu_cau_dang_nhap(){
	$('.yeu_cau_dang_nhap').unbind('click').click(function(){
		if ($(this).hasClass('yeu_cau_dang_nhap')) {
			change_type_popup('dang_nhap');
			close_popup();
		};
	})
}
/*	End - nhóm function đăng nhập popup */
/*	Nhóm hàm đọc màu của ảnh */
$(function(){
	load_color_image();
});
/*	1. Hàm đọc danh sách hành
	2. Kiểm tra hình đã lưu trong sessionStorage?
		2.1 Nếu chưa lưu thì đẩy vào danh sách hình
	3. ajaxCall danh sách hình để lấy hình thông số và lưu lại
	4. Gọi hàm show color
*/
function load_color_image(){
	/*	Danh sách hình */
	var list_img = [];

	$('.load_img').each(function(){
		var img = $(this).attr('data-src');

		if(sessionStorage.getItem(img) !== 'null' && list_img.indexOf(img) < 0){
			img = img.replace('http://img.' + global.domain + '/','');
			list_img.push(img);
		}
	});

	/*	Nếu ko có hình mới được thêm -> đọc hình cũ từ session storage */
	if(list_img.length <= 0){
		show_color();
		return false;
	}

	/*	Nếu có hình mới thì gửi ajax để lấy thông số */
	var url = 'http://img.' + global.domain + ':8080/tools/api.php?type=pixel&list=' + list_img.join(',');	
	$.ajax({
		url: url, 
		success: function(data)
		{
			/*	ERROR */
			if(typeof(data) == 'object' && data.type == 'error'){
				window.location.reload();
				return;
			}

			/*	đọc dữ liệu */
        	data = JSON.parse(data);
        	if (data.content != null) {
        		$.each(data.content, function(index, value){
	        		var img_url = value['link']['url'];
	        		
	        		/*	nhận các thông số hình ảnh */
	        		var img_data = {};
	        		img_data['width'] = value['size']['width'];
	        		img_data['height'] = value['size']['height'];
	        		img_data['light'] = value['light'];
	        		
	        		/*	lấy màu hiển thị nhiều nhất làm màu đặc trưng
	        			Nếu là màu trắng thì chuyển qua màu thứ 2 */
	        		var max = 0;
	        		$.each(value['hex'], function(index, value){
	        			if(value['point'] > max && index.toUpperCase() != 'FFFFFF'){
	        				max = value['point'];
	        				img_data['bg'] = index;
	        			}
	        		});

	        		/*	Lưu vào session storage 
	        			- key 		: 	Link ảnh
	        			- value 	: 	Dữ liệu ảnh*/
					sessionStorage.setItem(img_url, JSON.stringify(img_data));
	        	});
        	};
        	show_color();
    	}
    });
}
function show_color(){
	/*	Duyệt tất cả hình ảnh có chứa thẻ load_img*/
	$('.load_img').each(function(){
		var obj = $(this);
		/*	Đọc link và thẻ alt */
		var img = obj.attr('data-src');
		var alt = obj.attr('alt');
		
		if (typeof(alt) != 'undefined') 
			alt = 'Hình ảnh: ' + alt; 
		else 
			alt = '';

		var data = JSON.parse(sessionStorage.getItem(img));
		
		/*	Sau khi lấy thông tin -> thiết lập màu nền và kích thước ảnh 
			Hình ảnh chỉ load sau khi có sự kiện scroll xảy ra*/
		
		obj.css({
			'background'	: 	'#' + data['bg'],
			'width'			: 	data['width']+'px',
			'height'		: 	data['height']+'px'
		});
		obj.attr('alt', alt);

		/*	Nếu ảnh màu sáng thì color: #000 */
		if (data['light'] == 1) {
			obj.css({'color':'#000'});
		};

		change_image_color();
		$(window).scroll(function(event) {
			change_image_color();
		});	
	});	
}

/*	Khi scroll chuột thì tính vị trí hình ảnh để load src 
	Hình ảnh được load khi vị trí mép trên 200, mép dưới 200*/
function change_image_color(){
	var top_windows = $(window).scrollTop();
	var bottom_windows = top_windows + $(window).height();
	$('.load_img').each(function(){
		var obj = $(this);
		var img = obj.attr('data-src');
		var position = obj.offset().top;
		if (top_windows - 200 <= position && position <= bottom_windows + 200) {
			obj.animate({opacity: 0.75},300);
			setTimeout(function(){
				obj.animate({opacity: 1},200);
				obj.css({'padding':'0'});
				obj.attr('src', img);
			}, 150);
			obj.removeClass('load_img');
		};
	});
}
/*	End - Nhóm hàm đọc màu của ảnh */