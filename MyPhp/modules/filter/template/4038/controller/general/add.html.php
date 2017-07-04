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
    <?php if($status_global == 3):?>
    <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>.<br />
    
    
     <form class="box style width100">
         <button onclick="window.location = '/filter/general/add/'" type="button"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></button><button class="fright" type="button" onclick="window.location = '/filter/general/index/'"><span class="round"><span><?= Core::getPhrase('language_quan-ly')?></span></span></button>
     </form>
    <?php else :?>
    
    <form method="post" name="frm_dang_ky" class="box style width100" onsubmit="return sbm_frm()">
     <?php foreach($errors as $error):?>
        <div class="box-warning"><?= Core::getPhrase('language_da-co-loi-xay-ra')?>:<br /><?= $error?></div>
    <?php endforeach?>
    <div>
        <div style="float:left;width:180px;"><label for="ten"><?= Core::getPhrase('language_ten')?>:</label><span id="div_ten_kiem_tra_ma_ten"></span></div>
        <input type="text" id="ten" name="ten" value="<?= $ten?>" />
    </div>
    
    <div><div style="float:left;width:180px;"><label for="trang_thai"><?= Core::getPhrase('language_trang-thai')?>:</label></div><select name="trang_thai" id="trang_thai">
           <option value="1"<?php if($trang_thai ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
           <option value="0"<?php if($trang_thai ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
     </select>
     </div>
     <hr />
    <?= Core::getPhrase('language_gia-tri')?><br />
<? for($i=0;$i<count($gt_ten);$i++):?>
    <div class="fleft width30"><input type="checkbox" <? if(in_array($gt_stt[$i], $gt_chon)):?> checked="checked"<? endif?> value="<?= $gt_stt[$i]?>" name="gt[<?= $i?>]" id="gt_<?= $i?>" /> <label for="gt_<?= $i?>"><?= $gt_ten[$i]?></label></div>
<? endfor?>
<div style="clear:both"></div>
     <hr />
<br clear="all" />
    
    <div><button type="submit" name="submit"><span class="round"><span><?= Core::getPhrase('language_hoan-tat')?></span></span></button><button class="fright" type="button" onclick="window.location = '/filter/general/index/'"><span class="round"><span><?= Core::getPhrase('language_quan-ly')?></span></span></button></div>

    </div>

    </form>
    

<script>
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