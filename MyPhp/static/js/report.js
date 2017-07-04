function initLoadMultiDatepicker()
{
    /*
    $('#period_from').removeAttr('disabled');
    $('#period_to').removeAttr('disabled');
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var checkin = $('.dpd1').datepicker({
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('.dpd2')[0].focus();
        }).data('datepicker');
    var checkout = $('.dpd2').datepicker({
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
            checkout.hide();
        }).data('datepicker');
    */
}
$(document).ready(function(e) {
    initLoadMultiDatepicker();
    $('#remove_vendor').click(function(){
        $('#vendor_code').val('');
        $('#vendor_name').val('');
        $('#vendor_code').removeAttr('readonly'); 
        $('#vendor_name').removeAttr('readonly');
        return false;
    });
    $('#excel_export').click(function(){
        var filename =  $('#js-file-name').val();
        if (typeof(filename) != 'undefined' && filename != '') {
            $('#excel').val(1);
            $('#js-submit-form').submit();
            return true;
        }
        return false;
    });
    $('#print_export').click(function(){
        return false;
        $('#print').val(1);
        $('#js-submit-form').submit(); 
    });
    
    $('.js-delele-obj').click(function(){
        console.log('xóa');
    });
});