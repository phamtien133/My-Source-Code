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
         <button onclick="window.location = '/user/group/add/'" type="button"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></button><button class="fright" type="button" onclick="window.location = 'user/group/'"><span class="round"><span><?= Core::getPhrase('language_quan-ly')?></span></span></button>
     </form>
    <?php else :?>
    
    <form method="post" name="frm_dang_ky" class="box style width100" onsubmit="return sbm_frm()">

     <?php foreach($errors as $error):?>
        <div class="box-warning"><?= Core::getPhrase('language_da-co-loi-xay-ra')?>:<br /><?= $error?></div>
    <?php endforeach?>
<table class="zebra">
    <tr>
        <td><label for="name"><?= Core::getPhrase('language_ten')?>:</label><span id="div_ten_kiem_tra_name_code"></span></td>
		<td><input type="text" id="name" name="name" value="<?= $data_arr['name']?>" /></td>
    </tr>
    
    <tr>
        <td><label for="name_code"><?= Core::getPhrase('language_ma-ten')?>:</label>(<a href="javascript:" onclick="return btn_cap_nhat_name_code()"><?= Core::getPhrase('language_cap-nhat-tu-dong')?></a>)</td>
        <td><input type="text" id="name_code" name="name_code" value="<?= $data_arr['name_code']?>" onblur="kiem_tra_name_code()" /></td>
    </tr>
    
    <tr>
		<td><label for="status"><?= Core::getPhrase('language_trang-thai')?>:</label></td>
        <td><select name="status" id="status">
           <option value="1"<?php if($data_arr['status'] ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
           <option value="0"<?php if($data_arr['status'] ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
     </select></td>
     </tr>
     <tfoot>
     	<tr><td colspan="2"><button type="submit" name="submit"><span class="round"><span><?= Core::getPhrase('language_hoan-tat')?></span></span></button><button class="fright" type="button" onclick="window.location = 'user/group/'"><span class="round"><span><?= Core::getPhrase('language_quan-ly')?></span></span></button></td></tr>
	</tfoot>
</table>
    </div>

    </form>
    

<script>
<? if($id < 1):?>
$('#name').keyup(function(){
    lay_name_code_tu_dong($(this).val())
});
$('#name').change(function(){
    lay_name_code_tu_dong($(this).val());
    kiem_tra_name_code()
});
$('#name').blur(function(){
    lay_name_code_tu_dong($(this).val());
    kiem_tra_name_code();
});
<? endif?>
function lay_name_code_tu_dong(noi_dung, obj)
{
	if(obj == undefined) obj = 'name_code';
	noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
	noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
	
	document.getElementById(obj).value = noi_dung;
}
function kiem_tra_name_code() {
    
}
function btn_cap_nhat_name_code(id)
{
	if(id == undefined) id = 0;
	if(id == 0)
	{
		lay_name_code_tu_dong($('#name').val());
		kiem_tra_name_code();
	}
	else
	{
		lay_name_code_tu_dong($('#name').val());
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