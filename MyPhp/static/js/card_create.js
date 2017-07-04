
$(function(){
    if ($('#js-card-id').val() < 1) {
        initAddCard();
    }
    
    initRemoveCardValue();
    initSelectCardValue();
    initTypeNumber();
    $('.js-date-time').datetimepicker({
        timepicker:false,
        format:'Y/m/d'
    });
})

function initAddCard()
{
    $('#js-add-value').unbind('click').click(function(){
        var content = '';
        content += '<div class="row20 line-bottom padbot10 js-card-value">';
        content += '<div class="col3 padright10"><div class="row20 sub-black-title">Loại mệnh giá</div>';
        content += '<select class="js-sel-card-value" name="val[value][]">';
        content += '<option value="0">Chọn loại mệnh giá</option>';
        if (typeof(oCardValue) != 'undefined') {
            for (var i in oCardValue) {
                content += '<option value="'+oCardValue[i].name_code+'">'+oCardValue[i].name+'</option>';
            }
        }
        
        content += '</select></div>';
        content += '<div class="col3 padright10"><div class="row20 sub-black-title">Giá trị</div><input readonly="readonly" class="js-card-value-dt disable-pointer" type="text" placeholder="Giá trị" value="0" name="val[value][]"></div>';
        content += '<div class="col3 padright20"><div class="row20 sub-black-title">Số lượng</div><input class="js-total-dt" type="text" placeholder="Số lượng" value="0" name="val[quantity][]"></div>';
        content += '<div class="col3 padright10 padtop20"><span class="fa fa-trash icon-wh js-del-card-value" data-id="29"></span></div>';
        content += '</div>';
        content += '</div>';
        
        $('#js-list-card-value').append(content);
        initSelectCardValue();
        initRemoveCardValue();
    });
    $('#js-add-value').click();
    
    initSubmitCard();
}

function initSelectCardValue()
{
    $('.js-sel-card-value').unbind('change').change(function(){
        var _obj = $(this);
        var _objJ = this;
        var _objParent = _obj.parent().parent();
        var _val = _obj.val();
        if (_val > 0) {
            //Kiểm tra không cho chọn trùng
            _obj.addClass('atv');
            var check_exist = false;
            $('.js-sel-card-value').each(function(){
                if (!$(this).hasClass('atv')) {
                    var _val_tmp = $(this).val();
                    if (_val_tmp > 0 && _val_tmp == _val) {
                        check_exist = true;
                        return false;
                    }
                }
            });
            _obj.removeClass('atv');
            if (check_exist) {
                alert('Mệnh giá này đã có, vui lòng chọn mệnh giá khác!');
                _objJ.selectedIndex = '0';
                _val = 0;
            }
        }
        var _val_set = 0;
        if (_val > 0) {
            if (typeof(oCardValue) != 'undefined' && typeof(oCardValue[_val]) != 'undefined') {
                _val_set = oCardValue[_val].value;
            }
            else {
                alert('Mệnh giá không hợp lệ!');
                _objJ.selectedIndex = '0';
            }
        }
        _objParent.find('.js-card-value-dt').val(_val_set);
    });
}

function initRemoveCardValue()
{
    $('.js-del-card-value').unbind('click').click(function(){
        $(this).parent().parent().remove();
    });
}

function initSubmitCard()
{
    $('#js-btn-create-card').unbind('click').click(function(){
        var objSelect = $(this);
        var _name = $('#js-name').val();
        var sParam = '';
        if (_name == '') {
            alert('Chưa nhập tên đợt phát hành');
            return false;
        }
        sParam += '&val[name]=' + unescape(_name);
        
        var _start = $('#js-start-time').val();
        var _end = $('#js-end-time').val();
        
        if (_start == '' || _end == '') {
            alert('Vui lòng chọn thời gian bắt đầu và thời gian kết thúc');
            return false;
        }
        
        _start = convertTime(_start, 0);
        if (_start <1) {
            alert('Thời gian bắt đầu không hợp lệ!');
            return false;
        }
        _end = convertTime(_end, 1);
        if (_start <1) {
            alert('Thời gian kết thúc không hợp lệ!');
            return false;
        }
        
        if (_start > _end) {
            alert('Thời gian kết thúc phải lớn hơn thời gian bắt đầu!');
            return false;
        }
        sParam += '&val[start_time]=' + _start;
        sParam += '&val[end_time]=' + _end;
        if ($('.js-card-value').length < 1) {
            alert('Chưa chọn mệnh giá của thẻ');
            return false;
        }
        
        var _total_char = $('#js-total-char').val();
        if (_total_char < 12 || _total_char > 20) {
            alert('Tổng chữ số mã thẻ phải từ 12 đến 20 ký tự!');
            return false;
        }
        sParam += '&val[total_character]=' + _total_char;
        
        var bCheck = false;
        var sCheckValue = '';
        var cnt = 0;
        $('.js-card-value').each(function(){
            var sel_op = $(this).find('.js-sel-card-value').val();
            var sel_total = $(this).find('.js-total-dt').val();
            var sel_val = $(this).find('.js-card-value-dt').val();
            if (sel_op != 0) {
                if (sel_total < 1) {
                    bCheck  =true;
                    sCheckValue = 'Số lượng phát hành của các mệnh giá được chọn phải lớn hơn 0';
                    return false;
                }
                cnt++;
                sParam += '&val[value]['+cnt+']=' + sel_op;
                sParam += '&val[quantity]['+cnt+']=' + sel_total;
            }
        });
        if (sCheckValue != '') {
            alert(sCheckValue);
            return false;
        }
        if (cnt == 0) {
            alert('Chưa chọn mệnh giá của thẻ');
            return false;
        }
        
        objSelect.addClass('js-button-wait');
        sParam = '&'+ getParam('sGlobalTokenName') + '[call]=card.createCard' + sParam;
        $.ajax({
            crossDomain:true,
            xhrFields: {
                withCredentials: true
            },
            url: getParam('sJsAjax'),
            type: "POST",
            data: sParam,
            timeout: 15000,
            cache:false,
            dataType: 'json',
            error: function(jqXHR, status, errorThrown){
                alert('Lỗi hệ thống');
                objSelect.removeClass('js-button-wait');
            },
            success: function (result) {
                if(isset(result.status) && result.status == 'success') {
                    window.location = '/card/';
                }
                else {
                    var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                    alert(messg);
                    objSelect.removeClass('js-button-wait');
                }
            }
        });
    });
}

function convertTime(_sDate, _type)
{
    if (typeof(_type) == 'undefined') {
        _type = 0;
    }
    
    //convert time dang text sang dang unix time
    if (typeof(_sDate) == 'undefined' || _sDate == '') {
        return -1;
    }
    var aDate = _sDate.split("/");
    if (aDate.length != 3) {
        return -1;
    }
    var _y = aDate[0];
    var _m = aDate[1] - 1;
    var _d = aDate[2];
    var _h = 0;
    var _min = 0;
    var _s = 0;
    var sHour = '';
    if (_type == 0) {
        sHour = ' 00:00:00';
        _h = 0;
        _min = 0;
        _s = 0;
    }
    else {
        sHour = ' 23:59:59';
        _h = 23;
        _min = 59;
        _s = 59;
    }
    
    //var str_time = _sDate + sHour;
    //str_time = str_time.split(' ').join('T');
    var date = new Date(_y, _m, _d, _h, _min, _s, 0);
    var _time = date.getTime()/1000;
    _time = Math.round(_time);
    _time = _time + 7*3600;
    if (_time > 0) {
        return _time;
    }
    return -1;
}