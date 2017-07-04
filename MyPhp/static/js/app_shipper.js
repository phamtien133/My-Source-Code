function initShippers()
{
    $('.js-ban-user').click(function(){
       var id = $(this).attr('data-id')*1;
       if (id > 0) {
           //verify
           var status = $(this).attr('data-status')*1;
           banShipper(id, status);
       }
    });
    
    $('.js-detail-user').click(function(){
       var id = $(this).data('id')*1;
       if (id > 0) {
           var sLink = '/app/shipper/detail/?id=' + id;
           window.location = sLink;
       }
    });
    
    $('.js-del-user').click(function(){
       var id = $(this).data('id')*1;
       if (id > 0) {
           //delete
           deleteShipper(id);
       }
    });
    
    /*  Thêm người giao hàng mới */
    $('#js-add-shipper').click(function(){
        insertPopupCrm('\
            <div class="container page-transport-popup pad20 js-popup-add-shipper" style="width: 500px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Thêm Người giao hàng\
                    </div>\
                    <div class="box-inner">\
                        <form action="#" id="frm_add_shipper" method="post">\
                            <div class="row30">\
                                <div class="avatar-add-shipper"></div>\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Họ và tên</div>\
                                <input name="val[username]" id="shipper-name" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Điện thoại</div>\
                                <input name="val[phone]" id="shipper-phone" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Hộp thư</div>\
                                <input name="val[email]" id="shipper-email" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10 mgbt10">\
                                <div class="sub-black-title">Mật khẩu</div>\
                                <input name="val[password]" id="shipper-pass" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtop">\
                                <div class="row30">\
                                    <div class="col6"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-cancel-add-shipper">Hủy</div>\
                                    </div>\
                                    <div class="col3 padleft10">\
                                        <input type="hidden" name="val[job_type]" value="1">\
                                        <div class="button-blue" id="js-submit-add-shipper">Thêm</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>', ['#js-cancel-add-shipper'],'.js-popup-add-shipper', true);
            initAddShipper();
    });
}

function initAddShipper()
{
    $('#js-submit-add-shipper').click(function(){
        var obj = $(this);
        //submint form
        $(this).addClass('js-button-wait');
        var action = "$(this).ajaxSiteCall('app.register', 'afterRegisterShipper(data)'); return false;";
        $('#frm_add_shipper').attr('onsubmit', action);
        $('#frm_add_shipper').submit();
        $('#frm_add_shipper').removeAttr('onsubmit');
        return false; 
    });
}

function afterRegisterShipper(data)
{
    if (typeof(data.status) != 'undefined' && data.status == 'success') {
        alert('Thao tác thành công');
        $('#js-cancel-add-shipper').click();
        //refresh page
        window.location.reload();
    }
    else {
        var messg = (typeof(data.message) != 'undefined') ? data.message : 'Lỗi hệ thống';
        //Xóa thể br nếu có
        messg = messg.replace('<br />', '');
        alert(messg);
    }
    $('#js-submit-add-shipper').removeClass('js-button-wait');
    return false;
}

function deleteShipper(id)
{
    if (!confirm("Bạn có chắc muốn xóa?")) {
        return false;
    }
    var obj = $('#js-action-object-'+id);
    var obj_del = obj.find('.js-del-user');
    obj_del.removeClass('fa fa-trash');
    obj_del.addClass('fa fa-spinner fa-pulse');
    //unbind click
    obj_del.unbind('click');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.updateStatus' + '&val[type]=shipper' + '&val[status]=2&val[id]='+ id;
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
            obj_del.addClass('fa fa-trash');
            obj_del.click(function(){
                var id = $(this).data('id')*1;
               if (id > 0) {
                   //delete
                   deleteShipper(id);
               }
            });
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                alert('Xóa thành công');
                $('#js-row-object-'+id).remove();
            }
            else {
                if (isset(result.message)) {
                    alert(result.message);
                }
                else {
                    alert('Lỗi hệ thống');
                }
                obj_del.removeClass('fa fa-spinner fa-pulse');
                obj_del.addClass('fa fa-trash');
                obj_del.click(function(){
                    var id = $(this).data('id')*1;
                   if (id > 0) {
                       //delete
                       deleteShipper(id);
                   }
                });
            }
        }
    });
}

function banShipper(id, status)
{
    if (status == 1) {
        status = 0;
    }
    else {
        status = 1;
    }
    if (status == 0) {
        if (!confirm("Bạn có chắc muốn khóa tài khoản này?")) {
            return false;
        }
    }
    
    var obj = $('#js-action-object-'+id);
    var obj_ban = obj.find('.js-ban-user');
    
    if (status == 0) {
        obj_ban.removeClass('fa fa-unlock');
    }
    else {
        obj_ban.removeClass('fa fa-lock');
    }
    obj_ban.addClass('fa fa-spinner fa-pulse');
    //unbind click
    obj_ban.unbind('click');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.banUser' + '&val[type]=shipper' + '&val[status]=' + status +'&val[id]='+ id;
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
            obj_ban.removeClass('fa fa-spinner fa-pulse');
            if (status == 0) {
                obj_ban.addClass('fa fa-lock');
            }
            else {
                obj_ban.addClass('fa fa-unlock');
            }
            
            obj_ban.click(function(){
                var id = $(this).attr('data-id')*1;
               if (id > 0) {
                   //verify
                   var status = $(this).attr('data-status')*1;
                   banShipper(id, status);
               }
            });
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                //set new status
                obj_ban.removeClass('fa fa-spinner fa-pulse');
                if (status == 1) {
                    obj_ban.addClass('fa fa-unlock');
                }
                else {
                    obj_ban.addClass('fa fa-lock');
                }
                obj_ban.attr('data-status', status);
                obj_ban.click(function(){
                    var id = $(this).attr('data-id')*1;
                   if (id > 0) {
                       //verify
                       var status = $(this).attr('data-status')*1;
                       banShipper(id, status);
                   }
                });
            }
            else {
                if (isset(result.message)) {
                    alert(result.message);
                }
                else {
                    alert('Lỗi hệ thống');
                }
                obj_ban.removeClass('fa fa-spinner fa-pulse');
                if (status == 0) {
                    obj_ban.addClass('fa fa-lock');
                }
                else {
                    obj_ban.addClass('fa fa-unlock');
                }
                
                obj_ban.click(function(){
                    var id = $(this).attr('data-id')*1;
                   if (id > 0) {
                       //verify
                       var status = $(this).attr('data-status')*1;
                       banShipper(id, status);
                   }
                });
            }
        }
    });
}

$(function(){
    initShippers();
});