
$(function(){
    initInteractionObj();
    
    $('#js-add-vendor-store').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        if (id > 0) {
            window.location = '/vendor/addstore/?vendor_id=' + id;
        }
    });
});

function initInteractionObj()
{
    $('.js-display-item').unbind('click').click(function(){
        var sobj = $(this); 
        var status = $(this).attr('data-status');
        var id =  $(this).attr('data-id');
        var vid = $('#js-current-vendor').val();
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=vendor.updateStatusVendorStore' + '&val[id]=' + id;
        sParams += '&val[vid]=' + vid;
        sParams += '&val[status]=' + status;
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
                if (data.status == 'error') {
                    alert(data.message);
                    return false;
                }
                else {
                    if (status == 0) {
                        // chuyển qua off. 
                        sobj.removeClass('icon-status-on');
                        sobj.addClass('icon-status-off');
                        sobj.find('.sp .p').html('Chọn để kích hoạt');
                        sobj.attr('data-status', 1);
                    }
                    if (status == 1) {
                        // chuyển qua on. 
                        sobj.removeClass('icon-status-off');
                        sobj.addClass('icon-status-on');
                        sobj.find('.sp .p').html('Chọn để hủy kích hoạt');
                        sobj.attr('data-status', 0);
                    }
                }
                
            }
        });
    });
    
    $('.js-obj-del').unbind('click').click(function(){
        if (!confirm('Bạn có chắc thực hiện thao tác này ?')) {
            return false;
        }
        var sobj = $(this); 
        var id =  $(this).attr('data-id');
        var vid = $('#js-current-vendor').val();
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=vendor.updateStatusVendorStore' + '&val[id]=' + id;
        sParams += '&val[vid]=' + vid;
        sParams += '&val[status]=' + 2;
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
                if (data.status == 'error') {
                    alert(data.message);
                    return false;
                }
                else {
                    //remove
                    $('#js-row-obj-' + id).remove();
                }
            }
        });
    });
    
    $('.js-obj-view').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        if (id > 0) {
            window.location = '/vendor/addstore/?id=' + id;
        }
    });
}

// function initSort()
// {
//     alert('123');
//     var sort_path = '<?= $sLinkSort?>';
//     $('.js-sort-by').each(function(){
//        $(this).unbind('click').click(function(){
//            var sort = $(this).data('sort');
//            if (typeof(sort) == 'number' && typeof(sort_path) != 'undefined') {
//                var tag = $(this).find('div.js-icon-sort');
//                var hasSort = tag.hasClass('ic3');
//                if (hasSort) {
//                    sort = sort - 1;
//                }
//                sort_link = sort_path +'&sort=' + sort;
//                window.location = sort_link;
//            }
//            return false;
//        });
//     });
// }