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
		<td colspan="11" style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_danh-sach')?> (<?= $tong_cong?>)<div class="fright">
        <form method="GET"><input type="hidden" name="type" value="<?= $sType?>" /><?= Core::getPhrase('language_so-luong')?>: <input type="number" id="limit" name="limit" value="<?= $limit?>" class="inputbox" style="width:30px;" /> <?= Core::getPhrase('language_tu-khoa')?>: <input type="text" id="tu_khoa" name="q" value="<?= $tu_khoa?>" class="inputbox" style="width:200px" /> <button type="submit">Go</button></form></div></td>
	</tr>
	<tr>
		<td width="50px" align="center"><a href="javascript:void(this)" onclick="chon_tat_ca()">
			<?= Core::getPhrase('language_chon')?>
			</a></td>
		<td><?= Core::getPhrase('language_stt')?></td>
		<td><?= Core::getPhrase('language_ma-so')?></td>
		<td width="560px;"><a href="/user/index/?page=<?= $trang_hien_tai?>&sap_xep=1" title="<?= Core::getPhrase('language_sap-theo-ten-thanh-vien')?>">
			<?= Core::getPhrase('language_ten')?>
			</a></td>
		<td width="155px;"><a href="/user/index/?page=<?= $trang_hien_tai?>&sap_xep=2" title="<?= Core::getPhrase('language_sap-theo-hop-thu')?>">
			<?= Core::getPhrase('language_hop-thu')?>
			</a></td>
		<td width="80px"><a href="/user/index/?page=<?= $trang_hien_tai?>&sap_xep=3" title="<?= Core::getPhrase('language_sap-theo-thoi-gian')?>">
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
		<td><?= ($trang_bat_dau + $i + 1)?></td>
		<td><?= $ma_so[$i]?></td>
		<td title="<?= $ten[$i]?>"><a href="/user/edit/id_<?= $stt[$i]?>"><?= Core::getService('core.tools')->cutString($ten[$i],50)?></a></td>
		<td><?= $hop_thu[$i]?></td>
		<td><?= $thoi_gian_dang_ky[$i]?></td>
		<td align="center"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/openid/<?= $openid[$i]?>.png" width="20" /></td>
		<td id="div_loi_binh_<?= $stt[$i]?>"><a href="javascript:void(this);" onclick="hien_thi(<?= $stt[$i]?>, <?= $trang_thai_text[$i]?>);" title="<?php if($trang_thai_text[$i] == 0) {?><?= Core::getPhrase('language_dang-bi-cam-truy-cap')?><?php } else {?><?= Core::getPhrase('language_khong-bi-cam-truy-cap')?><?php }?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/<?= $trang_thai[$i]?>.png" /></a></td>
		<td><a href="./user/permission/?id=<?= $stt[$i]?>" title="<?= Core::getPhrase('language_sua-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
		<td><a href="./user/edit/id_<?= $stt[$i]?>" title="<?= Core::getPhrase('language_sua-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
		<td id="div_xoa_thanh_vien_<?= $stt[$i]?>"><a href="javascript:void(this);" onclick="xoa_thanh_vien(<?= $stt[$i]?>);" title="<?= Core::getPhrase('language_xoa-thanh-vien')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a></td>
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
	<button type="button" onclick="window.location='./user/permission/?id=-1';"><span class="round"><span>Set Default Permission</span></span></button>
	<button type="button" onclick="window.location='./user/add/';"><span class="round"><span><?= Core::getPhrase('language_them-thanh-vien')?></span></span></button>
</p>
<script type="text/javascript" >
function xoa_thanh_vien(stt) {
if(!confirm("Bạn có chắc muốn xóa thành viên ?<?//= Core::getPhrase('language_ban-co-chac-muon-xoa-thanh-vien')?>"))
 {
	 return false;
 }
	document.getElementById('div_xoa_thanh_vien_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_thanh_vien(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
	http.open('get', '/includes/ajax.php?=&core[call]=user.deleteUser&val[id]='+stt);
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				document.getElementById('div_xoa_thanh_vien_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_thanh_vien(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
			} else {
				document.getElementById('tr_loi_binh_' + stt).innerHTML = '';
				document.getElementById('tr_loi_binh_' + stt).style.display = "none";				
			}
		}
	};
	http.send(null);
}
function xoa_danh_sach_bai() {
if(!confirm("Bạn có chắc muốn xóa danh sách thành viên?<?//= Core::getPhrase('language_ban-co-chac-muon-xoa-danh-sach-thanh-vien')?>"))
 {
	 return false;
 }
	var field = document.getElementsByName('ckb_loi_binh');
	var n=0, danh_sach = '', danh_sach_mang = new Array(1);
	for (i = 0; i < field.length; i++)
	{
		if(field[i].checked == true)
		{
			document.getElementById('div_xoa_thanh_vien_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="xoa_thanh_vien(' + field[i].value + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
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
	http.open('get', '/includes/ajax.php?=&core[call]=user.deleteUser&val[list]='+danh_sach);
	http.onreadystatechange = function() {
		if(http.readyState == 4){
			var response = http.responseText;
			var error = http.responseText.split('<-errorvietspider->');
			if(error[1] != undefined) {
				for (i = 0; i < danh_sach_mang.length; i++)
				{
					document.getElementById('div_xoa_thanh_vien_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="xoa_thanh_vien(' + danh_sach_mang[i] + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
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
	http.open('get', '/includes/ajax.php?=&core[call]=user.updateStatusUser&val[list]='+danh_sach+'&val[status]='+trang_thai+'&val[math]math='+Math.random());
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
	http.open('get', '/includes/ajax.php?=&core[call]=user.updateStatusUser&val[id]='+stt+'&val[status]='+trang_thai+'&val[math]='+Math.random());
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
				<td><?= Core::getPhrase('language_ten-truy-cap')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><input style="width:245px;" name="ten_truy_cap" id="ten_truy_cap" class="inputbox" value="<?= $ten_truy_cap?>" type="text"></td>
			</tr>
			<tr>
				<td><?= Core::getPhrase('language_mat-khau')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><input style="width:245px;" name="mat_khau" size="30" class="inputbox" value="" type="password"></td>
			</tr>
			<tr>
				<td><?= Core::getPhrase('language_mat-khau-nhap-lai')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><input style="width:245px;" name="mat_khau_nhap_lai" size="30" class="inputbox" value="" type="password" autocomplete=off></td>
			</tr>
			<tr>
				<td><?= Core::getPhrase('language_hop-thu')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><input style="width:245px;" name="hop_thu" size="30" value="<?= $hop_thu?>" class="inputbox required validate-hop_thu" maxlength="100" type="text"></td>
			</tr>
			<tr>
				<td><?= Core::getPhrase('language_hop-thu-nhap-lai')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><input style="width:245px;" name="hop_thu_nhap_lai" size="30" value="<?= $hop_thu_nhap_lai?>" class="inputbox required validate-hop_thu" maxlength="100" type="text" autocomplete=off></td>
			</tr>
			<tr>
				<td><?= Core::getPhrase('language_ngay-sinh')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><input style="width:245px;" name="ngay_sinh" size="30" value="<?= $ngay_sinh?>" class="inputbox" maxlength="100" type="text">
					<span> (
					<?= Core::getPhrase('language_vi-du-ngay-sinh')?>
					)</span></td>
			</tr>
			<tr>
				<td><?= Core::getPhrase('language_gioi-tinh')?>
					: (<span style="color: rgb(255, 0, 0);">*</span>)</td>
				<td><select style="width:245px;" class="inputbox" name="gioi_tinh">
						<option value="1"<?php if($gioi_tinh==1){?> selected="selected"<?php }?>>
						<?= Core::getPhrase('language_gioi-tinh-nam')?>
						</option>
						<option value="0"<?php if($gioi_tinh==0){?> selected="selected"<?php }?>>
						<?= Core::getPhrase('language_gioi-tinh-nu')?>
						</option>
					</select></td>
			</tr>
		</table>
		<script>document.getElementById("ten_truy_cap").focus();</script>
		<div style="clear:both;"></div>
		<button type="submit" name="submit"><span class="round"><span><?= Core::getPhrase('language_hoan-thanh')?></span></span></button>
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
<a href="user/edit/id_<?= $id?>">
<?= Core::getPhrase('language_bam-vao-day-de=xem-thanh-vien')?>
<?= $ten_truy_cap?>
</a>!
<p class="buttonarea">
	<button type="button" onclick="window.location='user/';"><span class="round"><span>
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
	<button type="button" onclick="window.location='user/';"><span class="round"><span>
	<?= Core::getPhrase('language_quan-ly-thanh-vien')?>
	</span></span></button>
</p>
<?php
}
?>