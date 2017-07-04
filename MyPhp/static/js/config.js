var aUser = null;
function addUsertoCRM(){
    $('.js-add-user-crm').click(function(){
        var content = '';
        //call ajax
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.callAddCrmUserForm';
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
                insertPopupCrm(content, ['.js-cancel-send-email'], '.page-add-crm-user', true);
                initAddCRMUser();
            }
        });
    });
}
function initAddCRMUser()
{
    $('#js-search-user').unbind('keyup').keyup(function(e){
        var pvalue = $(this).val();
        switch(e.keyCode){
            case 37:
            case 38:
            case 39:
            case 40:
                break;
            case 13:
                suggestSearchUser(pvalue);
                break;
            default:
                clearTimeout(k_oTimer);
                k_oTimer = setTimeout(function(){
                    suggestSearchUser(pvalue);
                }, 500);
                break;
        }
    });
    $('#js-add-user-bt').unbind('click').click(function(){
        user_id = $('#js-user-id').val();
        status = $('#js-user-state').val();
        usr_name = $('#js-crm-name').val();
        usr_pass = $('#js-crm-pass').val();
        if (empty(user_id)) {
            alert('Vui lòng chọn thành viên');
            return false;
        }
        $(this).addClass('js-button-wait');
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.addCrmUser' + '&val[user_id]='+ user_id;
        sParams += '&val[status]='+ status;
        sParams += '&val[usr_name]='+ usr_name;
        sParams += '&val[usr_pass]='+ usr_pass;
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
                if (result.status == 'error') {
                    alert(result.message);
                    $('#js-add-user-bt').removeClass('js-button-wait');
                    return false;
                }
                else {
                    alert('Thêm thành viên thành công.');
                    window.location.reload();
                    return false;
                }
            }
        });
    })
}
function suggestSearchUser(keyword)
{
    content = '';
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
            if (result.status == 'error') {
                content = '<div class="item-sg-user" >'+ result.message + '</div>';
                $('.list-sg-user').html(content);
                $('.list-sg-user').removeClass('none');
            }
            else if(result.status == 'success') {
                data = result.data;
                if (typeof(data) != 'undefined' && !empty(data)) {
                    aUser = data;
                    for (var i in data) {
                        content += '<div class="item-sg-user" data-id="'+ i +'">'+ data[i]['fullname'] +'</div>';
                    }
                    $('.list-sg-user').html(content);
                    $('.list-sg-user').removeClass('none');
                    selectUser();
                }
            }
        }
    });
}
function selectUser()
{
    $('.item-sg-user').click(function(){
        var index = $(this).attr('data-id');
        $('#js-user-name').val(aUser[index].fullname);
        $('#js-user-email').val(aUser[index].email);
        $('#js-user-id').val(aUser[index].id);
        $('#js-user-state').val(1);
        $('.js-user-state').removeClass('none');
        $('.js-user-info').removeClass('none');
        $('.list-sg-user').html('');
    });
}
function initAddCrmUser()
{

}
function deleteCrmUser(userid)
{
    if (!userid)
        return false;

    var sParams = '&'+ getParam('sGlobalTokenName') + '[call]=support.deleteCrmUser';
    sParams += '&cid='+ userid;
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
            window.location.reload();
        }
    });
}
$(function(){
     addUsertoCRM();
     $('.js-delete-crm-user').unbind('click').click(function(){
         var cid = $(this).attr('data-id');
         if (!cid) {
             return false;
         }
        if (confirm('Bạn có chắc muốn loại bỏ user này?')) {
            console.log(cid);
            deleteCrmUser(cid);
        }
     });
});