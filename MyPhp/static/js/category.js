function settingRateLimitFilter(id)
{
    if (id > 0) {
        var aFilterValueTmp = [];
        $('#list_tl_' + id + ' .trich_loc_chinh_sua').each(function(){
            if (this.checked) {
                var id_filter = $(this).val()*1;
                var name_filter = $(this).attr('data-name');
                if (id_filter > 0) {
                    aFilterValueTmp.push({'id':id_filter,'name':name_filter});
                }
            }
        });
        
        if (empty(aFilterValueTmp)) {
            alert('Không có giá trị trích lọc nào được chọn');
            return false;
        }
        //Lấy thông tin danh sách đã thiết lập trước đó
        var aValLimitExist = {};
        $('.js-item-limit-rate').each(function(){
            var id_tmp = $(this).attr('data-id')*1;
            var min_tmp = $(this).find('.js-item-limit-rate-min').val();
            var max_tmp = $(this).find('.js-item-limit-rate-max').val();
            if (id_tmp > 0) {
                aValLimitExist[id_tmp] = {'min':min_tmp,'max':max_tmp};
            }
            
        });
        //show popup setting
        var html = '<div class="container pad20 order-purchase">';
            html += '<div class="content-box panel-shadow">';
                html += '<div class="col-md-12">';
                    html += '<div class="box-title title-blue line-bottom">Thiết lập ngưỡng giới hạn</div>';
                    html += '<input type="hidden" id="js-filter-parent-id" value="'+ id +'">';
                    html += '<div class="row30 padlr20 mgbt10">';
                        html += '<div class="col6">';
                            html += 'Tên trích lọc giá trị';
                        html += '</div>';
                        html += '<div class="col3">';
                            html += 'Giá trị tối thiểu';
                        html += '</div>';
                        html += '<div class="col3">';
                            html += 'Giá trị tối đa';
                        html += '</div>';
                    html += '';
                    html += '</div>';
                    var min_tmp = 0;
                    var max_tmp = 0;
                    for (var i in aFilterValueTmp) {
                        html += '<div class="row30 padlr20 mgbt10 js-row-limit-rate-filter" data-id="'+ aFilterValueTmp[i].id +'">';
                            html += '<div class="col6">';
                                html += aFilterValueTmp[i].name + ' :';
                            html += '</div>';
                            min_tmp = 0;
                            max_tmp = 0;
                            var key_tmp = aFilterValueTmp[i].id;
                            if (isset(aValLimitExist[key_tmp])) {
                                min_tmp = aValLimitExist[key_tmp].min;
                                max_tmp = aValLimitExist[key_tmp].max;
                            }
                            html += '<div class="col3 padlr10">';
                                html += '<input type="text" class="js-limit-min-rate-filter js-input-number" style="width: 100%;text-align: center;" value="'+min_tmp+'">';
                            html += '</div>';
                            html += '<div class="col3 padlr10">';
                                html += '<input type="text" class="js-limit-max-rate-filter js-input-number" style="width: 100%;text-align: center;" value="'+max_tmp+'">';
                            html += '</div>';
                            html += '';
                        html += '</div>';
                    }
                    html += '<div class="row30 mgbt20">';
                        html += '<div class="col-md-6 padright10">';
                            html += '<div class="button-default js-cancel-setting-limit">Thoát</div>';
                        html += '</div>';
                        html += '<div class="col-md-6 padleft10">';
                            html += '<div class="button-blue col4" data-id="'+ id +'" id="js-setting-limit-filter">Thiết lập</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';
        html += '</div>';
        
        insertPopupCrm(html, ['.js-cancel-setting-limit'], '.order-purchase', true);
        ValidateKey();
        
        $('#js-setting-limit-filter').unbind('click').click(function(){
            var id = $(this).attr('data-id')*1;
            var pid = $('#js-filter-parent-id').val()*1;
            //check các giá trị tối đa và tối thiểu
            var aLimitFilter = [];
            var bIsValidate = true;
            $('.js-row-limit-rate-filter').each(function(){
                var idVal = $(this).attr('data-id')*1;
                var minVal = $(this).find('.js-limit-min-rate-filter').val()*1;
                var maxVal = $(this).find('.js-limit-max-rate-filter').val()*1;
                if (minVal > maxVal) {
                    bIsValidate = false;
                    alert('Giá trị tối thiểu không thể lớn hơn giá trị tối đa');
                    return false;
                }
                aLimitFilter.push({'id':idVal,'min':minVal,'max':maxVal});
            });
            if (!bIsValidate) {
                return false;
            }
            if (empty(aLimitFilter)) {
                alert('Không có thông tin giá trị trích lọc nào');
                return false;
            }
            //xóa hết danh sách cũ
            var sIdListLimit = 'list_limit_rating_filter_' + id;
            $('#list_limit_rating_filter_' + id).html('');
            var html = '';
            for (var i in aLimitFilter) {
                html += '<div class="js-item-limit-rate" data-id="'+ aLimitFilter[i].id +'">'
                html += '<input type="hidden" class="js-item-limit-rate-min" name="limit_rate_filter['+pid+']['+ aLimitFilter[i].id +'][min]" value="'+ aLimitFilter[i].min +'"/>';
                html += '<input type="hidden" class="js-item-limit-rate-max" name="limit_rate_filter['+pid+']['+ aLimitFilter[i].id +'][max]" value="'+ aLimitFilter[i].max +'"/>';
                html += '</div>';
            }
            //đẩy xuống giao diện
            chinh_sua['filter_expend']=true;
            $('.js-cancel-setting-limit').click();
            $('#list_limit_rating_filter_' + id).html(html);
        });
    }
}

function ValidateKey()
{
    var numberKey = new Array(8,46,48,49,50,51,52,53,54,55,56,57);
    $('.js-input-number').keydown(function(e){
        if(numberKey.indexOf(e.keyCode) == -1){
            return false;
        }
    });
}