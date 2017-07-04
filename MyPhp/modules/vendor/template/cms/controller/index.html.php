<!--<section class="container">
<div class="panel-box">-->
<div class="container product unselectable page-list-vendor" ng-controller="product-ctrl">
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
                <div class="button-blue" onclick="window.location='/vendor/add/';">
                    Thêm Nhà cung cấp
                </div>
            </div>
            <div class="col5"></div>
            <div class="col1">
                <div class="sub-black-title mgtop10"><?= Core::getPhrase('language_tu-khoa')?> : </div>
            </div>
            <div class="col3 padright10">
                <input type="text" name="q" value="<?= $tu_khoa?>" id="tu_khoa" class="default-input product-srch-input tt tt1" placeholder="Nội dung tìm kiếm" style="margin-top: 5px">
            </div>
            <div class="col1 product-srch-bt">
                <button class="btn gr button-blue" style="margin-top: 5px; height: 30px ! important; line-height: 30px ! important; padding: 0px 10px;border: medium none;">Tìm</button>
            </div>
        </div>

        <div class="ctb left mxClrAft">
            <div class="tt left">

            </div>

        </div>
        <div class="ctb left mxClrAft ">

        </div>
        <div class="clear"></div>
      </form>
    </section>
    <section class="product-list-box content-box panel-shadow mgbt20" ng-class="showlist">
    <div class="title_list">
      <!--div class="cl1 cl">
        <md-checkbox class="ck" ng-click="checkAllProduct()" ng-model="checkBoxAllProduct" id="js-check-all"></md-checkbox>
        <div class="clear"></div>
      </div-->
      <div class="col1 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort']?>" data-sort=1>
        <div class="tt or">
          Mã số
        </div>
        <div class="<?php if(!isset($sort) ||$sort == 0):?>ic ic1 <?php elseif ($sort == 1):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="col5 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort'].'&sort=1'?>" data-sort=3>
        <div class="tt or">
          <?= Core::getPhrase('language_ten')?>
        </div>
        <div class="<?php if(isset($sort) && $sort == 2):?>ic ic1 <?php elseif (isset($sort) && $sort == 3):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="col6 cl">
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
      <!--div class="cl1 cl">
        <md-checkbox class="ck checkBox" ng-click="checkProduct" ng-model="checkBoxProduct<?=$aVals['id']?>"></md-checkbox>
        <div class="clear"></div>
      </div-->
      <div class="col1 cl atv">
        <div class="tt or">
          #<?= $aVals['id']?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="col5 cl">
        <div class="tt or">
          <?= $aVals['name']?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="col6 cl">
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
        <div class="stic bg ic2 js-edit" data-id="<?= $aVals['id']?>">
          <div class="sp">
            <div class="p">
              <?= Core::getPhrase('language_sua')?>
            </div>
          </div>
        </div>
        <div class="stic del bg ic2 js-delete" data-id="<?= $aVals['id']?>">
          <div class="sp">
            <div class="p">
              <?= Core::getPhrase('language_xoa')?>
            </div>
          </div>
        </div>
        <div class="stic bg ic3 js-view-store" data-id="<?= $aVals['id']?>">
          <div class="sp">
            <div class="p">
              Xem kho hàng
            </div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
    <? endforeach?>
    <? else:?>
    <div class="r">
        Không tìm thấy nhà cung cấp nào.
    </div>
    <? endif?>
    <!-- Phân trang -->
    <?= Core::getService('core.tools')->paginate(ceil($this->_aVars['aData']['data']['total']/$this->_aVars['aData']['data']['page_size']), $this->_aVars['aData']['data']['page'], '/vendor/?'.'&page=::PAGE::', '/vendor/?', '', '')?>
  </section>
<div id="div_chon"><a href="javascript:void(this);" onclick="xoa_danh_sach_bai();"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/delete.png" alt="<?= Core::getPhrase('language_xoa-bai')?>" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(1);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a> <a href="javascript:void(this);" onclick="trang_thai_danh_sach_bai(0);"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a></div>
<script type="text/javascript" >
function xoa_shop_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
    //document.getElementById('div_xoa_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=vendor&val[status]=2&val[id]='+stt);
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=vendor&val[status]=2&val[list]='+danh_sach);
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=vendor&val[status]='+trang_thai+'&val[list]='+danh_sach+'&val[math]='+Math.random());
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=vendor&val[status]='+trang_thai+'&val[id]='+stt+'&val[math]='+Math.random());
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

function initSort()
{
    var sort_path = '<?= $sLinkSort?>';
    $('.js-sort-by').each(function(){
       $(this).unbind('click').click(function(){
           var sort = $(this).data('sort');
           if (typeof(sort) == 'number' && typeof(sort_path) != 'undefined') {
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

function init()
{
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
                links = '/vendor/edit/?id='+id;
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

    $('.js-view-store').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).attr('data-id');
            if (id > 0) {
                window.location = '/vendor/store/?vendor_id='+id;
            }
       });
    });
}

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
    init();
});

</script>
    <div id="div_thong_bao" class="buttonarea"></div>
</section>
</div>

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

<!--</div>
</section>-->