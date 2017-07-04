<?php
foreach($this->_aVars['output'] as $key)
{
	$$key = $this->_aVars['data'][$key];
}
?>
   	<section class="grid-block"><div class="grid-box grid-h"><div class="module mod-box">
	<div class="deepest">
    <div class="badge badge-none"></div>
    <h3 class="module-title"><?= Core::getPhrase('language_chuc-nang')?></h3>
    <div class="content">
    <?php if($status == 3):?>
    <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>.<br />
    
    
     <form class="box style width100">
         <button onclick="window.location = './imageextend/add/'" type="button"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></button><button class="fright" type="button" onclick="window.location = './imageextend/'"><span class="round"><span><?= Core::getPhrase('language_quan-ly')?></span></span></button>
     </form>
    <?php else :?>
    
    <form method="post" name="frm_dang_ky" class="box style width100" onsubmit="return sbm_frm()">
     <?php foreach($errors as $error):?>
        <div class="box-warning"><?= Core::getPhrase('language_da-co-loi-xay-ra')?>:<br /><?= $error?></div>
    <?php endforeach?>
    

                <table class="zebra">
                    <tr>
                    	<td><label for="ten"><?= Core::getPhrase('language_ten')?>:</label><span id="div_ten_kiem_tra_ma_ten"></span></td>
                        <td>
							<input class="inputbox" type="text" id="ten" name="ten" value="<?= $ten?>" />
						</td>
					</tr>
                    <tr>
                    	<td><label for="ma_ten"><?= Core::getPhrase('language_ma-ten')?>:</label>(<a href="javascript:" onclick="return btn_cap_nhat_ma_ten()"><?= Core::getPhrase('language_cap-nhat-tu-dong')?></a>)</td>
                        <td>
						<input class="inputbox" type="text" id="ma_ten" name="ma_ten" value="<?= $ma_ten?>" onblur="kiem_tra_ma_ten()" />
                        </td>
					</tr>
                	<tr>
                    	<td><label for="loai"><?= Core::getPhrase('language_loai')?>:</label></td>
                        <td>
                    <select name="loai" id="loai" class="inputbox">
                        <?php for($i=0;$i<count($hinh_mo_rong_danh_sach_loai);$i++):?>
                        <option value="<?= $hinh_mo_rong_danh_sach_loai[$i]['ma_ten']?>"<?php if($loai == $hinh_mo_rong_danh_sach_loai[$i]['ma_ten']) {?> selected="selected"<?php }?> title="<?= $hinh_mo_rong_danh_sach_loai[$i]['ghi_chu']?>"><?= $hinh_mo_rong_danh_sach_loai[$i]['ten']?></option>
                        <?php endfor?>
                    </select>
                    	</td>
					</tr>
                	<tr>
                    	<td><label for="anh_huong_den">Inherit:</label></td>
                        <td>
                    <select name="anh_huong_den" id="anh_huong_den" class="inputbox">
                        <?php for($i=0;$i<count($hinh_mo_rong_danh_sach_anh_huong_den);$i++):?>
                        <option value="<?= $hinh_mo_rong_danh_sach_anh_huong_den[$i]['ma_ten']?>"<?php if($anh_huong_den == $hinh_mo_rong_danh_sach_anh_huong_den[$i]['ma_ten']) {?> selected="selected"<?php }?> title="<?= $hinh_mo_rong_danh_sach_anh_huong_den[$i]['ghi_chu']?>"><?= $hinh_mo_rong_danh_sach_anh_huong_den[$i]['ten']?></option>
                        <?php endfor?>
                    </select>
                    	</td>
					</tr>
                    <tr>
                    	<td><label for="trang_thai"><?= Core::getPhrase('language_trang-thai')?>:</label></td>
                        <td>
					<select class="inputbox" name="trang_thai" id="trang_thai">
						<option value="1"<?php if($trang_thai ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
						<option value="0"<?php if($trang_thai ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
					</select>
                    	</td>
					</tr>
				</table>
<br clear="all" />
    
    <div><button type="submit" name="submit"><span class="round"><span><?= Core::getPhrase('language_hoan-tat')?></span></span></button><button class="fright" type="button" onclick="window.location = './imageextend/'"><span class="round"><span><?= Core::getPhrase('language_quan-ly')?></span></span></button></div>

    </div>

    </form>
    

<script>
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
function lay_ma_ten_tu_dong(noi_dung, obj)
{
	if(obj == undefined) obj = 'ma_ten';
	noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
	noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
	
	document.getElementById(obj).value = noi_dung;
}
function kiem_tra_ma_ten() {
}
function btn_cap_nhat_ma_ten(id)
{
	if(id == undefined) id = 0;
	if(id == 0)
	{
		lay_ma_ten_tu_dong($('#ten').val());
		kiem_tra_ma_ten();
	}
	else
	{
		lay_ma_ten_tu_dong($('#ten').val());
	}
	return false;
}
function sbm_frm()
{
	return true;
}
</script>
        <?php endif?>
    <div>
    </div>
    
						
		</div>
		
	</div></div>