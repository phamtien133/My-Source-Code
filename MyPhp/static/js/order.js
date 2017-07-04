
    $('.shop_order_ghi_chu').each(function(index, element) {
        if($(this).height() > 120)
        {
            $(this).height(100);
        }
    });
    
// add 
function ExpandDiv(id)
{
    var obj = $('#tr_shop_order_' + id);
    var expandImg = obj.find('img.expandImg');
    var mo_rong = 0;
    // đang thu nhỏ, chuyển sang mở rộng
    if(expandImg.attr('src').indexOf('add') == -1)
    {
        mo_rong = 1;
    }
    
    if(!mo_rong)
    {
        expandImg.attr('src', 'http://img.<?= Core::getDomainName()?>/styles/acp/web/global/images/minus.png');
        if($('#expand_tr_shop_order_' + id).length > 0)
        {
            $('#expand_tr_shop_order_' + id).fadeIn('slow');
            return false;
        }
        obj.after('<tr id="expand_tr_shop_order_' + id +'"><td colspan=11><div><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" ></div></td></tr>');
    }
    else
    {
        $('#expand_tr_shop_order_' + id).fadeOut('slow');
        expandImg.attr('src', 'http://img.<?= Core::getDomainName()?>/styles/acp/web/global/images/add.png');
        return false;
    }
    // tải nội dung
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.getOrderDetail' + '&val[id]=' + id;
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
        },
        success: function (data) {
            var content = '<table class="zebra"><tr><td>Người nhận</td><td>Sản phẩm</td></tr>';
            if(typeof(data) == 'object' && data.type == 'error')
            {
                $('#txt_chat').val(data.error);
            }
            else
            {
                content += '<tr><td>';
                var thanh_vien = data.general.customer[0];
                content += 'Họ và tên:' + thanh_vien['full_name'] + '<br>';
                content += 'Địa chỉ:' + thanh_vien['address'] + '<br>';
                content += 'Điện thoại:' + thanh_vien['phone_number'];
                
                content += '</td><td>';
                
                var san_pham = data['detail'];
                tmp = '';
                for(i in san_pham['id'])
                {
                    
                    tmp += 'Tên: <a href="' + san_pham['path'][i] + '" target="_blank">' + san_pham['name'][i] + '</a><br>Giá bán: ' + numberFormat(san_pham['amount'][i]) + ' <sup><u><?= $this->_aVars['aSettings']['currency']['name_code'] ?></u></sup><hr>';
                }
                
                content += tmp + '</td></tr>';
            }
            if(data['general']['note'] != '')
            {
                content += '<tr><td colspan=2>Ghi chú:<hr>' + data['general']['note'] + '</td></tr>';
            }
            if(data['general']['admin_note'] != '')
            {
                content += '<tr><td colspan=2>Ghi chú Quản trị:<hr>' + data['general']['admin_note'] + '</td></tr>';
            }
            content += '<tfoot><tr><td colspan=2><a href="javascript:void(this);" onclick="ExpandDiv(' + data['general']['id'][0] + ')">Thu nhỏ</a> | <a href="javascript:void(this);" onclick="PrintDiv(' + data['general']['id'][0] + ')">In hóa đơn</a> | <a href="./shop/add/id_' + data['general']['id'][0] + '">Xem chi tiết</a></td></tr></tfoot></table>';
            $('#expand_tr_shop_order_' + id).find('div').html(content);
        }
    });
    
    return false;
}

// print
function PrintDiv(id)
{
    moPopup('/tools/in_phieu_giao_hang.php?id=' + id,
        {},
        {
            'title' : '<?= Core::getPhrase('language_in-phieu')?>',
            'width' : 900,
            'height' : 600,
            'type' : 'popup',
        }
    );
    return false;
}


function xoa_shop_order(id) {
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-bai')?>"))
 {
     return false;
 }
 return false;
    document.getElementById('div_xoa_shop_order_' + id).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_order(' + id + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
    http.open('get', '/tools/acp/xoa_shop_order.php?id='+id);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                document.getElementById('div_xoa_shop_order_' + id).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_order(' + id + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
            } else {
                document.getElementById('tr_shop_order_' + id).innerHTML = '';
                document.getElementById('tr_shop_order_' + id).style.display = "none";                
            }
        }
    };
    http.send(null);
}
function xoa_danh_sach_bai() {
    return false;
if(!confirm("<?= Core::getPhrase('language_ban-co-chac-muon-xoa-danh-sach-bai')?>"))
 {
     return false;
 }
    var field = document.getElementsByName('ckb_shop_order');
    var n=0, danh_sach = '', danh_sach_mang = new Array(1);
    for (i = 0; i < field.length; i++)
    {
        if(field[i].checked == true)
        {
            document.getElementById('div_xoa_shop_order_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_order(' + field[i].value + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
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
    http.open('get', '/tools/acp/xoa_shop_order.php?list='+danh_sach);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                for (i = 0; i < danh_sach_mang.length; i++)
                {
                    document.getElementById('div_xoa_shop_order_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="xoa_shop_order(' + danh_sach_mang[i] + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
                }
            } else {
                for (i = 0; i < danh_sach_mang.length; i++)
                {
                    document.getElementById('tr_shop_order_' + danh_sach_mang[i]).innerHTML = '';
                    document.getElementById('tr_shop_order_' + danh_sach_mang[i]).style.display = "none";
                    document.getElementById('div_chon').style.display = 'none';
                }
            }
        }
    };
    http.send(null);
}
function trang_thai_danh_sach_bai(trang_thai)
{
    return false;
    if(trang_thai == 0 && !confirm("<?= Core::getPhrase('language_ban-co-chac-khong-cho-phep-hien-thi-bai-viet')?>"))
    {
     return false;
    }
    var field = document.getElementsByName('ckb_shop_order');
    var n=0, danh_sach = '', danh_sach_mang = new Array(1);
    for (i = 0; i < field.length; i++)
    {
        if(field[i].checked == true)
        {
            document.getElementById('div_shop_order_' + field[i].value).innerHTML = '<a href="javascript:void(this);" onclick="status_deliver(' + field[i].value + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" /></a>';
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
    http.open('get', '/tools/acp/cap_nhat_shop_order.php?list='+danh_sach+'&trang_thai='+trang_thai+'&math='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                for (i = 0; i < danh_sach_mang.length; i++)
                {
                    document.getElementById('div_shop_order_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="status_deliver(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
                }
            } else {
                if(trang_thai == 1)
                {
                    for (i = 0; i < danh_sach_mang.length; i++)
                    {
                        document.getElementById('div_shop_order_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="status_deliver(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_yes.png" /></a>';
                    }
                }
                else
                {
                    for (i = 0; i < danh_sach_mang.length; i++)
                    {
                        document.getElementById('div_shop_order_' + danh_sach_mang[i]).innerHTML = '<a href="javascript:void(this);" onclick="status_deliver(' + danh_sach_mang[i] + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_no.png" /></a>';
                    }
                }
            }
        }
    };
    http.send(null);
    return false;
}
function status_deliver(objLink, id, trang_thai) {
if( ( trang_thai == 'huy' || trang_thai == 'khong-the-giao-hang' ) && !confirm("<?= Core::getPhrase('language_ban-co-chac-khong-cho-phep-hien-thi-bai-viet')?>"))
{
 return false;
}
    var tmps = {
        onclick : objLink.attr('onclick'),
        html : objLink.html(),
    };
    objLink.attr('onclick', 'return false')
    objLink.html('<img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" >');
    
    $.ajaxCall({
        url: document.location.protocol + '//' + window.location.host + '/includes/ajax.php?=&core[call]=shop.getOrderDetail&val[id]='+id+'&val[act]=edit&val[status]='+trang_thai,
        async:false,
        timeout: 8000,
        callback: function(data){
            var content = '';
            if(typeof(data) == 'object' && data.type == 'error')
            {
                alert(data.error);
                objLink.attr('onclick', tmps.onclick);
                objLink.html(tmps.html);
            }
            else
            {
                var trang_thai = data['content']['status'], id = data['content']['id'], update_time = data['content']['update_time'];
                
                cai_dat_thuc_hien(id, trang_thai, update_time);
            }
    
        },
    });
    return false;
}
function cai_dat_thuc_hien(id, trang_thai, update_time)
{
    var html = '', content = '';
    if(trang_thai == '') html = '<?= $status_deliver_list['']?>';
    else if(trang_thai == 'da-xac-nhan') html = '<?= $status_deliver_list['da-xac-nhan']?>';
    else if(trang_thai == 'dang-giao-hang') html = '<?= $status_deliver_list['dang-giao-hang']?>';
    else if(trang_thai == 'da-nhan-hang') html = '<?= $status_deliver_list['da-nhan-hang']?>';
    else if(trang_thai == 'da-huy') html = '<?= $status_deliver_list['da-huy']?>';
    else if(trang_thai == 'khong-nhan-hang') html = '<?= $status_deliver_list['khong-nhan-hang']?>';
    else if(trang_thai == 'bi-tra-ve') html = '<?= $status_deliver_list['bi-tra-ve']?>';
    
    
    if(trang_thai == ''){
    content = '<a href="javascript:void(this);" onclick="status_deliver($(this), ' + id + ', \'da-xac-nhan\')"><?= $status_deliver_list['da-xac-nhan']?></a>';
    }
    else if(trang_thai == 'da-xac-nhan') content = '<a  href="javascript:void(this);" onclick="status_deliver($(this), ' + id + ', \'dang-giao-hang\')"><?= $status_deliver_list['dang-giao-hang']?></a>';
    else if(trang_thai == 'dang-giao-hang') content = '<a  href="javascript:void(this);" onclick="status_deliver($(this), ' + id + ', \'da-nhan-hang\')"><?= $status_deliver_list['da-nhan-hang']?></a> | <a  href="javascript:void(this);" onclick="status_deliver($(this), ' + id + ', \'khong-nhan-hang\')">Không thể giao hàng</a>';
    else if(trang_thai == 'da-nhan-hang') content = '<a  href="javascript:void(this);" onclick="status_deliver($(this), ' + id + ', \'bi-tra-ve\')">Trả lại hàng</a>';
    
    if(trang_thai == 'da-xac-nhan' || trang_thai == '') content += '| <a  href="javascript:void(this);" onclick="status_deliver($(this), ' + id + ', \'da-huy\')">Hủy</a>';
    
    var obj = $('#tr_shop_order_' + id);
    if(init == 1) obj.fadeOut('fast', function(){
        obj.find('.thuc_hien').html(content);
        obj.find('.status_deliver').html(html);
        obj.find('.update_time').html(update_time);
        obj.fadeIn('slow');
    });
    else
    {
        obj.find('.thuc_hien').html(content);
        obj.find('.status_deliver').html(html);
        obj.find('.update_time').html(update_time);
    }
}
init = 0;
(function(){
    
    <? for($i=0;$i<count($shop_order['id']);$i++):?>
        cai_dat_thuc_hien(<?= $shop_order['id'][$i]?>, '<?= $shop_order['status_deliver_ma_ten'][$i]?>', '<?= $shop_order['update_time'][$i]?>');
    <? endfor?>
    init = 1;
})();
function hien_xu_ly_chon()
{
    var field = document.getElementsByName('ckb_shop_order');
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
    var field = document.getElementsByName('ckb_shop_order');
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