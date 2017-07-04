
function initLoadOrderSetting()
{
    $('.js-datetime').datetimepicker({
        format: "H:i",
        formatTime: "H:i",
        step: 1,
        datepicker:false
    });
    $('#js-add-more-times').unbind('click').click(function(){
         addMoreTimes();
    });
    $('#js-submit-times').unbind('click').click(function(){
        initSubmitOrderTime();
    });
    $('.js-delete-time-chose').unbind('click').click(function(){
        if (confirm('Bạn muốn xóa khung thời gian này?')) {
            $(this).parents('.row30').remove();
        }
    });
}
function initSubmitOrderTime()
{
    $('.list-item .row30').each(function(i, item){
        var index = $(this).attr('data-id');
        var timefrom = $('#js-min-from-time-'+index).val();
        var tofrom = $('#js-min-to-time-'+index).val();
        
    });
    
    $('#js-f-order-time').submit();
    return false;
}
function addMoreTimes()
{
    if (typeof(lastindex) == 'undefined') {
        lastindex = 0;
    }
    else {
        lastindex++;
    }
    var content = '<div class="row30 padbot10" data-id="'+lastindex+'">' +
        '<div class="col2"></div>' +
        '<div class="col1">Từ</div>' +
        '<div class="col2">' +
            '<input class="default-input js-datetime" type="text" id="js-min-from-time-'+lastindex+'" name="val[list]['+lastindex+'][from]" value="">'  + 
        '</div>' + 
        '<div class="col1"></div>'+
        '<div class="col1">Đến</div>'+
        '<div class="col2">' +
            '<input class="default-input js-datetime" type="text" id="js-min-to-time-'+lastindex+'" name="val[list]['+lastindex+'][to]" value="">'+ 
        '</div>'+
        '<div class="col1"></div>'+
        '<div class="2">'+
            '<a href="javascript:void(0);" title="Xóa" class="js-delete-time-chose"><img src="'+oParams['sJsImage']+'/styles/web/global/images/delete.png" /></a>'+
        '</div>'+
    '</div>';
    $('.list-item').append(content);
    $('#js-min-to-time-'+ lastindex).datetimepicker({
        format: "H:i",
        formatTime: "H:i",
        step: 1,
        datepicker:false
    });
    $('#js-min-from-time-'+ lastindex).datetimepicker({
        format: "H:i",
        formatTime: "H:i",
        step: 1,
        datepicker:false
    });
}
$(document).ready(function(e) {
    initLoadOrderSetting();
});