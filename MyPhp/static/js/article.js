var p_qstatus_obj = null;
$(function() {
    $('.js-change-tab-status').each(function(){
       $(this).unbind('click').click(function(){
           var links = $(this).data('link');
           window.location = links;
       });
    });
    
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
    
    //$('.js-update-price').each(function(){
//       $(this).unbind('click').click(function(){
//            var key = $(this).data('key');
//            if (typeof(key) == 'string' &&  key != '') {
//                alert(key);
//            }
//            
//       });
//    });
    
    $('.js-view-product').each(function(){
       $(this).unbind('click').click(function(){
            var links = $(this).data('link');
            if (typeof(links) == 'string' &&  links != '') {
                window.location = links;
            }
            
       });
    });
    $('.js-update-pquantity').unbind('change').change(function(){
        pid = $(this).attr('data-id');
        pvalue = $(this).val();
        p_qstatus_obj = $('.js-quantity-status[data-id="'+pid+'"]');
        addLoadingState(p_qstatus_obj, 'wait');
         sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.updateQuantity';
        sParams += '&val[pid]='+ pid + '&val[value]=' + pvalue;
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
            success: function (result) {
                if (result.status == 'error') {
                    alert(result.message);
                    addLoadingState(p_qstatus_obj, 'fail');
                    return false;
                }
                else {
                    removeLoadingState(p_qstatus_obj);
                }
            }
        });
    });
    $('.js-edit-product').each(function(){
       $(this).unbind('click').click(function(){
            var key = $(this).data('key');
            if (key != '') {
                links = '/article/add/?type=' + type + '&key='+key;
                window.location = links;
            }
            
       });
    });
    
    $('.js-approve').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                hien_thi(id, 0);
            }
            
       });
    });
    
    $('.js-cancel-approve').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                hien_thi(id, 1);
            }
            
       });
    });
    
    $('.js-delete-product').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                xoa_bai_viet(id);
            }
            
       });
    });
    
    initSetNewProduct();
    
    //Dành riêng khi serach tại trang danh sách bài viết
    $('#js-btn-search').unbind('click').click(function(){
        var keyword = $('#js-ctn-search').val();
        if (keyword.length > 0) {
            window.location = search_path + '&q=' + keyword;
        }
    });
});

function hien_thi(stt, trang_thai)
{
    if(trang_thai==1) trang_thai=0;
    else trang_thai=1;
    
    if(trang_thai == 0 && !confirm("Ban có chắc không cho phép hiển thị bài viết?")) return false;

    //document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
    
    var query = 'val[id]='+stt;
    if (trang_thai_kiem_duyet != -1) {
        query += '&val[vtype]=vid';
    }
    
    http.open('get', '/includes/ajax.php?=&core[call]=article.updateArticle&'+query+'&val[status]='+trang_thai+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //Lỗi
                alert('error');
                //document.getElementById('div_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
            } else {
                alert('Thao tác thành công');
                if(trang_thai == 1)
                {
                    //Thành công (duyệt)
                    //alert('Đã duyệt');
                    
                }
                else
                {
                    //alert('Đã hủy');
                    //Thành công hủy
                }
            }
        }
    };
    http.send(null);
    return false;
}

function xoa_bai_viet(stt) {
if(!confirm("Bạn có chắc xóa bài viết?"))
 {
     return false;
 }
    //document.getElementById('div_xoa_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_bai_viet(' + stt + ');"><img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/images/waiting.gif" title="<?= Core::getPhrase('language_dang-tai-du-lieu')?>" /></a>';
    
    var query = 'val[id]='+stt;
    if (trang_thai_kiem_duyet != -1) {
        query += '&val[vtype]=vid';
    }
    http.open('get', '/includes/ajax.php?=&core[call]=article.deleteArticle&'+query);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //lỗi
                alert('error');
                //document.getElementById('div_xoa_bai_viet_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="xoa_bai_viet(' + stt + ');"><img src="http://img.<?= $this->_aVars['sDomainName']?>/styles/web/global/images/status_warning.png" title="<?= Core::getPhrase('language_da-co-loi-xay-ra')?>: ' + error[1] + '" /></a>';
            } else {
                //Thành công
                alert('Thành công');
                document.getElementById('tr_article_' + stt).innerHTML = '';
                document.getElementById('tr_article_' + stt).style.display = "none";                
            }
        }
    };
    http.send(null);
}

function initSetNewProduct()
{
    $('.js-set-new-product').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            var status = 1;
            if (status == 1) {
                status == 0;
            }
            else {
                status = 1;
            }
            if (typeof(id) == 'number' &&  id > 0) {
                //addLoadingState($(this), 'wait');
                var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.setNewProduct';
                sParams += '&val[id]='+ id + '&val[status]=' + status;
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
                    },
                    success: function (result) {
                        if (typeof(result.status) != 'undefined' &&  result.status == 'success') {
                            //Thành công
                        }
                        else {
                            if (typeof(result.message) != 'undefined' && result.message != '') {
                                alert(result.message);
                            }
                            else {
                                alert('Lỗi hệ thống');
                            }
                            return false;
                        }
                    }
                });
            }
            
       });
    });
}