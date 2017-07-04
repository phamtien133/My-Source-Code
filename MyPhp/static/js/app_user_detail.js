function initdetailAppUser()
{
    $('#js-request-payment').click(function(){
        //alert('Yêu cầu thanh toán ');
    })
    
    $('#js-save-info-user').click(function(){
        var id = $('#current_user_id').val()*1;
        if (id < 1) {
            return false;
        }
        
        $(this).addClass('js-button-wait');
        
        var action = "$(this).ajaxSiteCall('app.updateUserInfo', 'afterUpdateUserInfo(data)'); return false;";
        $('#frm_update_info').attr('onsubmit', action);
        $('#frm_update_info').submit();
        $('#frm_update_info').removeAttr('onsubmit');
        //$('#js-save-info-user').unbind('click');
        return false;
    })
    
    $('#js-view-image-verify').click(function(){
        var aData = (typeof(aImageVerify) != 'undefined') ? aImageVerify : {};
        var id = $('#current_user_id').val()*1;
        if (id > 0) {
            var content = '\
            <div class="container page-transport-popup pad20 js-popup-image-verify" style="width: 500px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Hình ảnh chứng thực\
                    </div>\
                    <div class="box-inner">\
                        <form action="#" id="frm_image_verify" method="post">';
            if (!empty(aData)) {
                for (var i in aData) {
                    content += '<div class="row30 mgbt20">\
                                <img src="'+ aData[i].image_path + '" alt="Hình ảnh chứng thực" class="img-image-verify" />\
                            </div>';
                }
            }
            else {
                content += '<div class="row30 mgbt20">Không có ảnh xác thực nào</div>';
            }
                content += '<div class="row30 padtop">\
                                <div class="row30">\
                                    <div class="col9"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-close-image-verify">Đóng</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>';
            
            insertPopupCrm(content, ['#js-close-image-verify'],'.js-popup-add-shipper', true);
        }
        
    });
    
    $('#js-view-info-bank').click(function(){
        var aData = (typeof(aBank) != 'undefined') ? aBank : {};
        if (!empty(aData)) {
            insertPopupCrm('\
            <div class="container page-transport-popup pad20 js-popup-info-bank" style="width: 500px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Thông tin tài khoản ngân hàng\
                    </div>\
                    <div class="box-inner">\
                        <form action="#" id="frm_setting_bank" method="post">\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Họ và tên</div>\
                                <input name="val[username]" value="'+ aData.account_name +'" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Ngân hàng</div>\
                                <input name="val[bank]" value="'+ aData.account_bank +'" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Chi nhánh</div>\
                                <input name="val[branch]" value="'+ aData.account_branch +'" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Số tài khoản</div>\
                                <input name="val[account_id]" value="'+ aData.account_code +'" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtop">\
                                <div class="row30">\
                                    <div class="col6"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-cancel-info-bank">Hủy</div>\
                                    </div>\
                                    <div class="col3 padleft10">\
                                        <input type="hidden" id="bank_user_id" name="val[user_id]" value="0">\
                                        <div class="button-blue" id="js-submit-info-bank">Lưu thông tin</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>', ['#js-cancel-info-bank'],'.js-popup-info-bank', true);
            initSbmSettingBank();
        }
    });
    
    //chuyển tab
    $('.js-type-order').click(function(){
        var objSelect = $(this);
        var objOld = $('#js-list-type-order .js-type-order.atv');
        var oldType = objOld.attr('data-type');
        var newType = objSelect.attr('data-type');
        if (typeof(oldType) == 'undefined' || typeof(newType) == 'undefined' || empty(oldType) || empty(newType)) {
            return;
        }
        //hide old
        $('#js-'+ oldType +'-order').addClass('none');
        objOld.removeClass('atv');
        
        //show new
        $('#js-'+ newType +'-order').removeClass('none');
        objSelect.addClass('atv');
    });
    
    //verify
    initVerifyUser();
    
    if ($('#page-detail-shipper').length > 0) {
        $('#js-select-vehicle').change(function(){
            var value = $(this).val();
            if (value == 'working') {
                $('#drive_name').val('');
                $('#drive_number').val('');
                $('#driver_license').val('');
                $('#drive_name').attr('disabled', 'disabled');
                $('#drive_number').attr('disabled', 'disabled');
                $('#driver_license').attr('disabled', 'disabled');
            }
            else {
                //remove disable
                $('#drive_name').removeAttr('disabled');
                $('#drive_number').removeAttr('disabled');
                $('#driver_license').removeAttr('disabled');
            }
        });
        
        $('#js-save-info-drive').click(function(){
            //
            var id = $('#vehicle_user_id').val()*1;
            if (id < 1) {
                return false;
            }
                      
            $(this).addClass('js-button-wait');
            
            var action = "$(this).ajaxSiteCall('app.updateVehicle', 'afterUpdateVehicle(data)'); return false;";
            $('#frm_update_vehicle').attr('onsubmit', action);
            $('#frm_update_vehicle').submit();
            $('#frm_update_vehicle').removeAttr('onsubmit');
            //$('#js-save-info-user').unbind('click');
            return false;
        });
        
        $('.js-receive-money').click(function(){
            var type = $(this).attr('data-type');
            var value = $(this).attr('data-value')*1;
            var user_id = $('#current_user_id').val()*1;
            var order_id = $(this).attr('data-id');
            console.log(order_id);
            var obj = $(this);
            if (value > 0 && type != '' && user_id > 0) {
                obj.addClass('js-button-wait');
                sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.receiveMoney' + '&val[type]='+ type + '&val[total_money]='+ value + '&val[user_id]=' + user_id;
                if (order_id > 0) {
                    sParams += '&val[order_id]=' + order_id;
                }
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
                        obj.removeClass('js-button-wait');
                    },
                    success: function (result) {
                        if(isset(result.status) && result.status == 'success') {
                            //Show code verify
                            alert('Mã xác nhận là: '+ result.data.code_verify)
                        }
                        else {
                            var mssg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                            alert(mssg);
                        }
                        obj.removeClass('js-button-wait');
                    }
                });
            }
        });
        
        $('.js-type-receive').click(function(){
            var objSelect = $(this);
            var objOld = $('#js-list-type-receive .js-type-receive.atv');
            var oldType = objOld.attr('data-type');
            var newType = objSelect.attr('data-type');
            if (typeof(oldType) == 'undefined' || typeof(newType) == 'undefined' || empty(oldType) || empty(newType)) {
                return;
            }
            //hide old
            $('#js-receive-'+ oldType).addClass('none');
            $('#js-info-receive-'+ oldType).addClass('none');
            objOld.removeClass('atv');
            
            //show new
            $('#js-receive-'+ newType).removeClass('none');
            $('#js-info-receive-'+ newType).removeClass('none');
            objSelect.addClass('atv');
        });
        
        initUserPaginationReceive();
    };
    
    //event pagination
    initUserPaginationOrder();
}

function initVerifyUser()
{
    var hideIconVerifyUser = function () {
        $('#js-icon-process-verify').removeClass('fa-check');
        $('#js-icon-process-verify').removeClass('fa-warning');
        $('#js-icon-process-verify').removeClass('fa-spinner fa-pulse');
        $('#js-icon-process-verify').addClass('none');
    }
    
    $('#js-verify-user').change(function(){
        var value = $(this).val()*1;
        var iUserId = $('#current_user_id').val()*1;
        var iJob = $('#job_type').val()*1;
        if (iUserId < 1) {
            return false;
        }
        var objProcess = $('#js-icon-process-verify');
        objProcess.removeClass('none');
        objProcess.addClass('fa-spinner fa-pulse');
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.verifyUser' + '&val[user_id]=' + iUserId + '&val[status]='+ value + '&val[job_type]='+ iJob;
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
                objProcess.removeClass('fa-spinner fa-pulse');
                objProcess.addClass('fa-warning');
                alert('Lỗi hệ thống');
                setTimeout(hideIconVerifyUser, 5000);
            },
            success: function (result) {
                objProcess.removeClass('fa-spinner fa-pulse');
                if(isset(result.status) && result.status == 'success') {
                    objProcess.addClass('fa-check');
                    setTimeout(hideIconVerifyUser, 5000);
                }
                else {
                    var mssg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                    objProcess.addClass('fa-warning');
                    alert(mssg);
                    setTimeout(hideIconVerifyUser, 5000);
                }
            }
        });
    });
}

function initUserPaginationOrder()
{
    $('.js-pagi-order').each(function(){
        var sObjContent = $(this).attr('data-obj_ctn');
        var objPagiSelect = $(this);
        if (typeof(sObjContent) == 'undefined') {
            return;
        }
        //Kiểm tra các input cần gửi ajax
        var sType = $('#' + sObjContent).attr('data-type');
        var iJob = $('#job_type').val()*1;
        var iUserId = $('#current_user_id').val()*1;
        if (typeof(sType) == 'undefined' || typeof(iJob) == 'undefined' || iUserId < 1 || empty(sType)) {
            return false;
        }
        
        objPagiSelect.find('.item-trp-pagi').click(function(){
            var iPage = $(this).attr('data-page')*1;
            if ($(this).hasClass('atv') || iPage < 1) {
                return;
            }
            var objSelect = $(this);
            //gọi ajax load page
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.getActivityOrder' + '&val[page]=' + iPage + '&val[type]=' + sType + '&val[job_type]=' + iJob + '&val[user_id]=' + iUserId;
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
                    //objSelect.removeClass('js-button-wait');
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        var html = '';
                        if (isset(result.data) && !empty(result.data)) {
                            for (var i in result.data) {
                                html += '<div class="row20 line-bottom padtb10">\
                                <div class="row20">\
                                    <div class="col8">\
                                        <div class="sub-black-title opacity-70 small">'+ result.data[i].receive_time.text + '-' + result.data[i].complete_time.text + '</div>\
                                        <div class="sub-black-title-large">#'+ result.data[i].code +'</div>\
                                    </div>\
                                    <div class="col4">\
                                        <select name="" id="">\
                                            <option value="">'+ result.data[i].status_txt +'</option>\
                                        </select>\
                                    </div>\
                                </div>\
                                <div class="row20">\
                                    <div class="left">\
                                        <div class="sub-black-title">\
                                            Trạng thái\
                                            <span>'+ result.data[i].status_txt +'</span>\
                                        </div>\
                                    </div>\
                                    <div class="right">'+ currencyFormat(result.data[i].order.total_amount) + 'đ\
                                    </div>\
                                </div>\
                            </div>';
                            }
                        }
                        else {
                            html += '<div class="row20 line-bottom padtb10">\
                                    <div class="row20">\
                                        Không có đơn hàng nào.\
                                    </div>\
                                </div>';
                        }
                        $('#' + sObjContent).find('.js-ctn-order').html(html);
                        //Thay đổi trang hiện tại
                        var currentPage =  objPagiSelect.find('.item-trp-pagi.atv');
                        currentPage.removeClass('atv');
                        var sTitle = currentPage.attr('data-page');
                        sTitle = 'Trang ' + sTitle;
                        currentPage.attr('title', sTitle);
                        objSelect.addClass('atv');
                        objSelect.attr('title', 'Trang hiện tại');
                    }
                    else {
                        var mssg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(mssg);
                    }
                }
            });
        });
    });
}

function initUserPaginationReceive()
{
    $('.js-pagi-receive').each(function(){
        var sObjContent = $(this).attr('data-obj_ctn');
        var objPagiSelect = $(this);
        if (typeof(sObjContent) == 'undefined') {
            return;
        }
        //Kiểm tra các input cần gửi ajax
        var sType = $('#' + sObjContent).attr('data-type');
        var iUserId = $('#current_user_id').val()*1;
        if (typeof(sType) == 'undefined' || iUserId < 1 || empty(sType)) {
            return false;
        }
        
        objPagiSelect.find('.item-trp-pagi').click(function(){
            var iPage = $(this).attr('data-page')*1;
            if ($(this).hasClass('atv') || iPage < 1) {
                return;
            }
            var objSelect = $(this);
            //gọi ajax load page
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.getReceiveMoneyByType' + '&val[page]=' + iPage + '&val[type]=' + sType + '&val[user_id]=' + iUserId;
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
                    //objSelect.removeClass('js-button-wait');
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        var html = '';
                        if (isset(result.data.detail) && !empty(result.data.detail)) {
                            for (var i in result.data.detail) {
                                html += '<div class="row20 line-bottom padtb10">\
                                        <div class="row20">\
                                            <div class="col8">\
                                                <div class="sub-black-title small opacity-70">'+ result.data.detail[i].create_time_txt +'</div>\
                                                <div class="sub-black-title-large">'+ result.data.detail[i].order_code +'</div>\
                                            </div>\
                                            <div class="col4">\
                                                <select name="" id="">\
                                                    <option value="">'+ result.data.detail[i].status_txt +'</option>\
                                                </select>\
                                            </div>\
                                        </div>\
                                        <div class="row20">\
                                            <div class="left">\
                                                <div class="sub-black-title">\
                                                    Trạng thái\
                                                    <span>'+ result.data.detail[i].status_txt +'</span>\
                                                </div>\
                                            </div>\
                                            <div class="right">\
                                                Tiền thu hộ: '+ currencyFormat(result.data.detail[i].sum_monney) +' đ\
                                            </div>\
                                        </div>\
                                    </div>';
                            }
                        }
                        else {
                            html += '<div class="row20 line-bottom padtb10">\
                                    <div class="row20">\
                                        Chưa có tiền đã thu hộ nào.\
                                    </div>\
                                </div>';
                        }
                        $('#' + sObjContent).find('.js-ctn-order').html(html);
                        //Thay đổi trang hiện tại
                        var currentPage =  objPagiSelect.find('.item-trp-pagi.atv');
                        currentPage.removeClass('atv');
                        var sTitle = currentPage.attr('data-page');
                        sTitle = 'Trang ' + sTitle;
                        currentPage.attr('title', sTitle);
                        objSelect.addClass('atv');
                        objSelect.attr('title', 'Trang hiện tại');
                    }
                    else {
                        var mssg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(mssg);
                    }
                }
            });
        });
    });
}

function afterUpdateUserInfo(data)
{
    if (typeof(data.status) != 'undefined' && data.status == 'success') {
        alert('Đã câp nhật thành công');
    }
    else {
        var messg = (typeof(data.message) != 'undefined') ? data.message : 'Lỗi hệ thống';
        //Xóa thể br nếu có
        messg = messg.replace('<br />', '');
        alert(messg);
    }
    $('#js-save-info-user').removeClass('js-button-wait');
    return false;
}

function afterUpdateVehicle(data)
{
    if (typeof(data.status) != 'undefined' && data.status == 'success') {
        alert('Đã câp nhật thành công');
    }
    else {
        var messg = (typeof(data.message) != 'undefined') ? data.message : 'Lỗi hệ thống';
        //Xóa thể br nếu có
        messg = messg.replace('<br />', '');
        alert(messg);
    }
    $('#js-save-info-drive').removeClass('js-button-wait');
    return false;
}


function initSbmSettingBank()
{
    $('#js-submit-info-bank').click(function(){
       var id = $('#current_user_id').val()*1;
        if (id < 1) {
            return false;
        }
        $('#bank_user_id').val(id);
        $(this).addClass('js-button-wait');
        
        var action = "$(this).ajaxSiteCall('app.updateUserBank', 'afterUpdateUserBank(data)'); return false;";
        $('#frm_setting_bank').attr('onsubmit', action);
        $('#frm_setting_bank').submit();
        $('#frm_setting_bank').removeAttr('onsubmit');
        return false;
    });
}

function afterUpdateUserBank(data)
{
    if (typeof(data.status) != 'undefined' && data.status == 'success') {
        alert('Đã câp nhật thành công');
        aBank = data.data;
        $('#js-cancel-info-bank').click();
    }
    else {
        var messg = (typeof(data.message) != 'undefined') ? data.message : 'Lỗi hệ thống';
        //Xóa thể br nếu có
        messg = messg.replace('<br />', '');
        alert(messg);
    }
    $('#js-submit-info-bank').removeClass('js-button-wait');
    return false;
}

$(function(){
    initdetailAppUser();
});