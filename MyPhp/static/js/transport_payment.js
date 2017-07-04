function initRequestPayment()
{
    $('.js-payment').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        if (id > 0) {
            var objSelect = $(this);
            var objIcon = $(this).find('span');
            if (objIcon.hasClass('fa-credit-card')) {
                if (confirm("Xác nhận đã thanh toán ?")) {
                    objIcon.removeClass('fa-credit-card');
                    objIcon.addClass('fa-spinner fa-pulse');
                    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.updateRequestPayment' + '&val[status]=1' + '&val[id]='+id;
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
                            objIcon.removeClass('fa-spinner fa-pulse');
                            objIcon.addClass('fa-credit-card');
                        },
                        success: function (result) {
                            if(isset(result.status) && result.status == 'success') {
                                alert('Cập nhật thành công');
                                
                                $('#js-status-object-' + id).html('Đã thanh toán');
                                $('#js-action-object-' + id).html('');
                            }
                            else {
                                objIcon.removeClass('fa-spinner fa-pulse');
                                objIcon.addClass('fa-credit-card');
                                var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                                alert(messg);
                                
                            };
                        }
                    });
                }
            }
        }
    });
    
    $('.js-cancel-payment').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        if (id > 0) {
            var objSelect = $(this);
            var objIcon = $(this).find('span');
            if (objIcon.hasClass('fa-times')) {
                if (confirm("Bạn có chắc muốn hủy yêu cầu thanh toán này ?")) {
                    objIcon.removeClass('fa-times');
                    objIcon.addClass('fa-spinner fa-pulse');
                    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.updateRequestPayment' + '&val[status]=3' + '&val[id]='+id;
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
                            objIcon.removeClass('fa-spinner fa-pulse');
                            objIcon.addClass('fa-times');
                        },
                        success: function (result) {
                            if(isset(result.status) && result.status == 'success') {
                                alert('Cập nhật thành công');
                                
                                $('#js-status-object-' + id).html('Bị hủy');
                                $('#js-action-object-' + id).html('');
                            }
                            else {
                                objIcon.removeClass('fa-spinner fa-pulse');
                                objIcon.addClass('fa-times');
                                var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                                alert(messg);
                                
                            };
                        }
                    });
                }
            }
        }
    });
}

$(function(){
    initRequestPayment();
});