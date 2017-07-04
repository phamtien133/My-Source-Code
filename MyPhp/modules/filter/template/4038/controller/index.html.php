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
    <td width="50px" align="center"><a href="javascript:void(this)" onclick="chon_tat_ca()"><?= Core::getPhrase('language_chon')?></a></td>
    <td><?= Core::getPhrase('language_ten')?></td>
    <td><?= Core::getPhrase('language_tt')?></td>
    <td><?= Core::getPhrase('language_sua')?></td>
    <td><?= Core::getPhrase('language_xoa')?></td>
    <td align="center"><?= Core::getPhrase('language_vi-tri')?>(<a href="javascript:" onclick="return luu_vi_tri()"><?= Core::getPhrase('language_luu')?></a>)</td>
</tr>
</thead>
<tbody>
<?php
for($i=0;$i<count($shop_custom['id']);$i++)
{
?>
<tr id="div_menu_<?= $shop_custom['id'][$i]?>" class="div_menu div_menu_nhom_0">
    <td><input type="checkbox" name="ckb_shop_custom" onkeyup="hien_xu_ly_chon()" onclick="hien_xu_ly_chon()" onchange="hien_xu_ly_chon()" value="<?= $shop_custom['id'][$i]?>" /></td>
    <td><a href="/filter/edit/?id=<?= $shop_custom['id'][$i]?>"><?= $shop_custom['name'][$i]?></a></td>
    <td id="div_shop_custom_<?= $shop_custom['id'][$i]?>"><a href="javascript:void(this);" onclick="hien_thi(<?= $shop_custom['id'][$i]?>, <?= $shop_custom['status'][$i]?>);" title="<?php if($shop_custom['status'][$i] == 1) {?><?= Core::getPhrase('language_duoc-cho-phep-hien-thi')?><?php } else {?><?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?><?php }?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/<?= $shop_custom['status_text'][$i]?>.png" /></a></td>
    <td><a href="/filter/edit/?id=<?= $shop_custom['id'][$i]?>" title="<?= Core::getPhrase('language_sua-bai')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
    <td id="div_xoa_shop_custom_<?= $shop_custom['id'][$i]?>">
        <a href="javascript:void(this);" onclick="xoa_shop_custom(<?= $shop_custom['id'][$i]?>);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a>
    </td>
    <td align="center">
        <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $shop_custom['id'][$i]?>, 1)">
            <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up.png" />
        </a>
        <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $shop_custom['id'][$i]?>, 2)">
            <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up-down-custom.png" />
        </a>
        <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $shop_custom['id'][$i]?>, 0)">
            <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/down.png" />
        </a>
    </td>
    <td class="hidden val_pos_0"><?= $shop_custom['position'][$i]?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<p class="buttonarea"><button type="button" onclick="window.location='/filter/add/';"><span class="round"><span><?= Core::getPhrase('language_them')?></span></span></button></p>
<div id="div_chon"><a href="javascript:void(this);" onclick="xoa_danh_sach_bai();"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(1);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(0);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a></div>
<script type="text/javascript" >
function xoa_shop_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
    document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.deleteObject&val[type]=filter&val[id]='+stt);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
            } else {
                document.getElementById('div_menu_' + stt).innerHTML = '';
                document.getElementById('div_menu_' + stt).style.display = "none";                
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.deleteObject&val[type]=filter&val[list]='+danh_sach);
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
                    document.getElementById('div_menu_' + danh_sach_mang[i]).innerHTML = '';
                    document.getElementById('div_menu_' + danh_sach_mang[i]).style.display = "none";
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=filter&val[list]='+danh_sach+'&val[status]='+trang_thai+'&val[math]='+Math.random());
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=filter&val[id]='+stt+'&val[status]='+trang_thai+'&val[math]='+Math.random());
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
var dang_luu_menu = 0;
function luu_vi_tri() {
    if(dang_luu_menu == 1)
    {
        alert('<?= Core::getPhrase('language_dang-luu-vui-long-doi')?>');
        return false;
    }
    var obj, vi_tri = 0, data = '';
    $('.div_menu').each(function(index, element) {
        
        obj = $(element).attr('class');
        vi_tri = obj.indexOf('div_menu_nhom_') + 'div_menu_nhom_'.length;        
        obj = obj.substr(vi_tri, obj.length-vi_tri);
        vi_tri = obj.indexOf(' ');
        if(vi_tri > -1)
        {
            obj = obj.substr(0, vi_tri);
        }
        data += 'val[' + $(element).attr('id').replace('div_menu_', '') + ']=' + $('#' + $(element).attr('id') + ' .val_pos_' + obj).html() + '&';
    });
    dang_luu_menu = 1;
    data += 'val[type]=filter'
    http.open('POST', '/includes/ajax.php?=&core[call]=core.savePosition',true);
    http.onreadystatechange = function () {
        if(http.readyState == 4){
            dang_luu_menu = 0;
            chk_updownpos = false;
            var response_error = http.responseText.split('<-errorvietspider->');
            var error = response_error[1];
            if(error != undefined)
            {
                alert(error);
            }
            else
            {
                alert('<?= Core::getPhrase('language_thanh-cong')?>');
            }
        }
    };
    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    http.send(data);
}
function cap_nhat() {
    $('.div_menu_nhom_0').datasort({ 
        datatype    : 'number',
        sortElement : '.val_pos_0',
        reverse     : true
    });
}
function cap_nhat_gia_tri()
{
    // xét giá trị mặc định từ trên xuống dưới
    var i = 999;
    $('.div_menu').each(function(index, element) {
        i--;
        obj = $(element).attr('class');
        vi_tri = obj.indexOf('div_menu_nhom_') + 'div_menu_nhom_'.length;        
        obj = obj.substr(vi_tri, obj.length-vi_tri);
        vi_tri = obj.indexOf(' ');
        if(vi_tri > -1)
        {
            obj = obj.substr(0, vi_tri);
        }
        $('#' + $(element).attr('id') + ' .val_pos_' + obj).html(i);
    });
}
$(document).ready(function(){
    <? if($cap_nhat_vi_tri):?>
    cap_nhat();
    <? endif?>
    cap_nhat_gia_tri()
});
function doi_vi_tri(classname, stt_can, stt_thay)
{
    var obj = ' .val_pos_' + classname;
    var gia_tri_can = $('#div_menu_' + stt_can + obj).html();
    $('#div_menu_' + stt_can + obj).html($('#div_menu_' + stt_thay + obj).html());
    $('#div_menu_' + stt_thay + obj).html(gia_tri_can);

    cap_nhat();
}
var chk_updownpos = false;
function dong_trang(e) {
    if(chk_updownpos)
    {
        alert('<?= Core::getPhrase('language_canh-bao-ban-van-chua-thuc-hien-luu-vi-tri')?>');
        if(!e) e = window.event;
        //e.cancelBubble is supported by IE - this will kill the bubbling process.
        e.cancelBubble = true;
        e.returnValue = 'You sure you want to leave?'; //This is displayed on the dialog
        //e.stopPropagation works in Firefox.
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
    }
}
window.onbeforeunload = dong_trang;
function tang_giam_vi_tri(classname, stt, loai)
{
    chk_updownpos = true;
    var objs = $('.div_menu_nhom_' + classname),  i = 0, stt_sau = '', obj;
    if(loai == 1)
    {
        for(i = 0;i<objs.length;i++)
        {
            obj = $(objs[i+1]);
            if(obj.attr('id') == undefined)
            {
                stt_sau = $(objs[objs.length-1]).attr('id').replace('div_menu_', '');
                doi_vi_tri(classname, stt_sau, stt);
                break;
            }
            else
            {
                stt_sau = obj.attr('id').replace('div_menu_', '');
                if(stt_sau == stt)
                {
                    stt = $(objs[i]).attr('id').replace('div_menu_', '');
                    doi_vi_tri(classname, stt, stt_sau);
                    break;
                }
            }
        }
    }
    else if(loai == 0)
    {
        for(i = objs.length-1;i>=0;i--)
        {
            obj = $(objs[i-1]);
            if(obj.attr('id') == undefined)
            {
                stt_sau = $(objs[0]).attr('id').replace('div_menu_', '');
                doi_vi_tri(classname, stt_sau, stt);
                break;
            }
            else
            {
                stt_sau = obj.attr('id').replace('div_menu_', '');
                if(stt_sau == stt)
                {
                    stt = $(objs[i]).attr('id').replace('div_menu_', '');
                    doi_vi_tri(classname, stt, stt_sau);
                    break;
                }
            }
        }
    }
    else
    {
        var vi_tri = null;
        while(1)
        {
            vi_tri = prompt("Vui lòng nhập vị trí cần chuyển tới", "1");
            if(vi_tri == null)
            {
                break;
            }
            vi_tri *= 1;
            
            if(vi_tri > 0)
            {
                break;
            }
            else alert('Không thể xác định vị trí cần chuyển tới!');
        }
        if(vi_tri == null) return false;
        
        stt = 'div_slide_' + stt;
        vi_tri -= 1;
        var tong = 0, vi_tri_can = -1;
        var ton_tai = false;
        for(i = 0;i<objs.length;i++)
        {
            tong++;
            if(i == vi_tri)
            {
                vi_tri_can = tong;
                tong++;
            }
            if($(objs[i]).attr('id') == stt)
            {
                // nếu vị trí nhỏ hơn đối tượng
                if(vi_tri_can == -1)
                {
                    // lưu trạng thái
                    continue ;
                }
                ton_tai = true;
                $('#' + $(objs[i]).attr('id') + ' .val_pos_' + classname).html(vi_tri_can);
                continue;
            }
            $('#' + $(objs[i]).attr('id') + ' .val_pos_' + classname).html(tong);
        }
        // nếu vị trí nhỏ hơn đối tượng
        if(!ton_tai)
        {
            if(vi_tri_can > 0)
            {
                for(i = 0;i<objs.length;i++)
                {
                    if($(objs[i]).attr('id') == stt)
                    {
                        $('#' + $(objs[i]).attr('id') + ' .val_pos_' + classname).html(vi_tri_can);
                        ton_tai = true;
                        break;
                    }
                }
            }
            else
            {
                $('#' + stt + ' .val_pos_' + classname).html(objs.length + 1);
            }
        }
        cap_nhat();
    }
    return false;
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
