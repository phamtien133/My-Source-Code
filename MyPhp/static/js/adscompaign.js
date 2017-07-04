$(function(){
    initAddUnitToCompaign();
});
function initAddUnitToCompaign()
{
    loadExistUnitAds();
    $('#add-new-unit').unbind('click').click(function(){
        $(this).addClass('js-button-wait');
        var unitid= $('#select-unit').val();
        if (unitid == -1) {
            alert('Vui lòng chọn đơn vị quảng cáo.');
            $('#add-new-unit').removeClass('js-button-wait');
            return false;   
        }
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=advertise.getUnit' + '&val[id]=' + unitid;
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
            error: function(jqXHR, status, errorThrown){
                alert('Error');
            },
            success: function (data) {
                content = data;
                $('.none-unit-mesage').hide();
                $('#add-new-unit').removeClass('js-button-wait');
                $('.list-unit').append(content);
                initUploadContentUnitAds();
            }
        }); 
    });
    
    /*  init date time */
    $('.js-date-time').datetimepicker({
        timepicker:true,
        format:'Y/m/d H:i',
    });
    
    $('#js-btn-submit').unbind('click').click(function(){
        var action = "$(this).ajaxSiteCall('advertise.addCampaignAds', 'afterAddCampaignAds(data)'); return false;";
        $('#frm_add_campaign').attr('onsubmit', action);
        $('#frm_add_campaign').submit();
        $('#frm_add_campaign').removeAttr('onsubmit');
        $(this).addClass('js-button-wait');
        //$('#js-submit-adsposition').unbind('click');
        return false;
    });
}

function afterAddCampaignAds(data) {
    if (data.status == 'success') {
        window.location = '/advertise/';
    }
    else {
        var msg = isset(data.message) ? data.message : 'Lỗi hệ thống';
        alert(msg);
        $('#js-btn-submit').removeClass('js-button-wait');
    }
}

function initUploadContentUnitAds()
{
    $('.js-ads-upload-video, .js-ads-upload-image').unbind('click').click(function(){
        var data_id = $(this).attr('data-id')*1;
        var data_type = $(this).attr('data-type');
        if (data_id > 0) {
            var path_id = 'image-content-'+data_id;
            if (data_type == 'video') {
                path_id = 'video-content-'+data_id;
            }
            upHinhMoRong({obj: path_id});
        }
    });
    
    $('.js-select-page-option').unbind('onChange').change(function(){
        var val_sel = $(this).val();
        var val_id = $(this).attr('data-id')*1;
        if (val_sel == 1) {
            $('#js-page-path-' + val_id).find('.js-title-page-option').html('Danh sách trang không áp dụng:');
        }
        else {
            $('#js-page-path-' + val_id).find('.js-title-page-option').html('Danh sách trang áp dụng:');
        }
    });
}

function loadExistUnitAds()
{
    var id = $('#js-campaign-id').val()*1;
    if (id > 0) {
        if (typeof(aDataUnitAds) != 'undefined') {
            console.log(aDataUnitAds);
            var html = '';
            for (i in aDataUnitAds) {
                html += '<div class="unit-form" data-id="'+aDataUnitAds[i].id+'" id="unit-item-'+aDataUnitAds[i].id+'">';
                html += '<div class="row30">';
                html += '<div class="col3">Đơn vị quảng cáo:</div>';
                html += '<div class="col6">';
                html += aDataUnitAds[i].info.name;
                html += '<input type="hidden" id="js-ads-unit" value="'+aDataUnitAds[i].id+'">';
                html += '</div>';
                html += '</div>';
                html += '<div class="row30">';
                html += '<div class="col3">Trang áp dụng:</div>';
                html += '<div class="col6">'+aDataUnitAds[i].info.page_name+'</div>';
                html += '</div>';
                if (aDataUnitAds[i].info.page_code == 'category' || aDataUnitAds[i].info.page_code == 'article') {
                    html += '<div class="row30">';
                    html += '<div class="col3">Áp dụng theo:</div>';
                    html += '<div class="col6">';
                    html += '<div class="row30">';
                    html += '<div class="col6">';
                    html += '<select data-id="'+aDataUnitAds[i].id+'" name="val[unit_ads]['+aDataUnitAds[i].id+'][page_option]" class="js-select-page-option">';
                    var page_option = 0;
                    if (aDataUnitAds[i].page_option == 0) {
                        html += '<option value="0" selected="selected">Chọn trang áp dụng</option>';
                        html += '<option value="1">Tất cả</option>';
                    }
                    else {
                        html += '<option value="0">Chọn trang áp dụng</option>';
                        html += '<option value="1" selected="selected">Tất cả</option>';
                    }
                    html += '</select>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="row30" class="js-page-path-container" id="js-page-path-'+aDataUnitAds[i].id+'">';
                    html += '<div class="col3 js-title-page-option">';
                    if (aDataUnitAds[i].page_option == 0) {
                        html += 'Danh sách trang áp dụng:';
                    }
                    else {
                        html += 'Danh sách trang không áp dụng:';
                    }
                    html += '</div>';
                    html += '<div class="col6 padright10">';
                    html += '<textarea name="val[unit_ads]['+aDataUnitAds[i].id+'][page_option_content]" style="width: 100%; line-height: 20px">'+aDataUnitAds[i].page_option_content+'</textarea>';
                    html += '</div>';
                    html += '<div class="col3 js-note-page">';
                    html += 'Nhập vào đường dẫn các trang, cách nhau bởi dấu ";"';
                    html += '</div>';
                    html += '</div>';
                }
                
                html += '<div class="page-option"></div>';
                html += '<div class="row30">';
                html += '<div class="col3">Kích thước (rộng x cao):</div>';
                html += '<div class="col6">'+aDataUnitAds[i].info.width+' x '+aDataUnitAds[i].info.height+'</div>';
                html += '</div>';
                html += '<div class="row30">';
                html += '<div class="col3">Nội dung quảng cáo:</div>';
                html += '<div class="col6">';
                if (aDataUnitAds[i].info.content_type == 'html5') {
                    html += '<textarea name="val[unit_ads]['+aDataUnitAds[i].id+'][html5]">';
                    if (isset(aDataUnitAds[i].html5)) {
                        html += aDataUnitAds[i].html5;
                    }
                    html += '</textarea>';
                    html += '<span class="extra-info">Mã html5 cho nội dung quảng cáo</span>';
                }
                else if (aDataUnitAds[i].info.content_type == 'video') {
                    html += '<div class="col8">';
                    var link_tmp = '';
                    if (isset(aDataUnitAds[i].video)) {
                        link_tmp = aDataUnitAds[i].video;
                    }
                    html += '<input type="text" id="video-content-'+aDataUnitAds[i].id+'" name="val[unit_ads]['+aDataUnitAds[i].id+'][video]" placeholder="Link video quảng cáo" value="'+link_tmp+'">';
                    html += '</div>';
                    html += '<div class="col3 padleft10">';
                    html += '<div class="button-blue js-ads-upload-video" data-type="video" data-id="'+aDataUnitAds[i].id+'" id="upload-video-button">Upload video</div>';
                    html += '</div>';
                }
                else {
                    html += '<div class="col8">';
                    var link_tmp = '';
                    if (isset(aDataUnitAds[i].image)) {
                        link_tmp = aDataUnitAds[i].image;
                    }
                    html += '<input type="text" id="image-content-'+aDataUnitAds[i].id+'" name="val[unit_ads]['+aDataUnitAds[i].id+'][image]" placeholder="Link hình ảnh" value="'+link_tmp+'">';
                    html += '<span class="extra-info">Định dạng cho phép: ';
                    var type_option = aDataUnitAds[i].info.content_type_option['accept-type'];
                    for (j in type_option) {
                        html += type_option[j];
                    }
                    html += '</span>';
                    html += '</div>';
                    html += '<div class="col3 padleft10">';
                    html += '<div class="button-blue js-ads-upload-image" data-type="image" data-id="'+aDataUnitAds[i].id+'" id="upload-image-button">Upload ảnh</div>';
                    html += '</div>';
                }
                html += '</div>';
                html += '</div>';
                if (aDataUnitAds[i].info.content_type != 'html5') {
                    html += '<div class="row30">';
                    html += '<div class="col3">Đường dẫn quảng cáo:</div>';
                    html += '<div class="col6">';
                    html += '<input type="text" name="val[unit_ads]['+aDataUnitAds[i].id+'][path]" placeholder="Đường dẫn quảng cáo" value="'+aDataUnitAds[i].path+'">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="row30">';
                    html += '<div class="col3">Phụ đề quảng cáo:</div>';
                    html += '<div class="col6">';
                    html += '<input type="text" name="val[unit_ads]['+aDataUnitAds[i].id+'][caption]" placeholder="Phụ đề quảng cáo:" value="'+aDataUnitAds[i].caption+'">';
                    html += '</div>';
                    html += '</div>';
                }
                html += '<div class="row30">';
                html += '<div class="col3">Trạng thái:</div>';
                html += '<div class="col6">';
                html += '<div class="col6">';
                html += '<select name="val[unit_ads]['+aDataUnitAds[i].id+'][status]">';
                html += '<option value="1"';
                if (aDataUnitAds[i].status == 1) {
                    html += 'selected="selected"';
                }
                html += '>Kích hoạt</option>';
                html += '<option value="0"';
                if (aDataUnitAds[i].status == 0) {
                    html += 'selected="selected"';
                }
                html += '>Chưa kích hoạt</option>';
                html += '</select>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            }
            
            $('.none-unit-mesage').hide();
            $('.list-unit').append(html);
            initUploadContentUnitAds();
        }
    }
}
