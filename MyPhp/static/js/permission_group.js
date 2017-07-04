$(function(){
    $('#js-set-pri-permission').unbind('click').click(function(){
        var id = $(this).attr('data-obj')*1;
        if (id > 0) {
            window.location = '/user/permission/?otype=1&id='+id;
        }
    });
    
    checkAllByParent();
    initSubmitPermission();
});

function checkAllByParent()
{
    $('.js-parent-per').unbind('change').change(function(){
        console.log(222);
    });
}

function initSubmitPermission()
{
    $('#js-sbm-permission').unbind('click').click(function(){
        console.log(1);
        objSelect = $(this);
        //Lấy thông tin các quyền được chọn
        var aTemp  = [];
        var sParam = '';
        $('.js-child-per').each(function(){
            var obj = $(this);
            var iTmp = 0;
            if (obj.hasClass('md-checked')) {
                iTmp = 1;
            }
            sParam += '&val[list]['+obj.attr('data-value')+']=' + iTmp;
        });
        console.log(sParam);
        sParam += '&val[priority]=' + $('#priority').val();   
        sParam += '&val[id]=' + $('#js-obj-id').val();   
        
        objSelect.addClass('js-button-wait');
        
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.setPermissionGroup' + sParam;
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
                objSelect.removeClass('js-button-wait');
            },
            success: function (result) {
                if(isset(result.status) && result.status == 'success') {
                    alert('Cập nhật thành công');
                    objSelect.removeClass('js-button-wait');
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