
/*  Danh sách vị trí quảng cáo */
function initPageAds() 
{
    if($('.page-ads').length <= 0){
        return;
    }
    /*  Thêm mới */
    $('.js-add-add-pos').click(function(){
        showPopupAddPosition(0);
    })
    
    /* Chỉnh sửa */
    $('.js-edit-ads-item').unbind('click').click(function(){
        var id = $(this).data('id');
        if (typeof(id) !='undefined' && id > 0) {
            showPopupAddPosition(id);
        }
    });

    /* Xóa *
    $(' .js-delete-ads-item').unbind('click').click(function(){
        console.log('Xóa');
        var id = $(this).data('id');
    });
    /* */
    
    initStatus();
}

/* ajax call popup add position*/
function showPopupAddPosition(id)
{
    var content = '';
    //call ajax
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=advertise.getBlockPosition' + '&val[id]=' + id;
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
            insertPopupCrm(content, ['.js-cancel-adsedit'], '.page-ads-edit', true);
            initPageAdsEdit();
            initSbmAdsUnit();
        }
    });
}

/* init action submit form add unit ads*/
function initSbmAdsUnit()
{
    $('#js-submit-adsposition').unbind('click').click(function(){
        
        var path = $('#image-adsedit_img').attr('src');
        $('#js-image-path').val(path);
        var action = "$(this).ajaxSiteCall('advertise.addAdsUnit', 'afterAddAdsUnit(data)'); return false;";
        $('#js-frm-ads-position').attr('onsubmit', action);
        $('#js-frm-ads-position').submit();
        $('#js-frm-ads-position').removeAttr('onsubmit');
        $('#js-submit-adsposition').unbind('click');
        return false;
    });
}
/* after call ajax add unit ads*/
function afterAddAdsUnit(data)
{
    if (typeof(data) == 'object' && typeof(data.iStatus) == 'number') {
        if (data.iStatus == 3) {
            //reload page
            window.location.reload();
        }
        else {
            if (typeof(data.aErrors) != 'undefined' && data.aErrors.length > 0) {
                //add notice error
                var html = '';
                html += '<div class="row30">Đã có lỗi xảy ra</div>';
                for(i in data.aErrors) {
                    html += '<div class="row30">' + data.aErrors[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                initSbmAdsUnit();
                return false;
            }
            else {
                alert('Đã có lỗi xảy ra');
                initSbmAdsUnit();
            }
        }
    }
    return false;
}


/* after call ajax add position ads*/
function afterAddAdsPosition(data)
{
    if (typeof(data) == 'object' && typeof(data.iStatus) == 'number') {
        if (data.iStatus == 3) {
            //reload page
            window.location.reload();
        }
        else {
            if (typeof(data.aErrors) != 'undefined' && data.aErrors.length > 0) {
                //add notice error
                var html = '';
                html += '<div class="row30">Đã có lỗi xảy ra</div>';
                for(i in data.aErrors) {
                    html += '<div class="row30">' + data.aErrors[i] +'</div>';
                }
                $('#js-error-popup').html(html);
                initSbmAdsPosition();
                return false;
            }
            else {
                alert('Đã có lỗi xảy ra');
                initSbmAdsPosition();
            }
        }
    }
    return false;
}

/*  Tạo / Sửa Vị trí quảng cáo */
function initPageAdsEdit()
{
    $('.js-upload-image-adsedit').click(function(){
        upHinhMoRong({id: 1, obj: 'image-adsedit', width: 0, height: 0});
        $('#image-adsedit_img').removeClass('none');
        $('.js-upload-image-adsedit').addClass('none');
        $('.js-del-ads').removeClass('none');
        
        $('.js-del-ads').unbind('click').click(function(){
            $('#image-adsedit_img').attr('src', '');
            $('#image-adsedit_img').addClass('none');
            $('.js-upload-image-adsedit').removeClass('none');
            $('.js-del-ads').addClass('none');
        });
    });
    $('.js-del-ads').unbind('click').click(function(){
        $('#image-adsedit_img').attr('src', '');
        $('#image-adsedit_img').addClass('none');
        $('.js-upload-image-adsedit').removeClass('none');
        $('.js-del-ads').addClass('none');
    });
    
    $('#page_type').unbind('change').change(function(){
        var type = $(this).val();
        if (type == -1 ) {
            var option = '<option value="-1">Vui lòng chọn loại trang </option>';
        }
        else {
            // load nội dung những item được 
            var option = '<option value="-1">Chọn vị trí</option>';
            for (var i in display_type) {
                for (var j in display_type[i]['page']) {
                    if (type == display_type[i]['page'][j]) {
                        option += '<option value="'+i+'">'+display_type[i]['name']+'</option>';
                    }
                }
            }
        }
        $('#display_positon').empty();
        $('#display_positon').append(option);
        initSelectPosition();
    });
    
    $('.js-ads-type').change(function(){
        var value = $(this).val();
        value = value*1;
        $('#apply_method').val(value);
    });
    initTypeNumber();
    
    initSelectPosition();
    initSelectDisplayType(true);
}
function initSelectPosition()
{
    console.log(display_type);
    $('#display_positon').unbind('change').change(function(){
        var s_position = $(this).val();
        console.log(s_position);
        var option  = '<option value="-1">Chọn kiểu hiển thị</option>';
        if (empty(s_position) || s_position == -1) {
            $('#display-type').empty();
            $('#display-type-select').hide();
            $('.list-accept-item').html();
            $('.list-accept').hide();
        }
        else {
            if (isset(display_type[s_position]) && isset(display_type[s_position]['list'])) {
                for (var i in display_type[s_position]['list']) {
                    option += '<option value="'+i+'">'+ display_type[s_position]['list'][i]['name'] +'</option>';
                }
            }
            if (!empty(option)) {
                $('#display-type').empty();
                $('#display-type').append(option);
                // hiển thị khung chọn loại
                $('#display-type-select').show();
                initSelectDisplayType();
            }
            else {
                $('#display-type').empty();
                $('#display-type-select').hide();
                $('.list-accept-item').html();
                $('.list-accept').hide();
            }
        }
    });
}
function initSelectDisplayType(flag)
{
    if (typeof(flag) == 'undefined' || !flag) {
        $('.list-accept-item').html();
        $('.list-accept').hide();
    }
    
    $('#display-type').unbind('change').change(function(){
         // hiển thị các option được chọn theo đang radio box
         var s_position = $('#display_positon').val();
         if (empty(s_position) || s_position == -1) {
             alert('Vui lòng chọn vị trí hiển thị');
             return false;
         }
         var s_display = $(this).val();
         if (empty(s_display) || s_display == -1) {
             $('.list-accept-item').html();
             $('.list-accept').hide();
         }
         else {
             var html = '';
             if (isset(display_type[s_position]) && isset(display_type[s_position]['list']) && isset(display_type[s_position]['list'][s_display]) && isset(display_type[s_position]['list'][s_display]['accept-type'])) {
                 for (var i in display_type[s_position]['list'][s_display]['accept-type']) {
                     html += '<div class="col3"><input type="checkbox" class="accept-type" id="accept-type-'+i+'" name="val[accept-type][]" value="'+display_type[s_position]['list'][s_display]['accept-type'][i]+'"><label class="padlr10" for="accept-type-'+i+'">'+display_type[s_position]['list'][s_display]['accept-type'][i]+'</label></div>';
                 }
             }
             if (!empty(html)) {
                 $('.list-accept-item').html(html);
                 $('.list-accept').show();
             }
             else {
                 $('.list-accept-item').html();
                 $('.list-accept').hide();
             }
         }
    });
}
/*  Danh sách Quảng cáo */
function initAdsPosition()
{
    if ($('.page-ads-position').length <= 0) {
        return;
    };

    /*  Chỉnh sửa */
    $('.js-edit-ads').click(function(){
        console.log('Chỉnh sửa');
    });

    /*  Xóa */
    $('.js-delete-ads').click(function(){
        console.log('Xóa');
    });
    
    initStatus();
}

/*  Tạo / Sửa Quảng cáo */
function initAdsPositionEdit()
{
    if($('.page-adsposition-edit').length <= 0){
        return;
    }
    
    /*  init tag input */
    $('.tags').tagsInput({
        width: 'auto',
        delimiter: '|',
        'data-default' : "Thêm Nhà Quảng cáo",
        onChange: function(elem, elem_tags)
        {
            if(lan_dau) return ;
            chinh_sua['keyword'] = true;
            var n = 0;
            $('.tags', elem_tags).each(function(){
                if(n % 2 === 0)
                {
                    $(this).css('background-color', 'yellow');
                }
                n++;
            });
        }
    });

    /* init select2 chọn vendor */
    $('.select2').select2();

    /*  init date time */
    $('.js-date-time').datetimepicker({
            timepicker:false,
            format:'Y/m/d'
        });

    /*  Xem trước */
    $('.js-review-ads').click(function(){
        console.log('Xem trước');
    })
    loadImageExist();

    /*  Tải danh sách ảnh */
    $('.js-upload-image-ads-pos').click(function(){
        var obj = $(this);
        var n = obj.attr('data-max') * 1 + 1;
        obj.attr('data-max', n);
        var content = '<div class="col4 item-img-upload mgbt20" data-id="'+n+'">\
                            <div class="combo-upload">\
                            <div class="img-upload">\
                                <img src="" alt="" id="img-upload-'+n+'_img" />\
                                <div class="control-img">\
                                    <span class="fa fa-pencil js-edit-item-upload"></span>\
                                    <span class="fa fa-trash js-del-item-upload"></span>\
                                </div>\
                            </div>\
                            <input type="hidden" name=val[image_link][image_path][] class="js-image-path" value="">\
                            <input type="text" name=val[image_link][detail_path][] class="link-upload" placeholder="Nhập Liên kết">\
                            <input type="text" name=val[image_link][caption][] class="link-upload" placeholder="Nhập mô tả">\
                        </div>\
                    </div>';
        var listImgUpload = $('.js-list-img-upload');
        newdiv = document.createElement( "div" ),
        existingdiv = document.getElementById( "js-img-linked" );
        var addElement = $(content);
        //listImgUpload.prepend(content);
        listImgUpload.append(addElement, [newdiv,existingdiv]);
        upHinhMoRong({id: 1, obj: 'img-upload-'+n, width: 0, height: 0});

        initEditImgUpload();
    });
}
$(function(){
    initPageAds();
    initAdsPosition();
    initAdsPositionEdit();
});
    