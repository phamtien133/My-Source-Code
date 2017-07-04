var filters = [];
function initFilterAction()
{
    $('.add-filter').unbind('click').click(function(){
        // gọi popup để thêm.
        $('.add-new-filter').removeClass('none'); 
    });
    $('.call-add-filter-value').unbind('click').click(function(){
        var dataid = $(this).attr('data-id');
        if (isset(filters[dataid])) {
            var fitem = filters[dataid];
            var list_sparent = '';
            var list_sparent_html = '';
            if (fitem['require_parent'] == 1) {
                // kiểm tra các dữ liệu cha đã chọn dữ liệu hay chưa (chỉ cần 1 trường hợp đã chọn)
                var b_select = false;
                var list_fparent = isset(fitem['parent_list']) ? fitem['parent_list'] : [];
                if (empty(list_fparent)) {
                    alert('Dữ liệu lỗi, vui lòng liên hệ quản trị.');
                    return false;
                }
                for (var i =0; i < list_fparent.length; i++) {
                    var tmp = $('#filter_'+list_fparent[i]).val();
                    if (tmp > 0) {
                        list_sparent += tmp +',';
                        list_sparent_html += '<span class="tag" data-id="'+tmp+'"><span>'+filters[list_fparent[i]]['detail'][tmp]['name']+'</span><a href="javascript:void(0);" title="Remove this item">x</a></span>';
                        b_select = true;
                    }
                }
                list_sparrent = rtrim(content, ',');
                if (!b_select) {
                    // hiện thông báo lỗi yêu cầu chọn trích lọc cha trước.
                    content = 'Vui lòng chọn giá trị ';
                    for (var i =0; i < list_fparent.length; i++) {
                        content += filters[list_fparent[i]]['name'] + ',';
                    }
                    content = rtrim(content, ',');
                    alert(content);
                    return false;
                }
            }
            if (empty(list_sparent_html)) {
                list_sparent_html = 'Chưa có dữ liệu.';
            }
            // gọi popup để tạo giá trị trích lọc mới
            content = '<div class="container pad20 add-filter-value-block"><div class="content-box mgbt20 panel-shadow"><div class="box-title">Thêm giá trị trích lọc</div><div class="box-inner"><form action="" method="post" id="add-filter-value-form"><div class="dialog-err none mgbt20"></div><div class="dialog-success none mgbt20"></div><div class="row20 mgbt20"><div class="col4">Tên giá trị</div><div class="col6"><input placeholder="Điền thông tin..." type="text" name="val[name]" class="default-input" ></div></div><div class="row20 mgbt20"><div class="col4">Mã tên</div><div class="col6"><input name="val[name_code]" placeholder="Điền thông tin..." type="text" class="default-input"></div></div><div class="row20 mgbt20"><div class="col4">Ảnh hưởng bởi</div><div class="col6 filter-inherit-display">'+list_sparent_html+'</div></div><div class="row30 mgbt20"><div class="col2"><input type="hidden" id="js-list-parent-value" name="val[list_parent]" value="'+list_sparent+'"><input type="hidden" name="val[filter_id]" value="'+dataid+'"></div><div class="col3 padright20"><div class="button-default js-cancel-add-filter">Hủy</div></div><div class="col3"><div id="js-add-filter-value" class="button-blue col4">Thêm</div></div></div></form></div></div></div>';
            insertPopupCrm(content, ['.js-cancel-add-filter'], '.add-filter-value-block', true);
            initAddFilterValue();
        }
        else {
            alert('Dữ liệu lỗi, vui lòng liên hệ quản trị.');
            return false;
        }
        // nếu là trích lọc con thì kiểm tra xem trước đó đã chọn trích lọc cha chưa.
        
        //$('#add-new-value-'+dataid).removeClass('none'); 
    });
}
function initAddFilterValue()
{
    $('#js-add-filter-value').unbind('click').click(function(){
        $(this).addClass('js-button-wait');
        $('.dialog-err').addClass('none');
        var action = "$(this).ajaxSiteCall('article.addFilterValue', 'addFilterValueCallback(data)'); return false;";
        $('#add-filter-value-form').attr('onsubmit', action);
        $('#add-filter-value-form').submit();
        $('#add-filter-value-form').removeAttr('onsubmit');
    });
    $('.filter-inherit-display span.tag a').unbind('click').click(function(){
        total = $('.filter-inherit-display').find('span.tag').length;
        if (total == 1) {
            alert('Bắt buộc phải có ít nhất một dữ liệu.');
            return false;
        }
        sid = $(this).parents('.tag').attr('data-id'); 
        value = $('#js-list-parent-value').val();
        value = value + ',';
        value = value.replace(sid+',','');
        value = trim(value, ',');
        $('#js-list-parent-value').val(value);
        $(this).parents('.tag').remove();
    });
}
function addFilterValueCallback(data)
{
    $('#js-add-filter-value').removeClass('js-button-wait');
    if (data.status == 'error') {
        $('.dialog-err').html(data.message);
        $('.dialog-err').removeClass('none');
        $('.dialog-success').addClass('none');
        return false;
    }
    else if (data.status == 'success') {
        $('.dialog-success').html('Thêm thành công.');
        $('.dialog-success').removeClass('none');
        $('.dialog-err').addClass('none');
        fvalue = data.data;
        option = '<option value="'+fvalue['id']+'">'+fvalue['name']+'</option>';
        $('#filter_'+fvalue['filter_id']).append(option);
        $('#s2id_filter_'+fvalue['filter_id']).find('.select2-chosen').html(fvalue['name']);
        $('#filter_'+fvalue['filter_id']).val(fvalue['id']);
        $('.js-cancel-add-filter').click();
        filters[fvalue['filter_id']]['detail'][fvalue['id']] = fvalue;
    }
}
var current_filter = 0;
function format(state) 
{
    tmp = state.text;
    if (tmp.indexOf('://') == -1) return state.text; // optgroup
    return "<img src='" + state.text + "' />";
}
function initLoadFilter()
{
    var de_tai = $('#category_id').select2('val'), content = '', stt = 0, tmp = '';
    if(de_tai < 1) {
        // reload lại khung trích lọc. ẩn các trích lọc đã load.
        $('.display-filter').html('Vui lòng chọn danh mục để kích hoạt trích lọc.');
        return false;
    }
    if(current_filter == de_tai) return ;
    current_filter = de_tai;
    //call ajax
    var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.getFilterCategory' + '&val[id]=' + de_tai; 
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
        dataType: 'text',
        beforeSend: function ( xhr ) {
            $('.display-filter').addClass('js-button-wait');
        },
        error: function(jqXHR, status, errorThrown){
        },
        success: function (result) {
                
        }
    });
}
$(function() {
    initFilterAction();
});