<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<!--<section class="container">
<div class="panel-box">-->
<?php if($status==1){?>
<div class="container product unselectable" ng-controller="product-ctrl">
    <section class="sanpham fthtab">
      <section class="statistic-bar" ng-class="showlist">
        <div class="hb">
          <div class="tab hasab atv">
            <div class="tt or">
              <?= Core::getPhrase('language_danh-sach')?>
            </div>
            <div class="ab">
              <?= $tong_cong?>
            </div>
            <div class="clear"></div>
            <div class="hv"></div>
          </div>
          <div class="clear"></div>
        </div>
      </section>
      
    <section class="overview-statistic-bar statistic-bar">
      <form method="GET" id="frm" action="#">
        <div class="lb left mxClrAft js-tab" data-call-back="">
            
        </div>
        <div class="rb right">
            <div class="ctb left mxClrAft product-catg">
                <div class="tt left">
                    Loại khu vục:
                    <select name="lvl" id="js-degree" class="inputbox" style="width:150px;">
                        <option value="0">Chọn</option>
                        <?php foreach ($aDegrees as $sKey => $sDegree):?>
                        <option value="<?= $sKey?>"<?php if($sKey == $iDegree) {?> selected="selected"<?php }?> ><?= $sDegree?></option>
                        <?php endforeach?>
                    </select>
                </div>
                <div class="tt left">
                    Quốc gia:
                    <select name="country" id="js-country" class="inputbox" style="width:150px;">
                        <option value="0">Chọn quốc gia</option>
                        <?php foreach ($aCountries as $sKey => $aCountry):?>
                        <option value="<?= $aCountry['id']?>"<?php if($aCountry['id'] == $iCountryId) {?> selected="selected"<?php }?> ><?= $aCountry['name']?></option>
                        <?php endforeach?>
                    </select>
                </div>
                <div class="tt left">
                    Tỉnh thành:
                    <select name="city" id="js-city" class="inputbox" style="width:150px;">
                        <option value="0">Chọn Tỉnh thành</option>
                        <?php foreach ($aCities as $sKey => $aCity):?>
                        <option value="<?= $aCity['id']?>"<?php if($aCity['id'] == $iCityId) {?> selected="selected"<?php }?> ><?= $aCity['name']?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="ctb left mxClrAft">
                <div class="tt left">
                    <?= Core::getPhrase('language_tu-khoa')?> : 
                </div>
                <input type="text" name="q" value="<?= $tu_khoa?>" id="tu_khoa" class="product-srch-input left tt tt1" placeholder="Nội dung tìm kiếm">
            </div>
            <div class="ctb left mxClrAft product-srch-bt">
                <button class="btn gr button-blue">Tìm</button>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </form>
    </section>
    <section class="product-list-box" ng-class="showlist">
    <div class="title_list">
      <div class="cl1 cl">
        <md-checkbox class="ck" ng-click="checkAllProduct()" ng-model="checkBoxAllProduct" id="js-check-all"></md-checkbox>
        <div class="clear"></div>
      </div>
      <div class="cl2 cl js-sort-by" data-link="<?= $sLinkSort?>" data-sort=1>
        <div class="tt or">
          Mã số
        </div>
        <div class="<?php if(!isset($sap_xep) ||$sap_xep == 0):?>ic ic1 <?php elseif ($sap_xep == 1):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="cl3 cl js-sort-by" style="width: 450px !important;" data-sort=3>
        <div class="tt or">
          <?= Core::getPhrase('language_ten')?>
        </div>
        <div class="<?php if(isset($sap_xep) && $sap_xep == 2):?>ic ic1 <?php elseif (isset($sap_xep) && $sap_xep == 3):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="cl4 cl js-sort-by" style="width: 150px !important;" data-sort=5>
        <div class="tt or">
          Mã khu vực
        </div>
        <div class="<?php if(isset($sap_xep) && $sap_xep == 4):?>ic ic1 <?php elseif (isset($sap_xep) && $sap_xep == 5):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="cl9 cl">
        <div class="tt or">
          Thao tác
        </div>
        <div class="bg"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="clear"></div>
    </div>
    <?php if (count($shop_custom['id']) > 0):?>
    <?php for($i=0;$i<count($shop_custom['id']);$i++):?>
    <div class="r" id="tr_object_<?=$shop_custom['id'][$i]?>">
      <div class="hv"></div>
      <div class="cl1 cl">
        <md-checkbox class="ck checkBox" ng-click="checkProduct" ng-model="checkBoxProduct<?=$shop_custom['id'][$i]?>"></md-checkbox>
        <div class="clear"></div>
      </div>
      <div class="cl2 cl atv">
        <div class="tt or">
          #<?= $shop_custom['id'][$i]?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="cl3 cl" style="width: 450px !important;">
        <div class="tt or">
          <?= $shop_custom['name'][$i]?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="cl4 cl" style="width: 150px !important;">
        <div class="tt or">
          <?= $shop_custom['code'][$i]?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="cl9 cl">
        <?php if($shop_custom['status'][$i] == 1):?>
        <div class="stic bg ic1 js-activity" id="js-status-object-<?= $shop_custom['id'][$i]?>" data-id="<?= $shop_custom['id'][$i]?>" data-status="1">
          <div class="sp">
            <div class="p">
              Chọn để hủy kích hoạt
            </div>
          </div>
        </div>
        <?php else: ?>
        <div class="stic bg ic4 js-activity" id="js-status-object-<?= $shop_custom['id'][$i]?>" data-id="<?= $shop_custom['id'][$i]?>" data-status="0">
          <div class="sp">
            <div class="p">
              Chọn để kích hoạt
            </div>
          </div>
        </div>
        <?php endif?>
        <div class="stic bg ic2 js-edit" data-id="<?= $shop_custom['id'][$i]?>">
          <div class="sp">
            <div class="p">
              <?= Core::getPhrase('language_sua')?>
            </div>
          </div>
        </div>
        <div class="stic del bg ic2 js-delete" data-id="<?= $shop_custom['id'][$i]?>">
          <div class="sp">
            <div class="p">
              <?= Core::getPhrase('language_xoa')?>
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
    <?php endfor?>
    <?php else:?>
    <div class="r">
        Không tìm thấy kết quả nào.
    </div>
    <?php endif?>
    <!-- Phân trang -->
    <?= Core::getService('core.tools')->paginate($tong_trang, $trang_hien_tai, $duong_dan_phan_trang.'&page=::PAGE::', $duong_dan_phan_trang, '', '')?>
  </section>

<div class="w100 line-border">
    <md-button class="button-blue" onclick="window.location='/area/add/';">
        <?= Core::getPhrase('language_them')?>
    </md-button>
</div>
<div id="div_chon"><a href="javascript:void(this);" onclick="xoa_danh_sach_bai();"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(1);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(0);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a></div>
<script type="text/javascript" >
var sort_path = '<?= $sLinkSort?>';
function xoa_shop_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
    //document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=area&val[status]=2&val[id]='+stt);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
                //document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=area&val[status]=2&val[list]='+danh_sach);
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=area&val[status]='+trang_thai+'&val[list]='+danh_sach+'&val[math]='+Math.random());
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
    //document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=area&val[status]='+trang_thai+'&val[id]='+stt+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
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

</script>
    <div id="div_thong_bao" class="buttonarea"></div>
</section>
</div>
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
    <div class="w150 line-border">
        <md-button class="button-blue" onclick="window.location='/';">
            <?= Core::getPhrase('language_trang-quan-tri')?>
        </md-button>
    </div>
<?php
}
?>
<!--</div>
</section>-->