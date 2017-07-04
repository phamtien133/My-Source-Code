function loadInitManage()
{
    initImportPage();
    $('#js-submit-button').unbind('click').click(function(){
        //get warehouse
        var id = $('#js-warehouse').val();
        if (typeof(id) != 'undefined') {
            $('#js-warehouse-value').val(id);
        }
        $('#js-file-name').val('');
        
        var type = $(this).data('type');
        if (typeof(type) != 'undefined' && type == 'permission') {
            //set value permission
            $('.js-permission').each(function(){
                var checked = 0;
                if ($(this).hasClass('md-checked')) {
                    checked = 1;
                }
                $(this).find('input.js-value').val(checked);
            });
        }
         $('#js-submit-form').submit();
    });
    
    $('#js-cancel-button').unbind('click').click(function(){
        return false;
    });
    
    $('.js-date-time').datetimepicker({
        timepicker:false,
        format:'m/d/Y'
    });
    
    
    $('#js-add-quantitative').click(function(){
        //show popup quantitative
        showPopupAddQuantitative(0);
    });
}

/* ajax call popup quantitative */
function showPopupAddQuantitative(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=store.getBlockQuantitative' + '&val[id]=' + id;
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
            alert('Error');
        },
        success: function (data) {
            content = data;
            insertPopupCrm(content, ['.js-canel-popup'], '.page-ads-edit', true);
            initSbmQuantitative();
        }
    });
}

function afterAddQuantitative(data)
{
    if (typeof(data.status) != 'undefined')
    {
        if (data.status == 'error') {
            //error
            var i = 0;
            var html = '<div class="row30 padtb10 dialog-err">Đã có lỗi xảy ra</div>';
            for (i in data.message) {
                html += '<div class="row30">' + data.message[i] +'</div>';
            }
            $('#js-notice').html(html);
            initSbmQuantitative();
            return false;
        }
        else {
            //success
            var html = '<div class="row30 padtb10 dialog-success">Đã tạo thành công</div>';
            $('#js-notice').html(html);
            //add into list vendor
        }
    }
    else {
        //system error
        
    }
}

function initSbmQuantitative()
{
    $('#js-submit-add-quantitative').click(function(){
        var action = "$(this).ajaxSiteCall('store.addQuantitative', 'afterAddQuantitative(data)'); return false;";
        $('#js-frm-quantitative').attr('onsubmit', action);
        $('#js-frm-quantitative').submit();
        $('#js-frm-quantitative').removeAttr('onsubmit');
        $('#js-submit-add-quantitative').unbind('click');
        return false;
    });
}

function initImportPage()
{
    $('#js-select-vendor').change(function(){
        $('#js-tax-code').val($(this).attr('data-code'));
        $('#js-vendor-name').val($(this).find(":selected").text());
        $('#js-vendor-address').val($(this).attr('data-address'));
        return false;
    });
    $('#vendor_addbt').click(function(){
        $Core.box('manage.callVendorPopup', 500);
    });
    
}
$(document).ready(function(e) {
    loadInitManage();
});