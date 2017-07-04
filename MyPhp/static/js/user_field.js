
$(function(){
    $('#js-add-new-field').unbind('click').click(function(){
        window.location = '/user/field/add/'
    });
    
    $('.js-group-status').unbind('click').click(function(){
        var id  = $(this).attr('data-id')*1;
        var obj = $(this);
        var obj_status = $(this).find('span');
        var wait = obj_status.hasClass('fa-spinner');
        if (id > 0 && !wait) {
            var current_status = obj.attr('data-status');
            var status = 1;
            if (current_status == 1) {
                if (!confirm("Bạn có chắc muốn hủy kích hoạt?")) {
                    return false;
                }
                status = 0;
            }
            if (current_status == 1) {
                obj_status.removeClass('fa fa-check');
            }
            else {
                obj_status.removeClass('fa fa-close');
            }
            
            obj_status.addClass('fa fa-spinner fa-pulse');
            //unbind click
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.updateFieldStatus' + '&val[type]=user_field' + '&val[status]='+status+'&val[id]='+ id;
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
                    obj_status.removeClass('fa fa-spinner fa-pulse');
                    if (status == 1) {
                        obj_status.addClass('fa fa-close');
                    }
                    else {
                        obj_status.addClass('fa fa-check');
                    }
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        obj_status.removeClass('fa fa-spinner fa-pulse');
                        obj.attr('data-status', status);
                        if (status == 1) {
                            obj_status.addClass('fa fa-check');
                        }
                        else {
                            obj_status.addClass('fa fa-close');
                        }
                    }
                    else {
                        if (isset(result.message)) {
                            alert(result.message);
                        }
                        else {
                            alert('Lỗi hệ thống');
                        }
                        obj_status.removeClass('fa fa-spinner fa-pulse');
                        if (status == 1) {
                            obj_status.addClass('fa fa-close');
                        }
                        else {
                            obj_status.addClass('fa fa-check');
                        }
                    }
                }
            });
        }
        
    })
    
    $('.js-delete-group').unbind('click').click(function(){
        var id  = $(this).attr('data-id')*1;
        var obj = $(this);
        var obj_status = $(this).find('span');
        var wait = obj_status.hasClass('fa-spinner');
        if (id > 0 && !wait) {
            obj_status.removeClass('fa fa-trash');
            obj_status.addClass('fa fa-spinner fa-pulse');
            //unbind click
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.updateFieldStatus' + '&val[type]=user_field' + '&val[status]=2&val[id]='+ id;
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
                    obj_status.removeClass('fa fa-spinner fa-pulse');
                    obj_status.addClass('fa fa-trash');
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        alert('Xóa thành công');
                        $('#js-group-' + id).remove();
                    }
                    else {
                        if (isset(result.message)) {
                            alert(result.message);
                        }
                        else {
                            alert('Lỗi hệ thống');
                        }
                        obj_status.removeClass('fa fa-spinner fa-pulse');
                        obj_status.addClass('fa fa-trash');
                    }
                }
            });
        }
        
    })
});