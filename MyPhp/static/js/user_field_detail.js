var aUser = null;
var iCount = 0;

function initUserFieldDetail()
{
    initAddNewValue();
    checkTypeField();
    $('#js-btn-submit').unbind('click').click(function(){
        if ($('#name').val() == '') {
            alert('Vui lòng nhập tên');
            return false;
        }
        
        var type_select = $('#js-type-file').val();
        if (type_select != 'text') {
            if($('.js-field-value').length < 1) {
                alert('Vui lòng thêm giá trị đối với các kiều dữ liệu không phải là "Text"');
                return false;
            }
        }
        //$(this).unbind('click');
        $(this).addClass('js-button-wait');
        $('#frm_add').submit();
    });
    $('#name').keyup(function(){
        lay_name_code_tu_dong($(this).val())
    });
    $('#name').change(function(){
        lay_name_code_tu_dong($(this).val());
        kiem_tra_name_code()
    });
    $('#name').blur(function(){
        lay_name_code_tu_dong($(this).val());
        kiem_tra_name_code();
    });
    initDeleteValue();
}

$(function(){
    initUserFieldDetail();
});

function initAddNewValue()
{
    $('#js-add-value').unbind('click').click(function(){
        insertPopupCrm('\
            <div class="container pad20 js-new-value-popup" style="width: 500px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Thêm giá trị mới\
                    </div>\
                    <div class="box-inner">\
                        <div class="row30 padtb10 line-bottom" id="js-store-container">\
                            <div class="row30 padbot10 ">\
                                <div class="col1"></div>\
                                <div class="col3 padlr10">\
                                    <div class="sub-black-title">Giá trị:</div>\
                                </div>\
                                <div class="col6 padlr10">\
                                    <input type="text" id="js-new-value" value=""/>\
                                </div>\
                            </div>\
                            <div class="row30 padbot10 ">\
                                <div class="col1"></div>\
                                <div class="col3 padlr10">\
                                    <div class="sub-black-title">Mã code:</div>\
                                </div>\
                                <div class="col6 padlr10">\
                                    <input type="text" id="js-new-code" value=""/>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="row30 padtop10">\
                            <div class="row30">\
                                <div class="col6">\
                                </div>\
                                <div class="col3 padright10">\
                                    <div class="button-blue" id="js-select-new-value">Thêm</div>\
                                </div>\
                                <div class="col3">\
                                    <div class="button-default" id="js-close-popup-new-value">Đóng</div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>', ['#js-close-popup-new-value'],'.js-new-value-popup', true);
            
        $('#js-new-value').change(function(){
            var name_value = $('#js-new-value').val();
            name_value = getNameCode(name_value)
            $('#js-new-code').val(name_value);
        });
        
        $('#js-new-value').keyup(function(){
            var name_value = $('#js-new-value').val();
            name_value = getNameCode(name_value)
            $('#js-new-code').val(name_value);
        });
            
        $('#js-select-new-value').unbind('click').click(function(){
            var value = $('#js-new-value').val();
            var code = $('#js-new-code').val();
            if (empty(value)) {
                alert('Vui lòng nhập giá trị mới');
                return false;
            }
            var cnt = $('#js-field-value-list').attr('data-count')*1;
            cnt++;
            var html = '';
            html += '<div class="row30 js-field-value" id="js-object-'+ cnt +'">';
            html += '<div class="col1">Giá trị:</div>';
            html += '<div class="col4"><input type="text" name="val[list_name][]" value="'+ value +'" /></div>';
            html += '<div class="col2 padleft20">Mã code:</div>';
            html += '<div class="col4"><input type="text" name="val[list_code][]" value="'+ code +'" /></div>';
            html += '<input type="hidden" name="val[list_id][]" value="0">';
            html += '<div class="col1">';
            html += '<span class="fa fa-close right icon-wh js-delete-value" data-id="'+ cnt +'"></span>';
            html += '</div></div>';
            html += '';
            
            $('#js-field-value-list').append(html);
            $('#js-field-value-list').attr('data-count', cnt);
            $('#js-close-popup-new-value').click();
        });
    });
}

function checkTypeField()
{
    $('#js-type-file').change(function(){
        var value = $(this).val();
        var hasList = $('#js-field-value-list').length;
        if (value == 'text') {
            if (hasList) {
                //xóa
                $('#js-field-value-list').remove();
            }
        }
        else {
            if (hasList < 1) {
                var html = '<div class="row30 mgbt10" id="js-field-value-list" data-count="0">';
                html += '<div class="row30 mgbt10">';
                html += '<div class="col3">';
                html += '<label for="status" class="sub-black-title">Danh sách giá trị:</label>';
                html += '</div>';
                html += '<div class="col2">';
                html += '<div id="js-add-value" class="button-blue"> Thêm giá trị mới </div>';
                html += '</div>';
                html += '</div>';
                html += '';
                
                $('#js-block-list').html(html);
                initAddNewValue();
            }
        }
    });
}

function getNameCode(str)
{
    if (!empty(str)) {
        str = str.toLowerCase().stripViet().stripExtra().trim().stripSpace();
        str = str.replace(/[^a-zA-Z 0-9\-_]+/g,'');
    }
    return str;
}

var ma_ten_truoc = document.getElementById("name_code").value;
function lay_name_code_tu_dong(noi_dung, obj)
{
    if(obj == undefined) obj = 'name_code';
    noi_dung = noi_dung.toLowerCase().stripViet().stripExtra().trim().stripSpace();
    noi_dung = noi_dung.replace(/[^a-zA-Z 0-9\-_]+/g,'');
    
    document.getElementById(obj).value = noi_dung;
    if(obj == 'name_code' && ma_ten_truoc != noi_dung)
    {
        var obj_ten = document.getElementById("div_ten_kiem_tra_name_code");
        if(obj_ten.innerHTML != '<img src="http://img.' + domain_name + '/styles/web/global/images/waiting.gif">' )
        {
            obj_ten.innerHTML = '<img src="http://img.' + domain_name + '/styles/web/global/images/waiting.gif">';
        }
    }
}
function kiem_tra_name_code() {
    var noi_dung = document.getElementById("name_code").value;
    var obj_ten = document.getElementById("div_ten_kiem_tra_name_code");
    if(ma_ten_truoc != noi_dung)
    {
        obj_ten.innerHTML = '<img src="http://img.' + domain_name + '/styles/web/global/images/waiting.gif">';
        http.open('POST', '/includes/ajax.php?=&core[call]=core.checkNameCode', true);
        http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
        http.onreadystatechange = function () {
            if(http.readyState == 4){
                ma_ten_truoc = noi_dung;
                if( http.responseText == 1)
                {
                    obj_ten.innerHTML = '<img src="http://img.' + domain_name + '/styles/web/global/images/status_no.png">';
                }
                else
                {
                    obj_ten.innerHTML = '<img src="http://img.' + domain_name + '/styles/web/global/images/status_yes.png">';
                }
            }
        };
        http.send('val[id]='+ group_id +'&val[type]=menu&val[name_code]='+unescape(noi_dung));
    }
}
function btn_cap_nhat_name_code(id)
{
    if(id == undefined) id = 0;
    if(id == 0)
    {
        lay_name_code_tu_dong($('#name').val());
        kiem_tra_name_code();
    }
    else
    {
        lay_name_code_tu_dong($('#name').val());
    }
    return false;
}
function sbm_frm()
{
    return true;
}
function initDeleteValue()
{
    $('.js-delete-value').click(function(){
        var id = $(this).attr('data-id');
        id = id*1;
        $('#js-object-' + id).remove();
    });
}