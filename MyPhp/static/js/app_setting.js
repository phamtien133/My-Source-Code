
function initAppSetting()
{
    $('#js-update-free-shopper').click(function(){
        console.log('shopper');
        //update
        $(this).addClass('js-button-wait');
        var action = "$(this).ajaxSiteCall('app.updateFreeShopper', 'afterUpdateFreeShopper(data)'); return false;";
        $('#frm_update_free_shopper').attr('onsubmit', action);
        $('#frm_update_free_shopper').submit();
        $('#frm_update_free_shopper').removeAttr('onsubmit');
        //$('#js-save-info-user').unbind('click');
        return false;
        
        
    });
    $('#js-update-free-shipper').click(function(){
        console.log('shipper');
        $(this).addClass('js-button-wait');
        
        var action = "$(this).ajaxSiteCall('app.updateFreeShipper', 'afterUpdateFreeShipper(data)'); return false;";
        $('#frm_update_free_shipper').attr('onsubmit', action);
        $('#frm_update_free_shipper').submit();
        $('#frm_update_free_shipper').removeAttr('onsubmit');
        //$('#js-save-info-user').unbind('click');
        return false;
    });
    
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57);
    $('.js-input-number').keydown(function(e){
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
    
    initSettingBusyTime();
    initSettingShipper();
    initSettingShopper();
    
    initSubmitUpdateCostShopper();
    initSubmitUpdateCostShipper();
    initDeleteObjectSetting();
}

function initSettingShipper()
{
    $('#js-add-bonus-by-total-order').unbind('click').click(function(){
        //gọi 1 popup nhỏ
        var html = '<div class="container pad20 search-filter-box">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Thêm mức thưởng theo số lương đơn hàng</div>' +
                            '<div class="box-inner">' +
                                '<div class="row30 mgbt10">' +
                                    '<div class="col2">S.L đơn hàng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-order" value="" placeholder="S.L">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">Loại:</div>' +
                                    '<div class="col2">' +
                                        '<select class="trans-select" id="js-type-bonus" style="width: 100%;">' +
                                            '<option value="day">Ngày</option>' +
                                            '<option value="month">Tháng</option>' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col2">Tiền thưởng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-money" value="" placeholder="Số tiền">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">VNĐ</div>' +
                                '</div>' +
                                '<div class="row30 mgtop20">' +
                                    '<div class="col8"></div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-blue" id="js-add-bonus-by-order">Thêm</div>' +
                                    '</div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-default js-cancel-add">Đóng</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
        initShipperAddBonusByOrder();
    });
    
    $('#js-add-bonus-by-online-time').unbind('click').click(function(){
        //gọi 1 popup nhỏ
        var html = '<div class="container pad20 search-filter-box">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Thêm mức thưởng theo thời gian hoạt động</div>' +
                            '<div class="box-inner">' +
                                '<div class="row30 mgbt10">' +
                                    '<div class="col2">Tổng giờ:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-hour" value="" placeholder="Số giờ">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">Loại:</div>' +
                                    '<div class="col2">' +
                                        '<select class="trans-select" id="js-type-bonus" style="width: 100%;">' +
                                            '<option value="day">Ngày</option>' +
                                            '<option value="month">Tháng</option>' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col2">Tiền thưởng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-money" value="" placeholder="Số tiền">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">VNĐ</div>' +
                                '</div>' +
                                '<div class="row30 mgtop20">' +
                                    '<div class="col8"></div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-blue" id="js-add-bonus-by-order">Thêm</div>' +
                                    '</div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-default js-cancel-add">Đóng</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
        initShipperAddBonusByOnlineTime();
    });
    
    $('#js-add-bonus-by-busy-time').unbind('click').click(function(){
        //gọi 1 popup nhỏ
        var html = '<div class="container pad20 search-filter-box">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Thêm mức thưởng theo thời gian hoạt động giờ cao điểm</div>' +
                            '<div class="box-inner">' +
                                '<div class="row30 mgbt10">' +
                                    '<div class="col2">Tổng giờ:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-hour" value="" placeholder="Số giờ">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">Loại:</div>' +
                                    '<div class="col2">' +
                                        '<select class="trans-select" id="js-type-bonus" style="width: 100%;">' +
                                            '<option value="day">Ngày</option>' +
                                            '<option value="month">Tháng</option>' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col2">Tiền thưởng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-money" value="" placeholder="Số tiền">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">VNĐ</div>' +
                                '</div>' +
                                '<div class="row30 mgtop20">' +
                                    '<div class="col8"></div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-blue" id="js-add-bonus-by-order">Thêm</div>' +
                                    '</div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-default js-cancel-add">Đóng</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
        initShipperAddBonusByBusyTime();
    });
}

function initSettingShopper()
{
    $('#js-shopper-bonus-by-total-order').unbind('click').click(function(){
        //gọi 1 popup nhỏ
        var html = '<div class="container pad20 search-filter-box">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Thêm mức thưởng theo số lương đơn hàng</div>' +
                            '<div class="box-inner">' +
                                '<div class="row30 mgbt10">' +
                                    '<div class="col2">S.L đơn hàng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-order" value="" placeholder="S.L">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">Loại:</div>' +
                                    '<div class="col2">' +
                                        '<select class="trans-select" id="js-type-bonus" style="width: 100%;">' +
                                            '<option value="day">Ngày</option>' +
                                            '<option value="month">Tháng</option>' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col2">Tiền thưởng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-money" value="" placeholder="Số tiền">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">VNĐ</div>' +
                                '</div>' +
                                '<div class="row30 mgtop20">' +
                                    '<div class="col8"></div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-blue" id="js-add-bonus-by-order">Thêm</div>' +
                                    '</div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-default js-cancel-add">Đóng</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
        initShopperAddBonusByOrder();
    });
    
    $('#js-shopper-bonus-by-online-time').unbind('click').click(function(){
        //gọi 1 popup nhỏ
        var html = '<div class="container pad20 search-filter-box">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Thêm mức thưởng theo thời gian hoạt động</div>' +
                            '<div class="box-inner">' +
                                '<div class="row30 mgbt10">' +
                                    '<div class="col2">Tổng giờ:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-hour" value="" placeholder="Số giờ">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">Loại:</div>' +
                                    '<div class="col2">' +
                                        '<select class="trans-select" id="js-type-bonus" style="width: 100%;">' +
                                            '<option value="day">Ngày</option>' +
                                            '<option value="month">Tháng</option>' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col2">Tiền thưởng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-money" value="" placeholder="Số tiền">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">VNĐ</div>' +
                                '</div>' +
                                '<div class="row30 mgtop20">' +
                                    '<div class="col8"></div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-blue" id="js-add-bonus-by-order">Thêm</div>' +
                                    '</div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-default js-cancel-add">Đóng</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
        initShopperAddBonusByOnlineTime();
    });
    
    $('#js-shopper-bonus-by-busy-time').unbind('click').click(function(){
        //gọi 1 popup nhỏ
        var html = '<div class="container pad20 search-filter-box">' +
                        '<div class="content-box mgbt20 panel-shadow">' +
                            '<div class="box-title">Thêm mức thưởng theo thời gian hoạt động giờ cao điểm</div>' +
                            '<div class="box-inner">' +
                                '<div class="row30 mgbt10">' +
                                    '<div class="col2">Tổng giờ:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-hour" value="" placeholder="Số giờ">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">Loại:</div>' +
                                    '<div class="col2">' +
                                        '<select class="trans-select" id="js-type-bonus" style="width: 100%;">' +
                                            '<option value="day">Ngày</option>' +
                                            '<option value="month">Tháng</option>' +
                                        '</select>' +
                                    '</div>' +
                                    '<div class="col2">Tiền thưởng:</div>' +
                                    '<div class="col2">' +
                                        '<input type="text" class="default-input" id="js-total-money" value="" placeholder="Số tiền">' +
                                    '</div>' +
                                    '<div class="col1 padleft10">VNĐ</div>' +
                                '</div>' +
                                '<div class="row30 mgtop20">' +
                                    '<div class="col8"></div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-blue" id="js-add-bonus-by-order">Thêm</div>' +
                                    '</div>' +
                                    '<div class="col2 padleft10">' +
                                        '<div class="button-default js-cancel-add">Đóng</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
        insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
        initShopperAddBonusByBusyTime();
    });
}

function initDeleteObjectSetting()
{
    $('.js-del-object-setting').unbind('click').click(function(){
        var id = $(this).attr('data-id')*1;
        var obj_map = $(this).attr('data-map');
        if (id > 0 && typeof(obj_map) != 'undefined' && !empty(obj_map)) {
            if (confirm('Bạn có chắc muốn xóa?')) {
                $('#' + obj_map + '-' + id).remove();
            }
        }
    });
}

function initSubmitUpdateCostShipper()
{
    $('#js-update-cost-shipper').unbind('click').click(function(){
        
        //validate input
        
        //submit form
        var action = "$(this).ajaxSiteCall('app.updateCostShipper', 'afterUpdateCostShipper(data)'); return false;";
        $('#frm_update_cost_shipper').attr('onsubmit', action);
        $('#frm_update_cost_shipper').submit();
        $('#frm_update_cost_shipper').removeAttr('onsubmit');
        //$('#js-purchase').unbind('click');
        return false;
    });
    
    $('#js-update-bonus-shipper').unbind('click').click(function(){
        
        //validate input
        
        //submit form
        var action = "$(this).ajaxSiteCall('app.updateCostShipper', 'afterUpdateCostShipper(data)'); return false;";
        $('#frm_update_bonus_shipper').attr('onsubmit', action);
        $('#frm_update_bonus_shipper').submit();
        $('#frm_update_bonus_shipper').removeAttr('onsubmit');
        //$('#js-purchase').unbind('click');
        return false;
    });
    
    $('#js-update-penalty-shipper').unbind('click').click(function(){
        
        //validate input
        
        //submit form
        var action = "$(this).ajaxSiteCall('app.updateCostShipper', 'afterUpdateCostShipper(data)'); return false;";
        $('#frm_update_penalty_shipper').attr('onsubmit', action);
        $('#frm_update_penalty_shipper').submit();
        $('#frm_update_penalty_shipper').removeAttr('onsubmit');
        //$('#js-purchase').unbind('click');
        return false;
    });
}

function initSubmitUpdateCostShopper()
{
    $('#js-update-cost-shopper').unbind('click').click(function(){
        
        //validate input
        
        //submit form
        var action = "$(this).ajaxSiteCall('app.updateCostShopper', 'afterUpdateCostShipper(data)'); return false;";
        $('#frm_update_cost_shopper').attr('onsubmit', action);
        $('#frm_update_cost_shopper').submit();
        $('#frm_update_cost_shopper').removeAttr('onsubmit');
        //$('#js-purchase').unbind('click');
        return false;
    });
    
    $('#js-update-bonus-shopper').unbind('click').click(function(){
        
        //validate input
        
        //submit form
        var action = "$(this).ajaxSiteCall('app.updateCostShopper', 'afterUpdateCostShipper(data)'); return false;";
        $('#frm_update_bonus_shopper').attr('onsubmit', action);
        $('#frm_update_bonus_shopper').submit();
        $('#frm_update_bonus_shopper').removeAttr('onsubmit');
        //$('#js-purchase').unbind('click');
        return false;
    });
    
    $('#js-update-penalty-shopper').unbind('click').click(function(){
        
        //validate input
        
        //submit form
        var action = "$(this).ajaxSiteCall('app.updateCostShopper', 'afterUpdateCostShipper(data)'); return false;";
        $('#frm_update_penalty_shopper').attr('onsubmit', action);
        $('#frm_update_penalty_shopper').submit();
        $('#frm_update_penalty_shopper').removeAttr('onsubmit');
        //$('#js-purchase').unbind('click');
        return false;
    });
}

function afterUpdateCostShipper(data)
{
    if (isset(data.status) && data.status == 'success') {
        alert('Đã cập nhật thành công');
    }
    else {
        var messg = isset(data.message) ? data.message : 'Lỗi hệ thống';
        alert(messg);
    }
}

function initSettingBusyTime()
{
    $('.js-setting-busy-time').unbind('click').click(function(){
        //gọi ajax 1 popup nhỏ
        console.log('thiết lập giờ cao điểm');
        //insertPopupCrm(html, ['.js-cancel-add'], '.search-filter-box', true);
    });
}

function initShopperAddBonusByOrder()
{
    $('#js-add-bonus-by-order').unbind('click').click(function(){
        var total_order = $('#js-total-order').val()*1;
        var total_money = $('#js-total-money').val()*1;
        var type_bonus = $('#js-type-bonus').val();
        if (total_order <= 0) {
            alert('Tổng số đơn hàng phải lớn hơn 0');
            return false;
        }
        if (total_money <= 0) {
            alert('Tổng số tiền thưởng phải lớn hơn 0');
            return false;
        }
        var html = '';
        var cnt = $('#js-shopper-total-bonus-order').val()*1;
        if (cnt < 1) {
            cnt = 1;
        }
        else {
            cnt++;
        }
        html += '<div class="row30 mgtop10" id="js-shopper-bonus-order-'+cnt+'">' +
                    '<div class="col2 padleft10">S.L đơn hàng:</div>' +
                    '<div class="col1">' +
                        '<input type="text" name="val[value][bonus_order][total_order][]" class="default-input disable-pointer" value="'+total_order+'" placeholder="S.L đơn hàng">' +
                    '</div>' +
                    '<div class="col1 padleft10">Loại:</div>' +
                    '<div class="col2">' +
                        '<select class="trans-select disable-pointer" name="val[value][bonus_order][type][]">' +
                            '<option value="day" ';
                            if (type_bonus == 'day') {
                                html += ' selected="selected"'
                            }
                            html += '>Ngày</option>' +
                            '<option value="month" ';
                            if (type_bonus == 'month') {
                                html += ' selected="selected"'
                            }
                            html += '>Tháng</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col2 padleft20">Tiền thưởng:</div>' +
                    '<div class="col2">' +
                        '<input type="text" name="val[value][bonus_order][value][]" class="default-input disable-pointer" value="'+total_money+'" placeholder="Số tiền">' +
                    '</div>' +
                    '<div class="col1 padleft10">VNĐ</div>' +
                    '<div class="col1">' +
                        '<div class="btn button-default button-right small-btn js-del-object-setting" data-id="'+cnt+'" data-map="js-shopper-bonus-order">' +
                            'Bỏ' +
                        '</div>' +
                    '</div>' +
                '</div>';
        $('#js-shopper-container-bonus-order').append(html);
        $('.js-cancel-add').click();
        $('#js-shopper-total-bonus-order').val(cnt);
        initDeleteObjectSetting();
    });
}

function initShopperAddBonusByOnlineTime()
{
    $('#js-add-bonus-by-order').unbind('click').click(function(){
        var total_hour = $('#js-total-hour').val()*1;
        var total_money = $('#js-total-money').val()*1;
        var type_bonus = $('#js-type-bonus').val();
        
        if (total_hour <= 0) {
            alert('Tổng số giờ phải lớn hơn 0');
            return false;
        }
        if (total_money <= 0) {
            alert('Tổng số tiền thưởng phải lớn hơn 0');
            return false;
        }
        var html = '';
        var cnt = $('#js-shopper-total-bonus-online-time').val()*1;
        if (cnt < 1) {
            cnt = 1;
        }
        else {
            cnt++;
        }
        html += '<div class="row30 mgtop10" id="js-shopper-bonus-online-time-'+cnt+'">' +
                    '<div class="col2 padleft10">Số giờ:</div>' +
                    '<div class="col1">' +
                        '<input type="text" name="val[value][bonus_online_time][total_hour][]" class="default-input disable-pointer" value="'+total_hour+'" placeholder="Số giờ">' +
                    '</div>' +
                    '<div class="col1 padleft10">Loại:</div>' +
                    '<div class="col2">' +
                        '<select class="trans-select disable-pointer" name="val[value][bonus_online_time][type][]">' +
                            '<option value="day" ';
                            if (type_bonus == 'day') {
                                html += ' selected="selected"'
                            }
                            html += '>Ngày</option>' +
                            '<option value="month" ';
                             if (type_bonus == 'month') {
                                html += ' selected="selected"'
                            }
                            html += '>Tháng</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col2 padleft20">Tiền thưởng:</div>' +
                    '<div class="col2">' +
                        '<input type="text" name="val[value][bonus_online_time][value][]" class="default-input disable-pointer" value="'+total_money+'" placeholder="Số tiền">' +
                    '</div>' +
                    '<div class="col1 padleft10">VNĐ</div>' +
                    '<div class="col1">' +
                        '<div class="btn button-default button-right small-btn js-del-object-setting" data-id="'+cnt+'" data-map="js-shopper-bonus-online-time">' +
                            'Bỏ' +
                        '</div>' +
                    '</div>' +
                '</div>';
        $('#js-shopper-container-bonus-online-time').append(html);
        $('.js-cancel-add').click();
        $('#js-shopper-total-bonus-online-time').val(cnt);
        initDeleteObjectSetting();
    });
}

function initShopperAddBonusByBusyTime()
{
    $('#js-add-bonus-by-order').unbind('click').click(function(){
        var total_hour = $('#js-total-hour').val()*1;
        var total_money = $('#js-total-money').val()*1;
        var type_bonus = $('#js-type-bonus').val();
        
        if (total_hour <= 0) {
            alert('Tổng số giờ phải lớn hơn 0');
            return false;
        }
        if (total_money <= 0) {
            alert('Tổng số tiền thưởng phải lớn hơn 0');
            return false;
        }
        var html = '';
        var cnt = $('#js-shopper-total-bonus-online-time').val()*1;
        if (cnt < 1) {
            cnt = 1;
        }
        else {
            cnt++;
        }
        html += '<div class="row30 mgtop10" id="js-shopper-bonus-busy-time-'+cnt+'">' +
                    '<div class="col2 padleft10">Số giờ:</div>' +
                    '<div class="col1">' +
                        '<input type="text" name="val[value][bonus_busy_time][total_hour][]" class="default-input disable-pointer" value="'+total_hour+'" placeholder="Số giờ">' +
                    '</div>' +
                    '<div class="col1 padleft10">Loại:</div>' +
                    '<div class="col2">' +
                        '<select class="trans-select disable-pointer" name="val[value][bonus_busy_time][type][]">' +
                            '<option value="day" ';
                            if (type_bonus == 'day') {
                                html += ' selected="selected"'
                            }
                            html += '>Ngày</option>' +
                            '<option value="month" ';
                             if (type_bonus == 'month') {
                                html += ' selected="selected"'
                            }
                            html += '>Tháng</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col2 padleft20">Tiền thưởng:</div>' +
                    '<div class="col2">' +
                        '<input type="text" name="val[value][bonus_busy_time][value][]" class="default-input disable-pointer" value="'+total_money+'" placeholder="Số tiền">' +
                    '</div>' +
                    '<div class="col1 padleft10">VNĐ</div>' +
                    '<div class="col1">' +
                        '<div class="btn button-default button-right small-btn js-del-object-setting" data-id="'+cnt+'" data-map="js-shopper-bonus-busy-time">' +
                            'Bỏ' +
                        '</div>' +
                    '</div>' +
                '</div>';
        $('#js-shopper-container-bonus-busy-time').append(html);
        $('.js-cancel-add').click();
        $('#js-shopper-total-bonus-busy-time').val(cnt);
        initDeleteObjectSetting();
    });
}

function initShipperAddBonusByOrder()
{
    $('#js-add-bonus-by-order').unbind('click').click(function(){
        var total_order = $('#js-total-order').val()*1;
        var total_money = $('#js-total-money').val()*1;
        var type_bonus = $('#js-type-bonus').val();
        if (total_order <= 0) {
            alert('Tổng số đơn hàng phải lớn hơn 0');
            return false;
        }
        if (total_money <= 0) {
            alert('Tổng số tiền thưởng phải lớn hơn 0');
            return false;
        }
        var html = '';
        var cnt = $('#js-shipper-total-bonus-order').val()*1;
        if (cnt < 1) {
            cnt = 1;
        }
        else {
            cnt++;
        }
        html += '<div class="row30 mgtop10" id="js-obj-bonus-order-'+cnt+'">' +
                    '<div class="col2 padleft10">S.L đơn hàng:</div>' +
                    '<div class="col1">' +
                        '<input type="text" name="val[value][bonus_order][total_order][]" class="default-input disable-pointer" value="'+total_order+'" placeholder="S.L đơn hàng">' +
                    '</div>' +
                    '<div class="col1 padleft10">Loại:</div>' +
                    '<div class="col2">' +
                        '<select class="trans-select disable-pointer" name="val[value][bonus_order][type][]">' +
                            '<option value="day" ';
                            if (type_bonus == 'day') {
                                html += ' selected="selected"'
                            }
                            html += '>Ngày</option>' +
                            '<option value="month" ';
                            if (type_bonus == 'month') {
                                html += ' selected="selected"'
                            }
                            html += '>Tháng</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col2 padleft20">Tiền thưởng:</div>' +
                    '<div class="col2">' +
                        '<input type="text" name="val[value][bonus_order][value][]" class="default-input disable-pointer" value="'+total_money+'" placeholder="Số tiền">' +
                    '</div>' +
                    '<div class="col1 padleft10">VNĐ</div>' +
                    '<div class="col1">' +
                        '<div class="btn button-default button-right small-btn js-del-object-setting" data-id="'+cnt+'" data-map="js-obj-bonus-order">' +
                            'Bỏ' +
                        '</div>' +
                    '</div>' +
                '</div>';
        $('#js-shipper-container-bonus-order').append(html);
        $('.js-cancel-add').click();
        $('#js-shipper-total-bonus-order').val(cnt);
        initDeleteObjectSetting();
    });
}

function initShipperAddBonusByOnlineTime()
{
    $('#js-add-bonus-by-order').unbind('click').click(function(){
        var total_hour = $('#js-total-hour').val()*1;
        var total_money = $('#js-total-money').val()*1;
        var type_bonus = $('#js-type-bonus').val();
        
        if (total_hour <= 0) {
            alert('Tổng số giờ phải lớn hơn 0');
            return false;
        }
        if (total_money <= 0) {
            alert('Tổng số tiền thưởng phải lớn hơn 0');
            return false;
        }
        var html = '';
        var cnt = $('#js-shipper-total-bonus-online-time').val()*1;
        if (cnt < 1) {
            cnt = 1;
        }
        else {
            cnt++;
        }
        html += '<div class="row30 mgtop10" id="js-obj-bonus-online-time-'+cnt+'">' +
                    '<div class="col2 padleft10">Số giờ:</div>' +
                    '<div class="col1">' +
                        '<input type="text" name="val[value][bonus_online_time][total_hour][]" class="default-input disable-pointer" value="'+total_hour+'" placeholder="Số giờ">' +
                    '</div>' +
                    '<div class="col1 padleft10">Loại:</div>' +
                    '<div class="col2">' +
                        '<select class="trans-select disable-pointer" name="val[value][bonus_online_time][type][]">' +
                            '<option value="day" ';
                            if (type_bonus == 'day') {
                                html += ' selected="selected"'
                            }
                            html += '>Ngày</option>' +
                            '<option value="month" ';
                             if (type_bonus == 'month') {
                                html += ' selected="selected"'
                            }
                            html += '>Tháng</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col2 padleft20">Tiền thưởng:</div>' +
                    '<div class="col2">' +
                        '<input type="text" name="val[value][bonus_online_time][value][]" class="default-input disable-pointer" value="'+total_money+'" placeholder="Số tiền">' +
                    '</div>' +
                    '<div class="col1 padleft10">VNĐ</div>' +
                    '<div class="col1">' +
                        '<div class="btn button-default button-right small-btn js-del-object-setting" data-id="'+cnt+'" data-map="js-obj-bonus-online-time">' +
                            'Bỏ' +
                        '</div>' +
                    '</div>' +
                '</div>';
        $('#js-shipper-container-bonus-online-time').append(html);
        $('.js-cancel-add').click();
        $('#js-shipper-total-bonus-online-time').val(cnt);
        initDeleteObjectSetting();
    });
}

function initShipperAddBonusByBusyTime()
{
    $('#js-add-bonus-by-order').unbind('click').click(function(){
        var total_hour = $('#js-total-hour').val()*1;
        var total_money = $('#js-total-money').val()*1;
        var type_bonus = $('#js-type-bonus').val();
        
        if (total_hour <= 0) {
            alert('Tổng số giờ phải lớn hơn 0');
            return false;
        }
        if (total_money <= 0) {
            alert('Tổng số tiền thưởng phải lớn hơn 0');
            return false;
        }
        var html = '';
        var cnt = $('#js-shipper-total-bonus-online-time').val()*1;
        if (cnt < 1) {
            cnt = 1;
        }
        else {
            cnt++;
        }
        html += '<div class="row30 mgtop10" id="js-obj-bonus-busy-time-'+cnt+'">' +
                    '<div class="col2 padleft10">Số giờ:</div>' +
                    '<div class="col1">' +
                        '<input type="text" name="val[value][bonus_busy_time][total_hour][]" class="default-input disable-pointer" value="'+total_hour+'" placeholder="Số giờ">' +
                    '</div>' +
                    '<div class="col1 padleft10">Loại:</div>' +
                    '<div class="col2">' +
                        '<select class="trans-select disable-pointer" name="val[value][bonus_busy_time][type][]">' +
                            '<option value="day" ';
                            if (type_bonus == 'day') {
                                html += ' selected="selected"'
                            }
                            html += '>Ngày</option>' +
                            '<option value="month" ';
                             if (type_bonus == 'month') {
                                html += ' selected="selected"'
                            }
                            html += '>Tháng</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col2 padleft20">Tiền thưởng:</div>' +
                    '<div class="col2">' +
                        '<input type="text" name="val[value][bonus_busy_time][value][]" class="default-input disable-pointer" value="'+total_money+'" placeholder="Số tiền">' +
                    '</div>' +
                    '<div class="col1 padleft10">VNĐ</div>' +
                    '<div class="col1">' +
                        '<div class="btn button-default button-right small-btn js-del-object-setting" data-id="'+cnt+'" data-map="js-obj-bonus-busy-time">' +
                            'Bỏ' +
                        '</div>' +
                    '</div>' +
                '</div>';
        $('#js-shipper-container-bonus-busy-time').append(html);
        $('.js-cancel-add').click();
        $('#js-shipper-total-bonus-busy-time').val(cnt);
        initDeleteObjectSetting();
    });
}

function afterUpdateFreeShopper(data)
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
    $('#js-update-free-shopper').removeClass('js-button-wait');
    return false;
}

function afterUpdateFreeShipper(data)
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
    $('#js-update-free-shipper').removeClass('js-button-wait');
    return false;
}

$(function(){
    initAppSetting();
});