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
            <?php if($status_global == 3):?>
            <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
            .<br />
            <form class="box style width100">
                <button onclick="window.location = '/discount/add/'" type="button"><span class="round"><span>
                <?= Core::getPhrase('language_them')?>
                </span></span></button>
                <button class="fright" type="button" onclick="window.location = '/discount/index/'"><span class="round"><span>
                <?= Core::getPhrase('language_quan-ly')?>
                </span></span></button>
            </form>
            <?php elseif($status_global == 2) :?>
            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
            .<br />
            <?php foreach($errors as $error):?>
            <?= $error?>
            <br />
            <?php endforeach?>
            <form class="box style width100">
                <button onclick="window.location = '/discount/add/'" type="button"><span class="round"><span>
                <?= Core::getPhrase('language_them')?>
                </span></span></button>
                <button class="fright" type="button" onclick="window.location = '/discount/index/'"><span class="round"><span>
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
                            <td width="240px"> <?= Core::getPhrase('language_ten')?> </td>
                            <td><input type="text" id="ten" name="ten" value="<?= $name?>" class="inputbox" /></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_ma-so')?> </td>
                            <td><input type="text" id="ma_ten" name="ma_ten" value="<?= $name_code?>" /></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_loai')?> </td>
                            <td><select name="loai" id="loai">
                                    <option value="-1">- <?= Core::getPhrase('language_chon')?> -</option>
                                    <? for($i = 0; $i < count($loai_danh_sach); $i++):?>
                                    <option value="<?= $i?>"<? if($type == $i):?> selected="selected"<?php endif?>>
                                    <?= $loai_danh_sach[$i]?>
                                    </option>
                                    <? endfor?>
                                </select></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_gia-tri')?></td>
                            <td><input type="text" id="gia_tri" name="gia_tri" value="<?= $value?>" /></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_cong-thuc')?> </td>
                            <td><select name="cong_thuc" id="cong_thuc" disabled>
                                    <option value="-1">- <?= Core::getPhrase('language_chon')?> -</option>
                                    <? for($i = 0; $i < count($cong_thuc_danh_sach); $i++):?>
                                    <option value="<?= $i?>"<? if($formula == $i):?> selected="selected"<?php endif?>>
                                    <?= $cong_thuc_danh_sach[$i]?>
                                    </option>
                                    <? endfor?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?= Core::getPhrase('language_gia-ap-dung')?></td>
                            <td><select name="gia_ap_dung" id="gia_ap_dung">
                                    <option value="-1">- <?= Core::getPhrase('language_chon')?> -</option>
                                    <? for($i = 0; $i < count($gia_ap_dung_danh_sach); $i++):?>
                                    <option value="<?= $i?>"<? if($price_apply == $i):?> selected="selected"<?php endif?>>
                                    <?= $gia_ap_dung_danh_sach[$i]?>
                                    </option>
                                    <? endfor?>
                                </select></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_tong-tien')?> <br />
                                <small><?= Core::getPhrase('language_so-tien-can-dat')?></small></td>
                            <td><input type="text" id="tong_tien_ap_dung" name="tong_tien_ap_dung" value="<?= $tong_tien_ap_dung?>" /></td>
                        </tr>
                        <tr>
                            <td><?= Core::getPhrase('language_thoi-gian-bat-dau')?>:</td>
                            <td><input type="text" name="thoi_gian_bat_dau" id="thoi_gian_bat_dau" value="<?= $start_time?>" class="inputbox width50" /></td>
                        </tr>
                        <tr>
                            <td><?= Core::getPhrase('language_thoi-gian-ket-thuc')?>:</td>
                            <td><input type="text" name="thoi_gian_ket_thuc" id="thoi_gian_ket_thuc" value="<?= $end_time?>" class="inputbox width50" /></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_nhom-thanh-vien-duoc-ap-dung')?> </td>
                            <td> <?= Core::getPhrase('language_tat-ca')?> </td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_so-lan-su-dung')?> </td>
                            <td><input type="text" id="so_lan_su_dung" name="so_lan_su_dung" value="<?= $times_to_use?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="trang_thai">
                                    <?= Core::getPhrase('language_trang-thai')?>
                                    :</label></td>
                            <td><select name="trang_thai" id="trang_thai">
                                    <option value="1"<?php if($status ==1):?> selected="selected"<?php endif?>>
                                    <?= Core::getPhrase('language_kich-hoat')?>
                                    </option>
                                    <option value="0"<?php if($status ==0):?> selected="selected"<?php endif?>>
                                    <?= Core::getPhrase('language_chua-kich-hoat')?>
                                    </option>
                                </select></td>
                        </tr>
                        <tr>
                            <td> <?= Core::getPhrase('language_ap-dung')?> </td>
                            <td>
                                <label><input type="radio" name="ap_dung" class="ap_dung" value="0"<?php if($apply ==0):?> checked<?php endif?> /><?= Core::getPhrase('language_tren-hoa-don')?></label>
                                <br />
                                <label><input type="radio" name="ap_dung" class="ap_dung" value="1"<?php if($apply ==1):?> checked<?php endif?> /><?= Core::getPhrase('language_theo-san-pham')?></label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="none" id="danh_sach_chi_tiet"><div id="div_slide"></div>
                                <div style="clear:both"></div>
                                <div>
                                    <select id="sel_div_slide">
                                        <option value="0" selected="selected"><?= Core::getPhrase('language_cuoi-cung')?></option>
                                    </select>
                                    <a onclick="them_slide()"><span class="round"><span>
                                    <?= Core::getPhrase('language_them')?>
                                    </span></span></a> </div>
                                <div style="clear:both"></div></td>
                        </tr>
                    </tbody>
                </table>
                <hr />
                <hr />
                <br clear="all" />
                <div id="dialog_search_box">
                    <div style="background:#FFF;border:1px solid #CCC;padding-left:5px;">
                        <input type="hidden" id="stt_de_tai_bai_viet_lien_quan" value="0" />
                        <input type="text" id="txt_de_tai_bai_viet_lien_quan" value="" style="border:0;width:495px;height:30px" onkeydown="return tim_bai_lien_quan_dem_nguoc(event, 'de_tai_bai_viet')" onblur="tim_bai_lien_quan('de_tai_bai_viet')">
                        <a href="javascript:" onclick="return tim_bai_lien_quan('de_tai_bai_viet')" title="<?= Core::getPhrase('language_tim-bai-lien-quan')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a> <a href="javascript:" onclick="return xoa_bai_lien_quan('de_tai_bai_viet')" title="<?= Core::getPhrase('language_xoa')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/delete.png"></a> </div>
                    <div id="div_tim_de_tai_bai_viet_lien_quan"> </div>
                </div>
                <div>
                    <button type="submit" name="submit"><span class="round"><span>
                    <?= Core::getPhrase('language_hoan-tat')?>
                    </span></span></button>
                    <button class="fright" type="button" onclick="window.location = '/discount/index/'"><span class="round"><span>
                    <?= Core::getPhrase('language_quan-ly')?>
                    </span></span></button>
                </div>
                </div>
            </form>
            <script>
var tu_vua_tim = [];
function link_search_box(id) {
    $('#dialog_search_box').modal(
        {
            onShow: function (dialog) {
                if(tu_vua_tim['de_tai_bai_viet'] != '')
                {
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').val(tu_vua_tim['de_tai_bai_viet']);
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').focus();
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').select();
                    tu_vua_tim['de_tai_bai_viet'] = '';
                    tim_bai_lien_quan('de_tai_bai_viet');
                }
                $('#dialog_search_box #stt_de_tai_bai_viet_lien_quan').val(id);
                $("body").css("overflow","hidden");
            }, 
            onClose: function (dialog) {
                $("body").css("overflow","auto");
                dialog.data.fadeOut('fast', function () {
                    dialog.container.slideUp('fast', function () {
                        dialog.overlay.fadeOut('fast', function () {
                            $.modal.close(); // must call this!
                        });
                    });
                });
            }
        }
    );
    return false;
};
function chon_lien_ket(mang)
{
    // lấy stt
    if(mang['id'] == undefined) mang['id'] = $('#dialog_search_box #stt_de_tai_bai_viet_lien_quan').val();
    
    $('#ma_so_' + mang['id']).val(mang['ma_so']);
    $('#div_lien_ket_' + mang['id']).html('<a href="' + mang['duong_dan'] + '" target="_blank">' + mang['ten'] + '</a>');
    
    $('#gt_loai_' + mang['id']).val(mang['loai']);
    $.modal.close();
    return false;
}

function cap_nhat() {
$('.div_menu_nhom_0').datasort({ 
    datatype    : 'number',
    sortElement : '.val_pos_0',
    reverse     : false
});
}

    $('.ap_dung').change(function(e) {
        if($(this).val() == 0)
        {
            $('#danh_sach_chi_tiet').css('display', 'none');
        }
        else if($(this).val() == 1)
        {
            $('#danh_sach_chi_tiet').css('display', 'table-cell');
        }
    });
    $('.ap_dung').change();
<? if($id > 0):?>
    $('.ap_dung').each(function(index, element) {
        if($(this).val() == <?= $apply?>)
        {
            $(this).attr('checked', true);
            $(this).change();
            return false;
        }
    });
<? endif?>
$(document).ready(function(){
    $('#thoi_gian_bat_dau, #thoi_gian_ket_thuc').AnyTime_picker( { format: "%d-%m-%Z %H:%i:%s" } );
    cap_nhat();
});
function doi_vi_tri(classname, stt_can, stt_thay)
{
var obj = ' .val_pos_' + classname;
var gia_tri_can = $('#div_slide_' + stt_can + obj).html();

$('#div_slide_' + stt_can + obj).html($('#div_slide_' + stt_thay + obj).html());
$('#div_slide_' + stt_thay + obj).html(gia_tri_can);

cap_nhat();
}

function tang_giam_vi_tri(classname, stt, loai)
{
var objs = $('.div_menu_nhom_' + classname),  i = 0, stt_sau = '', obj;
if(loai == 1)
{
    for(i = 0;i<objs.length;i++)
    {
        obj = $(objs[i+1]);
        if(obj.attr('id') == undefined)
        {
            stt_sau = $(objs[objs.length-1]).attr('id').replace('div_slide_', '');
            doi_vi_tri(classname, stt_sau, stt);
            break;
        }
        else
        {
            stt_sau = obj.attr('id').replace('div_slide_', '');
            if(stt_sau == stt)
            {
                stt = $(objs[i]).attr('id').replace('div_slide_', '');
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
            stt_sau = $(objs[0]).attr('id').replace('div_slide_', '');
            doi_vi_tri(classname, stt_sau, stt);
            break;
        }
        else
        {
            stt_sau = obj.attr('id').replace('div_slide_', '');
            if(stt_sau == stt)
            {
                stt = $(objs[i]).attr('id').replace('div_slide_', '');
                doi_vi_tri(classname, stt, stt_sau);
                break;
            }
        }
    }
}
return false;
}

var slide = 0, lan_dau = true;
function them_slide(ma_so, ten, duong_dan, trang_thai, stt, loai)
{
    slide++;
    if(ma_so == undefined) ma_so = '';
    if(ten == undefined) ten = '';
    if(duong_dan == undefined) duong_dan = '';
    if(trang_thai == undefined) trang_thai = 1;
    if(stt == undefined) stt = 0;
    if(loai == undefined) loai = '';
    
    var vi_tri = parseInt($('#sel_div_slide').val());
    var slide_pos = 0;
    var content = '';
    // update cac node
    var objs = $('.div_menu_nhom_0'),  i = 0;
    if(vi_tri == -1)
    {
        slide_pos = 1;
        for(i = 0; i < objs.length; i++)
        {
            slide_pos += 1;
            $('#' + $(objs[i]).attr('id') + ' .val_pos_0').html(slide_pos);
        }
        slide_pos = 1;
    }
    else if(vi_tri > 0)
    {
        slide_pos = vi_tri + 1;
        for(i = vi_tri; i < objs.length; i++)
        {
            slide_pos += 1;
            $('#' + $(objs[i]).attr('id') + ' .val_pos_0').html(slide_pos);
        }
        slide_pos = vi_tri + 1;
    }
    else
    {
        slide_pos = slide;
    }
    // end
    // check trang thai 
    content = '<select name="gt_trang_thai[' + slide + ']" id="trang_thai_' + slide + '">';
    if(trang_thai == 1)
    {
        content += '<option value="1" selected="selected"><?= Core::getPhrase('language_kich-hoat')?></option><option value="0"><?= Core::getPhrase('language_chua-kich-hoat')?></option>';
    }
    else
    {
        content += '<option value="1"><?= Core::getPhrase('language_kich-hoat')?></option><option value="0" selected="selected"><?= Core::getPhrase('language_chua-kich-hoat')?></option>';
    }
    content += '</select>';
    
    // end
    content = '<div class="div_menu div_con div_menu_nhom_0" id="div_slide_' + slide + '"><div style="float:right"> \
                <a href="javascript:" onclick="return link_search_box(' + slide + ')" title="<?= Core::getPhrase('language_tim')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a>\
                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 1)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up.png" />\
                </a>\
                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 0)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/down.png" />\
                </a>\
                <a href="javascript:void(this)" onclick="xoa_slide(' + slide + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a>\
                </div>\
                <p>\
                <div style="float:left;width:180px;"><label for="ma_so_' + slide + '"><?= Core::getPhrase('language_ten')?></div>\
                <input type="hidden" name="gt_ma_so[' + slide + ']" id="ma_so_' + slide + '" value="' + ma_so + '" class="inputbox" style="width:50%;" />\
                <div id="div_lien_ket_' + slide + '" style="border:0;float:left"></div>\
                </p><div class="clear"><div style="float:left;width:180px;"><label for="trang_thai_' + slide + '"><?= Core::getPhrase('language_loai')?>:</label></div><input type="text" id="gt_loai_' + slide + '" name="gt_loai[' + slide + ']" value="' + loai + '" readonly="true" /></div><div class="clear"><div style="float:left;width:180px;"><label for="trang_thai_' + slide + '"><?= Core::getPhrase('language_trang-thai')?>:</label></div>' + content + '</div>\
            <div class="hidden val_pos_0">' + slide_pos + '</div>\
            <input type="hidden" name="gt_stt[' + slide + ']" value="' + stt + '" />\
            </div>';
    /*
    if(vi_tri == -1)
    {
        $('#div_slide').first().before(content);
    }
    else if(vi_tri > 0)
    {
        $('#div_slide_' + vi_tri).after(content);
    }
    else
    {
        $('#div_slide').append(content);
    }
    */
    $('#div_slide').append(content);
    if(ten != '')
    {
        chon_lien_ket({'ma_so' : ma_so, 'duong_dan' : duong_dan, 'ten' : ten, 'id' : slide, 'loai' : loai});
    }
    cap_nhat();
    if(!lan_dau) $('html, body').animate({scrollTop: $('#div_slide_' + slide).offset().top}, 800);
}
var tu_vua_tim = [];
var t_tim_lien_quan_dem_nguoc = [];
function xoa_bai_lien_quan(obj) {
    if(obj == undefined) obj = '';
    tu_vua_tim[obj] = '';
    document.getElementById('txt_' + obj + '_lien_quan').value = ''
    document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = '';
    return false;
}
function bai_lien_quan_da_them(obj)
{
    if(obj == undefined) obj = '';
    var tmp = document.getElementById('txt_them_' + obj + '_lien_quan'), tmp_canh_bao = document.getElementById('txt_them_' + obj + '_lien_quan_canh_bao');
    if(tmp.style.display == 'none')
    {
        tmp.style.display = 'block';
        tmp_canh_bao.style.display = 'none';
    }
    else
    {
        tmp.style.display = 'none';
        tmp_canh_bao.style.display = 'block';
        setTimeout('bai_lien_quan_da_them("' + obj + '")', 1200);
    }
    
}
function tim_bai_lien_quan(obj) {
    if(obj == undefined) obj = '';
    if(tu_vua_tim[obj] != document.getElementById('txt_' + obj + '_lien_quan').value)
    {
        document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif"> <?= Core::getPhrase('language_dang-sinh-khoa-vui-long-doi')?>';
        http.open('POST', '/includes/ajax.php?=&core[call]=core.getRelated', true);
        http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
        http.onreadystatechange = function () {
            if(http.readyState == 4){
                tu_vua_tim[obj] = document.getElementById('txt_' + obj + '_lien_quan').value;
                var tmp, tmp_lk = [], noi_dung = '', dong = http.responseText.split('<-vietspider->');
                var tong = dong.length-1;
                noi_dung = '<table class="zebra">';
                if(obj == 'thanh_vien')
                {
                    for( var i=0;i<tong;i++)
                    {
                        tmp = dong[i].split('<->');
                        noi_dung += '<tr><td><a>' + tmp[1] + '</a></td><td align="center"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/openid/' + tmp[2] + '.png" width="20" /></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="Đã chọn" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" onClick="return chon_thanh_vien(' + tmp[0] + ', \'' + tmp[1] + '\')"><?= Core::getPhrase('language_chon')?></a></div></td></tr>';
                    }
                }
                else if(obj == 'youtube')
                {
                    for( var i=0;i<tong;i++)
                    {
                        tmp = dong[i].split('<->');
                        noi_dung += '<tr><td><img src="http://img.youtube.com/vi/' + tmp[0] + '/2.jpg" /></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="http://www.youtube.com/watch?v=' + tmp[0] + '" target="_blank">' + tmp[1] + '</a></div></td><td><td><a href="" onClick="return chen_youtube(\'' + tmp[0] + '\')"><?= Core::getPhrase('language_them')?></a></td></tr>';
                    }
                }
    else if(obj == 'de_tai_bai_viet')
    {
        for( var i=0;i<tong;i++)
        {
            tmp = dong[i].split('<->');
            tmp_lk[1] = tmp[1].replace(/'/g, "\\'");
            tmp_lk[2] = tmp[2].replace(/'/g, "\\'");
            
            tmp_lk[1] = tmp_lk[1].replace(/"/g, '&quot;');
            tmp_lk[2] = tmp_lk[2].replace(/"/g, '&quot;');
            
            noi_dung += '<tr><td><a href="' + tmp[1] + '" target="_blank">' + tmp[2] + '</a></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" class="simplemodal-close" onClick="return chon_lien_ket({ma_so : ' + tmp[0] + ', duong_dan : \'' + tmp_lk[1] + '\', ten : \'' + tmp_lk[2] + '\', loai : \'' + tmp[3] + '\'})"><?= Core::getPhrase('language_chon')?></a></div></td></tr>';
        }
    }
                else
                {
                    for( var i=0;i<tong;i++)
                    {
                        tmp = dong[i].split('<->');
                        tmp_lk[0] = tmp[0].replace(/'/g, "\\'");
                        tmp_lk[1] = tmp[1].replace(/'/g, "\\'");
                        
                        tmp_lk[0] = tmp_lk[0].replace(/"/g, '&quot;');
                        tmp_lk[1] = tmp_lk[1].replace(/"/g, '&quot;');
                        noi_dung += '<tr><td><a href="' + tmp[0] + '" target="_blank">' + tmp[1] + '</a></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" onClick="return chon_lien_ket(\'' + tmp_lk[0] + '\', \'' + tmp_lk[1] + '\')"><?= Core::getPhrase('language_them')?></a></div></td></tr>';
                    }
                }
    if(tong == 0)
    {
        noi_dung += '<tr><td><a><?= Core::getPhrase('language_bai-viet-khong-ton-tai')?></a></td></tr>';
    }
                noi_dung += '</table>';
                document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = noi_dung;
            }
        };
        http.send('val[type]=' + obj + '&val[keyword]='+unescape(document.getElementById('txt_' + obj + '_lien_quan').value));
    }
    return false;
}

function tim_bai_lien_quan_dem_nguoc(e, obj)
{
    if(obj == undefined) obj = '';
    var bat_phim = false;
    if(window.event && window.event.keyCode == 13) // IE
    {
        event.returnValue = false;
        event.cancelBubble = true;
        bat_phim = true;
    }
    else if(e.which && e.keyCode == 13) // Netscape/Firefox/Opera
    {
        e.cancelBubble = true;
        e.stopPropagation();
        bat_phim = true;
    }
    clearTimeout(t_tim_lien_quan_dem_nguoc[obj]);
    if(bat_phim)
    {
        tim_bai_lien_quan(obj);
        return false;
    }
    t_tim_lien_quan_dem_nguoc[obj] = setTimeout('tim_bai_lien_quan("' + obj + '")', 1000);
}
function cap_nhat_slide()
{
    // cập nhật select box
    var val = '', stt = 0;
    $('#sel_div_slide').html('<option value="0" selected="selected"><?= Core::getPhrase('language_cuoi-cung')?></option><option value="-1"><?= Core::getPhrase('language_tren-dau')?></option>');
    //$('#sel_div_slide').append(new Option(val, 'Slide ' + val, true, true));
    //alert($('#div_slide').html());
    $('#div_slide .div_con').each(function(index, element) {
        stt = $(this).attr('id').replace('div_slide_', '');
        val = $('#ten_' + stt).val();
        $('#sel_div_slide').append('<option value="' + stt + '">' + val + '</option>');
        //$('#sel_div_slide').append(new Option(val, 'Slide ' + val, true, true));
    });
}
$('#sel_div_slide').focus(function(e) {
    cap_nhat_slide();
});
function xoa_slide(stt)
{
    if(!confirm('<?= Core::getPhrase('language_ban-co-chac-muon-xoa-slide-nay')?>')) return false;
    $('#div_slide_' + stt).remove();
    cap_nhat();
}
<?php
if(is_array($gt_ma_so))
{
    for($i=0;$i<count($gt_ma_so);$i++)
    {
        $tmp_ma_so = str_replace("'", "\'", $gt_ma_so[$i]);
        $tmp_ten = str_replace("'", "\'", $gt_ten[$i]);
        $tmp_duong_dan = str_replace("'", "\'", $gt_duong_dan[$i]);
        $tmp_trang_thai = str_replace("'", "\'", $gt_trang_thai[$i]);
        $tmp_stt = str_replace("'", "\'", $gt_stt[$i]);
        $tmp_loai = str_replace("'", "\'", $gt_loai[$i]);
    ?>
    them_slide('<?= $tmp_ma_so?>', '<?= $tmp_ten?>', '<?= $tmp_duong_dan?>', '<?= $tmp_trang_thai?>', '<?= $tmp_stt?>', '<?= $tmp_loai?>');
    <?php
    }
}
else
{
?>
    them_slide();
<?php
}
?>
    lan_dau = false;

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
