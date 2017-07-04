/* ajax */
var xhrAjax = {};
window.onbeforeunload = function() 
{
	// remove ajax
	for(i in xhrAjax)
	{
		if(typeof(xhrAjax[i]) == 'undefined') continue;
		xhrAjax[i].abort();
    }    
};

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
    
	if(arr['url'].substr(0, 1) == '/' && arr['url'].indexOf('//') == -1) arr['url'] = document.location.protocol + '//' + window.location.host + arr['url'];
	callback = arr['callback'];
	
	xhrAjax[arr['obj']] = $.ajax({
		type: arr['type'],
		url: arr['url'],
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

if(typeof(jQuery) != 'undefined')
{
	var tinyMCE;
	// insert at cursor with textara
	jQuery.fn.extend({
	insertAtCaret: function(myValue){
	  return this.each(function(i) {
		if (document.selection) {
		  //For browsers like Internet Explorer
		  this.focus();
		  sel = document.selection.createRange();
		  sel.text = myValue;
		  this.focus();
		}
		else if (this.selectionStart || this.selectionStart == '0') {
		  //For browsers like Firefox and Webkit based
		  var startPos = this.selectionStart;
		  var endPos = this.selectionEnd;
		  var scrollTop = this.scrollTop;
		  this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
		  this.focus();
		  this.selectionStart = startPos + myValue.length;
		  this.selectionEnd = startPos + myValue.length;
		  this.scrollTop = scrollTop;
		} else {
		  this.value += myValue;
		  this.focus();
		}
	  })
	}
	});
}
// end
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
			popup = (newwin.document || newwin);
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
// main chinh

function dem_so_ky_tu(len, type, obj)
{
    return;
	var objs = document.getElementById(obj);
	if(objs == null) return ;
	var mang_min=Array(25, 25, 165, 205, 1000);
	var mang_max=Array(65, 65, 205, 256, 6000);
	obj += '_goi_y_so_ky_tu';
	if(len == 0)
	{
		objs.innerHTML = '';
	}
	else if(len < mang_min[type])
	{
		objs.innerHTML = 'Nên thêm <strong>' + (mang_min[type]-len) + '</strong> ký tự';
	}
	else if(len > mang_max[type])
	{
		objs.innerHTML = 'Nên bỏ bớt <strong>' + (len-mang_max[type]) + '</strong> ký tự';
	}
	else if(len == mang_max[type])
	{
		objs.innerHTML = 'Đã đạt, bạn có thể bỏ <strong>' + (len-mang_min[type]) + '</strong> ký tự';
	}
	else
	{
		objs.innerHTML = 'Đã đạt, và vẫn có thể thêm <strong>' + (mang_max[type]-len) + '</strong> ký tự';
	}
}


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

//end
// bookmark & homepage
function dat_vao_bookmark(title){
var url = 'http://' + window.location.hostname;
   if (document.all)
     window.external.AddFavorite(url, title);
   else if (window.sidebar)
     window.sidebar.addPanel(title, url, "")
   else if (window.sidebar&&window.sidebar.addPanel)
     window.sidebar.addPanel(title,url,"");
}
function dat_lam_trang_chu(i)
{
	var url = 'http://' + window.location.hostname;
	if (document.all)
    {
        document.body.style.behavior='url(#default#homepage)';
  document.body.setHomePage(url);
 
    }
    else if (window.sidebar)
    {
    if(window.netscape)
    {
         try
   {  
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
         }  
         catch(e)  
         {  
    alert("this action was aviod by your browser，if you want to enable，please enter about:config in your address line,and change the value of signed.applets.codebase_principal_support to true");  
         }
    } 
    var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
    prefs.setCharPref('browser.startup.homepage', url);
 }

}

// giao_dien.js
function doi_giao_dien(gia_tri)
{
	var date = new Date();
	date.setTime(date.getTime()+(31104000000));
	document.cookie = 'giao_dien=' + gia_tri + '; expires=' + date.toGMTString() + '; path=/';
	window.location.reload(true);

}

// search.js
var str = '...nhập từ khóa cần tìm...';

function setValue(obj) {
    if (obj.value == '') {
        obj.value = str;
        obj.style.color = '#5A5A5A';
    }
    else if (obj.value == str) {
        obj.value = '';
        obj.style.color = '#1E1E1E';
    }
}

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
    if (length < 2 || obj.tim_kiem_tu_khoa.value == str) {
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

// notification
var time_pushNotification = 0;
function pushNotification(callback)
{
    return false;
	$.ajax({
	url: document.location.protocol + '//' + window.location.host + '/tools/acp/thong_bao.php?t=' + time_pushNotification,
	type: "GET",
	timeout: 3000,
	cache: false,
	dataType: 'json',
	beforeSend: function ( xhr ) {
	},
	success: function (data) {
		if(data == null) data = [];
		callback(data);
	}
	});
	setTimeout(
		function(){
			time_pushNotification = Date.now().toString().substr(0, 10);
			pushNotification(callback);
		},
		5000
	);
	return false;
}