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

<p class="buttonarea">
    <button type="button" onclick="window.location='/menu/detail/?mid=<?= $id?>';"><span class="round"><span>
    <?= Core::getPhrase('language_tao-menu')?>
    </span></span></button>
    |
    <button type="button" onclick="window.location='/article/add/';"><span class="round"><span>
    <?= Core::getPhrase('language_viet-bai')?>
    </span></span></button>
</p>
<table class="zebra">
    <tr>
        <td><?= Core::getPhrase('language_ten')?></td>
        <td><?= $menu_chinh['name']?></td>
    </tr>
    <tr>
        <td><?= Core::getPhrase('language_ma-ten')?></td>
        <td><?= $menu_chinh['name_code']?></td>
    </tr>
</table>
<br />
<div class="fclear">
    <div class="fleft" style="width:50px" align="center">
        <?= Core::getPhrase('language_ma-so')?>
    </div>
    <div class="fleft" style="width:560px">
        <?= Core::getPhrase('language_ten')?>
    </div>
    <div class="fleft" style="width:30px">
        <?= Core::getPhrase('language_tao')?>
    </div>
    <div class="fleft" style="width:30px">
        <?= Core::getPhrase('language_sua')?>
    </div>
    <div class="fleft" style="width:30px">
        <?= Core::getPhrase('language_xoa')?>
    </div>
    <div class="fleft" style="width:30px">
        <?= Core::getPhrase('language_tt')?>
    </div>
    <div class="fleft" style="width:100px" align="center">
        <?= Core::getPhrase('language_vi-tri')?>
        (<a href="javascript:" onclick="return luu_vi_tri()">
        <?= Core::getPhrase('language_luu')?>
        </a>)</div>
</div>
<div class="fclear">&nbsp;</div>
<?php
function Menu_gui_bai($parentid,$menu,$res = '',$sep = '')
{
    //global $id;
//    d($id); die();
    $id = 1;
    foreach($menu as $v)
    {
        if($v[2] == $parentid)
        {
            $them = '';
            $phu = '';
            $ton_tai = false;
            if($v[3] > 0)
            {
                $ton_tai = true;
                $them = 'div_parent ';
                $phu = '<div style="border-right:1px solid;float:right;height:30px;padding:0;margin:0;"></div><div style="border-left:1px solid;float:left;height:30px;padding:0;margin:0;"></div>';
            }
            $val = $parentid;
            if($val == -1) $val = 0;
            
            $trang_thai_gia_tri = $v[7]*1;
            
            if($trang_thai_gia_tri == 0)
            {
                $trang_thai_text = Core::getPhrase('language_khong-duoc-cho-phep-hien-thi');
                $trang_thai = 'status_no';
            }
            else
            {
                $trang_thai_text = Core::getPhrase('language_duoc-cho-phep-hien-thi');
                $trang_thai = 'status_yes';
            }
            
            $re = '
            <li id="div_menu_'.$v[0].'" class="div_menu '.$them.'div_menu_nhom_'.$val.'">'.$phu.'
                <div class="fleft" style="width:50px" align="center">'.$v[0].'</div>
                <div class="fleft" style="width:560px">'.$sep.'<a href="'.$v[4].'">'.$v[1].'</a></div>
                <div class="fleft" style="width:30px" title="'.Core::getPhrase('language_tao-menu').'">
                    <a href="/menu/detail/?mid='.$id.'&sub='.$v[0].'">
                        <img src="http://img.'.Core::getDomainName().'/styles/web/global/images/add.png" />
                    </a>
                </div>
                <div class="fleft" style="width:30px" title="'.Core::getPhrase('language_sua-bai').'">
                    <a href="/menu/detail/?mid='.$id.'&id='.$v[0].'">
                        <img src="http://img.'.Core::getDomainName().'/styles/web/global/images/edit.png" />
                    </a>
                </div>
                <div class="fleft" style="width:30px">
                    <a href="javascript:" onclick="return xoa_menu('.$v[0].', \''.str_replace("'", "\'", $v[1]).'\');" title="'.Core::getPhrase('language_xoa-bai').'">
                        <img src="http://img.'.Core::getDomainName().'/styles/web/global/images/delete.png" />
                    </a>
                </div>
                <div class="fleft" style="width:30px" id="div_bai_viet_'.$v[0].'"><a href="javascript:void(this);" onclick="hien_thi('.$v[0].', '.$trang_thai_gia_tri.');" title="'.$trang_thai_text.'"><img src="http://img.'.Core::getDomainName().'/styles/web/global/images/'.$trang_thai.'.png" /></a></div>
                <div class="fleft" style="width:100px" align="center">
                    <a href="javascript:" onclick="return tang_giam_vi_tri('.$val.', '.$v[0].', 1)">
                        <img src="http://img.'.Core::getDomainName().'/styles/acp/img/up.png" />
                    </a>
                    <a href="javascript:" onclick="return tang_giam_vi_tri('.$val.', '.$v[0].', 0)">
                        <img src="http://img.'.Core::getDomainName().'/styles/acp/img/down.png" />
                    </a>
                </div>
                <div class="hidden val_pos_menu val_pos_'.$val.'">'.$v[6].'</div>
            ';
            if(!$ton_tai)
            {
                $re .= '
                </div>';
            }
            if($v[3] > 0)
                $re = $re.'<ol>';
            
            $res.=Menu_gui_bai($v[0],$menu,$re,$sep."<span style='padding-left:15px'>&nbsp;</span>");
            if($v[3] > 0)
                $res .= '</ol></li>';
            else
                $res .= '</li>';
        }
    }
    return $res;
}
    
$sMenu = Menu_gui_bai(-1,$menu);

echo ('<ol class="default vertical list_all_de_tai">'.$sMenu.'</ol>');;
?>
<script>
$(function(){
    for(var c = 1; c <= 6; c++){
        var s1='', s2='';
        for(var d = 1; d<= c; d++){
            s1 += ' ol ';
            if(d > 1)
                s2 += '>li>ol';
        }    
        $('.list_all_de_tai '+s1+' li').addClass('none_sort_'+c);
        $('.list_all_de_tai '+s2).sortable( {exclude: '.none_sort_'+c});
    }
    var check = 1;
    $('.list_all_de_tai').mouseup(function(e) {
        check = 1;
    });
    $('.list_all_de_tai').mousemove(function(e) {
        if(check == 1)
            $('.placeholder').remove();
    });
    $('.list_all_de_tai').mousedown(function(e) {
       check = 0;
    });
})
</script>
<script>
var dang_xoa_menu = 0;
function xoa_menu(stt, ten) {
    if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-menu')?> "+ten+" ?")) return false;
    if(dang_xoa_menu == 1)
    {
        alert('<?= Core::getPhrase('language_dang-xoa-vui-long-doi')?>');
        return false;
    }
    dang_xoa_menu = 1;
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=menu_value&val[status]=2&val[id]='+stt);
    http.onreadystatechange = function () {
        if(http.readyState == 4){
            dang_xoa_menu = 0;
            var response_error = http.responseText.split('<-errorvietspider->');
            var error = response_error[1];
            if(error != undefined)
            {
                alert(error);
            }
            else
            {
                document.getElementById('div_menu_' + stt).style.display = 'none';
            }
        }
    };
    http.send(null);
    return false;
}
function hien_thi(stt, trang_thai)
{
    if(trang_thai==1) trang_thai=0;
    else trang_thai=1;
    
    if(trang_thai == 0 && !confirm("<?= Core::getPhrase('language_ban-co-chac-khong-cho-phep-hien-thi-bai-viet')?>")) return false;
    document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=menu_value&val[id]='+stt+'&val[status]='+trang_thai+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
            } else {
                if(trang_thai == 1)
                {
                    document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" title="<?= Core::getPhrase('language_dang-duoc-cho-phep-hien-thi')?>"></a>';
                }
                else
                {
                    document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" title="<?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?>"></a>';
                }
            }
        }
    };
    http.send(null);
    return false;
}

var dang_luu_menu = 0;
function luu_vi_tri() {
    update_val_pos(0);
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
        
        data += 'val[' +$(element).attr('id').replace('div_menu_', '') + ']' + '=' + $('#' + $(element).attr('id') + ' .val_pos_' + obj).html() + '&';
    });
    dang_luu_menu = 1;
    data += '&val[id]=<?= $id?>';
    http.open('POST', '/includes/ajax.php?=&core[call]=menu.savePositionMenu',true);
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
    <?php foreach($nhom_uu_tien as $v):?>
    $('.div_menu_nhom_<?= $v?>').datasort({ 
        datatype    : 'number',
        sortElement : '.val_pos_<?= $v?>',
        reverse     : true
    });
    <?php endforeach?>
}
$(document).ready(function(){
    cap_nhat();
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
    else
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
    return false;
}
function update_val_pos(id){
    var c = 1;
    $('.div_menu_nhom_' + id).each(function(index, element) {
        $(this).children('.val_pos_menu').html(1000 - c);
        if($(this).hasClass('div_parent')){
            var id = $(this).attr('id').replace('div_menu_','');
            update_val_pos(id);
        }
        c++;
    });
}
</script>
<?php
}
elseif($status==2){}
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
<?php if($global['thiet_lap']['luu_hinh_tren_may_chu'] == 1) {?>
<br />
<a href="?type=quan_ly_menu&id=<?= $id?>&act=update">
<?= Core::getPhrase('language_bam-vao-day-de-cap-nhat-hinh')?>
<?= $ten?>
</a>
<?php }?>
<br />
<?= Core::getPhrase('language_bam-vao-day-de-xem-menu')?>
<a href="/<?= $duong_dan_tao_menu?>">
<?= $ten?>
</a>!
<p class="buttonarea">
    <button type="button" onclick="window.location='?type=quan_ly_menu';"><span class="round"><span>
    <?= Core::getPhrase('language_quan-ly-menu')?>
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
    <button type="button" onclick="window.location='?type=quan_ly_menu';"><span class="round"><span>
    <?= Core::getPhrase('language_quan-ly-menu')?>
    </span></span></button>
</p>
<?php
}
?>
