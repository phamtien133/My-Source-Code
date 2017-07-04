
$(function(){
    initTypeNumber();
    initCreateSurcharge();
    initSubmitCreateSurchare();
});

function initCreateSurcharge()
{
    var _id = $('#js-type-apply').val()*1;
    if (_id == 0) {
        $('#js-by-vendor').addClass('none');
        $('#js-by-location').removeClass('none');
    }
    else {
        $('#js-by-vendor').removeClass('none');
        $('#js-by-location').addClass('none');
    }
    
    $('#js-type-apply').unbind('change').change(function(){
        var _val = $(this).val();
        if (_val == 0) {
            $('#js-by-vendor').addClass('none');
            $('#js-by-location').removeClass('none');
        }
        else {
            $('#js-by-vendor').removeClass('none');
            $('#js-by-location').addClass('none');
        }
    });
}

function initSubmitCreateSurchare()
{
    $('#js-btn-create-surcharge').unbind('click').click(function(){
        var objSelect = $(this);
        var _apply = $('#js-type-apply').val();
        var sParam = '';
        sParam += '&val[apply]=' + _apply;
        var _val_id = $('#js-surcharge-id').val()*1;
        if (_val_id < 1) {
            if (_apply == 1) {
                var _vendor = $('#js-val-by-vendor').val();
                if (_vendor < 1) {
                    alert('Vui lòng chọn nhà cung cấp!');
                    return false;
                }
                sParam += '&val[vendor]=' + _vendor;
            }
            else {
                var _location = $('#js-val-by-location').val();
                if (_location < 1) {
                    alert('Vui lòng chọn khu vực');
                    return false;
                }
                sParam += '&val[area]=' + _location;
            }
        }
        else {
            sParam += '&val[id]=' + _val_id;
        }
        
        var _total_money = $('#js-value-surcharge').val()*1;
        var _total_coin = $('#js-coin-surcharge').val()*1;
        if (_total_money == 0 && _total_coin == 0) {
            alert('Vui lòng nhập mức phí dịch vụ');
            return false;
        }
        
        if (_total_money < 0 || _total_coin < 0) {
            alert('Mức phí phải lớn hơn 0');
            return false;
        }
        
        sParam += '&val[money]=' + _total_money;
        sParam += '&val[coin]=' + _total_coin;
        
        objSelect.addClass('js-button-wait');
        sParam = '&'+ getParam('sGlobalTokenName') + '[call]=surcharge.createSurCharge' + sParam;
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParam,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
                alert('Lỗi hệ thống');
                objSelect.removeClass('js-button-wait');
            },
            success: function (result) {
                if(isset(result.status) && result.status == 'success') {
                    window.location = '/surcharge/';
                }
                else {
                    var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                    alert(messg);
                    objSelect.removeClass('js-button-wait');
                }
            }
        });
    });
}
