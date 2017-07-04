var v_waitTime = 100;
var v_oTimer = '';
var vendor = null;
function searchVendorByName(value)
{
    killRequest();
    if (empty(value)){
        hideResults('#list_vendor');
        return;
    }
    var aParams = new Array();
    aParams['key'] = value;
    aParams['type'] = 'name';
    addRequest(aParams, 'store.searchVendor', 'POST', 'json', 'displayVendors()');
}
function searchVendorByCode(value)
{
    killRequest();
    if (empty(value)){
        hideResults('#list_vendor'); 
        return;
    }
    var aParams = new Array();
    aParams['key'] = value;
    aParams['type'] = 'code';
    addRequest(aParams, 'store.searchVendor', 'POST', 'json', 'displayVendors()');
}
function displayVendors()
{
    if(result)
    {
        var data = result.data;
        
        var sHtml = '';
        if(data.length > 0)
        {
            sHtml += '<table class=\"table table-hover general-table\"><thead><tr><th>Mã NCC</th><th>MS Thuế</th><th>Tên NCC</th><th>Địa Chỉ</th></tr></thead><tbody>';
            for(var key in data)
            {
                sHtml += '<tr rel=\"'+data[key]['ma_ncc']+'\"><td>'+data[key]['ma_ncc']+'</td><td>'+data[key]['ms_thue']+'</td><td>'+data[key]['ten']+'</td><td>'+data[key]['dia_chi']+'</td></tr>';  
            }
            sHtml += '</tbody></table>';
            $('#list_vendor').html(sHtml).show();
            selectVendor();
        }
    }
}
function selectVendor()
{
    $('#list_vendor table tbody tr').click(function(){
        killRequest();
        $('#vendor_code').val($(this).find("td").eq(0).html()).attr('readonly','');
        $('#vendor_tax').val($(this).find("td").eq(1).html()).attr('readonly','');
        $('#vendor_name').val($(this).find("td").eq(2).html()).attr('readonly','');
        $('#vendor_addr').val($(this).find("td").eq(3).html()).attr('readonly','');
        $('#list_vendor').html('').hide();
        $('#vendor_editbt').parent().show();
        $('#vendor_addbt').parent().hide();
    });
}
function appendVendor(data)
{
    $('#vendor_code').val(data.vendor_code).attr('readonly','');
    $('#vendor_tax').val(data.vendor_tax).attr('readonly','');
    $('#vendor_name').val(data.vendor_name).attr('readonly','');
    $('#vendor_addr').val(data.vendor_addr).attr('readonly','');
    $('#vendor_editbt').parent().show();
    $('#vendor_addbt').parent().hide();
}
function initSearchVendor()
{
    $('#vendor_name').keyup(function(e){
        clearTimeout(v_oTimer);
        v_oTimer = setTimeout(function(){
            var pvalue = $("#vendor_name").val();
            searchVendorByName(pvalue);
        }, v_waitTime);
    });
    $('#vendor_code').keyup(function(e){
        clearTimeout(v_oTimer);
        v_oTimer = setTimeout(function(){
            var pvalue = $("#vendor_code").val();
            searchVendorByCode(pvalue);
        }, v_waitTime);
    });
}
function clearInitVendor()
{
    $('#vendor_name').unbind('keyup');
    $('#vendor_code').unbind('keyup');
}
function resetVendorForm()
{
    $('#vendor_code').val('');
    $('#vendor_name').val('');
    $('#vendor_tax').val('');
    $('#vendor_addr').val('');
    $('#vendor_action').hide();
    $('.vendor_horver').css({'padding':'0','position':'relative','background-color':'inherit'});
    $('.f_b_hover').remove();
}
$(document).ready(function(e) {
    initSearchVendor();
    $('#vendor_addbt').click(function(){
        $Core.box('manage.callVendorPopup', 500);
    });
    $('#vendor_editbt').click(function(){
         var code = $('#vendor_code').val();
         $Core.box('manage.callVendorPopup', 500, 'vid='+encodeURIComponent(code));
    });

    $('#vendor_cancel').click(function(){
         resetVendorForm();
    });
    
});