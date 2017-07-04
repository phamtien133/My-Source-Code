
$(function(){
    initCard();
});

function initCard()
{
    $('.js-activity-object').each(function(){
       $(this).unbind('click').click(function(){
           if ($(this).hasClass('fa-check') || $(this).hasClass('fa-close')) {
               //$(this).unbind('click');
                var id = $(this).attr('data-id');
                if (typeof(id) == 'string') {
                    id = parseInt(id);
                }
                if (typeof(id) == 'number' &&  id > 0) {
                    setStatus(id);
                }
           }
            
       });
    });
}

function setStatus(id)
{
    var obj = $('#tr_object_' + id);
    var objStatus = obj.find('.js-activity-object');
    var abc = objStatus.data('id');
    var status = 0;
    if (objStatus.hasClass('fa-check')) {
        status = 1;
    }
    else {
        status = 0;
    }
    
    if (status == 1)
        status = 0;
    else
        status = 1;
        
    //loading
    if (status == 1) {
        objStatus.removeClass('fa fa-close');
    }
    else {
        objStatus.removeClass('fa fa-check');
    }
    objStatus.addClass('fa fa-spinner fa-pulse');
    
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=card.updateStatus&val[ctype]=card&val[status]='+status+'&val[id]='+id+'&val[math]='+Math.random();
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
            objStatus.removeClass('fa fa-spinner fa-pulse');
            objStatus.addClass('fa fa-warning');
            objStatus.attr('title', 'Lỗi hệ thống');
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                //remove old class and add new class
                objStatus.removeClass('fa fa-spinner fa-pulse');
                if (status == 0) {
                    objStatus.addClass('fa fa-close');
                }
                else {
                    objStatus.addClass('fa fa-check');
                }
            }
            else {
                var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                alert(messg);
                objStatus.attr('title', messg);
                objStatus.removeClass('fa fa-spinner fa-pulse');
                objStatus.addClass('fa fa-warning');
            }
        }
    });
}