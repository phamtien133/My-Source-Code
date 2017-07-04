function actionDelete(id)
{
    if (!confirm("Bạn có chắc muốn xóa?")) {
        return false;
    }
    var obj = $('#rowOrder'+id);
    var obj_del = obj.find('.js-act-del');
    obj_del.removeClass('fa fa-trash');
    obj_del.addClass('fa fa-spinner fa-pulse');
    //unbind click
    //obj_del.unbind('click');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=community.updateStatus&val[type]=comment' + '&val[status]=2&val[id]='+ id;
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
            obj_del.removeClass('fa fa-spinner fa-pulse');
            obj_del.addClass('fa fa-close');
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                alert('Xóa thành công');
                obj.remove();
            }
            else {
                if (isset(result.message)) {
                    alert(result.message);
                }
                else {
                    alert('Lỗi hệ thống');
                }
                obj_del.removeClass('fa fa-spinner fa-pulse');
                obj_del.addClass('fa fa-close');
            }
        }
    });
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
    
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=community.updateStatus&val[type]=comment' + '&val[status]=' + status +'&val[id]='+ id;
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
            obj_select.removeClass('fa fa-spinner fa-pulse');
            if (status == 0) {
                obj_select.addClass('fa fa-check-square');
            }
            else {
                obj_select.addClass('fa fa-warning');
            }
            if (is_detail) {
                if (status == 0) {
                    obj_detail.addClass('fa fa-check-square');
                }
                else {
                    obj_detail.addClass('fa fa-warning');
                }
            }
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
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
                    if (status == 0) {
                        obj_detail.addClass('fa fa-check-square');
                    }
                    else {
                        obj_detail.addClass('fa fa-warning');
                    }
                }
            }
            else {
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
                    if (status == 0) {
                        obj_detail.addClass('fa fa-check-square');
                    }
                    else {
                        obj_detail.addClass('fa fa-warning');
                    }
                }
            }
        }
    });
}

function initCommunity()
{
    /*
    $('.js-change-tab-status').each(function(){
       $(this).unbind('click').click(function(){
           var links = $(this).data('link');
           window.location = links;
       });
    });
    */
    
    $('.js-sort-by').each(function(){
       $(this).unbind('click').click(function(){
           var sort = $(this).data('sort');
           if (typeof(sort) == 'number' && typeof(sort_path) != 'undefined' && !empty(sort_path)) {
               var tag = $(this).find('div.js-icon-sort');
               var hasSort = tag.hasClass('ic0');
               if (hasSort) {
                   sort = sort - 1;
               }
               sort_link = sort_path +'&sort=' + sort;
               window.location = sort_link;
           }
           return false;
       });
    });
    
    
    $('.js-act-del').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id')*1;
            if (id > 0) {
                actionDelete(id);
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
    
}

function initDetailCommunity()
{
    autoSize(); 
    expandGroup();
    scrollMenu();
}

$(function() {
    initCommunity();
});