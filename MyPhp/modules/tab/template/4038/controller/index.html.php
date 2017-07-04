<?php
foreach($this->_aVars['output'] as $key)
{
	$$key = $this->_aVars['data'][$key];
}
?>
<?php if($status==1){?>
<table width="100%" class="zebra">
<thead>
<tr>
	<td width="50px" align="center"><a href="javascript:void(this)" onclick="chon_tat_ca()"><?= Core::getPhrase('language_chon')?></a></td>
    <td><?= Core::getPhrase('language_ten')?></td>
    <td><?= Core::getPhrase('language_tt')?></td>
    <td><?= Core::getPhrase('language_sua')?></td>
    <td><?= Core::getPhrase('language_xoa')?></td>
</tr>
</thead>
<tbody>
<?php
for($i=0;$i<count($shop_custom['stt']);$i++)
{
?>
<tr id="tr_shop_custom_<?= $shop_custom['stt'][$i]?>">
	<td><input type="checkbox" name="ckb_shop_custom" onkeyup="hien_xu_ly_chon()" onclick="hien_xu_ly_chon()" onchange="hien_xu_ly_chon()" value="<?= $shop_custom['stt'][$i]?>" /></td>
    <td><a href="./tab/add/id_<?= $shop_custom['stt'][$i]?>"><?= $shop_custom['ten'][$i]?></a></td>
    <td id="div_shop_custom_<?= $shop_custom['stt'][$i]?>"><a href="javascript:void(this);" onclick="hien_thi(<?= $shop_custom['stt'][$i]?>, <?= $shop_custom['trang_thai'][$i]?>);" title="<?php if($shop_custom['trang_thai'][$i] == 1) {?><?= Core::getPhrase('language_duoc-cho-phep-hien-thi')?><?php } else {?><?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?><?php }?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/<?= $shop_custom['trang_thai_text'][$i]?>.png" /></a></td>
    <td><a href="./tab/add/id_<?= $shop_custom['stt'][$i]?>" title="<?= Core::getPhrase('language_sua-bai')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
    <td id="div_xoa_shop_custom_<?= $shop_custom['stt'][$i]?>"><a href="javascript:void(this);" onclick="xoa_shop_custom(<?= $shop_custom['stt'][$i]?>);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>
<p class="buttonarea"><button type="button" onclick="window.location='./tab/add/';"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></button></p>
<div id="div_chon"><a href="javascript:void(this);" onclick="xoa_danh_sach_bai();"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(1);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(0);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a></div>
<script type="text/javascript" >
function xoa_shop_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
	 return false;
 }
	document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]=2&val[id]='+stt);
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
			} else {
				document.getElementById('tr_shop_custom_' + stt).innerHTML = '';
				document.getElementById('tr_shop_custom_' + stt).style.display = "none";				
			}
		}
	};
	http.send(null);
}
function xoa_danh_sach_bai() {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-danh-sach-bai')?>"))
 {
	 return false;
 }
	var field = document.getElementsByName('ckb_shop_custom');
	var n=0, danh_sach = '', danh_sach_mang = new Array(1);
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			document.getElementById('div_xoa_shop_custom_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + field[i].value + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
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
		alert('<?= Core::getPhrase('language_chon-it-nhat-1-bai-viet')?>');
		return false;
	}
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]=2&val[list]='+danh_sach);
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('div_xoa_shop_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + danh_sach_mang[i] + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
				}
			} else {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('tr_shop_custom_' + danh_sach_mang[i]).innerHTML = '';
					document.getElementById('tr_shop_custom_' + danh_sach_mang[i]).style.display = "none";
					document.getElementById('div_chon').style.display = 'none';
				}
			}
		}
	};
	http.send(null);
}
function trang_thai_danh_sach_bai(trang_thai)
{
	if(trang_thai == 0 && !confirm("<?= Core::getPhrase('language_ban-co-chac-khong-cho-phep-hien-thi-bai-viet')?>"))
	{
	 return false;
	}
	var field = document.getElementsByName('ckb_shop_custom');
	var n=0, danh_sach = '', danh_sach_mang = new Array(1);
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			document.getElementById('div_shop_custom_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + field[i].value + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
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
		alert('<?= Core::getPhrase('language_chon-it-nhat-1-bai-viet')?>');
		return false;
	}
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]='+trang_thai+'&val[list]='+danh_sach+'&val[math]='+Math.random());
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('div_shop_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
				}
			} else {
				if(trang_thai == 1)
				{
					for (i = 0; i < danh_sach_mang.length; i++)
					{
						document.getElementById('div_shop_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a>';
					}
				}
				else
				{
					for (i = 0; i < danh_sach_mang.length; i++)
					{
						document.getElementById('div_shop_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a>';
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
if(trang_thai == 0 && !confirm("<?= Core::getPhrase('language_ban-co-chac-khong-cho-phep-hien-thi-bai-viet')?>"))
{
 return false;
}
	document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
	http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]='+trang_thai+'&val[id]='+stt+'&val[math]='+Math.random());
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
			} else {
				if(trang_thai == 1)
				{
					document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png"></a>';
				}
				else
				{
					document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png"></a>';
				}
			}
		}
	};
	http.send(null);
	return false;
}
function hien_xu_ly_chon()
{
	var field = document.getElementsByName('ckb_shop_custom');
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
	var field = document.getElementsByName('ckb_shop_custom');
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
	<?= Core::getService('core.tools')->paginate($tong_trang, $trang_hien_tai, $duong_dan_phan_trang.'&page=::PAGE::', $duong_dan_phan_trang, '', '')?>
	<div id="div_thong_bao" class="buttonarea"></div>
<?php
}
else
{
?>
    <p><?= Core::getPhrase('language_da-co-loi-xay-ra')?>
    	<?php foreach($errors as $error):?>
        <br />
	    <?= $error?>
        <?php endforeach?>
    </p>
   <p class="buttonarea"><button type="button" onclick="window.location='/acp/';"><span class="round"><span><?= Core::getPhrase('language_trang-quan-tri')?></span></span></button></p>
<?php
}
?>