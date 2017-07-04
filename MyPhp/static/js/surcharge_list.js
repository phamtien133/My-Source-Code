
$(function(){
    initSurcharge();
});

function initSurcharge()
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
    
    $('.js-edit-object').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                links = '/surcharge/add/?id='+id;
                window.location = links;
            }
            
       });
    });

    $('.js-delete-object').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                deleteObject(id);
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
    
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=surcharge&val[status]='+status+'&val[id]='+id+'&val[math]='+Math.random());
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
                alert(error[1]);
                objStatus.attr('title', error[1]);
                objStatus.removeClass('fa fa-spinner fa-pulse');
                objStatus.addClass('fa fa-warning');
            } else {
                //remove old class and add new class
                objStatus.removeClass('fa fa-spinner fa-pulse');
                if (status == 0) {
                    objStatus.addClass('fa fa-close');
                }
                else {
                    objStatus.addClass('fa fa-check');
                }
            }
        }
    };
    http.send(null);
}

function deleteObject(id)
{
    if (!confirm("Bạn có chắc muốn xóa?")) {
        return false;
    }
    var obj = $('#tr_object_' + id);
    var obj_del = obj.find('.js-delete-object');
    obj_del.removeClass('fa fa-trash');
    obj_del.addClass('fa fa-spinner fa-pulse');
    //unbind click
    obj_del.unbind('click');
    http.open('get', '/includes/ajax.php?=&core[call]=core.updateStatus&val[type]=surcharge&val[status]=2&val[id]='+id);
    http.onreadystatechange = function() {
        if(http.readyState == 4){
            var response = http.responseText;
            var error = http.responseText.split('<-errorvietspider->');
            if(error[1] != undefined) {
                //error
                alert(error[1]);
                obj_del.attr('title', error[1]);
                obj_del.removeClass('fa fa-spinner fa-pulse');
                obj_del.addClass('fa fa-warning');
            } else {
                //remove display
                alert('Xóa thành công');
                document.getElementById('tr_object_' + id).innerHTML = '';
                document.getElementById('tr_object_' + id).style.display = "none";               
            }
        }
    };
    http.send(null);
}
