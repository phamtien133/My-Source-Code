<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<?php
if($status==2)
{
?>
<section class="container">
<div class="panel-box">
<form id="jForm" action="" method="post" onsubmit="return submit_frm_dang_cau_hoi()">
    <?php if(!empty($errors)):?>
    <br />
    <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
    :<br />
    <?php foreach($errors as $error):?>
    <?= $error?>
    <br />
    <?php endforeach?>
    <?php endif?>
    <input type="hidden" name="ma_so_bai_viet" value="<?= rand(1000, 9999)?>" />
    <table class="zebra">
        <tr>
            <td><label for="area_parent">Thuộc khu vực:</label></td>
            <td>
                <select name="parent_id" id="parent_id" class="inputbox">
                    <option value="-1">
                    <?= Core::getPhrase('language_menu-goc')?>
                    </option>
                    <?php for($i=0;$i<count($danh_sach_menu);$i++):?>
                    <option value="<?= $danh_sach_menu[$i][0]?>"<?php if($cha_stt == $danh_sach_menu[$i][0]) {?> selected="selected"<?php }?>>
                    <?= $danh_sach_menu[$i][1]?>
                    </option>
                    <?php endfor?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="ten"><?= Core::getPhrase('language_ten')?>:</label><span id="div_ten_kiem_tra_ma_ten"></span></td>
            <td>
                <input type="text" name="name" id="name" value="<?= $ten?>" class="inputbox" onblur="dem_so_ky_tu(this.value.length, 0, this.id);" />
            </td>
        </tr>
        
        <tr>
            <td><label for="name_code"><?= Core::getPhrase('language_ma-ten')?>:</label>(<a href="javascript:" onclick="return btn_cap_nhat_ma_ten()"><?= Core::getPhrase('language_cap-nhat-tu-dong')?></a>)</td>
            <td><input type="text" class="inputbox" id="name_code" name="name_code" value="<?= $data['name_code']?>" onblur="kiem_tra_ma_ten();"/></td>
        </tr>
        <tr>
            <td><label for="status"><?= Core::getPhrase('language_trang-thai')?>:</label></td>
            <td><select name="status" class="inputbox" id="status">
               <option value="1"<?php if($data['status'] ==1):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_kich-hoat')?></option>
               <option value="0"<?php if($data['status'] ==0):?> selected="selected"<?php endif?>><?= Core::getPhrase('language_chua-kich-hoat')?></option>
         </select></td>
         </tr>
    </table>
    <!--<div>
        <div style="float:left;">
            <?= Core::getPhrase('language_ten')?>
            :</div>
        <br style="clear:both" />
        <input type="text" name="name" id="name" value="<?= $ten?>" class="inputbox" style="width:100%" onblur="dem_so_ky_tu(this.value.length, 0, this.id);" />
    </div>-->
    <!--<p><span style="padding-right:20px;">
        <?= Core::getPhrase('language_thuoc-menu')?>
        </span>
        <select name="parent_id" id="parent_id" class="inputbox">
            <option value="-1">
            <?= Core::getPhrase('language_menu-goc')?>
            </option>
            <?php for($i=0;$i<count($danh_sach_menu);$i++):?>
            <option value="<?= $danh_sach_menu[$i][0]?>"<?php if($cha_stt == $danh_sach_menu[$i][0]) {?> selected="selected"<?php }?>>
            <?= $danh_sach_menu[$i][1]?>
            </option>
            <?php endfor?>
        </select>
    </p>
    <div>
        <div style="float:left;">
            <?= Core::getPhrase('language_duong-dan')?>
            :</div>
        (<a href="javascript:" onclick="return btn_cap_nhat_duong_dan()">
        <?= Core::getPhrase('language_cap-nhat-duong-dan')?>
        </a>) <br style="clear:both" />
        <input type="text" name="path" id="path" value="<?= $duong_dan?>" class="inputbox" style="width:100%" />
    </div>
    <br clear="all" />
    <br />-->
    <script>

var lan_dau_nap_du_lieu = true;

    function upHinhMoRong(arr) {
        if(arr == undefined) arr = {};
        if(arr.obj == undefined) arr.obj = '';
        if(arr.type == undefined) arr.type = '1';
        if(arr.width == undefined) arr.width = 0;
        else arr.width *= 1;
        if(arr.height == undefined) arr.height = 0;
        else arr.height *= 1;
        
        function receiveMessage(e) {
            if (e.origin !== 'http://img.' + global['domain'] + ':8080') return;
            window.removeEventListener("message", receiveMessage, false);
            settings = JSON.parse(e.data);
            
            $('#' + settings['id']).val(settings['value']);
            chinh_sua[settings['id']] = true;
            
            $.modal.close();
            $.fancybox.close();
        }
        window.addEventListener('message', receiveMessage);
        
        moPopup(document.location.protocol + '//img.' + global['domain'] + ':8080/dialog.php?type=' + arr.type +'+&field_id=' + arr.obj +'&height=' + arr.height + '&width=' + arr.width + '&sid=' + session_id,
            function(){},
            {width: '860px', height:'600px'}
        );
    }
function opHinhAnh() {
    upHinhMoRong({obj: 'image_path'});
}

function cap_nhat_hinh(val, obj)
{
    obj.close();
    //val = val.replace(
    var vi_tri = val.indexOf('href="');
    vi_tri += 6;
    var vi_tri_sau = val.indexOf('"', vi_tri);
    val = val.substring(vi_tri, vi_tri_sau);
    $('#image_path').val(val);
    chinh_sua['image_path'] = true;
}
var chinh_sua = [];
    $(function() {
        $("#tabs").tabs({
            show: function(event, ui) {
                if(!lan_dau_nap_du_lieu) $('html, body').animate({scrollTop: $('#tabs').offset().top}, 800);
            },
            fx: { height: 'toggle', opacity: 'toggle', duration: 200 }
        });
    });
<?php foreach($tonTaiMang as $key => $val):?>
    chinh_sua['<?= $key?>'] = true;
<?php endforeach // cai dat chinh sua mac dinh?>
$(document).ready(function(e) {
    $('#jForm input.inputbox, #jForm select.inputbox, #jForm textarea.inputbox').change(function(){
        chinh_sua[$(this).attr('id')]=true;
    });
});

lan_dau_nap_du_lieu = false;
var tu_vua_tim = [];
tu_vua_tim['bai_lien_ket'] = '';
function link_search_box(id) {
    $('#dialog_search_box').modal(
        {
            onShow: function (dialog) {
                if(tu_vua_tim['bai_lien_ket'] != '')
                {
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').val(tu_vua_tim['de_tai_bai_viet']);
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').focus();
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').select();
                    tu_vua_tim['de_tai_bai_viet'] = '';
                    tim_bai_lien_quan('de_tai_bai_viet');
                }
                else
                {
                    $('#dialog_search_box #txt_de_tai_bai_viet_lien_quan').val($('#name').val());
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
    
    $('#link_id').val(mang['ma_so']);
    $('#path').val(mang['duong_dan']);
    
    if(mang['loai'] == 'article') mang['loai'] = 1;
    else mang['loai'] = 0;
    
    $('#link_type').val(mang['loai']);
    chinh_sua['lien_ket_key'] = true;
    $.modal.close();
    return false;
}
function btn_cap_nhat_duong_dan()
{
    link_search_box(0);
    return false;
}
function lay_duong_dan_tu_dong(noi_dung)
{
    noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
    noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
    
    document.getElementById('path').value = '/' + noi_dung + '/';
    chinh_sua['path'] = true;
}
function dong_trang(e) {
    var xac_thuc = false;
    for(var val in chinh_sua)
    {
        xac_thuc = true;
        break;
    }
    if(xac_thuc)
    {
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
function submit_frm_dang_cau_hoi()
{
    var error = 0;
    var obj = document.getElementById("name");
    if(obj != undefined)
    {
        if(chinh_sua['name'] && obj.value.length<2)
        {
            alert("<?= Core::getPhrase('language_vui-long-nhap-tieu-de')?>");
            dobj.focus();
            error = 1;
        }
    }
    obj = document.getElementById("path");
    if(obj != undefined)
    {
        if(chinh_sua['path'] && obj.value.length>225)
        {
            alert("<?= sprintf(Core::getPhrase('language_duong-dan-phai-nho-hon-x-ky-tu'), 225)?>");
            obj.focus();
            error = 1;
        }
    }
    obj = document.getElementById("parent_id");
    if(obj != undefined)
    {
        if(chinh_sua['parent_id'] && obj.value==0)
        {
            alert("<?= Core::getPhrase('language_vui-long-nhap-menu')?>");
            obj.focus();
            error = 1;
        }
    }
    var content = ',', obj_id = '';
    $('#jForm input.inputbox, #jForm select.inputbox, #jForm textarea.inputbox').each(function(e, i){
        if(chinh_sua[$(this).attr('id')] != true && chinh_sua[$(this).attr('name')] != true)
        {
            $(this).remove();
        }
        else
        {
            if(chinh_sua[$(this).attr('id')] == true) obj_id = $(this).attr('id');
            else obj_id = $(this).attr('name');
            
            if(content.indexOf(',' + obj_id + ',') == -1)
                content += obj_id + ',';
        }
    });
    obj_id = 'lien_ket_key';
    if(chinh_sua[obj_id])
    {
        content += obj_id + ',';
    }
    content = content.substr(0, content.length-1);
    $('#mang').val(content);
    if(error == 0)
    {
        window.onbeforeunload = null;
        return true;
    }
    else return false;
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
var t_tim_lien_quan_dem_nguoc = [];
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

$('#name').focus();

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
        chinh_sua['name_code']=true;
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
        http.send('val[id]=<?= $id?>&val[type]=vendor&val[name_code]='+unescape(noi_dung));
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
    
    return false;
}
</script> 
    <br clear="all" />
    <div id="dialog_search_box">
        <div style="background:#FFF;border:1px solid #CCC;padding-left:5px;">
            <input type="hidden" id="stt_de_tai_bai_viet_lien_quan" value="0" />
            <input type="text" id="txt_de_tai_bai_viet_lien_quan" value="" style="border:0;width:495px;height:30px" onkeydown="return tim_bai_lien_quan_dem_nguoc(event, 'de_tai_bai_viet')" onblur="tim_bai_lien_quan('de_tai_bai_viet')">
            <a href="javascript:" onclick="return tim_bai_lien_quan('de_tai_bai_viet')" title="<?= Core::getPhrase('language_tim-bai-lien-quan')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/search.png"></a> <a href="javascript:" onclick="return xoa_bai_lien_quan('de_tai_bai_viet')" title="<?= Core::getPhrase('language_xoa')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/delete.png"></a> </div>
        <div id="div_tim_de_tai_bai_viet_lien_quan"> </div>
    </div>
    <div class="buttonarea">
        <div class="w100 line-border">
            <md-button class="button-blue" type="submit" name="submit" value="1"><?= Core::getPhrase('language_hoan-thanh')?></md-button>
        </div>
    </div>
    <input type="hidden" name="mang" id="mang" value="" />
</form>
</div>
</section>
<?php
}
elseif($status==3)
{
?>
<section class="container">
<div class="panel-box">
<p>
    <?= Core::getPhrase('language_da-hieu-chinh-thanh-cong')?>
</p>
<?php if(!empty($errors)):?>
<br />
<?= Core::getPhrase('language_da-co-loi-xay-ra')?>
:<br />
<?php foreach($errors as $error):?>
<?= $error?>
<br />
<?php endforeach?>
<?php endif?>
<div class="buttonarea">
    <div class="w100 line-border" style="float: left;">
        <md-button class="button-blue"  onclick="window.location='menu/detail/?mid=<?= $mid?>&sub=<?= $cha_stt?>';">
            <?= Core::getPhrase('language_tao-menu')?>
        </md-button>
    </div>
    <div class="w150 line-border" style="float: left;">
        <md-button class="button-blue" onclick="window.location='menu/list/id_2<?= $mid?>';">
            <?= Core::getPhrase('language_quan-ly-menu')?>
        </md-button>
    </div>
    <div class="clear" ></div>
</div>
</div>
</section>
<?php
}
else
{
?>
<section class="container">
<div class="panel-box">
<?php if(!empty($errors)):?>
<br />
<?= Core::getPhrase('language_da-co-loi-xay-ra')?>
:<br />
<?php foreach($errors as $error):?>
<?= $error?>
<br />
<?php endforeach?>
<?php endif?>
<div class="w150 line-border">
    <md-button class="button-blue" onclick="window.location='/menu/';"><?= Core::getPhrase('language_quan-ly-menu')?></md-button>
</div>
</div>
</section>
<?php
}
?>
