function initSelectVendor()
{
    $('#js-select-vendor-bt').click(function(){
        vendor = $('#js-select-vendor').val();
        $('.alert-success').addClass('none');
        $('.alert-danger').addClass('none');
        if (vendor < 0) {
            $('.alert-danger').html('Vui lòng chọn siêu thị');
            $('.alert-danger').removeClass('none');
            return false;
        }
        
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.setPermission';
        sParams += '&val[vendor_id]=' + encodeURIComponent(vendor);
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "GET",
            data: sParams,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
            } ,
            success: function (data) {
                selectVendorCallback(data);
            }
        });
        return false;
    });
}
function selectVendorCallback(data)
{
    if(data.status == 'error') {
        $('.alert-danger').html(data.message);
        $('.alert-danger').removeClass('none');
        $('.alert-success').addClass('none');
        return false;
    }
    
    if(data.status == 'success') {
        $('.alert-success').html('Chọn thành công.');
        $('.alert-danger').addClass('none');
        $('.alert-success').removeClass('none');
        window.location.reload();
    }
}
$(document).ready(function(e) {
    initSelectVendor();
});