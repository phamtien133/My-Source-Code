var dang_luu_cot = [];
var t_an_toolbar;
function sua_logo()
{
	var hinh_dai_dien = '';

	content = '<form method="post" onsubmit="return luu_logo()"> \
			<table>\
				<tr>\
				<td>Hình đại diện:</td> \
				<td><input type="text" style="width:485px" id="slide_hinh_dai_dien" value="' + hinh_dai_dien + '" /><button type="button" onclick="opHinhAnh()"><span class="round"><span>Tải hình lên</span></span></button></td> \
				</tr>\
				</table>\
				<div class="cot_admin_footer"><input type="submit" value="Save" /> | <input type="button" onclick="bo_luu_cot()" value="Cancel" /></div>\
		</form>\
	';
	document.getElementById('div_quang_cao_tieu_de').innerHTML = 'Đổi Logo';
	document.getElementById('div_quang_cao_noi_dung').innerHTML = content;
	
	document.getElementById('cot_form').style.display = "block";
	return false;
}
function logo_mouseOverCot(handler, e)
{
	//var handler = document.getElementById(id);
	var reltg = e.relatedTarget ? e.relatedTarget :
	e.type == 'mouseout' ? e.toElement : e.fromElement;
	while (reltg && reltg != handler) reltg = reltg.parentNode;
	if(reltg == handler) return false;
	
	var newdiv = document.getElementById('div_toolbar');
	newdiv.style.left = e.pageX + 'px';
	newdiv.style.top = e.pageY + 'px';
	newdiv.style.display = 'block';
}
function logo_mouseOutCot(handler, e)
{
	//var handler = document.getElementById(id);
	var reltg = e.relatedTarget ? e.relatedTarget :
	e.type == 'mouseout' ? e.toElement : e.fromElement;
	while (reltg && reltg != handler) reltg = reltg.parentNode;
    if(reltg == handler) return false;
	
	handler.style.backgroundImage = "";
	
	clearTimeout(t_an_toolbar);
	t_an_toolbar = setTimeout('an_toolbar()', 3000);
}
function an_toolbar()
{
	clearTimeout(t_an_toolbar);
	newdiv = document.getElementById('div_toolbar');
	newdiv.style.display = 'none';
}
function cai_dat_chinh_sua_logo()
{
	var objs = document.getElementsByClassName('logo'), i;
	if(objs  == null) return ;
	for (i = 0; i < objs.length; i++)
	{
		objs[i].onmouseover = function(event)
		{
			logo_mouseOverCot(this, event);
		}
		objs[i].onmouseout = function(event)
		{
			logo_mouseOutCot(this, event);
		}
	}
	
	
	var newdiv = document.getElementById('div_toolbar');
	if(newdiv == null)
	{
		newdiv = document.createElement('div');
		newdiv.setAttribute('id', 'div_toolbar');
		newdiv.setAttribute('class', 'div_toolbar');
		newdiv.innerHTML = '<a href="#" onclick="return sua_logo()"><img src="http://img.' + window.location.hostname + '/styles/web/global/images/edit.png" /> | <a href=' + document.location.protocol + '//' + window.location.host + '"/acp/?type=doi_hinh">Đổi hình</a>';
		newdiv.style.display = 'none';
		document.body.appendChild(newdiv);
	}
	objs = document.getElementById('div_toolbar');
	objs.onmouseover =  function(event)
	{
		clearTimeout(t_an_toolbar);
	}
	objs.onmouseout =  function(event)
	{
		clearTimeout(t_an_toolbar);
		t_an_toolbar = setTimeout('an_toolbar()', 3000);
	}
	
}

function luu_slide(t, id)
{
	if(dang_luu_cot['slide' + t])
	{
		alert('Đang lưu dữ liêu. Nếu bạn muốn bỏ qua vui lòng làm mới trang.');
		return false;
	}
	dang_luu_cot['slide' + t] = true;
	var type = '';
	if(t == 1) type = 'slide-chinh';
	else type = 'slide-phu';
	// ajax get content from main slide
	var ten = '', mo_ta = '', duong_dan = '', hinh_dai_dien = '', content = '';
	
	ten = document.getElementById('slide_ten').value;
	duong_dan = document.getElementById('slide_duong_dan').value;
	mo_ta = document.getElementById('slide_mo_ta').value;
	hinh_dai_dien = document.getElementById('slide_hinh_dai_dien').value;
	
	
	$.ajaxCall({
		url: '/tools/acp/get_set_slide.php?type=' + type + '&id=' + id,
		data: {
			ten: ten,
			duong_dan: duong_dan,
			mo_ta: mo_ta,
			hinh_dai_dien: hinh_dai_dien,
			submit: 1,
		},
		type: 'POST',
		timeout: 8000,
		callback: function(data){
			if(typeof(data) == 'object' && data.type == 'error')
			{
				return ;
			}
			dang_luu_cot['slide' + t] = false;
			if(http.responseText == '')
			{
				alert('Đã có lỗi xảy ra. Không thể tải dữ liệu');
				return false;
			}
			var data = eval("("+ http.responseText +")");
			if(data.error != '')
			{
				alert(data.error);
			}
			else
			{
				window.location.reload(true);
				bo_luu_cot();
			}
		},
    });
	return false;
}
function sua_slide(t, id)
{
	if(dang_luu_cot['slide' + t])
	{
		alert('Đang tải dữ liêu. Nếu bạn muốn bỏ qua vui lòng làm mới trang.');
		return false;
	}
	dang_luu_cot['slide' + t] = true;
	var type = '';
	if(t == 1) type = 'slide-chinh';
	else type = 'slide-phu';
	// ajax get content from main slide
	var ten = '', mo_ta = '', duong_dan = '', hinh_dai_dien = '', content = '';
	
	$.ajaxCall({
		url: '/tools/acp/get_set_slide.php?type=' + type + '&id=' + id,
		timeout: 8000,
		callback: function(data){
			if(typeof(data) == 'object' && data.type == 'error')
			{
				return ;
			}
			dang_luu_cot['slide' + t] = false;
			if(http.responseText == '')
			{
				alert('Đã có lỗi xảy ra. Không thể tải dữ liệu');
				bo_luu_cot();
				return false;
			}
			var data = eval("("+ http.responseText +")");
			ten = data.ten;
			duong_dan = data.duong_dan;
			mo_ta = data.mo_ta;
			hinh_dai_dien = data.hinh_dai_dien;
			
			content = '<form method="post" onsubmit="return luu_slide(' + t + ', ' + id + ')"> \
					<table>\
						<tr>\
						<td>Tiêu đề:</td> \
						<td><input type="text" style="width:600px" id="slide_ten" value="' + ten + '" /></td> \
						</tr>\
						<tr>\
						<td>Đường dẫn:</td> \
						<td><input type="text" style="width:600px" id="slide_duong_dan" value="' + duong_dan + '" /></td> \
						</tr> \
						<tr>\
						<td>Hình đại diện:</td> \
						<td><input type="text" style="width:485px" id="slide_hinh_dai_dien" value="' + hinh_dai_dien + '" /><button type="button" onclick="opHinhAnh(' + id + ')"><span class="round"><span>Tải hình lên</span></span></button></td> \
						</tr>\
						<tr>\
						<td valign="top">Nội dung:</td> \
						<td><textarea style="height:100px;width:600px" id="slide_mo_ta">' + mo_ta + '</textarea></td> \
						</tr>\
						</table>\
						<div class="cot_admin_footer"><input type="submit" value="Save" /> | <input type="button" onclick="bo_luu_cot()" value="Cancel" /></div>\
				</form>\
			';
			document.getElementById('div_quang_cao_tieu_de').innerHTML = 'Đổi hình Slide chính';
			document.getElementById('div_quang_cao_noi_dung').innerHTML = content;
			
			document.getElementById('cot_form').style.display = "block";
			//window.location = document.location.protocol + '//' + window.location.host + '/acp/?type=quan_ly_slide_chinh';
		},
    });
	return false;
}
function opHinhAnh(id) {
	moPopup('/tools/up-anh/','popup_7');
}
	function cap_nhat_hinh(val, obj)
	{
		obj.close();
		//val = val.replace(
		var vi_tri = val.indexOf('href="');
		vi_tri += 6;
		var vi_tri_sau = val.indexOf('"', vi_tri);
		val = val.substring(vi_tri, vi_tri_sau);
		document.getElementById('slide_hinh_dai_dien').value = val;
	}
function bo_them_wuswug()
{
	var id = 'txt_cot';
	if (tinyMCE.getInstanceById(id) != null)
	{
		document.getElementById('div_bo_them').innerHTML = 'Thêm';
	  	addRemoveEditor(0, id);
	}
	else
	{
		document.getElementById('div_bo_them').innerHTML = 'Bỏ';
		addRemoveEditor(1, id);
	}
	return false;
}

function luu_cot()
{
	var id = document.getElementById('cot_ten').value;
	if(id == '') return false;
	var noi_dung = '';
	if (tinyMCE.getInstanceById('txt_cot') != null)
	{
		noi_dung = tinyMCE.get('txt_cot').getContent();
	}
	else
	{
		noi_dung = document.getElementById('txt_cot').value;
	}
	var data = '';
	data = 'n=' + id.replace('cot_', '') + '&c=' + encodeURIComponent(noi_dung);
	dang_luu_cot[id] = 1;
	http.open('POST', document.location.protocol + '//' + window.location.host + '/tools/acp/luu_cot.php',true);
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			dang_luu_cot[id] = 0;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined)
			{
				alert(error[1]);
			}
			else
			{
				document.getElementById('cot_form').style.display = "none";
				noi_dung = $.trim(noi_dung);
				if(noi_dung == '') noi_dung ='<i><b>{blank}</b></i>';
				document.getElementById(id).innerHTML = noi_dung;
			}
		}
	};
	http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
	http.send(data);
	return false;
}
function bo_luu_cot()
{
	if(tinyMCE.getInstanceById('txt_cot') != null) addRemoveEditor(0, 'txt_cot');
	document.getElementById('cot_form').style.display = "none";
}
function mouseClickCot(id)
{
	bo_luu_cot();
	var content = '';
	content = '<form method="post" onsubmit="return luu_cot()"> \
        	<input type="hidden" id="cot_ten" /> \
            <div>Nội dung:(<a href="#" onclick="return bo_them_wuswug()"><span id="div_bo_them">Bỏ</span> chế độ soạn thảo</a>)</div> \
        	<textarea style="height:100px;width:600px" id="txt_cot"></textarea> \
            <div class="cot_admin_footer"><input type="submit" value="Save" /> | <input type="button" onclick="bo_luu_cot()" value="Cancel" /></div>\
        </form>\
	';
	document.getElementById('div_cot_tieu_de').innerHTML = 'EDIT HTML<div style="float:right;padding-right:5px"><button type="button" onclick="luu_cot()">Save</button> | <input type="button" onclick="bo_luu_cot()" value="Cancel" /></div>';
	document.getElementById('div_cot_noi_dung').innerHTML = content;
	
	var obj = document.getElementById(id);
	var olddiv = document.getElementById('bar_chinh_sua_cot_' + id);
	
	if(olddiv != null)
		obj.removeChild(olddiv);
	if(obj.innerHTML != '<i><b>{blank}</b></i>')
	{
		var data = '';
		data = 'n=' + id.replace('cot_', '');
		http.open('POST', document.location.protocol + '//' + window.location.host + '/tools/acp/lay_cot.php');
		http.onreadystatechange = function() {
			if(http.readyState == 4){
				xu_ly_noi_dung_cot(id, http.responseText);
			}
		};
		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
		http.send(data);
		return false;
	}
	else
	{
		xu_ly_noi_dung_cot(id, '');
	}
	
	return false;
}
function xu_ly_noi_dung_cot(id, data)
{
	var olddiv = document.getElementById('txt_cot');
	if(olddiv != null) olddiv.value = data;
	
	document.getElementById('cot_form').style.display = "block";
	document.getElementById('cot_ten').value = id;
	
	if (tinyMCE.getInstanceById('txt_cot') == null)
	{
		addRemoveEditor(1, 'txt_cot');
		setTimeout("tinyMCE.execCommand('mceFocus', false, 'txt_cot')", 700);
	}
}
function mouseOverCot(id, e)
{
	var handler = document.getElementById(id);
	var reltg = e.relatedTarget ? e.relatedTarget :
	e.type == 'mouseout' ? e.toElement : e.fromElement;
	while (reltg && reltg != handler) reltg = reltg.parentNode;
	if(reltg == handler) return false;
	
	handler.style.backgroundImage = "url(/styles/web/global/images/bg_cot.gif)";

	/*
	newdiv = document.createElement('div');
	newdiv.setAttribute('id', 'bar_chinh_sua_cot_' + id);
	newdiv.setAttribute('class', 'bar_chinh_sua_cot');
	newdiv.innerHTML = '<img src='http://img.' + window.location.hostname + "/styles/web/global/images/edit.png" onclick="return mouseClickCot(\'' + id + '\')" title="Chỉnh sửa" class="pointer" /></a>';
	newdiv.style.left = e.pageX + 'px';
	newdiv.style.top = e.pageY + 'px';
	handler.appendChild(newdiv);
	*/
}
function mouseOutCot(id, e)
{
	var handler = document.getElementById(id);
	var reltg = e.relatedTarget ? e.relatedTarget :
	e.type == 'mouseout' ? e.toElement : e.fromElement;
	while (reltg && reltg != handler) reltg = reltg.parentNode;
    if(reltg == handler) return false;
	
	handler.style.backgroundImage = "";
	
	var olddiv = document.getElementById('bar_chinh_sua_cot_' + id);
	if(olddiv != null)
		handler.removeChild(olddiv);
}
function cai_dat_chinh_sua_cot()
{
	var objs = document.getElementsByClassName('chinh_sua'), i;
	for (i = 0; i < objs.length; i++)
	{
		objs[i].title = '{Nhấp đôi để chỉnh sửa}';
		if(objs[i].innerHTML == '')
		{
			objs[i].innerHTML = '<i><b>{blank}</b></i>';
		}
		objs[i].ondblclick = function()
		{
			mouseClickCot(this.id);
			return;
		}
		objs[i].onmouseover = function(event)
		{
			mouseOverCot(this.id, event);
		}
		objs[i].onmouseout = function(event)
		{
			mouseOutCot(this.id, event);
		}
	}

}
if (document.getElementsByClassName == undefined) {
	document.getElementsByClassName = function(className)
	{
		var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
		var allElements = document.getElementsByTagName("*");
		var results = [];

		var element;
		for (var i = 0; (element = allElements[i]) != null; i++) {
			var elementClass = element.className;
			if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
				results.push(element);
		}

		return results;
	}
}
cai_dat_chinh_sua_cot();
cai_dat_chinh_sua_logo();