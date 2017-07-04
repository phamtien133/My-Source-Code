<?php
foreach($this->_aVars['output'] as $key)
{
    $key = $this->_aVars['return'][$key];
}
?>
<section class="container">
<div class="panel-box">
    <section class="grid-block"><div class="grid-box grid-h">
        <div class="module mod-box">
            <div class="content-box panel-shadow">
                <? if ($this->_aVars['aData']['id']): ?>
                    <h3 class="box-title">Chỉnh sửa dự án mã <?= $this->_aVars['aData']['id']; ?></h3>
                <? else: ?>
                    <h3 class="box-title">Tạo mới dự án</h3>
                <? endif; ?>    
                
                <div class="box-inner">
                <?php if($status == 2):?>
                    <div class="row30 padtb10 ">
                        <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                    </div>
                    <div class="row30 padtb10">
                        <?php foreach($errors as $error):?>
                            <div class="row30">
                                <?= $error?>
                            </div>
                        <?php endforeach?>
                    </div>
                <?php elseif($status == 3):?>
                    <div class="row30 padtop10">
                        <?= Core::getPhrase('language_da-tao-thiet-lap-thanh-cong')?>
                    </div>
                    <div class="row30 padtop10">
                        <div class="col3 padright10">
                            <div class="button-blue" onclick="window.location='/project/add/';">
                                <?= Core::getPhrase('language_them')?>
                            </div>
                        </div>
                        <div class="col3 padright10">
                            <div class="button-blue" onclick="window.location='/project/';">
                                <?= Core::getPhrase('language_quan-ly')?>
                            </div>
                        </div>      
                    </div>
                    <script type="text/javascript">
                    //redirect page
                    redirectPage();
                    function redirectPage()
                    {
                        window.location = '/project/';
                    }
                    </script>
                <?php else :?>
                <form action="/project/add" method="post" name="frm_dang_ky" id="frm_add" class="box style width100" onsubmit="return sbm_frm()">
                    <? if ($this->_aVars['bIsEdit']): ?>
                        <div>
                            <input type="hidden" name="val[id]" value="<?=$this->_aVars['aData']['id']?>">
                        </div>
                    <? endif; ?>
                     <?php if(!empty($errors)):?>
                        <div class="row30 padtb10 ">
                            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>            
                        </div>
                        <div class="row30 padtb10">
                            <?php foreach($errors as $error):?>
                                <div class="row30">
                                    <?= $error?>
                                </div>
                            <?php endforeach?>
                        </div>
                    <?php endif?>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col3"><label for="ten" class="sub-black-title" style="width: 50px"><?= Core::getPhrase('language_ten')?>:</label><span id=""></span></div> <!--id="div_ten_kiem_tra_ma_ten"-->
                        <div class="col9"><input type="text" id="ten" name="val[name]" value="<?=$this->_aVars['aData']['name']?>" class="default-input"/></div>
                    </div>
                    <div class="row30 line-bottom padbot10 mgbt10">
                        <div class="col3"><label for="ma_ten" class="sub-black-title"><?= Core::getPhrase('language_ma-ten')?>:<a href="javascript:" onclick="return btn_cap_nhat_ma_ten()" style="margin-left: 10px; font-size:12px; font-family: HelveticaNeue; color: #999; font-weght: 200"><!--(<?= Core::getPhrase('language_cap-nhat-tu-dong')?>)--></a></label></div>
                        <div class="col9"><input type="text" id="ma_ten" name="val[name_code]" value="<?=$this->_aVars['aData']['name_code']?>" onblur="kiem_tra_ma_ten()" class="default-input"/></div>
                    </div>
                    <div class="row30 line-bottom  padbot10 mgbt10">
                        <div class="col3">
                            <label for="trang_thai" class="sub-black-title"><?= Core::getPhrase('language_trang-thai')?>:</label>
                        </div>
                        <div class="col9">
                            <select name="val[status]" id="trang_thai" style="height: 30px; width:100%">
                               <option value="1"<?php if($this->_aVars['aData']['status'] ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
                               <option value="0"<?php if($this->_aVars['aData']['status'] ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
                             </select>
                        </div>
                    </div>
                    <div class="row30"> 
                        <div class="col1">
                            <input for="frm_dang_ky" class="button-blue" type="submit" name="val[submit]" value="<?= Core::getPhrase('language_hoan-tat')?>" id="js-btn-submit"/>
                        </div>
                        <div class="col10"></div>

                        <div class="col1">
                            <div class="button-blue" onclick="window.location = '/project/index/'">
                                <?= Core::getPhrase('language_quan-ly')?>
                            </div>
                        </div>
                    </div>
                    <div class="txt-err">
                        <p class="error-color"><?php echo $this->_aVars['sMessage'];?></p>
                    </div>
                    
                    <div class="clear"></div>
                </form>

<script>
function changeExtend(obj)
{
    var is_check = obj.checked;
    if (typeof(is_check) != 'undefined') {
        if(is_check) {
            $('#danh_sach_chi_tiet').css('display', 'table-cell');
        }
        else {
             $('#danh_sach_chi_tiet').css('display', 'none');
        }
    }
    return;
    if($(this).val() == 0)
    {
        $('#danh_sach_chi_tiet').css('display', 'none');
    }
    else if($(this).val() == 1)
    {
        $('#danh_sach_chi_tiet').css('display', 'table-cell');
    }
}

var cache = [];
var tu_vua_tim = [];
var t_tim_lien_quan_dem_nguoc = [];
function tim_bai_lien_quan_dem_nguoc(e, obj)
{
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

function tim_bai_lien_quan(obj) {
    var tu_khoa = document.getElementById('txt_' + obj + '_lien_quan').value, classname = '';
    
    classname = obj;
    if(classname == 'san_lien_ket')
    {
        classname = $('#dialog_san_lien_ket #class_san_lien_ket_lien_quan').val();
    }
    if(tu_vua_tim[obj] == tu_khoa) return false;
    
    
    function xu_ly_tim_bai_lien_quan(obj, tu_khoa, data)
    {
        classname = obj;
        if(classname == 'san_lien_ket')
        {
            classname = $('#dialog_san_lien_ket #class_san_lien_ket_lien_quan').val();
        }
        if(data != undefined) cache['tu_vua_tim|' + obj + '|' + tu_khoa] = data;
        else data = cache['tu_vua_tim|' + obj + '|' + tu_khoa];
        
        tu_vua_tim[obj] = tu_khoa;
        var tmp, tmp_lk = [], noi_dung = '', dong = '', tong = 0;
        
        if(classname != 'vatgia')
        {
            dong = data.split('<-vietspider->');
            tong = dong.length-1;
        }
        
        noi_dung = '<table class="zebra">';
        if(obj == 'user')
        {
            for( var i=0;i<tong;i++)
            {
                tmp = dong[i].split('<->');
                noi_dung += '<tr><td><a>' + tmp[1] + '</a></td><td align="center"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/openid/' + tmp[2] + '.png" width="20" /></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-chon')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" onClick="return chon_thanh_vien(' + tmp[0] + ', \'' + tmp[1] + '\')"><?= Core::getPhrase('language_chon')?></a></div></td></tr>';
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
        else if(classname == 'vatgia')
        {
            data = JSON.parse(data);
            tong = 0;
            if(typeof(data['san_pham']) == 'undefined') noi_dung = '';
            else
            {
                tong = data['san_pham'].length;
            }
            
            for( var i=0;i<tong;i++)
            {
                tmp = data['san_pham'][i];
                noi_dung += '<tr><td><img style="max-width: 40px; max-height: 40px;" src="' + tmp['duong_dan_hinh'] + '" /></td><td><div id="txt_them_' + obj + '_lien_quan"><a href="' + tmp['duong_dan'] + '" target="_blank">' + tmp['ten'] + '</a><br>' + tmp['thong_tin'] + '</div></td><td><a href="" onClick="return chen_vatgia(\'' + tmp['stt'] + '\')"><?= Core::getPhrase('language_chon')?></a></td></tr>';
            }
        }
        else if(obj == 'bai_lien_ket')
        {
            for( var i=0;i<tong;i++)
            {
                tmp = dong[i].split('<->');
                tmp_lk[1] = tmp[1].replace(/'/g, "\\'");
                tmp_lk[2] = tmp[2].replace(/'/g, "\\'");
                
                tmp_lk[1] = tmp_lk[1].replace(/"/g, '&quot;');
                tmp_lk[2] = tmp_lk[2].replace(/"/g, '&quot;');
                noi_dung += '<tr><td><a href="' + tmp[1] + '" target="_blank">' + tmp[2] + '</a></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" class="simplemodal-close" onClick="return chon_lien_ket(\'' + tmp[0] + '\', \'' + tmp_lk[1] + '\', \'' + tmp_lk[2] + '\')"><?= Core::getPhrase('language_chon')?></a></div></td></tr>';
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
                noi_dung += '<tr><td><a href="' + tmp[0] + '" target="_blank">' + tmp[1] + '</a></td><td><div id="txt_them_' + obj + '_lien_quan_canh_bao" style="display:none"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/status_yes.png" title="<?= Core::getPhrase('language_da-them')?>" /></div><div id="txt_them_' + obj + '_lien_quan"><a href="javascript:" onClick="return chen_lien_ket(\'' + tmp_lk[0] + '\', \'' + tmp_lk[1] + '\')"><?= Core::getPhrase('language_them')?></a></div></td></tr>';
            }
        }
        if(tong == 0)
        {
            noi_dung += '<tr><td><a><?= Core::getPhrase('language_bai-viet-khong-ton-tai')?></a></td></tr>';
        }
        noi_dung += '</table>';
        document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = noi_dung;
    }

    if(cache['tu_vua_tim|' + obj + '|' + tu_khoa])
    {
        xu_ly_tim_bai_lien_quan(obj, tu_khoa);
    }
    else
    {
        document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif"> <?= Core::getPhrase('language_dang-sinh-khoa-vui-long-doi')?>';
        http.open('POST', '/includes/ajax.php?=&core[call]=core.getRelated', true);
        http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
        http.onreadystatechange = function () {
            if(http.readyState == 4){
                cache['tu_vua_tim|' + obj + '|' + tu_khoa] = http.responseText;
                xu_ly_tim_bai_lien_quan(obj, tu_khoa);
            }
        };
        http.send('val[type]=' + classname + '&val[keyword]='+unescape(document.getElementById('txt_' + obj + '_lien_quan').value));
    }
}
function chon_thanh_vien(stt, ten)
{
    // lấy tên div đc chọn
    
    var pstt = $('#dialog_search_box #stt_bai_lien_ket_lien_quan').val();
    var obj = $('#div_slide_' + pstt);
    var ten_cut = ten;
    if(ten.length > 13)
    {
        ten_cut = ten.substr(0, 10) + '...';
    }
    obj.find('#ten_' + pstt).val(ten);
    obj.find('#ma_ten_' + pstt).val(stt);
    
    $.modal.close();
    $.fancybox.close();
    return false;
}
function xoa_bai_lien_quan(obj) {
    tu_vua_tim[obj] = '';
    document.getElementById('txt_' + obj + '_lien_quan').value = ''
    document.getElementById('div_tim_' + obj + '_lien_quan').innerHTML = '';
    return false;
}

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
function chon_trich_loc(stt)
{
    $('#dialog_search_box').modal(
        {
            onShow: function (dialog) {
                $('#dialog_search_box #stt_bai_lien_ket_lien_quan').val(stt);
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
}
function cap_nhat() {
$('.div_menu_nhom_0').datasort({ 
    datatype    : 'number',
    sortElement : '.val_pos_0',
    reverse     : false
});
}
var hinh_slide_hien_tai = 0;

    function upHinhMoRong(arr) {
        if(arr == undefined) arr = {};
        if(arr.obj == undefined) arr.obj = '';
        if(arr.type == undefined) arr.type = '1';
        if(arr.width == undefined) arr.width = 0;
        else arr.width *= 1;
        if(arr.height == undefined) arr.height = 0;
        else arr.height *= 1;

        hinh_slide_hien_tai = arr.id;
    
        function receiveMessage(e) {
            if (e.origin !== 'http://img.' + global['domain'] + ':8080') return;
            window.removeEventListener("message", receiveMessage, false);
            settings = JSON.parse(e.data);
            $('#' + settings['id']).val(settings['value']);
            $('#' + settings['id']).trigger(settings['trigger']);
            
            $.modal.close();
            $.fancybox.close();
        }
        window.addEventListener('message', receiveMessage);
        
    moPopup(document.location.protocol + '//img.' + global['domain'] + ':8080/dialog.php?type=1&field_id=' + arr.obj +'&height=' + arr.height + '&width=' + arr.width + '&sid=<?= session_id()?>',
        function(){
            $('.duong_dan_hinh_mo_rong').change(function(e) {
                btn_cap_nhat_ma_ten(id);
            });
        },
        600,
        600
    );
}
$(document).ready(function(){
    $('#js-btn-submit').unbind('click').click(function(){
        if ($('#street').val() == '' || $('#js-select-ward').val() < 1) {
            alert('Vui long nhập đầy đủ thông tin địa chỉ');
            return;
        }
        $(this).unbind('click');
        $('#frm_add').submit();
    });
    
    $('#loai').change(function(e) {
        if($(this).val().indexOf('co-dinh') == -1) return ;
        
        if($(this).val().indexOf('khong-co-dinh') == -1)
        {
            $('#div_slide').fadeIn('fast');
            
            if($(this).val().indexOf('-hinh-anh') == -1)
                $('#div_slide').find('button').fadeOut('fast');
            else
                $('#div_slide').find('button').fadeIn('fast');
        }
        else
        {
            $('#div_slide').fadeOut('fast');
        }
            
    });
    $('#loai').change();
    <? if($cap_nhat_vi_tri):?>
    cap_nhat();
    <? endif?>
    
    initLoadArea();
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
    else if(loai == 0)
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

var slide = 0, lan_dau = true;
function them_slide(arr)
{
    /* default data */
    var tmps = {
        ten: [''],
        ma_ten: [''],
        trang_thai: [1],
        stt: [0],
        cha_trich_loc_gt: [[]],
    };
    
    if(arr == undefined) arr = tmps;
    for(i in tmps)
        if(arr[i] == undefined) arr[i] = tmps[i];
    tmps = {};
    
    if(arr == undefined) arr = tmps;
    
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
        slide++;
        slide_pos = slide;
    }
    // end
    
    slide--;
    
    for(i in arr['ten'])
    {
        slide++;
        if(arr['ten'].length > 1)
        {
            slide_pos = slide;
        }
        
        gt_cha_trich_loc = '';
        for(j in arr['cha_trich_loc_gt'][i])
        {
            gt_cha_trich_loc += arr['cha_trich_loc_gt'][i][j]['ten'] + '-' + arr['cha_trich_loc_gt'][i][j]['stt'] + '|';
        }
        
        content += '<div class="div_menu div_con div_menu_nhom_0" id="div_slide_' + slide + '"><div style="float:right"> \
                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 1)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up.png" />\
                </a>\
                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 2)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up-down-custom.png" />\
                </a>\
                <a href="javascript:" onclick="return tang_giam_vi_tri(0, ' + slide + ', 0)">\
                    <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/down.png" />\
                </a>\
                <a href="javascript:void(this)" onclick="xoa_slide(' + slide + ')"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a>\
                </div>\
                <p><div style="float:left;width:180px;"><label for="ten_' + slide + '"><?= Core::getPhrase('language_ten')?></label></div><input type="text" readonly onclick="chon_trich_loc(' + slide + ')" id="ten_' + slide + '" value="' + arr['ten'][i] + '" class="inputbox" style="width:50%;" /><button class="btn gr" type="button" onclick="chon_trich_loc(' + slide + ')"><span class="round"><span><?= Core::getPhrase('language_chon')?></span></span></button><input type="hidden" name="val_user_id[' + slide + ']" id="ma_ten_' + slide + '" value="' + arr['ma_ten'][i] + '" class="inputbox ma_ten" style="width:50%;" /></p>\
            <div class="hidden val_pos_0">' + slide_pos + '</div>\
            <input type="hidden" name="val_id[' + slide + ']" value="' + arr['stt'][i] + '" />\
            </div>';
        //thẻ quyền tạm bỏ <p><div style="float:left;width:180px;"><label for="quyen_' + slide + '"><?= Core::getPhrase('language_quyen')?></label></div><input type="text" name="val_permission[' + slide + ']" id="quyen_' + slide + '" value="' + arr['ma_ten'][i] + '" class="inputbox quyen" style="width:50%;" /></p>\
    }
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
    if(arr['ten'].length > 1) document.getElementById('div_slide').innerHTML = content;
    else $('#div_slide').append(content);
    
    cap_nhat();
    $('#loai').change();
    if(!lan_dau) $('html, body').animate({scrollTop: $('#div_slide_' + slide).offset().top}, 800);
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
    if(!confirm('<?=Core::getPhrase('language_ban-co-chac-muon-xoa-slide-nay')?>')) return false;
    $('#div_slide_' + stt).remove();
    cap_nhat();
}
<?php
if(is_array($data['val_permission']))
{
    ?>
    var tmps = {
        ten: [],
        ma_ten: [],
        trang_thai: [],
        stt: [],
        cha_trich_loc_gt: [],
    };
    
    <?
    for($i=0;$i<count($data['val_permission']);$i++)
    {
        $tmp_ten = str_replace("'", "\'", $data['val_permission'][$i]);
        $tmp_ma_ten = str_replace("'", "\'", $data['val_user_id'][$i]);
        $tmp_trang_thai = str_replace("'", "\'", $data['val_status'][$i]);
        $tmp_stt = str_replace("'", "\'", $data['val_id'][$i]);
        $tmp_cha_trich_loc_gt_chon = json_encode($cha_trich_loc_gt_chon[$i]);
    ?>
    tmps['ten'].push('<?= $tmp_ten?>');
    tmps['ma_ten'].push('<?= $tmp_ma_ten?>');
    tmps['trang_thai'].push('<?= $tmp_trang_thai?>');
    tmps['stt'].push('<?= $tmp_stt?>');
    tmps['cha_trich_loc_gt'].push(<?= $tmp_cha_trich_loc_gt_chon?>);
    <?php
    }
    ?>
    them_slide(tmps);
    <?
}
else
{
?>
    them_slide();
<?php
}
?>
    
    $('.tags').tagsInput({
        width: 'auto',
        delimiter: '|',
        interactive:false,
        onChange: function(elem, elem_tags)
        {
            var n = 0;
            $('.tags', elem_tags).each(function(){
                if(n % 2 === 0)
                {
                    $(this).css('background-color', 'yellow');
                }
                n++;
            });
        }
    });
    lan_dau = false;
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
    if(obj == 'ma_ten' && ma_ten_truoc != noi_dung)
    {
        var obj_ten = document.getElementById("div_ten_kiem_tra_ma_ten");
        if(obj_ten.innerHTML != '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">' )
        {
            obj_ten.innerHTML = '<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif">';
        }
    }
}
var ma_ten_truoc = document.getElementById("ma_ten").value;
function kiem_tra_ma_ten() {
    var noi_dung = document.getElementById("ma_ten").value;
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
        http.send('val[id]=<?= $id?>&val[type]=project&val[name_code]='+unescape(noi_dung));
    }
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
        var tmp = $('#ten_' + id).val();
            
        if($('#loai').val().indexOf('-hinh-anh') != -1)
        {
            var start, end;
            
            start = tmp.lastIndexOf('/');
            end = tmp.indexOf('?', start);
            if(end != -1) tmp = tmp.substr(0, end - start);
            
            end = tmp.indexOf('.', start);
            if(end == -1) end = tmp.length;
            
            tmp = tmp.substr(start, end - start);
            
            if(tmp == '') tmp = Math.floor((Math.random()*100000)+1);
        }
        lay_ma_ten_tu_dong(tmp, 'ma_ten_' + id);
            
        // check các giá trị trước đó
        $('#div_slide .ma_ten').each(function(index, element) {
            if( ($(this).attr('id') == 'ma_ten_' + id) || $(this).val() != $('#ma_ten_' + id).val()) return true;
            $('#ma_ten_' + id).val( $('#ma_ten_' + id).val() + '-' + Math.floor((Math.random()*100000)+1) );
            return false;
        });

    }
    return false;
}
function sbm_frm()
{
    // xử lý cha_trich_loc_gt
    $('.tags').each(function(index, element) {
        var tmps = $(this).val().split('|');
        var content = '', tmp = '';
        for(i in tmps)
        {
            tmp = tmps[i];
            if(tmp == '') continue ;
            pos = tmp.lastIndexOf('-') + 1;
            tmp = tmp.substr(pos, tmp.length - pos);
            tmp *= 1;
            if(tmp < 1) continue ;
            content += tmp + '|';
        }
        if(content != '')
        {
            content = content.substr(0, content.length - 1);
            $(this).val(content);
        }
        else
        {
            $(this).remove();
        }
    });
    return true;
}

function opHinhAnh() {
    upHinhMoRong({obj: 'image_path'});
}

function initLoadArea()
{
    $('#js-select-city').change(function(){
        var area_id = $(this).val();
        if (area_id > 0) {
            //call ajax get data
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=core.loadDistrict' + '&city='+area_id;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'json',
                error: function(jqXHR, status, errorThrown){
                    alert('Lỗi hệ thống');
                    //reset input district and ward
                    var html = '<option value="-1">Chọn Quận/Huyện</option>';
                    $('#js-select-district').html(html);
                    html = '<option value="-1">Chọn Phường/Xã</option>';
                    $('#js-select-ward').html(html);
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        var html = '';
                        html += '<option value="-1">Chọn Quận/Huyện</option>';
                        for (var i  in result.data) {
                            html += '<option value="'+ result.data[i].id +'">'+ result.data[i].name +'</option>';
                        }
                        $('#js-select-district').html(html);
                        html = '<option value="-1">Chọn Phường/Xã</option>';
                        $('#js-select-ward').html(html);
                    }
                    else {
                        var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(messg);
                        //reset input district and ward
                        var html = '<option value="-1">Chọn Quận/Huyện</option>';
                        $('#js-select-district').html(html);
                        html = '<option value="-1">Chọn Phường/Xã</option>';
                        $('#js-select-ward').html(html);
                    }
                }
            });
        }
        else {
            //reset input district and ward
            var html = '<option value="-1">Chọn Quận/Huyện</option>';
            $('#js-select-district').html(html);
            html = '<option value="-1">Chọn Phường/Xã</option>';
            $('#js-select-ward').html(html);
        }
    });
    
    $('#js-select-district').change(function(){
        var area_id = $(this).val();
        if (area_id > 0) {
            //call ajax get data
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=core.loadWard' + '&district='+area_id;
            $.ajax({
                crossDomain:true,
                xhrFields: {
                    withCredentials: true
                },
                url: getParam('sJsAjax'),
                type: "POST",
                data: sParams,
                timeout: 15000,
                cache:false,
                dataType: 'json',
                error: function(jqXHR, status, errorThrown){
                    alert('Lỗi hệ thống');
                    //reset input  ward
                    var html = '<option value="-1">Chọn Phường/Xã</option>';
                    $('#js-select-ward').html(html);
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        var html = '';
                        html += '<option value="-1">Chọn Phường/Xã</option>';
                        for (var i  in result.data) {
                            html += '<option value="'+ result.data[i].id +'">'+ result.data[i].name +'</option>';
                        }
                        $('#js-select-ward').html(html);
                    }
                    else {
                        var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(messg);
                        //reset input  ward
                        var html = '<option value="-1">Chọn Phường/Xã</option>';
                        $('#js-select-ward').html(html);
                    }
                }
            });
        }
        else {
            //reset input  ward
            var html = '<option value="-1">Chọn Phường/Xã</option>';
            $('#js-select-ward').html(html);
        }
    });
}
</script>
        <?php endif?>   
        </div>    
    </div>
    </div>
</div>
</section>
</div>
</section>
