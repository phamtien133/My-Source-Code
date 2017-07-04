//check and chang type view report
function setView()
{
    var html = '';
    var type = $('#js-report-type').val();
    if (type != 'monthly') {
        type = 'period';
        html = '<div class="col6 padright10">\
                                    <input type="text" name="val[period_from]" class="js-date-time js-zone-from" placeholder="Từ ngày" id="js-date-warehouse-from">\
                                </div>\
                                <div class="col6 padleft10">\
                                    <input type="text" name="val[period_to]" class="js-date-time js-zone-to" placeholder="Đến ngày" id="js-date-warehouse-to">';
        
    }
    else {
        html = '<div class="col6 padright10">\
                                    <input type="text" name="val[monthly]" class="js-date-time js-zone-from" placeholder="Chọn tháng " id="js-date-warehouse-from">\
                                </div>';
    }
    
    $('#js-view').html(html);
    if (type == 'period') {
        $('.js-date-time').datetimepicker({
            timepicker:false,
            format:'m/d/Y'
        });
        $('#js-view-type').html('Thời gian theo kỳ');
    }
    else {
        $('.js-date-time').datetimepicker({
            timepicker:false,
            format:'m/Y'
        });
        $('#js-view-type').html('Thời gian theo tháng');
    }
}

$('#js-view-type').click(function(){
    var type = $('#js-report-type').val();
    if (type != 'monthly') {
        $('#js-report-type').val('monthly');
    }
    else {
        $('#js-report-type').val('period');
    }
    setView();
});
setView();