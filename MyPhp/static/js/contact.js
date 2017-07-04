$(function(){
    $('.js-activity-object').unbind('click').click(function(){
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=contact.updateStatus';
        sParams += '&val[id]= ' + $(this).attr('data-id')*1;
        var _status = $(this).attr('data-status')*1;
        if(_status == 0) {
            _status = 1;
        } else {
            _status = 0;
        }
        sParams += '&val[status]= ' + _status;

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
                if(isset(result.status) && result.status == 'success') {
                    if (result.data == 0) {
                        $('.js-activity-object').removeClass('fa-check');
                        $('.js-activity-object').addClass('fa-close');
                        $('.js-activity-object').attr('data-status', 0);
                        $('.js-activity-object').attr('title', "Đã Liên hệ");
                    }
                    else {
                        $('.js-activity-object').removeClass('fa-close');
                        $('.js-activity-object').addClass('fa-check');
                        $('.js-activity-object').attr('data-status', 1);
                        $('.js-activity-object').attr('title', "Chưa Liên hệ");
                    }
                }            
                else {
                    var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                    alert(messg);
                }
            }
        });
    });
});