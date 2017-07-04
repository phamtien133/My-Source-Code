<?php
foreach($this->_aVars['output'] as $key)
{
	$$key = $this->_aVars['data'][$key];
}
?>
<?php if($status==2)
{
?>
<form id="jForm" action="" method="post" onsubmit="return sbm_frm()">
<table width="100%" class="zebra">
<tr>
<td colspan="2" style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_thong-tin')?></td>
</tr>
<tr>
	<td width="100px" align="center"><?= Core::getPhrase('language_thanh-vien-ten')?></td><td><?= $thanh_vien_ten?></td>
</tr>
<tr>
    <td align="center"><?= Core::getPhrase('language_thanh-vien-stt')?></td><td><a target="_blank" href="./user/add/id_<?= $id?>"><?= $id?></a></td>
</tr>
<tr>
	<td width="100px" align="center"><?= Core::getPhrase('language_hop-thu')?></td><td><?= $thanh_vien_hop_thu?></td>
</tr>
<tr>
    <td align="center"><?= Core::getPhrase('language_loai')?></td><td><?= $openid?></td>
</tr>
<tr>
    <td align="center"><?= Core::getPhrase('language_xoa')?></td><td><div id="div_thong_bao"><a href="javascript:" onclick="return xoa_quyen_thanh_vien(<?= $id?>, '<?= $thanh_vien_ten?>');" title="<?= Core::getPhrase('language_xoa-quyen-thanh-vien-stt')?> <?= $id?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a></div></td>
</tr>
</table>

		<select id="danh_sach_de_tai" class="hidden">
			<option value="0">Chọn siêu thị thiết lập</option>
			<?php for($i=0;$i<count($danh_sach_nha_cc);$i++):?>
			<option data-duong_dan="<?= $danh_sach_nha_cc[$i]['path']?>" data-parentid="<?= $danh_sach_nha_cc[$i]['parent_id']?>" value="<?= $danh_sach_nha_cc[$i][0]?>">
			<?= $danh_sach_nha_cc[$i][1]?>
			</option>
			<?php endfor?>
		</select>
<br />
<?php if (!$iVendorId):?>
    <table width="100%" class="zebra">
    <tr>
    <td colspan="3" style="font-weight:bold;text-align:center;">Danh sách nhà cung cấp quản lý</td>
    </tr>
    <?php if (isset($aVendorSelect) && !empty($aVendorSelect)):?>
    <tr>
        <td width="10%">STT</td>
        <td width="70%">Tên</td>
        <td width="20%">Sửa quyền</td>
    </tr>
    <?php $iCnt = 0; ?>
    <?php foreach ($aVendorSelect as $aTmp):?>
    <?php $iCnt++; ?>
    <tr>
        <td><?= $iCnt?></td>
        <td><?= $aTmp['name']?></td>
        <td>
            <a title="Sửa quyền" href="/user/permission/?id=<?= $id?>&vendor_id=<?= $aTmp['id']?><?php if($obj):?>&obj=1<?php endif?>">
                <img alt="Sửa quyền" src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png">
            </a>
        </td>
    </tr>
    <?endforeach?>
    <?php else:?>
    <tr>
    <td colspan="2" style="font-weight:bold;text-align:center;">Chưa có</td>
    </tr>
    <?php endif?>
    </table>
    <br />
	<table class="zebra">
        <tr>
            <td width="20%">Thiệt lập nhà cung cấp</td>
            <td width="40%">
                <div id="div_url_de_tai_chinh"></div>
                <div id="div_de_tai_chinh"></div>
                <span id="div_tai_the_noi_dung"></span>
            </td>
            <td>
                <a href="javascript:" onclick="return setVendor();">Thiết lập</a>
            </td>
        </tr>
	</table>
<br />
<?php else:?>
<table class="zebra">
    <tr>
        <td width="20%">Nhà cung cấp</td>
        <td> <?= $aVendor['name']?></td>
    </tr>
    
</table>
<input type="hidden" name="vendor_id" value="<?= $iVendorId?>">
<br />
<?php endif?>
<table width="100%" class="zebra">
<tr>
<td colspan="13" style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_khu-vuc')?></td>
</tr>
<tr>
<td width="20px" align="center"><a href="javascript:" onclick="return de_tai_chon_tat_ca()"><?= Core::getPhrase('language_chon')?></a></td>
<td width="230px"><?= Core::getPhrase('language_ten')?></td>
<? foreach($mang_khu_vuc as $key => $v):?>
<td><?= Core::getPhrase('language_'.str_replace('_', '-', $key))?><a href="javascript:" onclick="return de_tai_chon_tat_ca('<?= $v?>')"><?= Core::getPhrase('language_chon')?></a></td>
<? endforeach?>
</tr>
<?php
function Menu_gui_bai( $iVendorId,$parentid,$menu,$res = '',$sep = ''){
		foreach($menu as $v){
			if($v[2] == $parentid){
                if ($iVendorId) {
				    $re = '<tr id="div_de_tai_'.$v[0].'"><td width="20px" align="center"><a href="javascript:void(this)" onclick="chon_doi_tuong('.$v[0].')"><img src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png" id="hinh_anh_'.$v[0].'" class="de_tai_doi_tuong"></a></td><td><a href="'.$v[4].'">'.$sep.$v[1].'</td>
                    <td align="center"><img id="create_article_'.$v[0].'" class="create_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_article_'.$v[0].'" class="edit_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_other_article_'.$v[0].'" class="edit_other_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_comment_'.$v[0].'" class="edit_comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="comment_'.$v[0].'" class="comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="approve_comment_'.$v[0].'" class="approve_comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="was_approved_comment_'.$v[0].'" class="was_approved_comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="approve_article_'.$v[0].'" class="approve_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="was_approved_article_'.$v[0].'" class="was_approved_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    </tr>';
                }
                else {
                    $re = '<tr id="div_de_tai_'.$v[0].'"><td width="20px" align="center"><a href="javascript:void(this)" onclick="chon_doi_tuong('.$v[0].')"><img src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png" id="hinh_anh_'.$v[0].'" class="de_tai_doi_tuong"></a></td><td><a href="'.$v[4].'">'.$sep.$v[1].'</td>
                    <td align="center"><img id="create_category_'.$v[0].'" class="create_category cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_category_'.$v[0].'" class="edit_category cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="create_article_'.$v[0].'" class="create_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_article_'.$v[0].'" class="edit_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_other_article_'.$v[0].'" class="edit_other_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="edit_comment_'.$v[0].'" class="edit_comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="comment_'.$v[0].'" class="comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="approve_comment_'.$v[0].'" class="approve_comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="was_approved_comment_'.$v[0].'" class="was_approved_comment cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="approve_article_'.$v[0].'" class="approve_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    <td align="center"><img id="was_approved_article_'.$v[0].'" class="was_approved_article cl_khu_vuc" src="http://img.'.Core::getDomainName().'/styles/web/global/images/status_no.png"></td>
                    </tr>';
                }
				$res.=Menu_gui_bai($iVendorId, $v[0],$menu,$re,$sep."---");
			}
		}
		return $res;
	}
		
	$sMenu = Menu_gui_bai($iVendorId,-1,$menu);
	echo $sMenu;
?>
</table>

<br />
<table width="100%" class="zebra">
<thead>
<tr>
<td style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_menu')?></td>
</tr>
</thead>
<tbody>
<tr>
	<td>
<div id="div_slide_menu"></div>
<div style="clear:both"></div>
	</td>
</tr>
</tbody>
<tfoot>
<tr>
	<td>
    <select id="sel_div_slide_menu" class="sel_div_slide">
        <option value="0" selected="selected">
        <?= Core::getPhrase('language_cuoi-cung')?>
        </option>
    </select>
    <a onclick="them_slide_lien_ket('menu')"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></a>
</td>
</tr>
</tfoot>
</table>


<br />
<table width="100%" class="zebra">
<thead>
<tr>
<td style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_slide')?></td>
</tr>
</thead>
<tbody>
<tr>
	<td>
<div id="div_slide_slide"></div>
<div style="clear:both"></div>
	</td>
</tr>
</tbody>
<tfoot>
<tr>
	<td>
    <select id="sel_div_slide_slide" class="sel_div_slide">
        <option value="0" selected="selected">
        <?= Core::getPhrase('language_cuoi-cung')?>
        </option>
    </select>
    <a onclick="them_slide_lien_ket('slide')"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></a>
</td>
</tr>
</tfoot>
</table>

<br />
<table width="100%" class="zebra">
<tr>
<td colspan="2" style="font-weight:bold;text-align:center;"><?= Core::getPhrase('language_mo-rong')?></td>
</tr>
<tr>
	<td width="100px" align="center"><?= Core::getPhrase('language_uu-tien')?>:</td>
    <td>
    	<select name="priority" id="priority" />
    	<?php for($i=0;$i<10;$i++):?>
    	<option value="<?= $i?>" <?php if($priority == $i):?>selected="selected"<?php endif?>><?= $i?></option>
        <?php endfor?>
    	</select>
    </td>
</tr>
<tr>
	<td width="70px" align="center"><a href="javascript:void(this)" onclick="chon_tat_ca()" id="div_chon_tat_ca"><?= Core::getPhrase('language_chon-tat-ca')?></a></td>
	<td><b><?= Core::getPhrase('language_chuc-nang')?></b></td>
</tr>
<?php
foreach($mang as $i => $v)
{
    if($mang_tv[$i] != '')
    {
	?>
    <tr><td align="center"><input type="checkbox" name="<?= $v?>" id="<?= $v?>" value=1 <?php if($mang_khu_vuc_gia_tri[$i]==1) echo 'checked="checked"';?> class="inputbox" /></td><td><label for="<?= $v?>" style="padding-left:5px;"><?= $mang_tv[$i]?></label></td></tr>
    <?php
    }
}
?>
</table>
<p class="buttonarea"><button type="button" onclick="window.location = './user/'"><span class="round"><span><?= Core::getPhrase('language_quay-lai')?></span></span></button><button type="submit" name="submit"><span class="round"><span><?= Core::getPhrase('language_hoan-thanh')?></span></span></button></p>
<input type="hidden" name="create_category" id="create_category" value="" />
<input type="hidden" name="edit_category" id="edit_category" value="" />
<input type="hidden" name="create_article" id="create_article" value="" />
<input type="hidden" name="edit_article" id="edit_article" value="" />
<input type="hidden" name="edit_other_article" id="edit_other_article" value="" />
<input type="hidden" name="comment" id="comment" value="" />
<input type="hidden" name="edit_comment" id="edit_comment" value="" />
<input type="hidden" name="approve_comment" id="approve_comment" value="" />
<input type="hidden" name="was_approved_comment" id="was_approved_comment" value="" />
<input type="hidden" name="approve_article" id="approve_article" value="" />
<input type="hidden" name="was_approved_article" id="was_approved_article" value="" />
</form>

			<div id="dialog_search_box_slide" class="dialog_search_box none">
				<div style="background:#FFF;border:1px solid #CCC;padding-left:5px;">
					<input type="hidden" id="stt_slide_lien_quan" value="0" />
					<input type="hidden" id="class_slide_lien_quan" value="0" />
					<input type="text" id="txt_slide_lien_quan" value="" style="border:0;width:495px;height:30px" onkeydown="return tim_bai_lien_quan_dem_nguoc(event, 'slide')" onblur="tim_bai_lien_quan('slide')">
					<a href="javascript:" onclick="return tim_bai_lien_quan('slide')" title="<?= Core::getPhrase('language_tim-bai-lien-quan')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a> <a href="javascript:" onclick="return xoa_bai_lien_quan('slide')" title="<?= Core::getPhrase('language_xoa')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/delete.png"></a> </div>
				<div id="div_tim_slide_lien_quan"> </div>
			</div>
            
			<div id="dialog_search_box_menu" class="dialog_search_box none">
				<div style="background:#FFF;border:1px solid #CCC;padding-left:5px;">
					<input type="hidden" id="stt_menu_lien_quan" value="0" />
					<input type="hidden" id="class_menu_lien_quan" value="0" />
					<input type="text" id="txt_menu_lien_quan" value="" style="border:0;width:495px;height:30px" onkeydown="return tim_bai_lien_quan_dem_nguoc(event, 'menu')" onblur="tim_bai_lien_quan('menu')">
					<a href="javascript:" onclick="return tim_bai_lien_quan('menu')" title="<?= Core::getPhrase('language_tim-bai-lien-quan')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a> <a href="javascript:" onclick="return xoa_bai_lien_quan('menu')" title="<?= Core::getPhrase('language_xoa')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/delete.png"></a> </div>
				<div id="div_tim_menu_lien_quan"> </div>
			</div>
<script>
function setDirFromCat(objsel, objtxt)
{
	// dò cấu trúc ngược lại từ dưới lên
	var data = $(objsel).select2("val"), parentId = 0, url = '', parentObj;
	// lấy id dò với parent id
	
	parentObj = $('#danh_sach_de_tai').find("[value='" + data + "']");
	parentId = parentObj.data('parentid');
	url = $.trim(parentObj.text()) 
	
	while(parentId > 0)
	{
		parentObj = $('#danh_sach_de_tai').find("[value='" + parentId + "']");
		parentId = parentObj.data('parentid');
		
		url = $.trim(parentObj.text()) + ' » ' + url;
	}
	$(objtxt).html(url);
}
	$(function(){
		$('#div_de_tai_chinh').html('<select id="vendor_id" class="inputbox" style="width:300px">'+$('#danh_sach_de_tai').html()+'</select>');
		// chọn đề tài
		<? if($vendor_id > 0):?>$('#vendor_id').find("[value='" + <?= $vendor_id?> + "']").attr('selected', 'selected');<? endif?>
		
		$("#vendor_id").change(function(e) {
            setDirFromCat('#vendor_id', '#div_url_de_tai_chinh');
        }).select2();
		setDirFromCat('#vendor_id', '#div_url_de_tai_chinh');
	});
function xoa_quyen_thanh_vien(id, ten) {
	if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-quyen-thanh-vien')?> "+ten+" ?")) return false;
	document.getElementById('div_thong_bao').innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/loading.gif"> <?= Core::getPhrase('language_dang-tai-du-lieu')?>';
	http.open('get', '/includes/ajax.php?=&core[call]=user.deleteUserPermission&val[id]=' + id);
	http.onreadystatechange = function(){
		if(http.readyState == 4){
			var response = http.responseText;
			if(response == 0){
				document.getElementById('div_thong_bao').innerHTML = '<?= Core::getPhrase('language_ket-noi-he-thong-bi-loi-vui-long-thu-lai')?>';
			} else {
				document.getElementById('div_thong_bao').innerHTML = '<b><?= Core::getPhrase('language_thong-bao')?></b>:<p>'+response+'</p>';
			}
		}
	};
	http.send(null);
	return false;
}


/* ------------------------ */

function dong_bo_chon_khu_vuc(id)
{
	var mang_gia_tri = ['no', 'yes'];
	var mang = [
		'create_category',
		'edit_category',
		'create_article',
		'edit_article',
		'edit_other_article',
		'comment',
		'edit_comment',
		'approve_comment',
		'approve_article',
		];
	var obj = $('#hinh_anh_' + id);
	var val = 1;
	for(var tmp in mang)
	{
		$('.' + mang[tmp]).each(function() {
			if($(this).attr('id').indexOf('_' + id) > 0)
			{
				if($(this).attr('alt') != 1)
				{
					val = 0;
					return ;
				}
			}
		});
	}
	obj.attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_' + mang_gia_tri[val] + '.png');
	obj.attr('alt', val);
}
function chon_khu_vuc_mac_dinh()
{
    var is_vendor = <?= isset($iVendorId) ? $iVendorId: 0;?>;
    
	var mang_gia_tri = [];
    if (is_vendor < 1) {
        mang_gia_tri['edit_category'] = '<?= $mang_gia_tri['edit_category']?>';
        mang_gia_tri['create_category'] = '<?= $mang_gia_tri['create_category']?>';
    }
	
	mang_gia_tri['edit_article'] = '<?= $mang_gia_tri['edit_article']?>';
	mang_gia_tri['create_article'] = '<?= $mang_gia_tri['create_article']?>';
	mang_gia_tri['edit_other_article'] = '<?= $mang_gia_tri['edit_other_article']?>';
	mang_gia_tri['comment'] = '<?= $mang_gia_tri['comment']?>';
	mang_gia_tri['edit_comment'] = '<?= $mang_gia_tri['edit_comment']?>';
	mang_gia_tri['approve_comment'] = '<?= $mang_gia_tri['approve_comment']?>';
	mang_gia_tri['was_approved_comment'] = '<?= $mang_gia_tri['was_approved_comment']?>';
	mang_gia_tri['approve_article'] = '<?= $mang_gia_tri['approve_article']?>';
	mang_gia_tri['was_approved_article'] = '<?= $mang_gia_tri['was_approved_article']?>';
	
	var mang_chu = ['no', 'yes'];
    if (is_vendor < 1) {
        var mang = [
        'create_category',
        'edit_category',
        'create_article',
        'edit_article',
        'edit_other_article',
        'comment',
        'edit_comment',
        'approve_comment',
        'was_approved_comment',
        'approve_article',
        'was_approved_article',
        ];
    }
    else {
        var mang = [
        'create_article',
        'edit_article',
        'edit_other_article',
        'comment',
        'edit_comment',
        'approve_comment',
        'was_approved_comment',
        'approve_article',
        'was_approved_article',
        ];
    }
	
		var id, i, tmp;
		var val = 1, ten;
	
		for(ten in mang_gia_tri)
		{
			if(mang_gia_tri[ten] != '')
			{
				tmp = mang_gia_tri[ten].split(',');
				for(i=0;i<tmp.length;i++)
				{
					id = tmp[i];
					$('.' + ten).each(function() {
						if($(this).attr('id').indexOf('_' + id) > 0)
						{
							$(this).attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_' + mang_chu[val] + '.png');
							$(this).attr('alt', val);
						}
					});
				}
			}
		}
	$('.de_tai_doi_tuong').each(function(index, element) {
		id = $(this).attr('id').replace('hinh_anh_', '');
		dong_bo_chon_khu_vuc(id);
    });
	
}
chon_khu_vuc_mac_dinh();
$('.cl_khu_vuc').click(function(){
    chon_khu_vuc($(this).attr('id'));
});
function chon_khu_vuc(val)
{
	var obj = $('#' + val);
	if(obj.attr('alt') != 1)
	{
		obj.attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png');
		obj.attr('alt', 1);
	}
	else
	{
		obj.attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png');
		obj.attr('alt', 0);
	}
	
	var ten = obj.attr('class').replace(' cl_khu_vuc', '');
	var id = obj.attr('id').replace(ten + '_', '');
	dong_bo_chon_khu_vuc(id);
}
function sbm_frm()
{
	var i = 0;
	var tmps = [], tmps_key = [];
	var ten = '', tmp;
	var ton_tai = false;
	$('.cl_khu_vuc').each(function(){
		if($(this).attr('alt') == 1)
		{
			ten = $(this).attr('class').replace(' cl_khu_vuc', '');
			if(tmps[ten] == undefined) tmps[ten] = '';
			tmps[ten] += $(this).attr('id').replace(ten + '_', '') + ',';
		}
	});
	for(ten in tmps)
	{
		tmp = tmps[ten];
		i = tmp.length-1;
		tmp = tmp.substr(0, i);
		$('#' + ten).val(tmp);
	}
	return true;
}
function chon_doi_tuong(id)
{
	var mang_gia_tri = ['no', 'yes'];
	var mang = [
		'create_category',
		'edit_category',
		'create_article',
		'edit_article',
		'edit_other_article',
		'comment',
		'edit_comment',
		'approve_comment',
		'approve_article',
		];
	var obj = $('#hinh_anh_' + id);
	var val = obj.attr('alt');
	
	if(val == 0) val = 1;
	else val = 0;
	
	obj.attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_' + mang_gia_tri[val] + '.png');
	obj.attr('alt', val);
	for(var tmp in mang)
	{
		$('.' + mang[tmp]).each(function() {
			if($(this).attr('id').indexOf('_' + id) > 0)
			{
				$(this).attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_' + mang_gia_tri[val] + '.png');
				$(this).attr('alt', val);
			}
		});
	}
}
function de_tai_chon_tat_ca(obj)
{
	if(typeof(obj) == 'undefined') obj = '';
	var mang_gia_tri = ['no', 'yes'];
	var mang = <?= json_encode($mang_khu_vuc)?>;
	var val = 0;
	
	
	if(obj != '')
	{
		mang = [obj];
		tmp = 0;
		$('.' + mang[tmp]).each(function(index, element) {
			if($(this).attr('alt') == 1)
			{
				val = 1;
				return;
			}
		});
		if(val == 0) val = 1;
		else val = 0;
		
	}
	else
	{
		$('.de_tai_doi_tuong').each(function(index, element) {
			if($(this).attr('alt') == 1)
			{
				val = 1;
				return;
			}
		});
		if(val == 0) val = 1;
		else val = 0;
		
		for(var i in mang)
		{
			if(mang[i] == 'was_approved_article') delete mang[i];
			if(mang[i] == 'was_approved_comment') delete mang[i];
		}
		
		$('.de_tai_doi_tuong').attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_' + mang_gia_tri[val] + '.png');
		$('.de_tai_doi_tuong').attr('alt', val);
	}
	
	for(var tmp in mang)
	{
		$('.' + mang[tmp]).attr('src', 'http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_' + mang_gia_tri[val] + '.png');
		$('.' + mang[tmp]).attr('alt', val);
	}
	return false;
}
function chon_tat_ca()
{
	var field = document.getElementById('jForm');
	var chon = 1;
	for (i = 0; i < field.length; i++)
	{
		if(field[i].type == 'checkbox' && field[i].checked == true)
		{
			chon = 0;
			break;
		}
	}
	if(chon == 1)
	{
		document.getElementById('div_chon_tat_ca').innerHTML = '<?= Core::getPhrase('language_bo-tat-ca')?>';
		for (i = 0; i < field.length; i++)
		{
			if(field[i].type == 'checkbox') field[i].checked = true;
		}
	}
	else
	{
		document.getElementById('div_chon_tat_ca').innerHTML = '<?= Core::getPhrase('language_chon-tat-ca')?>';
		for (i = 0; i < field.length; i++)
		{
			if(field[i].type == 'checkbox') field[i].checked = false;
		}
	}
}

function setVendor()
{
    var vendor_id = $('#vendor_id').val();
    if (vendor_id < 1) {
        alert('Vui lòng chọn siêu thị trước!');
        return false;
    }
    var url = '/user/permission/?id='+ <?= $id?> + '&vendor_id='+vendor_id;
    var group = <?= $obj?>;
    if(typeof(group) != 'undefined' && group > 0)
    {
        url += '&obj=1';
    }
    window.open(url);
    return;
}
</script>
<?php
}
elseif($status==3)
{
?>
	<p><?= Core::getPhrase('language_da-hieu-chinh-thanh-cong')?></p>
	<?php if(!empty($errors)) {?><br /><?= Core::getPhrase('language_da-co-loi-xay-ra')?>:<br />
	<? foreach($errors as $error):?>
		<?= $error?>
	<? endforeach?>
	<?php }?>
   <p class="buttonarea"><button type="button" onclick="window.location='./user/index/?type=user';"><span class="round"><span><?= Core::getPhrase('language_thanh-vien')?></span></span></button> | <button type="button" onclick="window.location='./user/permission/?id=<?= $id?><? if($obj > 0):?>&obj=1<? endif?>';"><span class="round"><span><?= Core::getPhrase('language_sua-quyen-thanh-vien')?></span></span></button></p>
<?php } else {?>
	<br /><?= Core::getPhrase('language_da-co-loi-xay-ra')?>:<br />
	<? foreach($errors as $error):?>
		<?= $error?>
	<? endforeach?>
	<p class="buttonarea"><button type="button" onclick="window.location='./user/index/';"><span class="round"><span><?= Core::getPhrase('language_thanh-vien')?></span></span></button></p>
<?php }?>