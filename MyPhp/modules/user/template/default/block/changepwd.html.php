<h2 style="line-height:40px;padding-left:15px"><a><?= Core::getPhrase('language_doi-mat-khau')?></a></h2>
<?php
$this->__aVars['aError']['error'];
if ($this->__aVars['aResult']['status'] == 1)
{
?>
        <form method="post" name="frm_dang_ky">
        <div class="contact_email">

        <?php if(!empty($this->__aVars['aError'])) { ?>

        <div class="_menu"><div class="side-mod"><div class="module-header png"><div class="module-header2 png"><div class="module-header3 png"><h3 class="module-title"><?= Core::getPhrase('language_da-co-loi-xay-ra')?>: </h3></div></div></div><div class="module-tm png"><div class="module-tl png"><div class="module-tr png"><div class="module png" >

              <?= $this->__aVars['aError']['error']?>

        </div></div></div></div></div></div>

        <?php } ?>

        <div style="clear:both"><div style="float:left;width:180px;"><?= Core::getPhrase('language_mat-khau-hien-tai')?>: (<span style="color: rgb(255, 0, 0);">*</span>)</div><input name="mat_khau_hien_tai" size="30" class="inputbox" value="" type="password"></div>

        <div style="clear:both"><div style="float:left;width:180px;"><?= Core::getPhrase('language_mat-khau-moi')?>: (<span style="color: rgb(255, 0, 0);">*</span>)</div><input name="mat_khau" size="30" class="inputbox" value="" type="password"></div>

        <div style="clear:both"><div style="float:left;width:180px;"><?= Core::getPhrase('language_mat-khau-moi-nhap-lai')?>: (<span style="color: rgb(255, 0, 0);">*</span>)</div><input name="mat_khau_nhap_lai" size="30" class="inputbox" value="" type="password" autocomplete=off></div>

        <div style="clear:both"><div style="float:left;width:180px;"><?= Core::getPhrase('language_ma-xac-nhan')?>:</div><input name="ma_xac_nhan" class="inputbox" type="text" autocomplete=off></div>

        <div style="float:left;width:180px;">&nbsp;</div><div id="hinh_anh"><img ></div>
        <div style="float:left;width:180px;">&nbsp;</div><a href="javascript:void(this);" onclick="lay_hinh_moi();"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/refresh.png"> <?= Core::getPhrase('language_lay-hinh-moi')?></a>
        <div style="clear:both;"></div>

        <div><button type="submit" class="fright" name="submit"><span class="round"><span><?= Core::getPhrase('language_tiep-tuc')?></span></span></button></div>

        </div>

        </form>
    <script>
    function lay_hinh_moi()
    {
        document.getElementById("hinh_anh").innerHTML='<img src="' + setupPath('s') + '/tools/hinh_anh.php?_='+Math.random()+'" />';
    }
    lay_hinh_moi();
    </script>
<?php
}
elseif($this->__aVars['aResult']['status'] == 2)
{
?>
    <?php if(!empty($this->__aVars['aError'])):?>
        <br />
        <?= Core::getPhrase('language_da-co-loi-xay-ra')?>:<br />
        <?php foreach($this->__aVars['aError'] as $sError):?>
            <?= $sError?><br />
        <?php endforeach?>
    <?php endif?>
<?php
}
else
{
?>
<?= Core::getPhrase('language_chao')?> <b><?= Core::getUserName()?></b>

<p><?= Core::getPhrase('language_mat-khau-da-duoc-doi-thanh-cong')?>
<br /><?= Core::getPhrase('language_mat-khau')?>: <?= $this->__aVars['aResult']['sPassword']?> (<b><?= strlen($this->__aVars['aResult']['sPassword'])?> <?= Core::getPhrase('language_ky-tu')?></b>)
</p>

<br /><?= Core::getPhrase('language_cam-on')?>
<?php
}
?>