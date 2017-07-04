<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>

<section class="grid-block">
<div class="grid-box grid-h">
    <div class="module mod-box">
        <div class="deepest">
            <div class="badge badge-none"></div>
            <h3 class="module-title">
                <?= Core::getPhrase('language_chuc-nang')?>
            </h3>
            <div class="content">
            <?php if($status == 3):?>
            <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
            .<br />
            <form class="box style width100">
                <button onclick="window.location = '/acp/?type=tao_sua_menu'" type="button"><span class="round"><span>
                <?= Core::getPhrase('language_them')?>
                </span></span></button>
                <button class="fright" type="button" onclick="window.location = '/menu/'"><span class="round"><span>
                <?= Core::getPhrase('language_quan-ly')?>
                </span></span></button>
            </form>
            <?php elseif($status == 2) :?>
            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
            .<br />
            <?php foreach($errors as $error):?>
            <?= $error?>
            <br />
            <?php endforeach?>
            <form class="box style width100">
                <button onclick="window.location = '/acp/?type=tao_sua_menu'" type="button"><span class="round"><span>
                <?= Core::getPhrase('language_them')?>
                </span></span></button>
                <button class="fright" type="button" onclick="window.location = '/menu/'"><span class="round"><span>
                <?= Core::getPhrase('language_quan-ly')?>
                </span></span></button>
            </form>
            <?php else :?>
            <form method="post" name="frm_dang_ky" class="box style width100" onsubmit="return sbm_frm()">
                <?php foreach($errors as $error):?>
                <div class="box-warning">
                    <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
                    :<br />
                    <?= $error?>
                </div>
                <?php endforeach?>
                <table class="zebra">
                    <tbody>
                        <tr>
                            <td width="240px"> <?= Core::getPhrase('language_ten')?> <span id="div_ten_kiem_tra_ma_ten"></span></td>
                            <td><input type="text" id="name" name="name" value="<?= $ten?>" class="inputbox" /></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_ma-ten')?>:(<a href="javascript:" onclick="return btn_cap_nhat_ma_ten()">
                        <?= Core::getPhrase('language_cap-nhat-tu-dong')?>
                        </a>) </td>
                            <td><input type="text" id="name_code" name="name_code" value="<?= $ma_ten?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="trang_thai">
                                    <?= Core::getPhrase('language_trang-thai')?>
                                    :</label></td>
                            <td><select name="status" id="status">
                                    <option value="1"<?php if($trang_thai ==1):?> selected="selected"<?php endif?>>
                                    <?= Core::getPhrase('language_kich-hoat')?>
                                    </option>
                                    <option value="0"<?php if($trang_thai ==0):?> selected="selected"<?php endif?>>
                                    <?= Core::getPhrase('language_chua-kich-hoat')?>
                                    </option>
                                </select></td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <button type="submit" name="submit"><span class="round"><span>
                    <?= Core::getPhrase('language_hoan-tat')?>
                    </span></span></button>
                    <button class="fright" type="button" onclick="window.location = '/menu/'"><span class="round"><span>
                    <?= Core::getPhrase('language_quan-ly')?>
                    </span></span></button>
                </div>
                </div>
            </form>
            <script>
<? if($id < 1):?>
$('#name').keyup(function(){
    lay_ma_ten_tu_dong($(this).val())
});
$('#name').change(function(){
    lay_ma_ten_tu_dong($(this).val());
    kiem_tra_ma_ten()
});
$('#name').blur(function(){
    lay_ma_ten_tu_dong($(this).val());
    kiem_tra_ma_ten();
});
<? endif?>
function lay_ma_ten_tu_dong(noi_dung, obj)
{
    if(obj == undefined) obj = 'name_code';
    noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
    noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
    
    document.getElementById(obj).value = noi_dung;
    if(obj == 'name_code' && ma_ten_truoc != noi_dung)
    {
        var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
        if(obj_ten.innerHTML != '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">' )
        {
            obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
        }
    }
}
var ma_ten_truoc = document.getElementById("name_code").value;
function kiem_tra_ma_ten() {
    var noi_dung = document.getElementById("name_code").value;
    var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
    if(ma_ten_truoc != noi_dung)
    {
        obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
        http.open('POST', '/includes/ajax.php?=&core[call]=core.checkNameCode', true);
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
        http.send('val[type]=menu&val[name_code]='+unescape(noi_dung));
    }
}

function btn_cap_nhat_ma_ten(id)
{
    if(id == undefined) id = 0;
    if(id == 0)
    {
        lay_ma_ten_tu_dong($('#name').val());
        kiem_tra_ma_ten();
    }
    else
    {
        lay_ma_ten_tu_dong($('#ten_' + id).val(), 'ma_ten_' + id);
    }
    return false;
}
function sbm_frm()
{
    return true;
}
</script>
            <?php endif?>
            <div> </div>
        </div>
    </div>
</div>
