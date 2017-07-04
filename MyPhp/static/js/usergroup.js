function initLoadUserGroup()
{
    $('#js-add-pers-user').unbind('click').click(function(){
        var _id = $(this).attr('data-id');
        var link = oParams['sJsMain'] + 'user/group/add';
        if (_id > 0) {
            link += '/' + _id;
        }
        window.location = link;
        return false;
    });
    
    $('.js-group-status').unbind('click').click(function(){
         var _s = $(this).attr('data-status');
         var _id = $(this).attr('data-id');
         if (_id < 1) {
             alert('Có lỗi xảy ra, vui lòng liên hệ quản trị.');
             return false;
         }
         if (_s == 1) {
             _s = 0;
         }
         else {
             _s = 1;
         }
         updateGroupStatus(_s, _id);
    });
    
    $('.js-delete-group').unbind('click').click(function(){
         var _id = $(this).attr('data-id');
         if (_id < 1) {
             alert('Có lỗi xảy ra, vui lòng liên hệ quản trị.');
             return false;
         }
         // warning 
         if (confirm('Khi xóa nhóm này, các thành viên trong nhóm sẽ được chuyển qua nhóm mặc định. Đây là thao tác không thể phục hồi, bạn có chắc muốn xóa nhóm này?')) {
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.deleteGroup';
            sParams += '&val[id]='+_id;
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
                    
                }
            });
         }
         return false;
    });
}
function updateGroupStatus(ustatus, id)
{
    // confirm alert
    var _continue = true;
    if (ustatus == 0 && !confirm('Sau khi cập nhật, các thành viên trong nhóm này sẽ không thể truy cập hệ thống. Bạn có muốn tiếp tục thực hiện thao tác này?')) {
        _continue  = false;
    }
    if (_continue) {
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=core.updateStatus';
        sParams += '&val[type]=user_group&val[id]='+id+'&val[status]='+ustatus+'&val[math]='+Math.random();
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
            success: function (result) {
                var response = result;
                var error = response.split('<-errorvietspider->');
                if( typeof error[1] != 'undefined') {
                    alert(error[1]);
                    return false;
                } else {
                    if (ustatus == 1) {
                        $('#js-group-'+id + ' .js-group-status').attr('title', 'Đã kích hoạt');
                        $('#js-group-'+id + ' .js-group-status').attr('data-status', 1);
                        $('#js-group-'+id + ' .js-group-status span').removeClass('fa-close');
                        $('#js-group-'+id + ' .js-group-status span').addClass('fa-check');
                    }
                    else {
                        $('#js-group-'+id + ' .js-group-status').attr('title', 'Cấm truy cập');
                        $('#js-group-'+id + ' .js-group-status').attr('data-status', 0);
                        $('#js-group-'+id + ' .js-group-status span').removeClass('fa-check');
                        $('#js-group-'+id + ' .js-group-status span').addClass('fa-close');
                    }
                }
            }
        });
    }
    return false;
}
$(function(){
    initLoadUserGroup();
});