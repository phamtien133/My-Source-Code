var assign_select = 0;
function initLoadAppAssign()
{
    $('.js-assign-shipper').unbind('click').click(function(){
        var _id = $(this).attr('data-id');
        var content = '<div class="container page-transport-popup pad20 js-select-assign-shipper" style="width: 800px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Assign Shipper\
                    </div>\
                    <div class="box-inner">\
                        <div class="message"></div>\
                        <div class="row30 padtb10 ">\
                            <div class="col12">\
                                <div class="col9">\
                                    <input type="text" placeholder="Tìm theo email" id="js-search-shipper-input" class="default-input"/>\
                                </div>\
                                <div class="col3 padleft10">\
                                    <div id="js-search-shipper" data-id="'+_id+'" data-type="shipper" class="button-blue">Tìm</div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="row30 padtop10 line-bottom">\
                            <div class="col12">\
                                <div class="sub-blue-title">Danh sách đối tác</div>\
                            </div>\
                        </div>\
                        <div class="row30 padtop10 line-bottom">\
                            <div class="col12 padlr10 ">\
                                <div class="col1">ID</div>\
                                <div class="col3">Tên</div>\
                                <div class="col4">Email</div>\
                                <div class="col4">SĐT</div>\
                            </div>\
                        </div>\
                        <div class=" js-list-partner">\
                            <div class="row30 padtop10 not-search" >\
                                Vui lòng thực hiện tìm kiếm để lấy danh sách đối tác\
                            </div>\
                        </div>\
                        <div class="row30 padtop10">\
                            <div class="row30">\
                                <div class="col6">\
                                </div>\
                                <div class="col3 padright10">\
                                    <div class="button-blue" id="js-assign-shipper-bt">Assign</div>\
                                </div>\
                                <div class="col3">\
                                    <div class="button-default" id="js-close-assing-shipper">Đóng</div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>';
        insertPopupCrm(content, ['#js-close-assing-shipper'], '.js-select-assign-shipper', true);
        iniSearchAppUser();
        assignAppUser();
    });
    
    $('.js-assign-shopper').unbind('click').click(function(){
        var _id = $(this).attr('data-id');
        var content = '<div class="container page-transport-popup pad20 js-select-assign-shipper" style="width: 800px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Assign Shopper\
                    </div>\
                    <div class="box-inner">\
                        <div class="message"></div>\
                        <div class="row30 padtb10 ">\
                            <div class="col12">\
                                <div class="col9">\
                                    <input type="text" placeholder="Tìm theo email" id="js-search-shipper-input" class="default-input"/>\
                                </div>\
                                <div class="col3 padleft10">\
                                    <div id="js-search-shipper" data-id="'+_id+'" data-type="shopper" class="button-blue">Tìm</div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="row30 padtop10 line-bottom">\
                            <div class="col12">\
                                <div class="sub-blue-title">Danh sách đối tác</div>\
                            </div>\
                        </div>\
                        <div class="row30 padtop10 line-bottom">\
                            <div class="col12 padlr10 ">\
                                <div class="col1">ID</div>\
                                <div class="col3">Tên</div>\
                                <div class="col4">Email</div>\
                                <div class="col4">SĐT</div>\
                            </div>\
                        </div>\
                        <div class=" js-list-partner">\
                            <div class="row30 padtop10 not-search" >\
                                Vui lòng thực hiện tìm kiếm để lấy danh sách đối tác\
                            </div>\
                        </div>\
                        <div class="row30 padtop10">\
                            <div class="row30">\
                                <div class="col6">\
                                </div>\
                                <div class="col3 padright10">\
                                    <div class="button-blue" id="js-assign-shopper-bt">Assign</div>\
                                </div>\
                                <div class="col3">\
                                    <div class="button-default" id="js-close-assing-shipper">Đóng</div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>';
        insertPopupCrm(content, ['#js-close-assing-shipper'], '.js-select-assign-shipper', true);
        iniSearchAppUser();
        assignAppUser();
    });
}
function iniSearchAppUser()
{
    $('#js-search-shipper').unbind('click').click(function(){
        var _keyword = $('#js-search-shipper-input').val();
        var _rid = $(this).attr('data-id');
        var _type = $(this).attr('data-type');
        if (empty(_keyword)) {
            $('.message').html('Vui lòng nhập nội dung tìm kiếm.');
            $('.message').addClass('dialog-err');
            return false;
        }
        $('.message').html('');
        $('.message').removeClass('dialog-err');
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.searchUser';
        sParams += '&val[item_id]=' + encodeURIComponent(_rid);
        sParams += '&val[atype]=' +encodeURIComponent(_type);
        sParams += '&val[q]=' + encodeURIComponent(_keyword);
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
                    $('.message').html(data.message);
                    $('.message').addClass('dialog-err');
                }
                else {
                    displayAppUser(data.data);
                }
            }
        });
    });
}
function displayAppUser(data)
{
    var content = '';
    // xóa các dòng dữ liệu cũ
    $('.list-appuser').each(function(){
         if (!$(this).hasClass('not-search')) {
            $(this).remove();
         }
    });
    if (empty(data)) {
        content = '<div class="row30 padtop10 dialog-empty list-appuser" >\
            Không có đối tác được tìm thấy.\
        </div>';
    }
    else {
        for (var i in data) {
            content += '<div class="row30 line-bottom list-appuser" id="js-appuser-'+data[i]['user_id']+'" data-id="'+data[i]['user_id']+'">\
                        <div class="col12 padlr10">\
                            <div class="col1">'+data[i]['id']+'</div>\
                            <div class="col3">'+data[i]['info']['fullname']+'</div>\
                            <div class="col4">'+data[i]['info']['email']+'</div>\
                            <div class="col4">'+data[i]['info']['phone_number']+'</div>\
                        </div>\
                    </div>';
        }
    }
    $('.js-list-partner').append(content);
    $('.js-list-partner .not-search').hide();
    // chọn để assign
    $('.list-appuser').unbind('click').click(function(){
         $('.list-appuser').removeClass('atv');
         $(this).addClass('atv');
         assign_select = $(this).attr('data-id');
    });
}
function assignAppUser()
{
    $('#js-assign-shipper-bt').unbind('click').click(function(){
        if (assign_select < 1) {
            $('.message').html('Vui lòng chọn đối tác trước khi assign.');
            $('.message').addClass('dialog-err');
            return false;
        }
        //  check lại 1 lần nữa id select
        var _stmp = 0;
        var _total = 0;
        $('.list-appuser').each(function(){
             if ($(this).hasClass('atv')) {
                 _total++;
                 _stmp = $(this).attr('data-id');
             }
        });
        if (_total > 1) {
            $('.message').html('Có lỗi xảy ra, không thể chọn 2 đối tác một lúc.');
            $('.message').addClass('dialog-err');
            return false;
        }
        if (_stmp != assign_select) {
            $('.message').html('Có lỗi xảy ra, vui lòng chọn lại đối tác.');
            $('.message').addClass('dialog-err');
            return false;
        }
        var rid = $('#js-search-shipper').attr('data-id');
        if (rid < 1) {
            $('.message').html('Có lỗi xảy ra, vui lòng tải lại trang.');
            $('.message').addClass('dialog-err');
            return false;
        }
        // thực hiện ajax assign
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.assignAppUser';
        sParams += '&val[route_id]=' + encodeURIComponent(rid);
        sParams += '&val[atype]=shipper';
        sParams += '&val[user_id]=' + encodeURIComponent(assign_select);
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
                    $('.message').html(data.message);
                    $('.message').addClass('dialog-err');
                }
                else {
                    $('.message').html('Thao tác thành công.');
                    $('.message').addClass('dialog-success');
                    $('#js-close-assing-shipper').click();
                    window.location.reload();
                }
            }
        });
    });
    
    $('#js-assign-shopper-bt').unbind('click').click(function(){
        if (assign_select < 1) {
            $('.message').html('Vui lòng chọn đối tác trước khi assign.');
            $('.message').addClass('dialog-err');
            return false;
        }
        //  check lại 1 lần nữa id select
        var _stmp = 0;
        var _total = 0;
        $('.list-appuser').each(function(){
             if ($(this).hasClass('atv')) {
                 _total++;
                 _stmp = $(this).attr('data-id');
             }
        });
        if (_total > 1) {
            $('.message').html('Có lỗi xảy ra, không thể chọn 2 đối tác một lúc.');
            $('.message').addClass('dialog-err');
            return false;
        }
        if (_stmp != assign_select) {
            $('.message').html('Có lỗi xảy ra, vui lòng chọn lại đối tác.');
            $('.message').addClass('dialog-err');
            return false;
        }
        var tid = $('#js-search-shipper').attr('data-id');
        if (tid < 1) {
            $('.message').html('Có lỗi xảy ra, vui lòng tải lại trang.');
            $('.message').addClass('dialog-err');
            return false;
        }
        
        // thực hiện ajax assign
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.assignAppUser';
        sParams += '&val[order_id]=' + encodeURIComponent(tid);
        sParams += '&val[atype]=shopper';
        sParams += '&val[user_id]=' + encodeURIComponent(assign_select);
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
                    $('.message').html(data.message);
                    $('.message').addClass('dialog-err');
                }
                else {
                    $('.message').html('Thao tác thành công.');
                    $('.message').addClass('dialog-success');
                    $('#js-close-assing-shipper').click();
                    window.location.reload();
                }
            }
        });
    });
}
$(function(){
    initLoadAppAssign();
});