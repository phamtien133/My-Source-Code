<?php
foreach($this->_aVars['output'] as $key)
{
	$$key = $this->_aVars['data'][$key];
}
?>
<?php
if($status==1)
{
?>

<table width="100%" class="zebra">
<thead>
	<tr>
		<td colspan="8" style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_danh-sach')?> (<?= $tong_cong?>)<div class="fright"><form method="GET"><?= Core::getPhrase('language_so-luong')?>: <input type="number" id="limit" name="limit" value="<?= $limit?>" class="inputbox" style="width:30px;" /> <?= Core::getPhrase('language_tu-khoa')?>: <input type="text" id="tu_khoa" name="q" value="<?= $tu_khoa?>" class="inputbox" style="width:200px" /> <button type="submit">Go</button></form></div></td>
	</tr>
	<tr>
		<td width="50px" align="center"><a href="javascript:void(this)" onclick="chon_tat_ca()">
			<?= Core::getPhrase('language_chon')?>
			</a></td>
		<td width="560px;"><a href="user/group/&page=<?= $trang_hien_tai?>&sap_xep=1" title="<?= Core::getPhrase('language_sap-theo-ten-thanh-vien')?>">
			<?= Core::getPhrase('language_ten')?>
			</a></td>
		<td width="155px;"><a href="user/group/&page=<?= $trang_hien_tai?>&sap_xep=2" title="<?= Core::getPhrase('language_sap-theo-hop-thu')?>">
			<?= Core::getPhrase('language_hop-thu')?>
			</a></td>
		<td width="80px"><a href="user/group/&page=<?= $trang_hien_tai?>&sap_xep=3" title="<?= Core::getPhrase('language_sap-theo-thoi-gian')?>">
			<?= Core::getPhrase('language_thoi-gian')?>
			</a></td>
		<td align="center"><?= Core::getPhrase('language_loai')?></td>
		<td><?= Core::getPhrase('language_tt')?></td>
		<td><?= Core::getPhrase('language_quyen')?></td>
		<td><?= Core::getPhrase('language_sua')?></td>
		<td><?= Core::getPhrase('language_xoa')?></td>
	</tr>
</thead>
<tbody>
	<?php
for($i=0;$i<count($stt);$i++)
{
?>
	<tr id="tr_loi_binh_<?= $stt[$i]?>">
		<td><input type="checkbox" name="ckb_loi_binh" onkeyup="hien_xu_ly_chon()" onclick="hien_xu_ly_chon()" onchange="hien_xu_ly_chon()" value="<?= $stt[$i]?>" /></td>
		<td title="<?= $ten[$i]?>"><a href="user/group/edit/id_<?= $stt[$i]?>">
			<?= Core::getService('core.tools')->cutString($ten[$i],50)?>
			</a></td>
		<td><?= $hop_thu[$i]?></td>
		<td><?= $thoi_gian_dang_ky[$i]?></td>
		<td align="center"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/openid/<?= $openid[$i]?>.png" width="20" /></td>
		<td id="div_loi_binh_<?= $stt[$i]?>"><a href="javascript:void(this);" onclick="hien_thi(<?= $stt[$i]?>, <?= $trang_thai_text[$i]?>);" title="<?php if($trang_thai_text[$i] == 0) {?><?= Core::getPhrase('language_dang-bi-cam-truy-cap')?><?php } else {?><?= Core::getPhrase('language_khong-bi-cam-truy-cap')?><?php }?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/<?= $trang_thai[$i]?>.png" /></a></td>
		<td><a href="user/permission/?id=<?= $stt[$i]?>&obj=1" title="<?= Core::getPhrase('language_sua-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
		<td><a href="user/group/edit/id_<?= $stt[$i]?>" title="<?= Core::getPhrase('language_sua-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
		<td id="div_xoa_nhom_thanh_vien_<?= $stt[$i]?>"><a href="javascript:void(this);" onclick="xoa_nhom_thanh_vien(<?= $stt[$i]?>);" title="<?= Core::getPhrase('language_xoa-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a></td>
	</tr>
	<?php
}
?>
</tbody>
</table>
<div id="div_chon"><a href="javascript:void(this);" onclick="xoa_danh_sach_bai();" title="<?= Core::getPhrase('language_xoa-danh-sach-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(1);" title="<?= Core::getPhrase('language_cho-phep-danh-sach-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(0);" title="<?= Core::getPhrase('language_khong-cho-phep-danh-sach-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a></div>
<div id="div_thong_bao" class="buttonarea"></div>
	<?= Core::getService('core.tools')->paginate($tong_trang, $trang_hien_tai, $duong_dan_phan_trang.'&page=::PAGE::', $duong_dan_phan_trang, '', '')?>
<p class="buttonarea">
	<button type="button" onclick="window.location='./user/group/add/?id=-1';"><span class="round"><span>
	<?= Core::getPhrase('language_them')?>
	</span></span></button>
	|
	<button type="button" onclick="window.location='./user/index/?type=user';"><span class="round"><span>
	<?= Core::getPhrase('language_thanh-vien')?>
	</span></span></button>
</p>
<script type="text/javascript" >
function xoa_nhom_thanh_vien(stt) {
if(!confirm("Bạn có chắc muốn xóa nhóm thành viên này?<?//= Core::getPhrase('language_ban-co-chac-muon-xoa-thanh-vien')?>"))
 {
	 return false;
 }
	document.getElementById('div_xoa_nhom_thanh_vien_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_nhom_thanh_vien(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[id]='+stt+'&val[status]=2&val[type]=user_group');
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				document.getElementById('div_xoa_nhom_thanh_vien_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_nhom_thanh_vien(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
			} else {
				document.getElementById('tr_loi_binh_' + stt).innerHTML = '';
				document.getElementById('tr_loi_binh_' + stt).style.display = "none";				
			}
		}
	};
	http.send(null);
}
function xoa_danh_sach_bai() {
if(!confirm("Bạn có muốn xóa danh sách nhóm thành viên này?<?//= Core::getPhrase('language_ban-co-chac-muon-xoa-danh-sach-thanh-vien')?>"))
 {
	 return false;
 }
	var field = document.getElementsByName('ckb_loi_binh');
	var n=0, danh_sach = '', danh_sach_mang = new Array(1);
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			document.getElementById('div_xoa_nhom_thanh_vien_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="xoa_nhom_thanh_vien(' + field[i].value + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
			danh_sach_mang[n] = field[i].value;
			n++;
		}
	}
	if(danh_sach_mang[0] != undefined)
	{
		danh_sach = danh_sach_mang.join(',');
	}
	else
	{
		alert('<?= Core::getPhrase('language_chon-it-nhat-1-thanh-vien')?>');
		return false;
	}
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[list]='+danh_sach + '&val[status]=2&val[type]=user_group');
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('div_xoa_nhom_thanh_vien_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="xoa_nhom_thanh_vien(' + danh_sach_mang[i] + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
				}
			} else {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('tr_loi_binh_' + danh_sach_mang[i]).innerHTML = '';
					document.getElementById('tr_loi_binh_' + danh_sach_mang[i]).style.display = "none";
					document.getElementById('div_chon').style.display = 'none';
				}
			}
		}
	};
	http.send(null);
}
function trang_thai_danh_sach_bai(trang_thai)
{
	if(trang_thai == 0 && !confirm("<?= Core::getPhrase('language_ban-co-chac-muon-cam-truy-cap-cac-thanh-vien')?>"))
	{
	 return false;
	}
	var field = document.getElementsByName('ckb_loi_binh');
	var n=0, danh_sach = '', danh_sach_mang = new Array(1);
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			document.getElementById('div_loi_binh_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + field[i].value + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
			danh_sach_mang[n] = field[i].value;
			n++;
		}
	}
	if(danh_sach_mang[0] != undefined)
	{
		danh_sach = danh_sach_mang.join(',');
	}
	else
	{
		alert('<?= Core::getPhrase('language_chon-it-nhat-1-thanh-vien')?>');
		return false;
	}
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=user_group&val[list]='+danh_sach+'&val[status]='+trang_thai+'&val[math]='+Math.random());
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('div_loi_binh_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
				}
			} else {
				if(trang_thai == 1)
				{
					for (i = 0; i < danh_sach_mang.length; i++)
					{
						document.getElementById('div_loi_binh_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" title="<?= Core::getPhrase('language_dang-duoc-cho-phep-hien-thi')?>" /></a>';
					}
				}
				else
				{
					for (i = 0; i < danh_sach_mang.length; i++)
					{
						document.getElementById('div_loi_binh_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" title="<?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?>" /></a>';
					}
				}
			}
		}
	};
	http.send(null);
 return false;
}
function hien_thi(stt, trang_thai) {
	if(trang_thai==1) trang_thai=0;
	else trang_thai=1;
if(trang_thai == 0 && !confirm("<?= Core::getPhrase('language_ban-co-chac-muon-cam-thanh-vien')?>"))
{
 return false;
}
	document.getElementById('div_loi_binh_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=user_group&val[id]='+stt+'&val[status]='+trang_thai+'&val[math]='+Math.random());
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				document.getElementById('div_loi_binh_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
			} else {
				if(trang_thai == 1)
				{
					document.getElementById('div_loi_binh_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" title="<?= Core::getPhrase('language_dang-duoc-cho-phep-hien-thi')?>"></a>';
				}
				else
				{
					document.getElementById('div_loi_binh_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" title="<?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?>"></a>';
				}
			}
		}
	};
	http.send(null);
	return false;
}
function hien_xu_ly_chon()
{
	var field = document.getElementsByName('ckb_loi_binh');
	var chon = 1;
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			chon = 0;
			break;
		}
	}
	if(chon == 0)
	{
		document.getElementById('div_chon').style.display = 'block';
	}
	else document.getElementById('div_chon').style.display = 'none';
}
hien_xu_ly_chon();
function chon_tat_ca()
{
	var field = document.getElementsByName('ckb_loi_binh');
	var chon = 1;
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			chon = 0;
			break;
		}
	}
	if(chon == 1)
	{
		for (i = 0; i < field.length; i++)
		{
			field[i].checked = true ;
		}
		document.getElementById('div_chon').style.display = 'block';
	}
	else
	{
		document.getElementById('div_chon').style.display = 'none';
		for (i = 0; i < field.length; i++)
		{
			field[i].checked = false ;
		}
	}
}
</script>
<?php
}
elseif($status==2)
{
?>
<form method="post" name="frm_dang_ky">
	<div class="contact_email">
		<?php if($error) { ?>
		<div class="_menu">
			<div class="side-mod">
				<div class="module-header png">
					<div class="module-header2 png">
						<div class="module-header3 png">
							<h3 class="module-title">
								<?= Core::getPhrase('language_da-co-loi-xay-ra')?>
							</h3>
						</div>
					</div>
				</div>
				<div class="module-tm png">
					<div class="module-tl png">
						<div class="module-tr png">
							<div class="module png" >
								<?= $error?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<table width="100%" class="zebra">
			<tr>
				<td colspan="6" style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_thanh-vien')?></td>
			</tr>
			<tr>
				<td><label for="ten"><?= Core::getPhrase('language_ten')?>:</label><span id="div_ten_kiem_tra_ma_ten"></span></td>
				<td><input type="text" id="ten" name="ten" value="<?= $ten?>" class="text-input small-input" /></td>
			</tr>
			<tr>
				<td><label for="ma_ten"><?= Core::getPhrase('language_ma-ten')?>:</label>(<a href="javascript:" onclick="return btn_cap_nhat_ma_ten()"><?= Core::getPhrase('language_cap-nhat-tu-dong')?></a>)</td>
				<td><input type="text" id="ma_ten" name="ma_ten" value="<?= $ma_ten?>" onblur="kiem_tra_ma_ten()" class="text-input small-input" /></td>
			</tr>
    <td colspan="2" id="danh_sach_chi_tiet">
<div id="div_slide"></div>
<div style="clear:both"></div>
<div>
    <select id="sel_div_slide"><option value="0" selected="selected"><?= Core::getPhrase('language_cuoi-cung')?></option></select>
    <a onclick="them_slide()"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></a>
</div>
<div style="clear:both"></div>
</td>
</tr>
		</table>
<script>
	$("#ten").focus();
	
<? if($id < 1):?>
$('#ten').keyup(function(){
    lay_ma_ten_tu_dong($(this).val())
});
$('#ten').change(function(){
    lay_ma_ten_tu_dong($(this).val());
    kiem_tra_ma_ten()
});
$('#ten').blur(function(){
    lay_ma_ten_tu_dong($(this).val());
    kiem_tra_ma_ten();
});
<? endif?>
function lay_ma_ten_tu_dong(noi_dung)
{
	noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
	noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
	
	document.getElementById('ma_ten').value = noi_dung;
	var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
	if(obj_ten.innerHTML != '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">' )
	{
		obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
	}
}
var ma_ten_truoc = document.getElementById("ma_ten").value;
function kiem_tra_ma_ten() {
	var noi_dung = document.getElementById("ma_ten").value;
	var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
	if(ma_ten_truoc != noi_dung)
	{
		obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
		http.open('POST', '/tools/acp/kiem_tra_ma_ten.php', true);
		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
		http.onreadystatechange = function () {
			if(http.readyState == 4){
				ma_ten_truoc = noi_dung;
				if( http.responseText == 1)
				{
					obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png">';
				}
				else
				{
					obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png">';
				}
			}
		};
		http.send('type=nhom_thanh_vien&stt=<?= $id?>&ma_ten='+unescape(noi_dung));
	}
}

function btn_cap_nhat_ma_ten()
{
	lay_ma_ten_tu_dong($('#ten').val());
	kiem_tra_ma_ten();
	return false;
}


var tu_vua_tim = [];
function link_search_box(id) {
	$('#dialog_search_box').modal(
		{
			onShow: function (dialog) {
				if(tu_vua_tim['thanh_vien'] != '')
				{
					$('#dialog_search_box #txt_thanh_vien_lien_quan').val(tu_vua_tim['thanh_vien']);
					$('#dialog_search_box #txt_thanh_vien_lien_quan').focus();
					$('#dialog_search_box #txt_thanh_vien_lien_quan').select();
					tu_vua_tim['thanh_vien'] = '';
					tim_bai_lien_quan('thanh_vien');
				}
				$('#dialog_search_box #stt_thanh_vien_lien_quan').val(id);
				$("body").css("overflow","hidden");
			}, 
			onClose: function (dialog) {
				$("body").css("overflow","auto");
				dialog.data.fadeOut('fast', function () {
					dialog.container.slideUp('fast', function () {
						dialog.overlay.fadeOut('fast', function () {
							$.modal.close(); // must call this!
						});
					});
				});
			}
		}
	);
	return false;
};
function chon_lien_ket(stt, duong_dan, ten, id)
{
	// lấy stt
	if(id == undefined) id = $('#dialog_search_box #stt_thanh_vien_lien_quan').val();
	
	$('#ma_so_' + id).val(stt);
	$('#div_lien_ket_' + id).html('<a href="' + duong_dan + '" target="_blank">' + ten + '</a>');
	$.modal.close();
	return false;
}

function cap_nhat() {
$('.div_menu_nhom_0').datasort({ 
    datatype    : 'number',
    sortElement : '.val_pos_0',
    reverse     : false
});
}
$(document).ready(function(){
	cap_nhat();
});

function doi_vi_tri(classname, stt_can, stt_thay)
{
var obj = ' .val_pos_' + classname;
var gia_tri_can = $('#div_slide_' + stt_can + obj).html();

$('#div_slide_' + stt_can + obj).html($('#div_slide_' + stt_thay + obj).html());
$('#div_slide_' + stt_thay + obj).html(gia_tri_can);

cap_nhat();
}

function tang_giam_vi_tri(classname, stt, loai)
{
var objs = $('.div_menu_nhom_' + classname),  i = 0, stt_sau = '', obj;
if(loai == 1)
{
    for(i = 0;i<objs.length;i++)
    {
        obj = $(objs[i+1]);
        if(obj.attr('id') == undefined)
        {
            stt_sau = $(objs[objs.length-1]).attr('id').replace('div_slide_', '');
            doi_vi_tri(classname, stt_sau, stt);
            break;
        }
        else
        {
            stt_sau = obj.attr('id').replace('div_slide_', '');
            if(stt_sau == stt)
            {
                stt = $(objs[i]).attr('id').replace('div_slide_', '');
                doi_vi_tri(classname, stt, stt_sau);
                break;
            }
        }
    }
}
else
{
    for(i = objs.length-1;i>=0;i--)
    {
        obj = $(objs[i-1]);
        if(obj.attr('id') == undefined)
        {
            stt_sau = $(objs[0]).attr('id').replace('div_slide_', '');
            doi_vi_tri(classname, stt_sau, stt);
            break;
        }
        else
        {
            stt_sau = obj.attr('id').replace('div_slide_', '');
            if(stt_sau == stt)
            {
                stt = $(objs[i]).attr('id').replace('div_slide_', '');
                doi_vi_tri(classname, stt, stt_sau);
                break;
            }
        }
    }
}
return false;
}
var slide = 0, lan_dau = true;
function them_slide(ma_so, ten, duong_dan, trang_thai, stt)
{
    slide++;
    if(ma_so == undefined) ma_so = '';
    if(ten == undefined) ten = '';
    if(duong_dan == undefined) duong_dan = '';
    if(trang_thai == undefined) trang_thai = 1;
    if(stt == undefined) stt = 0;
    var vi_tri = parseInt($('#sel_div_slide').val());
    var slide_pos = 0;
    var content = '';
    // update cac node
    var objs = $('.div_menu_nhom_0'),  i = 0;
    if(vi_tri == -1)
    {
        slide_pos = 1;
        for(i = 0; i < objs.length; i++)
        {
            slide_pos += 1;
            $('#' + $(objs[i]).attr('id') + ' .val_pos_0').html(slide_pos);
        }
        slide_pos = 1;
    }
    else if(vi_tri > 0)
    {
        slide_pos = vi_tri + 1;
        for(i = vi_tri; i < objs.length; i++)
        {
            slide_pos += 1;
            $('#' + $(objs[i]).attr('id') + ' .val_pos_0').html(slide_pos);
        }
        slide_pos = vi_tri + 1;
    }
    else
    {
        slide_pos = slide;
    }
    // end
	
	// end
    content = '<div class="div_menu div_con div_menu_nhom_0" id="div_slide_' + slide + '"><div style="float:right"> \
                <a href="javascript:" onclick="return link_search_box(' + slide + ')" title="<?= Core::getPhrase('language_tim')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a>\
				<a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 1)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up.png" />\
                </a>\
                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 0)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/down.png" />\
                </a>\
                <a href="javascript:void(this)" onclick="xoa_slide(' + slide + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a>\
				</div>\
				<p>\
				<div style="float:left;width:180px;"><label for="ma_so_' + slide + '"><?= Core::getPhrase('language_ten')?></div>\
				<input type="hidden" name="gt_ma_so[' + slide + ']" id="ma_so_' + slide + '" value="' + ma_so + '" class="inputbox" style="width:50%;" />\
				<div id="div_lien_ket_' + slide + '" style="border:0;float:left"></div>\
				</p>\
            <div class="hidden val_pos_0">' + slide_pos + '</div>\
			<input type="hidden" name="gt_stt[' + slide + ']" value="' + stt + '" />\
			</div>';
    /*
    if(vi_tri == -1)
    {
        $('#div_slide').first().before(content);
    }
    else if(vi_tri > 0)
    {
        $('#div_slide_' + vi_tri).after(content);
    }
    else
    {
        $('#div_slide').append(content);
    }
    */
    $('#div_slide').append(content);
	if(ten != '')
	{
		chon_lien_ket(ma_so, duong_dan, ten, slide);
	}
    cap_nhat();
	if(!lan_dau) $('html, body').animate({scrollTop: $('#div_slide_' + slide).offset().top}, 800);
}
var tu_vua_tim = [];
var t_tim_lien_quan_dem_nguoc = [];
function xoa_bai_lien_quan(obj) {
	if(obj == undefined) obj = '';
	tu_vua_tim[obj] = '';
	document.getElementById('txt_' + obj + '_lien_quan').value = ''
	document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = '';
	return false;
}
function bai_lien_quan_da_them(obj)
{
	if(obj == undefined) obj = '';
	var tmp = document.getElementById('txt_them_' + obj + '_lien_quan'), tmp_canh_bao = document.getElementById('txt_them_' + obj + '_lien_quan_canh_bao');
	if(tmp.style.display == 'none')
	{
		tmp.style.display = 'block';
		tmp_canh_bao.style.display = 'none';
	}
	else
	{
		tmp.style.display = 'none';
		tmp_canh_bao.style.display = 'block';
		setTimeout('bai_lien_quan_da_them("' + obj + '")', 1200);
	}
	
}
function tim_bai_lien_quan(obj) {
	if(obj == undefined) obj = '';
	if(tu_vua_tim[obj] != document.getElementById('txt_' + obj + '_lien_quan').value)
	{
		document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif"> <?= Core::getPhrase('language_dang-sinh-khoa-vui-long-doi')?>';
		http.open('POST', '/tools/acp/tim_bai_lien_quan.php', true);
		http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
		http.onreadystatechange = function () {
			if(http.readyState == 4){
				tu_vua_tim[obj] = document.getElementById('txt_' + obj + '_lien_quan').value;
				var tmp, tmp_lk = [], noi_dung = '', dong = http.responseText.split('<-vietspider->');
				var tong = dong.length-1;
				noi_dung = '<table class="zebra">';
				if(obj == 'thanh_vien')
				{
					for( var i=0;i<tong;i++)
					{
						tmp = dong[i].split('<->');
						noi_dung += '<tr><td><a>' + tmp[1] + '</a></td><td align="center"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/openid/' + tmp[2] + '.png" width="20" /></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="Đã chọn" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" onClick="return chon_thanh_vien(' + tmp[0] + ', \'' + tmp[1] + '\')"><?= Core::getPhrase('language_chon')?></a></div></td></tr>';
					}
				}
				else if(obj == 'youtube')
				{
					for( var i=0;i<tong;i++)
					{
						tmp = dong[i].split('<->');
						noi_dung += '<tr><td><img src="http://img.youtube.com/vi/' + tmp[0] + '/2.jpg" /></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="http://www.youtube.com/watch?v=' + tmp[0] + '" target="_blank">' + tmp[1] + '</a></div></td><td><td><a href="" onClick="return chen_youtube(\'' + tmp[0] + '\')"><?= Core::getPhrase('language_them')?></a></td></tr>';
					}
				}
	else if(obj == 'thanh_vien')
	{
		for( var i=0;i<tong;i++)
		{
			tmp = dong[i].split('<->');
			tmp_lk[1] = tmp[1].replace(/'/g, "\\'");
			tmp_lk[2] = tmp[2].replace(/'/g, "\\'");
			
			tmp_lk[1] = tmp_lk[1].replace(/"/g, '&quot;');
			tmp_lk[2] = tmp_lk[2].replace(/"/g, '&quot;');
			noi_dung += '<tr><td><a href="' + tmp[1] + '" target="_blank">' + tmp[2] + '</a></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" class="simplemodal-close" onClick="return chon_lien_ket(\'' + tmp[0] + '\', \'' + tmp_lk[1] + '\', \'' + tmp_lk[2] + '\')"><?= Core::getPhrase('language_chon')?></a></div></td></tr>';
		}
	}
				else
				{
					for( var i=0;i<tong;i++)
					{
						tmp = dong[i].split('<->');
						tmp_lk[0] = tmp[0].replace(/'/g, "\\'");
						tmp_lk[1] = tmp[1].replace(/'/g, "\\'");
						
						tmp_lk[0] = tmp_lk[0].replace(/"/g, '&quot;');
						tmp_lk[1] = tmp_lk[1].replace(/"/g, '&quot;');
						noi_dung += '<tr><td><a href="' + tmp[0] + '" target="_blank">' + tmp[1] + '</a></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" onClick="return chon_lien_ket(\'' + tmp_lk[0] + '\', \'' + tmp_lk[1] + '\')"><?= Core::getPhrase('language_them')?></a></div></td></tr>';
					}
				}
	if(tong == 0)
	{
		noi_dung += '<tr><td><a><?= Core::getPhrase('language_bai-viet-khong-ton-tai')?></a></td></tr>';
	}
				noi_dung += '</table>';
				document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = noi_dung;
			}
		};
		http.send('type=' + obj + '&tu_khoa='+unescape(document.getElementById('txt_' + obj + '_lien_quan').value));
	}
	return false;
}

function tim_bai_lien_quan_dem_nguoc(e, obj)
{
	if(obj == undefined) obj = '';
	var bat_phim = false;
	if(window.event && window.event.keyCode == 13) // IE
	{
		event.returnValue = false;
		event.cancelBubble = true;
		bat_phim = true;
	}
	else if(e.which && e.keyCode == 13) // Netscape/Firefox/Opera
	{
		e.cancelBubble = true;
		e.stopPropagation();
		bat_phim = true;
	}
	clearTimeout(t_tim_lien_quan_dem_nguoc[obj]);
	if(bat_phim)
	{
		tim_bai_lien_quan(obj);
		return false;
	}
	t_tim_lien_quan_dem_nguoc[obj] = setTimeout('tim_bai_lien_quan("' + obj + '")', 1000);
}
function cap_nhat_slide()
{
    // cập nhật select box
    var val = '', stt = 0;
    $('#sel_div_slide').html('<option value="0" selected="selected"><?= Core::getPhrase('language_cuoi-cung')?></option><option value="-1"><?= Core::getPhrase('language_tren-dau')?></option>');
    //$('#sel_div_slide').append(new Option(val, 'Slide ' + val, true, true));
    //alert($('#div_slide').html());
    $('#div_slide .div_con').each(function(index, element) {
        stt = $(this).attr('id').replace('div_slide_', '');
        val = $('#ten_' + stt).val();
        $('#sel_div_slide').append('<option value="' + stt + '">' + val + '</option>');
        //$('#sel_div_slide').append(new Option(val, 'Slide ' + val, true, true));
    });
}
$('#sel_div_slide').focus(function(e) {
    cap_nhat_slide();
});
function xoa_slide(stt)
{
    if(!confirm('<?=Core::getPhrase('language_ban-co-chac-muon-xoa-slide-nay')?>')) return false;
    $('#div_slide_' + stt).remove();
	cap_nhat();
}
<?php
if(is_array($gt_ma_so))
{
    for($i=0;$i<count($gt_ma_so);$i++)
    {
        $tmp_ma_so = str_replace("'", "\'", $gt_ma_so[$i]);
        $tmp_ten = str_replace("'", "\'", $gt_ten[$i]);
        $tmp_duong_dan = str_replace("'", "\'", $gt_duong_dan[$i]);
        $tmp_trang_thai = str_replace("'", "\'", $gt_trang_thai[$i]);
        $tmp_stt = str_replace("'", "\'", $gt_stt[$i]);
    ?>
    them_slide('<?= $tmp_ma_so?>', '<?= $tmp_ten?>', '<?= $tmp_duong_dan?>', '<?= $tmp_trang_thai?>', '<?= $tmp_stt?>');
    <?php
    }
}
else
{
?>
    them_slide();
<?php
}
?>
	lan_dau = false;

function hien_thi_lien_ket()
{
	
}
function sbm_frm()
{
	
	return true;
}

</script>
		<div style="clear:both;"></div>
		<button type="submit" name="submit"><span class="round"><span><?= Core::getPhrase('language_hoan-thanh')?></span></span></button>
	</div>
        <div id="dialog_search_box">
            <div style="background:#FFF;border:1px solid #CCC;padding-left:5px;">
                <input type="hidden" id="stt_thanh_vien_lien_quan" value="0" />
                <input type="text" id="txt_thanh_vien_lien_quan" value="" style="border:0;width:495px;height:30px" onkeydown="return tim_bai_lien_quan_dem_nguoc(event, 'thanh_vien')" onblur="tim_bai_lien_quan('thanh_vien')"><a href="javascript:" onclick="return tim_bai_lien_quan('thanh_vien')" title="<?= Core::getPhrase('language_tim-bai-lien-quan')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a> <a href="javascript:" onclick="return xoa_bai_lien_quan('thanh_vien')" title="<?= Core::getPhrase('language_xoa')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/delete.png"></a>
            </div>
            <div id="div_tim_thanh_vien_lien_quan">
            </div>
        </div>
</form>
<?php
}
elseif($status==3)
{
?>
<p>
	<?= Core::getPhrase('language_da-hieu-chinh-thanh-cong')?>
</p>
<?php if($error) {?>
<br />
<?= Core::getPhrase('language_da-co-loi-xay-ra')?>
:<br />
<?= $error?>
<?php }?>
<br />
<a href="user/group/add/?id_<?= $id?>">
<?= Core::getPhrase('language_bam-vao-day-de-xem-thanh-vien')?>
<?= $ten_truy_cap?>
</a>!
<p class="buttonarea">
	<button type="button" onclick="window.location='./user/group/index/';"><span class="round"><span>
	<?= Core::getPhrase('language_quan-ly-thanh-vien')?>
	</span></span></button>
</p>
<?php
}
else
{
?>
<p>
	<?= Core::getPhrase('language_da-co-loi-xay-ra')?>
	<br />
	<?= $error?>
</p>
<p class="buttonarea">
	<button type="button" onclick="window.location='./user/group/index/';"><span class="round"><span>
	<?= Core::getPhrase('language_quan-ly-thanh-vien')?>
	</span></span></button>
</p>
<?php
}
?>