var aUser = null;
var iCount = 0;

function init()
{
    $('#js-btn-submit').unbind('click').click(function(){
        $(this).unbind('click');
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
    initSearchUser();
    initDeleteUser();
}

$(function(){
    init();
});

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

function initSearchUser()
{
    /*  Suggest user */
    $('.js-search-user').unbind('keyup').keyup(function(e){
        var inputSuggest    = $(this);
        var oListSuggest    = inputSuggest.parent('.suggest-marketing').find('.list-suggest');
        var type            = inputSuggest.data('type');
        var val             = inputSuggest.val();
        if (typeof(val) == 'undefined' || empty(val)) {
            //close popup
            oListSuggest.fadeOut(function(){
                oListSuggest.addClass('none');
            })
            return;
        }
        oListSuggest.addClass('js-button-wait');
        oListSuggest.fadeIn();
        switch(e.keyCode){
            case 37:
            case 38:
            case 39:
            case 40:
                break;
            case 13:
                showSuggest({
                    'keyword'   : val,
                    'type'      : type, 
                    'objInput'  : inputSuggest,
                    'objList'   : oListSuggest,
                });
                break;
            default:
                clearTimeout(k_oTimer);
                k_oTimer = setTimeout(function(){
                    showSuggest({
                        'keyword'   : val,
                        'type'      : type, 
                        'objInput'  : inputSuggest,
                        'objList'   : oListSuggest,
                    });
                }, 500);
                break;
        }
    });
}

/*  Hàm chung trả vể kết quả suggest */
function showSuggest(obj)
{
    var keyword     = obj['keyword'];
    var type        = obj['type'];
    var objList     = obj['objList'];
    var objInput    = obj['objInput'];

    var content = '';
    /*  Xử lý AJAX xong trả về content */
    killRequest();
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.searchUser' + '&key='+ keyword;
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
        dataType: 'json',
        error: function(jqXHR, status, errorThrown){
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                data = result.data;
                if (typeof(data) != 'undefined' && !empty(data)) {
                    for (var i in data) {
                        content += '<div class="item-suggest" data-id="'+ i +'">'+ data[i]['fullname'] +'</div>';
                    }
                    aUser = data;
                }
                
            }
            objList.removeClass('js-button-wait');
            objList.find('.mCSB_container').html(content);
            if (content == '') {
                aUser = null;
                objList.fadeOut(function(){
                    objList.addClass('none');
                });
            }
            

            objList.find('.item-suggest').unbind('click').click(function(){
                $(this).unbind('click');
                killRequest();
                iCount++;
                //add to list
                var id = $(this).attr('data-id');
                var html = '';
                if (isset(aUser[id])) {
                    html += '<div class="row30 mgbt20 js-dis-product" id="js-object-'+ iCount +'">\
                         <div class="col2">\
                            Tên thành viên:\
                         </div>\
                         <div class="col4">\
                            <label class="label-custom">'+ aUser[id]['fullname'] +'</label>\
                         </div>\
                         <div class="col1">\
                            Email:\
                         </div>\
                         <div class="col4">\
                            <label class="label-custom">'+ aUser[id]['email'] +'</label>\
                         </div>\
                         <input type="hidden" name="list_id[]" value="'+ aUser[id]['id'] +'">\
                         <input type="hidden" name="list_name[]" value="'+ aUser[id]['fullname'] +'">\
                         <input type="hidden" name="list_email[]" value="'+ aUser[id]['email'] +'">\
                         <div class="col1">\
                            <span class="fa fa-close right icon-wh js-delete-user" data-id="'+ iCount +'"></span>\
                         </div>\
                    </div>';
                }
                $('#js-product-list').append(html);
                objInput.val('');
                //objInput.attr('data-id', $(this).attr('data-id'));
                objList.fadeOut(function(){
                    objList.addClass('none');
                })
                initDeleteUser();
            });
        }
    });
}

function initDeleteUser()
{
    $('.js-delete-user').click(function(){
        var id = $(this).attr('data-id');
        id = id*1;
        document.getElementById('js-object-' + id).innerHTML = '';
        document.getElementById('js-object-' + id).style.display = "none";
    });
}