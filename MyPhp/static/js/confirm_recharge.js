function init()
{
    $('.js-cancel-recharge').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            id = id*1;
            if (typeof(id) == 'number' &&  id > 0) {
                confirmObject(id, 2);
            }
       });
    });

    $('.js-confirm-recharge').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            id = id*1;
            if (typeof(id) == 'number' &&  id > 0) {
                confirmObject(id, 1);
            }
       });
    });
}

function initSort()
{
    $('.js-sort-by').each(function(){
       $(this).unbind('click').click(function(){
           var sort = $(this).data('sort');
           if (typeof(sort) == 'number' && typeof(sort_path) != 'undefined') {
               var tag = $(this).find('.js-icon-sort');
               var hasSort = tag.hasClass('fa-angle-up');
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

function confirmObject(id, status)
{
    if (status == 2) {
        if (!confirm("Bạn có chắc muốn hủy lần nạp tiền này?")) {
            return false;
        }
    }
    
    var obj = $('#js-action-'+id);
    var obj_conf = null;
    
    if (status == 1) {
        obj_conf = obj.find('.js-confirm-recharge');
        obj_conf.removeClass('fa fa-check-square');
    }
    else {
        obj_conf = obj.find('.js-cancel-recharge');
        obj_conf.removeClass('fa fa-close');
    }
    
    obj_conf.addClass('fa fa-spinner fa-pulse');
    //unbind click
    obj_conf.unbind('click');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.approvalRecharge&val[status]=' + status + '&val[id]=' + id;
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
            alert('Error');
        },
        success: function (data) {
            content = data;
            if(isset(content.status) && content.status == 'error') {
                //error
                alert(content.message);
                obj_conf.removeClass('fa fa-spinner fa-pulse');
                return false;
                //obj_conf.addClass('fa fa-warning');
            } else {
                //remove display
                alert('Thao tác thành công');
                document.getElementById('tr_object_' + id).innerHTML = '';
                document.getElementById('tr_object_' + id).style.display = "none";               
            }
        }
    });
}


$(function() {
    init();
    initSort();
});