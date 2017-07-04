function acttionDelete(id)
{
    if (typeof(id) != 'number' && id < 1) {
        return false;
    }
    if(!confirm("Bạn có chắc muốn xóa?")) {
        return false;
    }
    var obj = $('#rowOrder'+id);
    var obj_del = obj.find('.js-act-del');
    obj_del.removeClass('fa fa-trash');
    obj_del.addClass('fa fa-spinner fa-pulse');
    
    var obj_detail = $('#js-detail-object-' + id).find('.js-act-del');
    var is_detail = false;
    if (obj_detail.length > 0) {
        is_detail = true;
    }
    if (is_detail) {
        obj_detail.removeClass('fa fa-trash');
        obj_detail.addClass('fa fa-spinner fa-pulse');
    }
    
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=cart&val[status]=2&val[id]='+id);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
                
            } else {
                //remove display
                alert('Xóa thành công');
                if (is_detail) {
                    window.location.reload();
                }
                else {
                    obj.remove();
                }
            }
        }
    };
    http.send(null);
    
}

function setStatus(id, status)
{
    if (status == 1) {
        status = 0;
    }
    else {
        status = 1;
    }
    if (status == 0) {
        if (!confirm("Bạn có chắc muốn hủy kích hoạt?")) {
            return false;
        }
    }
    
    var obj = $('#rowOrder'+id);
    var obj_select = obj.find('.js-act-state');
    
    var obj_detail = $('#js-status-detail-' + id);
    var is_detail = false;
    if (obj_detail.length > 0) {
        is_detail = true;
    }
    
    obj_select.removeClass('fa fa-warning');
    obj_select.removeClass('fa fa-check-square');
    
    obj_select.addClass('fa fa-spinner fa-pulse');
    if (is_detail) {
        obj_detail.removeClass('fa fa-warning');
        obj_detail.removeClass('fa fa-check-square');
        
        obj_detail.addClass('fa fa-spinner fa-pulse');
    }
    
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=cart&val[status]='+status+'&val[id]='+id+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                if (isset(result.message)) {
                    alert(result.message);
                }
                else {
                    alert('Lỗi hệ thống');
                }
                
                obj_select.removeClass('fa fa-spinner fa-pulse');
                if (status == 0) {
                    obj_select.addClass('fa fa-check-square');
                }
                else {
                    obj_select.addClass('fa fa-warning');
                }
                if (is_detail) {
                    obj_detail.removeClass('fa fa-spinner fa-pulse');
                    if (status == 0) {
                        obj_detail.addClass('fa fa-check-square');
                    }
                    else {
                        obj_detail.addClass('fa fa-warning');
                    }
                }
            } else {
                //set new status
                obj_select.removeClass('fa fa-spinner fa-pulse');
                if (status == 1) {
                    obj_select.addClass('fa fa-check-square');
                }
                else {
                    obj_select.addClass('fa fa-warning');
                }
                obj_select.attr('data-status', status);
                if (is_detail) {
                    obj_detail.removeClass('fa fa-spinner fa-pulse');
                    if (status == 1) {
                        obj_detail.addClass('fa fa-check-square');
                    }
                    else {
                        obj_detail.addClass('fa fa-warning');
                    }
                    obj_detail.attr('data-status', status);
                }
            }
        }
    };
    http.send(null);
    return false;
}

function changeHighlight(id , status)
{
    var obj = $('#js-highlight-object-' + id);
    var obj_detail = $('#js-highlight-detail-' + id);
    var is_detail = false;
    if (obj_detail.length > 0) {
        is_detail = true;
    }
    
    if (status == 1) {
        obj.removeClass('fa fa-star-o');
        obj.addClass('fa fa-star');
        
        obj.attr('data-status', 1);
        
        //obj.find('.sp').find('.p').html = 'Chọn để hủy kích hoạt';
        if (is_detail) {
            obj_detail.removeClass('fa fa-star-o');
            obj_detail.addClass('fa fa-star');
            
            obj_detail.attr('data-status', 1);
        }
    }
    else {
        obj.removeClass('fa fa-star');
        obj.addClass('fa fa-star-o');
        
        obj.attr('data-status', 0);
        
        //obj.find('.sp').find('.p').html = 'Chọn để kích hoạt';
        if (is_detail) {
            obj_detail.removeClass('fa fa-star');
            obj_detail.addClass('fa fa-star-o');
            
            obj_detail.attr('data-status', 0);
        }
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
            setHighlight(id, status);
        }
    });
    if (is_detail) {
        obj_detail.bind('click', function(){
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            if (typeof(id) == 'string') {
                id = parseInt(id);
            }
            if (typeof(status) == 'string') {
                status = parseInt(status);
            }
            if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
                setHighlight(id, status);
            }
        });
    }
}

function setHighlight(id, status)
{
    if (status == 1)
        status = 0;
    else
        status = 1;
    
    
    http.open('get', '/includes/ajax.php?=&core[call]=cart.setHighlight&val[status]='+status+'&val[id]='+id+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
                //document.getElementById('div_shop_custom_' + stt).innerHTML = '<a href="javascript:void(this);" onclick="hien_thi(' + stt + ', ' + trang_thai + ');"><img src="http://img.<?= Core::getDomainName()?>/styles/web/global/images/status_warning.png" /></a>';
            } else {
                //remove old class and add new class
                changeHighlight(id, status);
            }
        }
    };
    http.send(null);
    return false;
}

function initCommunity()
{
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
               var hasSort = tag.hasClass('ic0');
               if (hasSort) {
                   sort = sort - 1;
               }
               sort_link = sort_path +'&sap_xep=' + sort;
               window.location = sort_link;
           }
           return false;
       });
    });
    
    $('.js-act-view').each(function(){
       $(this).unbind('click').click(function(){
           //hidden pagination
        $('.pagination').hide();
        idObj = $(this).data('id');
        if (typeof(idObj) != 'number' || idObj < 1) {
            return false;
        }
        content = '';
        var jsRowOrder = $('#rowOrder' + idObj);
        $('.js-row-order').hide();
        jsRowOrder.show();
        
        // goi ajax
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=community.showCartDetail' + '&val[id]=' + idObj;
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
            dataType: 'text',
            error: function(jqXHR, status, errorThrown){
            },
            success: function (data) {
                $('#js-show-ordr-info').html(data);
                expandGroup();
                scrollMenu();
                initDetailCommunity();
                initCommunity();
            }
        });
           return;
            var links = $(this).data('link');
            if (typeof(links) == 'string' &&  links != '') {
                window.location = links;
            }
            
       });
    });
    
    $('.js-act-del').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                acttionDelete(id);
            }
            
       });
    });
    
    //chang status of object
    $('.js-act-state').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            if (typeof(id) == 'string') {
                id = parseInt(id);
            }
            if (typeof(status) == 'string') {
                status = parseInt(status);
            }
            if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
                setStatus(id, status);
            }
       });
    });
    
    //chang highlight of object
    $('.js-act-hot').each(function(){
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
                setHighlight(id, status);
            }
       });
    });
}

function initDetailCommunity()
{
    autoSize(); 
    expandGroup();
    scrollMenu();
    $('#cls-order-dt').unbind('click').click(function(){
        window.location.reload(); 
    });
}

$(function() {
    initCommunity();
});