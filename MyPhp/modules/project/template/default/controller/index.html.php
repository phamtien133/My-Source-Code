<?php
foreach($this->_aVars['output'] as $key)
{
    $$key = $this->_aVars['data'][$key];
}
?>
<?php
    $aMapStatusIcon =array(
        0 => 'fa-close',
        1 => 'fa-check'
    );
?>
<!--<section class="container">
<div class="panel-box">-->
<?php if($status==1){?>
<div class="container product unselectable page-list-vendor" ng-controller="product-ctrl">
    <section class="sanpham fthtab">
    <section class="overview-statistic-bar statistic-bar content-box">
      <form method="GET" id="frm" action="/project/index">
        <div class="row20">
            <div class="col2 row30" style="margin-top: 5px">
                <div class="button-blue" onclick="window.location='/project/add/';">
                    Thêm Project
                </div>
            </div>
            <div class="col5"></div>
            
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
      <div class="col1 cl js-sort-by" data-link="<?= $sLinkSort?>" data-sort=1>
        <div class="tt or">
          Mã số
        </div>
        <div class="<?php if(!isset($sap_xep) ||$sap_xep == 0):?>ic ic1 <?php elseif ($sap_xep == 1):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
        <div class="clear"></div>
        <div class="hv"></div>
      </div>
      <div class="col5 cl js-sort-by" data-link="<?= $sLinkSort.'&sap_xep=1'?>" data-sort=3>
        <div class="tt or">
          <?= Core::getPhrase('language_ten')?>
        </div>
        <div class="<?php if(isset($sap_xep) && $sap_xep == 2):?>ic ic1 <?php elseif (isset($sap_xep) && $sap_xep == 3):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
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
    <?php if (count($project_custom['id']) > 0):?>
    <?php for($i=0;$i<count($project_custom['id']);$i++):?>
    <div class="r" id="tr_object_<?=$project_custom['id'][$i]?>">
      <!--div class="cl1 cl">
        <md-checkbox class="ck checkBox" ng-click="checkProduct" ng-model="checkBoxProduct<?=$project_custom['id'][$i]?>"></md-checkbox>
        <div class="clear"></div>
      </div-->
      <!-- <script>
        init();
    </script> -->
      <div class="col1 cl atv">
        <div class="tt or">
          #<?= $project_custom['id'][$i]?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="col5 cl">
        <div class="tt or">
          <?= $project_custom['name'][$i]?>
        </div>
        <div class="clear"></div>
      </div>
      <div class="col6 cl">

        <?php if($project_custom['status'][$i] == 1):?>
        <div class="fleft">
            <a href="javascript:void(0);" class="js-project-status stic bg ic1" data-status="0" id="js-project-status-<?= $project_custom['id'][$i]?>" data-id="<?= $project_custom['id'][$i]?>">
                <div class="sp">
                    <div class="p">
                      Chọn để hủy kích hoạt
                    </div>
                </div>
            </a>
        </div>
        <?php else: ?>
        <div class="fleft">
            <a href="javascript:void(0);" class="js-project-status stic bg ic4" data-status="1" id="js-project-status-<?= $project_custom['id'][$i]?>" data-id="<?= $project_custom['id'][$i]?>">
                <div class="sp">
                    <div class="p">
                      Chọn để kích hoạt
                    </div>
                </div>
            </a>
        </div>
        <?php endif?>
        <form name="frm_edit" method="POST" action="/project/add/">
            <input type="hidden" name="id-edit" value="<?= $project_custom['id'][$i]?>">
            <div class="stic" data-id="<?= $project_custom['id'][$i]?>">
            <input type="submit" name="submit" value="" class="stic bg ic2 js-edit" >
              <div class="sp">
                <div class="p">                
                  <?= Core::getPhrase('language_sua')?>                  
                </div>
              </div>
            </div>
        </form>
        
        <div class="stic del bg ic2 js-delete" data-id="<?= $project_custom['id'][$i]?>">
          <div class="sp">
            <div class="p">
              <?= Core::getPhrase('language_xoa')?>
            </div>
          </div>
        </div>
        <div class="stic bg ic3 js-view-store" data-id="<?= $project_custom['id'][$i]?>">
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
    <?php endfor?>
    <?php else:?>
    <div class="r">
        Không tìm thấy Project nào.
    </div>
    <?php endif?>
    <!-- Phân trang -->
    <?= Core::getService('core.tools')->paginate($tong_trang, $trang_hien_tai, $duong_dan_phan_trang.'&page=::PAGE::', $duong_dan_phan_trang, '', '')?>
  </section>
<script type="text/javascript" >

function xoa_project_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
    //document.getElementById('div_xoa_project_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_project_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=project&val[status]=2&val[id]='+stt);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
                //document.getElementById('div_xoa_project_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_project_custom(' + stt + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
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
    var field = document.getElementsByName('ckb_project_custom');
    var n=0, danh_sach = '', danh_sach_mang = new Array(1);
    for (i = 0; i < field.length; i++)
    {
        if(field[i].checked == true)
        {
            document.getElementById('div_xoa_project_custom_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="xoa_project_custom(' + field[i].value + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=project&val[status]=2&val[list]='+danh_sach);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                for (i = 0; i < danh_sach_mang.length; i++)
                {
                    document.getElementById('div_xoa_project_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="xoa_project_custom(' + danh_sach_mang[i] + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
                }
            } else {
                for (i = 0; i < danh_sach_mang.length; i++)
                {
                    document.getElementById('tr_project_custom_' + danh_sach_mang[i]).innerHTML = '';
                    document.getElementById('tr_project_custom_' + danh_sach_mang[i]).style.display = "none";
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
    var field = document.getElementsByName('ckb_project_custom');
    var n=0, danh_sach = '', danh_sach_mang = new Array(1);
    for (i = 0; i < field.length; i++)
    {
        if(field[i].checked == true)
        {
            document.getElementById('div_project_custom_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + field[i].value + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=project&val[status]='+trang_thai+'&val[list]='+danh_sach+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                for (i = 0; i < danh_sach_mang.length; i++)
                {
                    document.getElementById('div_project_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
                }
            } else {
                if(trang_thai == 1)
                {
                    for (i = 0; i < danh_sach_mang.length; i++)
                    {
                        document.getElementById('div_project_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a>';
                    }
                }
                else
                {
                    for (i = 0; i < danh_sach_mang.length; i++)
                    {
                        document.getElementById('div_project_custom_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a>';
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
    //document.getElementById('div_project_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=project&val[status]='+trang_thai+'&val[id]='+stt+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //document.getElementById('div_project_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
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
    var field = document.getElementsByName('ckb_project_custom');
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
    // else document.getElementById('div_chon').style.display = 'none';
}
hien_xu_ly_chon();
function chon_tat_ca()
{
    var field = document.getElementsByName('ckb_project_custom');
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
               sort_link = sort_path +'&sap_xep=' + sort;
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
                links = '/project/edit/?id='+id;
                window.location = links;
            }

       });
    });

    $('.js-delete').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                xoa_project_custom(id);
            }
       });
    });

    $('.js-view-store').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).attr('data-id');
            if (id > 0) {
                window.location = '/project/store/?project_id='+id;
            }
       });
    });
}

// $('#js-check-all').unbind('click').click(function(){
//     var check = $(this).attr('aria-checked');
//     if (typeof(check) == 'string') {
//         if (check == 'true') {
//             //uncheck all
//         }
//         else {
//             //check all
//         }
//     }
// });

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

// $(function(){
//     init();
// });

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