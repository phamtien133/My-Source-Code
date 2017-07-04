
$(function(){
    if ($('#js-card-value-id').val() < 1) {
        initSubmitCardValue();
    }
    
    initTypeNumber();
})

function initSubmitCardValue()
{
    $('#js-btn-create-card-value').unbind('click').click(function(){
        var objSelect = $(this);
        var _name = $('#js-name').val();
        var sParam = '';
        if (_name == '') {
            alert('Chưa nhập tên mệnh giá');
            return false;
        }
        sParam += '&val[name]=' + unescape(_name);
        
        var _name_code = $('#js-name_code').val();
        if (_name_code.length != 2) {
            alert('Mã tên phải là chuỗi có 2 ký tự chữ số');
            return false;
        }
        sParam += '&val[name_code]=' + _name_code;
        var _value = $('#js-value').val();
        if (_value <= 0) {
            alert('Giá trị mệnh giá phải lớn hơn 0');
            return false;
        }
        sParam += '&val[value]=' + _value;
        
        objSelect.addClass('js-button-wait');
        sParam = '&'+ getParam('sGlobalTokenName') + '[call]=card.createCardValue' + sParam;
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
                    window.location = '/card/value/';
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
