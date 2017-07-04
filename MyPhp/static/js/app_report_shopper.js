function initAppReport()
{
    $('.tags').tagsInput({
        width: 'auto',
        delimiter: ',',
        'data-default' : "Chọn thêm",
        onChange: function(elem, elem_tags){
            console.log(1);
        },
    });

    $('#js-select-report-shopper').change(function(){
        var valSelect = $(this).val();
        if (valSelect == 'all') {
            $('#js-list-shopper-input').hide();
            return;
        }
        $('#js-list-shopper-input').show();
    });
    
    $('#js-view-report-shopper').click(function(){
        $('#frm_report_shopper').submit();
    });
    
    initPaginationReportShopper();
}

function initPaginationReportShopper()
{
    var sObjContent = $('#js-pagination-report').attr('data-obj_ctn');
    //var sType = $('#js-pagination-report').attr('data-type');
    if (typeof(sObjContent) == 'undefined') {
        return false;
    }
    if ($('#' + sObjContent).length < 1) {
        //not found display
        return;
    }
    
    //Kiểm tra các input cần gửi ajax
    var sType = $('#' + sObjContent).attr('data-type');
    var iBegin = $('#' + sObjContent).attr('data-begin');
    var iEnd = $('#' + sObjContent).attr('data-end');
    var iUserList = $('#' + sObjContent).attr('data-user');
    if (typeof(sType) == 'undefined' || typeof(iBegin) == 'undefined' || typeof(iEnd) == 'undefined' || typeof(iUserList) == 'undefined') {
        return false;
    }
    
    var user_list = '123,2323,23223';
    if (sType == 'all') {
        
    }
    //Click orther page
    $('#js-pagination-report .item-trp-pagi').unbind('click').click(function(){
        if ($(this).hasClass('none')) {
            return;
        }
        var iPage = $(this).attr('data-page')*1;
        if ($(this).hasClass('atv') || iPage < 1) {
            return;
        }
        var objSelect = $(this);
        //Lấy thông tin limit
        var iLimit = 0;
        if ($('#js-pagination-report .item-trp-pagi').length > 0) {
            
        }
        //gọi ajax load page
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.getReportOrderShopper' + '&val[page]=' + iPage + '&val[type]=' + sType + '&val[date_begin]=' + iBegin + '&val[date_end]=' + iEnd + '&val[user_list]=' + iUserList;
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
                console.log(sObjContent);
                if(isset(result.status) && result.status == 'success') {
                    var html = '';
                    if (isset(result.data) && !empty(result.data)) {
                        for (var i in result.data) {
                            var sName = '';
                            if (!empty(result.data[i].shopper)) {
                                sName = result.data[i].shopper.fullname;
                            }
                            else {
                                sName = 'Không có T.T';
                            }
                            html += '<div class="row30 line-bottom padtb10">\
                                <div class="col-trp-1">#'+ result.data[i].order_code +'</div>\
                                <div class="col-trp-2">'+ result.data[i].start_time_txt +'</div>\
                                <div class="col-trp-3">'+ result.data[i].finish_time_txt +'</div>\
                                <div class="col-trp-4">'+ sName +'</div>\
                                <div class="col-trp-5">'+ result.data[i].status_txt +'</div>\
                            </div>';
                        }
                    }
                    else {
                        html != '<div class="row50 line-bottom row-wh padlr20">\
                            Không có đơn hàng nào\
                        </div>';
                    }
                    $('#' + sObjContent).html(html);
                    //Thay đổi trang hiện tại
                    var currentPage = $('#js-pagination-report .item-trp-pagi.atv');
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
    
    //Change page size
    $('#js-pagination-report .pagi-select').change(function(){
        var iPageSize = $(this).val()*1;
        if (iPageSize > 0) {
            //alert(iPageSize);
        }
    });
}

$(function(){
    initAppReport();
})