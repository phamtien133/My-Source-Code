function updateHomePosition()
{
    if (oParams['sController'] == 'display.home') {
        $('.update-position').unbind('click').click(function(){
            if ($('#js-js-chagne-position').val() != 1) {
                alert('Không có dữ liệu cập nhật.');
                return false;
            }
            $('.update-position').addClass('js-button-wait');
            var action = "$(this).ajaxSiteCall('display.updateHomePosition', 'updateHomePositionCallback(data)'); return false;";
            $('#js-update-home-position').attr('onsubmit', action);
            $('#js-update-home-position').submit();
            $('#js-update-home-position').removeAttr('onsubmit'); 
        });
        $('.js-home-child').unbind('click').click(function(){
            var item_id = $(this).attr('data-id');
            if (!item_id) {
                alert('Lỗi dữ liệu, vui lòng tải lại trang hoặc liên hệ quản trị.');
                return false;
            }
            var path = oParams['sJsMain']+ 'display/home/'+ item_id;
            window.location = path;
        });
    }
    
    if (oParams['sController'] == 'display.home.category') {
        $('.update-position').unbind('click').click(function(){
            if ($('#js-js-chagne-position').val() != 1) {
                alert('Không có dữ liệu cập nhật.');
                return false;
            }
            $('.update-position').addClass('js-button-wait');
            var action = "$(this).ajaxSiteCall('display.updateDetailPosition', 'updatePositionCallback(data)'); return false;";
            $('#js-update-home-position').attr('onsubmit', action);
            $('#js-update-home-position').submit();
            $('#js-update-home-position').removeAttr('onsubmit'); 
        });
        $('.js-home-child').unbind('click').click(function(){
            var parent_id = $(this).attr('data-parent');
            var item_id = $(this).attr('data-id');
            if (!item_id) {
                alert('Lỗi dữ liệu, vui lòng tải lại trang hoặc liên hệ quản trị.');
                return false;
            }
            var path = oParams['sJsMain']+ 'display/home/'+ parent_id + '/' + item_id;
            window.location = path;
        });
    }
    
    if (oParams['sController'] == 'display.home.article') {
        $('.update-position').unbind('click').click(function(){
            if ($('#js-js-chagne-position').val() != 1) {
                alert('Không có dữ liệu cập nhật.');
                return false;
            }
            $('.update-position').addClass('js-button-wait');
            var action = "$(this).ajaxSiteCall('display.updateArticlePosition', 'updatePositionCallback(data)'); return false;";
            $('#js-update-home-position').attr('onsubmit', action);
            $('#js-update-home-position').submit();
            $('#js-update-home-position').removeAttr('onsubmit'); 
        });
        $('.js-go-to-position').unbind('click').click(function(){
            var itemid = $(this).attr('data-id');
            // gọi popup để chọn vị trí đến
            content = '<div class="container js-change-position pad20" style="width: 400px">\
                            <div class="content-box panel-shadow mgbt20">\
                                <div class="box-title">\
                                    Chọn vị trí thay đổi\
                                </div>\
                                <div class="box-inner">\
                                    <div id="js-notice"></div>\
                                    <div class="row30 padtb10">\
                                        <div class="col12">\
                                            <div class="col12 ed0 mgbt10">\
                                                <div class="col5 ed1 ed11">Chọn vị trí</div>\
                                                <div class="col7 ed2 ed21">\
                                                    <input type="text" class="form-control" id="js-position-change">\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="row30 padtop">\
                                        <div class="row30">\
                                            <div class="col6"></div>\
                                            <div class="col3 padleft10">\
                                                <div class="button-default js-cancel-popup">Hủy</div>\
                                            </div>\
                                            <div class="col3 padleft10">\
                                                <input type="hidden" id="js-old-value" value="'+ itemid +'">\
                                                <div class="button-blue" id="js-change-position-bt">Thay đổi</div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>';
            insertPopupCrm(content, ['.js-cancel-popup'], '.js-change-position', true);
            initGoToPosition();
        });
    }
    
    if (oParams['sController'] == 'display.category.index') {
        $('#display-category').select2();
        $('.manage-category-display').click(function(){
            catid = $('#display-category').val();
            if (empty(catid) || catid == 0) {
                alert('Vui lòng chọn danh mục cần quản lý.');
                return false;
            }
            else {
                var path = oParams['sJsMain']+ 'display/category/'+ catid;
                window.location = path;
            }
        });
    }

    if (oParams['sController'] == 'display.category.display') {
        $('.update-position').unbind('click').click(function(){
            if ($('#js-js-chagne-position').val() != 1) {
                alert('Không có dữ liệu cập nhật.');
                return false;
            }
            $('.update-position').addClass('js-button-wait');
            var action = "$(this).ajaxSiteCall('display.updatePosition', 'updatePositionCallback(data)'); return false;";
            $('#js-update-home-position').attr('onsubmit', action);
            $('#js-update-home-position').submit();
            $('#js-update-home-position').removeAttr('onsubmit'); 
        });
    }
    
    if (oParams['sController'] == 'display.vendor.index') {
        $('#display-vendor').select2();
        $('.manage-vendor-display').click(function(){
            catid = $('#display-vendor').val();
            if (empty(catid) || catid == 0) {
                alert('Vui lòng chọn siêu thị cần quản lý.');
                return false;
            }
            else {
                var path = oParams['sJsMain']+ 'display/vendor/'+ catid;
                window.location = path;
            }
        });
    }
    
    if (oParams['sController'] == 'display.vendor.display') {
        $('.update-position').unbind('click').click(function(){
            if ($('#js-js-chagne-position').val() != 1) {
                alert('Không có dữ liệu cập nhật.');
                return false;
            }
            $('.update-position').addClass('js-button-wait');
            var action = "$(this).ajaxSiteCall('display.updatePosition', 'updatePositionCallback(data)'); return false;";
            $('#js-update-home-position').attr('onsubmit', action);
            $('#js-update-home-position').submit();
            $('#js-update-home-position').removeAttr('onsubmit'); 
        });
        $('.js-view-article').unbind('click').click(function(){
             var item_id = $(this).attr('data-id');
             var parent_id = $(this).attr('data-parent');
            if (!item_id || !parent_id) {
                alert('Lỗi dữ liệu, vui lòng tải lại trang hoặc liên hệ quản trị.');
                return false;
            }
            var path = oParams['sJsMain']+ 'display/vendor/'+ parent_id + '/' + item_id;
            window.location = path;
        });
    }
    
    if (oParams['sController'] == 'display.vendor.article') {
        $('.update-position').unbind('click').click(function(){
            if ($('#js-js-chagne-position').val() != 1) {
                alert('Không có dữ liệu cập nhật.');
                return false;
            }
            $('.update-position').addClass('js-button-wait');
            var action = "$(this).ajaxSiteCall('display.updateArticlePosition', 'updatePositionCallback(data)'); return false;";
            $('#js-update-home-position').attr('onsubmit', action);
            $('#js-update-home-position').submit();
            $('#js-update-home-position').removeAttr('onsubmit'); 
        });
        
    }
    
    $('.js-display-item').unbind('click').click(function(){
        var sobj = $(this); 
        var status = $(this).attr('data-status');
        var id =  $(this).attr('data-id');
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=display.updateDisplay' + '&val[id]=' + id;
        sParams += '&val[status]=' + status;
        if (oParams['sController'] == 'display.vendor.display') {
            sParams += '&val[vendor]=1';
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
                        sobj.attr('data-status', 1);
                    }
                    if (status == 1) {
                        // chuyển qua on. 
                        sobj.removeClass('icon-status-off');
                        sobj.addClass('icon-status-on');
                        sobj.attr('data-status', 0);
                    }
                }
                
            }
        });
    });
    
    $('.js-display-detail').unbind('click').click(function(){
        var sobj = $(this); 
        var status = $(this).attr('data-status');
        var id =  $(this).attr('data-id');
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=display.updateDisplayDetail' + '&val[id]=' + id;
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
                        sobj.attr('data-status', 1);
                    }
                    if (status == 1) {
                        // chuyển qua on. 
                        sobj.removeClass('icon-status-off');
                        sobj.addClass('icon-status-on');
                        sobj.attr('data-status', 0);
                    }
                }
                
            }
        });
    });
    
    $('.js-display-article').unbind('click').click(function(){
        var sobj = $(this); 
        var status = $(this).attr('data-status');
        var id =  $(this).attr('data-id');
        var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=display.updateDisplayArticle' + '&val[id]=' + id;
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
                        sobj.attr('data-status', 1);
                    }
                    if (status == 1) {
                        // chuyển qua on. 
                        sobj.removeClass('icon-status-off');
                        sobj.addClass('icon-status-on');
                        sobj.attr('data-status', 0);
                    }
                }
                
            }
        });
    });
}
function initGoToPosition()
{
    $('#js-change-position-bt').unbind('click').click(function(){
        var oldid =  $('#js-old-value').val();
        var newid = $('#js-position-change').val();
        appendid  = parseInt(newid) + 1;
        
        var html =  $('.js-row-order[data-id="'+oldid+'"]').html();
        html = '<li class="r js-row-order" data-id="'+newid+'">' + html + '</li>'
        $('.js-row-order[data-id="'+appendid+'"]').after(html);
        $('.js-row-order[data-id="'+oldid+'"]').remove();
        // init lại các sự kiện.
        updateHomePosition();
        $('#js-js-chagne-position').val(1);
        var iCnt = $('.js_mp_order').length;
        $('.js_mp_order').each(function() {
            this.value = iCnt;
            $(this).parents('.js-row-order').find('.item-position').html(iCnt);
            iCnt--;
        });
        $('.js-cancel-popup').click();
    });
}
function updatePositionCallback(data)
{
    $('.update-position').removeClass('js-button-wait');
    if (data.status == 'error') {
        alert(data.message);
        return false;
    }
    else {
        alert('Cập nhật thành công.');
        $('#js-js-chagne-position').val(0);
        return false;
    }
}
function updateHomePositionCallback(data)
{
    $('.update-position').removeClass('js-button-wait');
    if (data.status == 'error') {
        alert(data.message);
        return false;
    }
    else {
        alert('Cập nhật thành công.');
        $('#js-js-chagne-position').val(0);
        return false;
    }
}
$(function() {
    $('.sortable ul').sortable({
            axis: 'y',
            update: function(element, ui)
            {
                $('#js-js-chagne-position').val(1);
                var iCnt = $('.js_mp_order').length;
                $('.js_mp_order').each(function()
                {
                    this.value = iCnt;
                    $(this).parents('.js-row-order').find('.item-position').html(iCnt);
                    iCnt--;
                });
            },
            opacity: 0.4
        }
    );
    updateHomePosition();
});