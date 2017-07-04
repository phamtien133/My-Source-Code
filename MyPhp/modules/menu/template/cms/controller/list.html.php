<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<section class="container page-list-cat">
<?php if($status==1) {?>
    <div class="content-box mgbt20">
        <div class="row30 pad10">
            <div class="col2">
                <div class="button-blue" onclick="window.location='/menu/detail/?mid=<?= $id?>';">
                    <span class="fa fa-plus"></span>
                    <?= Core::getPhrase('language_tao-menu')?>
                </div>
            </div>
            <div class="col8"></div>
            <div class="col2">
                <div class="button-blue" onclick="window.location='/article/add/?type=product';">
                    <span class="fa fa-pencil"></span>
                    <?= Core::getPhrase('language_viet-bai')?>
                </div>
            </div>
        </div>
    </div>
    <div class="content-box panel-shadow mgbt20">
        <div class="row30 line-bottom pad10">
            <div class="col4">
                <?= Core::getPhrase('language_ten')?>:
            </div>
            <div class="col8">
                <?= $menu_chinh['name']?>
            </div>
        </div>
        <div class="row30 pad10">
            <div class="col4">
                <?= Core::getPhrase('language_ma-ten')?>:
            </div>
            <div class="col8">
                <?= $menu_chinh['name_code']?>
            </div>
        </div>
    </div>
    <div class="content-box panel-shadow mgbt20">
        <div class="fclear header-cat line-bottom">
            <div class="fleft sub-black-title col-cat-1">
                <?= Core::getPhrase('language_ma-so')?>
            </div>
            <div class="fleft sub-black-title col-cat-2">
                <?= Core::getPhrase('language_ten')?>
            </div>
            <div class="right " style="width:100px" align="center">
                <a href="javascript:" class="sub-black-title button-blue" style="padding: 4px 8px; color: #fff" onclick="return luu_vi_tri()">Lưu Vị trí</a>
            </div>
            <div class="fclear"></div>
        </div>
        <div class="fclear"></div>
        <?php
function Menu_gui_bai($parentid,$menu,$res = '',$sep = '', $mid)
{
    $id = $mid;
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
                //$phu = '<div style="border-right:1px solid;float:right;height:30px;padding:0;margin:0;"></div><div style="border-left:1px solid;float:left;height:30px;padding:0;margin:0;"></div>';
                $phu = '';
            }
            $val = $parentid;
            if($val == -1) $val = 0;
            
            $trang_thai_gia_tri = $v[7]*1;
            
            if($trang_thai_gia_tri == 0)
            {
                $trang_thai_text = Core::getPhrase('language_khong-duoc-cho-phep-hien-thi');
                $trang_thai = 'status_no';
                $icon_trang_thai = 'fa-close';
            }
            else
            {
                $trang_thai_text = Core::getPhrase('language_duoc-cho-phep-hien-thi');
                $trang_thai = 'status_yes';
                $icon_trang_thai = 'fa-check';
            }
            
            $re = '
            <li id="div_menu_'.$v[0].'" class="div_menu '.$them.'div_menu_nhom_'.$val.'">'.$phu.'
                <div class="item-de-tai">
                    <div class="fleft col-cat-1" align="center">'.$v[0].'</div>
                    <div class="fleft col-cat-2">'.$sep.'<a target="_blank" href="'.$v[4].'">'.$v[1].'</a></div>
                    <div class="fleft col-cat-4" id="div_bai_viet_'.$v[0].'"><a href="javascript:void(this);" onclick="hien_thi('.$v[0].', '.$trang_thai_gia_tri.');" title="'.$trang_thai_text.'"><span class="fa '.$icon_trang_thai.'"></span></a></div>
                    <div class="fleft col-cat-5" title="'.Core::getPhrase('language_tao-menu').'">
                        <a href="/menu/detail/?mid='.$id.'&sub='.$v[0].'">
                            <span class="fa fa-plus"></span>
                        </a>
                    </div>
                    <div class="fleft col-cat-6" title="'.Core::getPhrase('language_sua-bai').'">
                        <a href="/menu/detail/?mid='.$id.'&id='.$v[0].'">
                            <span class="fa fa-pencil"></span>
                        </a>
                    </div>
                    <div class="fleft col-cat-7">
                        <a href="javascript:" onclick="return xoa_menu('.$v[0].', \''.str_replace("'", "\'", $v[1]).'\');" title="'.Core::getPhrase('language_xoa-bai').'">
                            <span class="fa fa-trash"></span>
                        </a>
                    </div>
                    <div class="fleft col-cat-8" align="center">
                        <a href="javascript:" onclick="return tang_giam_vi_tri('.$val.', '.$v[0].', 1)">
                            <span class="fa fa-chevron-up"></span>
                        </a>
                        <a href="javascript:" onclick="return tang_giam_vi_tri('.$val.', '.$v[0].', 0)">
                            <span class="fa fa-chevron-down"></span>
                        </a>
                    </div>
                </div>
                <div class="hidden val_pos_menu val_pos_'.$val.'">'.$v[6].'</div>
            ';
            if($v[3] > 0)
                $re = $re.'<ol>';
            
            $res = $res.Menu_gui_bai($v[0],$menu,$re,$sep."<span class='offset-subcat'>-----</span>", $id);
            
            if($v[3] > 0)
                $res .= '</ol></li>';
            $res .= '</li>';
        }
    }
    return $res;
}
    
$sMenu = Menu_gui_bai(-1,$menu, '', '', $id);
echo ('<ol class="default vertical list_all_de_tai">'.$sMenu.'</ol>');
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
    if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>")) return false;
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
    document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><span class="fa fa-spinner fa-pulse"></span></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=menu_value&val[id]='+stt+'&val[status]='+trang_thai+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><span class="fa fa-warning" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '"></span></a>';
            } else {
                if(trang_thai == 1)
                {
                    document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ')"><span class="fa fa-check" title="<?= Core::getPhrase('language_dang-duoc-cho-phep-hien-thi')?>"></span></a>';
                }
                else
                {
                    document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><span class="fa fa-close" title="<?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?>"></span></a>';
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
    </div>
<?php
}
elseif($status==2){}
elseif($status==3)
{
?>
<div class="content-box panel-shadow mgbt20">
    <h3 class="box-title"><?= Core::getPhrase('language_quan-ly-menu')?></h3>
    <div class="box-inner">
        <div class="row30 padtb10 ">
            <?= Core::getPhrase('language_da-hieu-chinh-thanh-cong')?>
        </div>
        <?php if($error) {?>
        <div class="row30 padtb10 ">
            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
        </div>
        <div class="row30 padtb10">
            <div class="row30">
                <?= $error?>
            </div>
        </div>
        <?php }?>
        <div class="row30 padtop10">
            <?php if($global['thiet_lap']['luu_hinh_tren_may_chu'] == 1) {?>
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='?type=quan_ly_menu&id=<?= $id?>&act=update';">
                    Cập nhật hình
                </div>
            </div>
            <?php }?>
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='/<?= $duong_dan_tao_menu?>';">
                    Xem
                </div>
            </div>
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='/menu/';">
                    <?= Core::getPhrase('language_quan-ly-menu')?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
else
{
?>
<div class="content-box panel-shadow mgbt20">
    <h3 class="box-title"><?= Core::getPhrase('language_quan-ly-menu')?></h3>
    <div class="box-inner">
        <div class="row30 padtb10 ">
            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
        </div>
        <div class="row30 padtb10">
            <div class="row30">
                <?= $error?>
            </div>
        </div>
        <div class="row30 padtop10">
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='/menu/';">
                    <?= Core::getPhrase('language_quan-ly-menu')?>
                </div>
            </div>      
        </div>
    </div>
</div>
<?php
}
?>
</section>
