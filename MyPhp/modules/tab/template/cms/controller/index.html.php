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
                    <div class="button-blue" onclick="window.location='/tab/add/';">
                        Thêm Thẻ nội dung
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
                    <div class="<?php if(!isset($sort) ||$sort == 'id_d'):?>ic ic1 <?php elseif ($sort == 'id_a'):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
                    <div class="clear"></div>
                    <div class="hv"></div>
                </div>
                <div class="col5 cl js-sort-by" data-link="<?= $this->_aVars['aData']['data']['link_sort'].'&sort=' .'name_d'?>" data-sort='name_d'>
                    <div class="tt or">
                    <?= Core::getPhrase('language_ten')?>
                    </div>
                    <div class="<?php if(isset($sort) && $sort == 'name_d'):?>ic ic1 <?php elseif (isset($sort) && $sort == 'name_a'):?>ic ic3 <?php endif?> bg js-icon-sort"></div>
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
                    <? if($aVals['status'] == 1):?>
                    <div class="stic bg ic1 js-activity" id="js-status-object-<?= $aVals['id']?>" data-id="<?= $aVals['id']?>" data-status="1">
                        <div class="sp">
                            <div class="p">
                            Chọn để hủy kích hoạt
                            </div>
                        </div>
                    </div>
                    <? else: ?>
                    <div class="stic bg ic4 js-activity" id="js-status-object-<?= $aVals['id']?>" data-id="<?= $aVals['id']?>" data-status="0">
                        <div class="sp">
                            <div class="p">
                            Chọn để kích hoạt
                            </div>
                        </div>
                    </div>
                    <? endif?>
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
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <? endforeach?>
            <? else:?>
            <div class="r">
                Không tìm thấy dữ liệu nào.
            </div>
            <? endif?>
            <!-- Phân trang -->
            <?= Core::getService('core.tools')->paginate(ceil($this->_aVars['aData']['data']['total']/$this->_aVars['aData']['data']['page_size']), $this->_aVars['aData']['data']['page'], '/tab/?'.'&page=::PAGE::', '/tab/?', '', '')?>
<script type="text/javascript" >
function xoa_shop_custom(stt) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]=2&val[id]='+stt);
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]=2&val[list]='+danh_sach);
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
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[list]='+danh_sach+'&val[math]='+Math.random());
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

    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=tab&val[status]='+trang_thai+'&val[id]='+stt+'&val[math]='+Math.random());
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
                links = '/tab/add/id_'+id;
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
<? } else { ?>
<section class="container">
    <div class="panel-box">
        <div class="row30 padtb10 ">
            <?= Core::getPhrase('language_da-co-loi-xay-ra')?>
        </div>
        <div class="row30 padtb10">
            
                <div class="row30">
                    <?= $this->_aVars['aData']['message'] ?>
                </div>
        </div>
        <div class="row30 padtop10">
            <div class="col3 padright10">
                <div class="button-blue" onclick="window.location='/';">
                    <?= Core::getPhrase('language_trang-quan-tri')?>
                </div>
            </div>
        </div>
    </div>
</section>
<? } ?>