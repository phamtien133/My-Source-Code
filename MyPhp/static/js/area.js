
function init()
{
    $('.js-activity').each(function(){
       $(this).unbind('click').click(function(){
            $(this).unbind('click');
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            if (typeof(id) == 'string') {
                id = parseInt(id);
            }
            if (typeof(status) == 'string') {
                status = parseInt(status);
            }
            if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
                hien_thi(id, status);
            }
       });
    });

    $('.js-edit').each(function(){
       $(this).unbind('click').click(function(){
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                links = '/area/edit/?id='+id;
                window.location = links;
            }
            
       });
    });

    $('.js-delete').each(function(){
       $(this).unbind('click').click(function(){
           $(this).unbind('click');
            var id = $(this).data('id');
            if (typeof(id) == 'number' &&  id > 0) {
                xoa_shop_custom(id);
            }
       });
    });

    $('#js-check-all').unbind('click').click(function(){
        var check = $(this).attr('aria-checked');
        if (typeof(check) == 'string') {
            if (check == 'true') {
                //uncheck all
            }
            else {
                //check all
            }
        }
    });
    
    //init for degree of area
    var val = $('#js-degree').val();
    changeArea(val);
    $('#js-degree').change(function(){
        var val = $(this).val();
        changeArea(val);
    });
    
    $('#js-country').change(function(){
        var val = $(this).val();
        loadCities(val);
    });
}

function initSort()
{
    $('.js-sort-by').each(function(){
       $(this).unbind('click').click(function(){
           var sort = $(this).data('sort');
           if (typeof(sort) == 'number' && typeof(sort_path) != 'undefined') {
               var tag = $(this).find('div.js-icon-sort');
               var hasSort = tag.hasClass('ic3');
               if (hasSort) {
                   sort = sort - 1;
               }
               sort_link = sort_path +'&sap_xep=' + sort;
               window.location = sort_link;
           }
           return false;
       });
    });
}

function changeStatus(id , status)
{
    var obj = $('#js-status-object-' + id);
    
    if (status == 1) {
        obj.removeClass('ic4');
        obj.addClass('ic1');
        
        obj.attr('data-status', 1);
        
        obj.find('.sp').find('.p').html = 'Chọn để hủy kích hoạt';
    }
    else {
        obj.removeClass('ic1');
        obj.addClass('ic4');
        
        obj.attr('data-status', 0);
        
        obj.find('.sp').find('.p').html = 'Chọn để kích hoạt';
    }
    obj.bind('click', function(){
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status');
        if (typeof(id) == 'string') {
            id = parseInt(id);
        }
        if (typeof(status) == 'string') {
            status = parseInt(status);
        }
        if (typeof(id) == 'number' &&  id > 0 && typeof(status) == 'number') {
            hien_thi(id, status);
        }
    });
}

function changeArea(value)
{
    //disable select box
    if (typeof(value) == 'string') {
        value = parseInt(value);
    }
    
    if (typeof(value) != 'number' || value < 0 ||value > 3) {
        return false;
    }
    
    if (value == 1 || value == 0) {
        $('#js-country').attr('disabled', 'disabled');
        $('#js-city').attr('disabled', 'disabled');
    }
    else if (value == 2) {
        $('#js-city').attr('disabled', 'disabled');
        $('#js-country').removeAttr('disabled');
    }
    else if (value == 3) {
        $('#js-country').removeAttr('disabled');
        $('#js-city').removeAttr('disabled');
    }
}

function loadCities(value)
{
    if (typeof(value) == 'string') {
        value = parseInt(value);
    }
    
    if (typeof(value) != 'number' || value < 1) {
        return false;
    }
    
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=area.loadCities' + '&val[id]=' + value;
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
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                var data = result.data;
                if (typeof(data) != 'undefined') {
                    var html = '<option value="0">Chọn Tỉnh thành</option>';
                    for (i in data) {
                        html += '<option value="'+ data[i]['id'] +'">' + data[i]['name'] +'</option>';
                    }
                    $('#js-city').html(html);
                }
            }
            else {
                var html = '<option value="0">Chọn Tỉnh thành</option>';
                $('#js-city').html(html);
            }
        }
    });
}

$(function() {
    init();
    initSort();
});
