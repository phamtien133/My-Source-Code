function initAddStaff()
{
    $('.add-staff').unbind('click').click(function(){
        var vid = $(this).attr('data-id');
        if (empty(vid) || vid == 0) {
            alert('Có lỗi xảy ra, vui lòng tải lại trang hoặc liên hệ quản trị.');
            return false;
        }
        else {
            var path = oParams['sJsMain']+ 'app/permission/add/'+ vid;
            window.location = path;
        } 
    });
}
function initAddStaffToVendor()
{
    $('.js-add-item').unbind('click').click(function(){
        var sobj = $(this);
        var id =  $(this).attr('data-id');
        var vid = $('#js-current-vendor').val();
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.addStaff' + '&val[sid]=' + id + '&val[vid]='+ vid;
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
                    alert('Thêm thành công');
                    sobj.parents('.js-row-order').remove();
                }
            }
        }); 
    });
    $('.js-display-item').unbind('click').click(function(){
        var sobj = $(this); 
        var status = $(this).attr('data-status');
        var sid =  $(this).attr('data-id');
        var vid = $('#js-current-vendor').val();
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.updateStaff' + '&val[sid]=' + sid;
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
                        sobj.find('.sp .p').html('Chọn để hợp tác');
                        sobj.attr('data-status', 1);
                    }
                    if (status == 1) {
                        // chuyển qua on. 
                        sobj.removeClass('icon-status-off');
                        sobj.addClass('icon-status-on');
                        sobj.find('.sp .p').html('Chọn để ngưng hợp tác');
                        sobj.attr('data-status', 0);
                    }
                }
                
            }
        });
    });
    $('.back-to-manage').unbind('click').click(function(){
        var vid = $(this).attr('data-id');
        if (empty(vid) || vid == 0) {
            alert('Có lỗi xảy ra, vui lòng tải lại trang hoặc liên hệ quản trị.');
            return false;
        }
        else {
            var path = oParams['sJsMain']+ 'app/permission/'+ vid;
            window.location = path;
        } 
    });
}
$(function() {
     initAddStaff();
     initAddStaffToVendor();
});
