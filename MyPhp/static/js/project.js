function initLoadproject()
{
    $('.js-project-status').unbind('click').click(function(){
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
         updateProjectStatus(_s, _id);
    });
}
function updateProjectStatus(ustatus, id)
{
    // confirm alert
    var _continue = true;
    if (ustatus == 0 && !confirm('Sau khi cập nhật, project này sẽ bị đổi status. Bạn có muốn tiếp tục thực hiện thao tác này?')) {
        _continue  = false;
    }
    if (_continue) {
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=core.updateProjectStatus';
        sParams += '&val[type]=project&val[id]='+id+'&val[status]='+ustatus+'&val[math]='+Math.random();
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
                console.log("ustatus" + ustatus);
                var response = result;
                if (ustatus == 1) {
                    $("#js-project-status-"+id).find('.sp').find('.p').html = 'Chọn để kích hoạt'
                    $("#js-project-status-"+id).attr('data-status', 1);
                    $("#js-project-status-"+id).removeClass('ic1');
                    $("#js-project-status-"+id).addClass('ic4');
                }
                else {
                    $('#js-project-status-'+id).find('.sp').find('.p').html = 'Chọn để hủy kích hoạt'
                    $('#js-project-status-'+id).attr('data-status', 0);
                    $('#js-project-status-'+id).removeClass('ic4');
                    $('#js-project-status-'+id).addClass('ic1');
                }
            }
        });
    }
    return false;
}
$(function(){
    initLoadproject();
});