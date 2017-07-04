<? if ($this->_aVars['aData']['status'] == 'success') {?>
<section class="container product unselectable page-list-vendor">
    <section class="sanpham fthtab"> 
        <section class="statistic-bar" ng-class="showlist">
            <div class="hb">
              <div class="tab hasab atv">
                <div class="tt or">
                  <?= Core::getPhrase('language_danh-sach')?>
                </div>
                <div class="ab">
                  <?= $this->_aVars['aData']['data']['total']?>
                </div>
                <div class="clear"></div>
                <div class="hv"></div>
              </div>
              <div class="clear"></div>
            </div>
        </section>
        <section class="overview-statistic-bar statistic-bar content-box">
          <form method="GET" id="frm" action="#">
            <div class="row20">
                <div class="col2 row30" style="margin-top: 5px">
                    <div class="button-blue" onclick="window.location='/filter/add/';">
                        Thêm Trích lọc
                    </div>
                </div>
                <div class="col5"></div>
                <!--<div class="col1">
                    <div class="sub-black-title mgtop10"><?= Core::getPhrase('language_tu-khoa')?> : </div>
                </div>
                <div class="col3 padright10">
                    <input type="text" name="q" value="<?= $tu_khoa?>" id="tu_khoa" class="default-input product-srch-input tt tt1" placeholder="Nội dung tìm kiếm" style="margin-top: 5px">
                </div>
                <div class="col1 product-srch-bt">
                    <button class="btn gr button-blue" style="margin-top: 5px; height: 30px ! important; line-height: 30px ! important; padding: 0px 10px;border: medium none;">Tìm</button>
                </div>-->
            </div>
            <div class="clear"></div>
          </form>
        </section>
        <section class="product-list-box content-box panel-shadow mgbt20" ng-class="showlist">
            <div class="title_list">
                <div class="col1 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort']?>" data-sort='id_d'>
                    <div class="tt or">
                    Mã số
                    </div>
                    <div class="<?php if(!isset($sort) ||$sort == 'id_a'):?>ic ic1 <?php elseif ($sort == 'id_d'):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="col6 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort'].'&sort=1'?>" data-sort='name_d'>
                    <div class="tt or">
                    <?= Core::getPhrase('language_ten')?>
                    </div>
                    <div class="<?php if(isset($sort) && $sort == 'name_a'):?>ic ic1 <?php elseif (isset($sort) && $sort == 'name_d'):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="col5 cl">
                    <div class="tt or">
                    Thao tác
                    </div>
                    <div class="bg"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="clear"></div>
            </div>
            <? if (isset($this->_aVars['aData']['data']) && !empty($this->_aVars['aData']['data'])):?>
            <? foreach ($this->_aVars['aData']['data']['list'] as $aVals):?>
            <div class="r" id="tr_object_<?=$aVals['id']?>">
                <div class="col1 cl atv">
                    <div class="tt or">
                    #<?= $aVals['id']?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="col6 cl">
                    <div class="tt or">
                    <?= $aVals['name']?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="col5 cl list-icon">
                    <?php if($aVals['status'] == 1):?>
                    <div class="stic bg ic1 js-activity" id="js-status-object-<?= $aVals['id']?>" data-id="<?= $aVals['id']?>" data-status="1">
                        <div class="sp">
                            <div class="p">
                            Chọn để hủy kích hoạt
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="stic bg ic4 js-activity" id="js-status-object-<?= $aVals['id']?>" data-id="<?= $aVals['id']?>" data-status="0">
                        <div class="sp">
                            <div class="p">
                            Chọn để kích hoạt
                            </div>
                        </div>
                    </div>
                    <?php endif?>
                    <div class="fleft js-edit mgright10" title="<?= Core::getPhrase('language_sua')?>" data-id="<?= $aVals['id']?>">
                        <a href="javascript:void(0);">
                            <span class="fa icon-medium fa-pencil"></span>
                        </a>
                    </div>
                    <div class="fleft mgright10" title="Danh sách giá trị" data-id="<?= $aVals['id']?>">
                        <a href="/filter/view/<?= $aVals['id']?>">
                            <span class="fa icon-medium fa-list"></span>
                        </a>
                    </div>
                    <div class="fleft mgright10 js-delete" title="<?= Core::getPhrase('language_xoa')?>" data-id="<?= $aVals['id']?>">
                        <a href="javascript:void(0);">
                            <span class="fa icon-medium fa-trash"></span>
                        </a>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <? endforeach?>
            <? else:?>
            <div class="r">
            Không tìm thấy trích lọc nào.
            </div>
            <? endif?>
            <!-- Phân trang -->
            <?= Core::getService('core.tools')->paginate(ceil($this->_aVars['aData']['data']['total']/$this->_aVars['aData']['data']['page_size']), $this->_aVars['aData']['data']['page'], '/filter/?'.'&page=::PAGE::', '/filter/?', '', '')?>
            <!--<div class="panel-box">
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
            for($i=0;$i<count($aVals['id']);$i++)
            {
            ?>
            <tr id="div_menu_<?= $aVals['id']?>" class="div_menu div_menu_nhom_0">
                <td><input type="checkbox" name="ckb_shop_custom" onkeyup="hien_xu_ly_chon()" onclick="hien_xu_ly_chon()" onchange="hien_xu_ly_chon()" value="<?= $aVals['id']?>" /></td>
                <td><a href="/filter/edit/?id=<?= $aVals['id']?>"><?= $aVals['name']?></a></td>
                <td id="div_shop_custom_<?= $aVals['id']?>"><a href="javascript:void(this);" onclick="hien_thi(<?= $aVals['id']?>, <?= $aVals['status']?>);" title="<?php if($aVals['status'] == 1) {?><?= Core::getPhrase('language_duoc-cho-phep-hien-thi')?><?php } else {?><?= Core::getPhrase('language_khong-duoc-cho-phep-hien-thi')?><?php }?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/<?= $aVals['status_text']?>.png" /></a></td>
                <td><a href="/filter/edit/?id=<?= $aVals['id']?>" title="<?= Core::getPhrase('language_sua-bai')?>"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/edit.png" alt="<?= Core::getPhrase('language_sua-bai')?>" /></a></td>
                <td id="div_xoa_shop_custom_<?= $aVals['id']?>">
                    <a href="javascript:void(this);" onclick="xoa_shop_custom(<?= $aVals['id']?>);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a>
                </td>
                <td align="center">
                    <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $aVals['id']?>, 1)">
                        <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up.png" />
                    </a>
                    <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $aVals['id']?>, 2)">
                        <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/up-down-custom.png" />
                    </a>
                    <a href="javascript:" onclick="return tang_giam_vi_tri(0, <?= $aVals['id']?>, 0)">
                        <img src="http://img.<?= Core::getDomainName()?>/styles/acp/img/down.png" />
                    </a>
                </td>
                <td class="hidden val_pos_0"><?= $aVals['position']?></td>
            </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
            <div class="w100 line-border">
                <md-button class="button-blue" onclick="window.location='/filter/add/';"><?= Core::getPhrase('language_them')?></md-button>
            </div>
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
            </div>-->

<script type="text/javascript" >
function xoa_shop_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
    http.open('get', '/includes/ajax.php?=&core[call]=core.deleteObject&val[type]=filter&val[id]='+stt);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
            } else {
                //remove display
                alert('Xóa thành công');
                document.getElementById('tr_object_' + stt).innerHTML = '';
                document.getElementById('tr_object_' + stt).style.display = "none";
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.deleteObject&val[type]=filter&val[status]=2&val[list]='+danh_sach);
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
                    document.getElementById('tr_shop_custom_' + danh_sach_mang[i]).innerHTML = '';
                    document.getElementById('tr_shop_custom_' + danh_sach_mang[i]).style.display = "none";
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=filter&val[list]='+danh_sach+'&val[math]='+Math.random());
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

    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=filter&val[status]='+trang_thai+'&val[id]='+stt+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {

            } else {
                //remove old class and add new class
                changeStatus(stt, trang_thai);
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
//hien_xu_ly_chon();
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


function initSort()
{
    var sort_path = '<?= $this->_aVars['aData']['data']['link_sort']?>';
    $('.js-sort-by').each(function(){
       $(this).unbind('click').click(function(){
           var sort = $(this).data('sort');
           if (typeof(sort) == 'string' && typeof(sort_path) != 'undefined') {
               var tag = $(this).find('div.js-icon-sort');
               var hasSort = tag.hasClass('ic3');
               if (hasSort) {
                   sort = sort - 1;
               }
               sort_link = sort_path +'&sort=' + sort;
               window.location = sort_link;
           }
           return false;
       });
    });
}

function changeStatus(id , status)
{
    var obj = $('#js-status-object-' + id);

    if (status == 1) {
        obj.removeClass('ic4');
        obj.addClass('ic1');

        obj.attr('data-status', 1);

        obj.find('.sp').find('.p').html = 'Chọn để hủy kích hoạt';
    }
    else {
        obj.removeClass('ic1');
        obj.addClass('ic4');

        obj.attr('data-status', 0);

        obj.find('.sp').find('.p').html = 'Chọn để kích hoạt';
    }
    obj.bind('click', function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        if (typeof(id) == 'string') {
            id = parseInt(id);
        }
        if (typeof(status) == 'string') {
            status = parseInt(status);
        }
        if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
            hien_thi(id, status);
        }
    });
}

$(function(){
    initSort();

    $('.js-activity').each(function(){
       $(this).unbind('click').click(function(){
            $(this).unbind('click');
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            if (typeof(id) == 'string') {
                id = parseInt(id);
            }
            if (typeof(status) == 'string') {
                status = parseInt(status);
            }
            if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
                hien_thi(id, status);
            }
       });
    });

    $('.js-edit').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                links = '/filter/add/?id='+id;
                window.location = links;
            }

       });
    });

    $('.js-delete').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                xoa_shop_custom(id);
            }
       });
    });

    $('#js-check-all').unbind('click').click(function(){
        var check = $(this).attr('aria-checked');
        if (typeof(check) == 'string') {
            if (check == 'true') {
                //uncheck all
            }
            else {
                //check all
            }
        }
    });
});

</script>
        </section>
    </section>
</section>
<?php
}
else
{
?>
<section class="container">
    <div class="panel-box">
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
        <div class="row30 padtop10">
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='/filter/add/';">
                    <?= Core::getPhrase('language_them')?>
                </div>
            </div>
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='/filter/';">
                    <?= Core::getPhrase('language_quan-ly')?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
}
?>
