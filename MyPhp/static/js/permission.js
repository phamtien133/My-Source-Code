function dong_bo_chon_khu_vuc(id)
{
    var mang_gia_tri = ['no', 'yes'];
    var mang = [
        'create_category',
        'edit_category',
        'create_article',
        'edit_article',
        'edit_other_article',
        'comment',
        'edit_comment',
        'approve_comment',
        'approve_article',
        ];
    var obj = $('#hinh_anh_' + id);
    var val = 1;
    for(var tmp in mang)
    {
        $('.' + mang[tmp]).each(function() {
            if($(this).attr('id').indexOf('_' + id) > 0)
            {
                if($(this).attr('alt') != 1)
                {
                    val = 0;
                    return ;
                }
            }
        });
    }
    
    if (mang_gia_tri[val] == 'yes') {
        $(this).addClass('atv');
    }
    else {
        $(this).removeClass('atv');
    }
}
function chon_khu_vuc_mac_dinh()
{
    if (typeof(a_mang_gia_tri) != 'object' || $.isEmptyObject(a_mang_gia_tri)) {
        return false;
    }
    var is_vendor = 0;
    if (vendor_id != 'undefined') {
        is_vendor = vendor_id;
    }
    var mang_gia_tri = [];
    
    if (is_vendor < 1) {
        mang_gia_tri['edit_category'] = a_mang_gia_tri.edit_category;
        mang_gia_tri['create_category'] = a_mang_gia_tri.create_category;
        mang_gia_tri['delete_category'] = a_mang_gia_tri.delete_category;
    }
    
    mang_gia_tri['edit_article'] = a_mang_gia_tri.edit_article;
    mang_gia_tri['create_article'] = a_mang_gia_tri.create_article;
    mang_gia_tri['delete_article'] = a_mang_gia_tri.delete_article;
    mang_gia_tri['edit_other_article'] = a_mang_gia_tri.edit_other_article;
    mang_gia_tri['create_comment'] = a_mang_gia_tri.create_comment;
    mang_gia_tri['edit_comment'] = a_mang_gia_tri.edit_comment;
    mang_gia_tri['delete_comment'] = a_mang_gia_tri.delete_comment;
    mang_gia_tri['approve_comment'] = a_mang_gia_tri.approve_comment;
    mang_gia_tri['was_approved_comment'] = a_mang_gia_tri.was_approved_comment;
    mang_gia_tri['approve_article'] = a_mang_gia_tri.approve_article;
    mang_gia_tri['was_approved_article'] = a_mang_gia_tri.was_approved_article;
    
    var mang_chu = ['no', 'yes'];
    if (is_vendor < 1) {
        var mang = [
        'create_category',
        'edit_category',
        'delete_category',
        'create_article',
        'edit_article',
        'edit_other_article',
        'delete_article',
        'create_comment',
        'edit_comment',
        'delete_comment',
        'approve_comment',
        'was_approved_comment',
        'approve_article',
        'was_approved_article',
        ];
    }
    else {
        var mang = [
        'create_article',
        'edit_article',
        'edit_other_article',
        'delete_article',
        'create_comment',
        'edit_comment',
        'delete_comment',
        'approve_comment',
        'was_approved_comment',
        'approve_article',
        'was_approved_article',
        ];
    }
    
        var id, i, tmp;
        var val = 1, ten;
        console.log(mang_gia_tri);
        for(ten in mang_gia_tri)
        {
            if(mang_gia_tri[ten] != '')
            {
                tmp = mang_gia_tri[ten].split(',');
                for(i=0;i<tmp.length;i++)
                {
                    id = tmp[i];
                    $('.' + ten).each(function() {
                        if ($('#'+ten+ '_'+id).length > 0) {
                            
                        }
                        //if($(this).attr('id').indexOf('_' + id) > 0)
                        if($(this).attr('id') == ten+ '_'+id)
                        {
                            if (mang_chu[val] == 'yes') {
                                $(this).addClass('atv');
                            }
                            else {
                                $(this).removeClass('atv');
                            }
                        }
                    });
                }
            }
        }
    
    $('.de_tai_doi_tuong').each(function(index, element) {
        id = $(this).attr('id').replace('hinh_anh_', '');
        dong_bo_chon_khu_vuc(id);
    });
}

function chon_khu_vuc(val)
{
    var obj = $('#' + val);
    if(obj.hasClass('atv'))
    {
        obj.removeClass('atv');
    }
    else
    {
        obj.addClass('atv');
    }
    
    var ten = obj.attr('class').replace(' cl_khu_vuc', '');
    var id = obj.attr('id').replace(ten + '_', '');
    dong_bo_chon_khu_vuc(id);
}
function chon_doi_tuong(id)
{
    var mang_gia_tri = ['no', 'yes'];
    var mang = [
        'create_category',
        'edit_category',
        'delete_category',
        'create_article',
        'edit_article',
        'edit_other_article',
        'delete_article',
        'create_comment',
        'edit_comment',
        'delete_comment',
        'approve_comment',
        'approve_article',
        ];
    var obj = $('#hinh_anh_' + id);
    if (obj.hasClass('atv')) {
        val = 1;
    }
    else {
        val = 0;
    }
    
    if(val == 0) val = 1;
    else val = 0;
    
    if (mang_gia_tri[val] == 'yes') {
        obj.addClass('atv');
    }
    else {
        obj.removeClass('atv');
    }
    
    
    for(var tmp in mang)
    {
        $('.' + mang[tmp]).each(function() {
            var pos = $(this).attr('id').indexOf('_' + id);
            if(pos > 0)
            {
                len = ('_' + id).length;
                if ($(this).attr('id').length == pos + len) {
                    if (mang_gia_tri[val] == 'yes') {
                        $(this).addClass('atv');
                    }
                    else {
                        $(this).removeClass('atv');
                    }
                }
                
            }
        });
    }
}
function de_tai_chon_tat_ca(obj)
{
    if(typeof(obj) == 'undefined') obj = '';
    var mang_gia_tri = ['no', 'yes'];
    var mang = a_mang_khu_vuc;
    var val = 0;
    
    var bFlag = false;
    //Kiểm tra có danh sách đề tài nào hay không
    var iLength = $('.de_tai_doi_tuong').length;
    if (iLength < 1 && obj == '') {
        //trường hợp đặt biệt
        var objSelect = $('#js-select-permission-all');
        if (objSelect.hasClass('atv')) {
            objSelect.removeClass('atv');
            $('#js-full-permission').val(2);
        }
        else {
            objSelect.addClass('atv');
            $('#js-full-permission').val(1);
        }
        return false;
    }
    
    if(obj != '')
    {
        mang = [obj];
        tmp = 0;
        $('.' + mang[tmp]).each(function(index, element) {
            if($(this).hasClass('atv'))
            {
                val = 1;
                return;
            }
        });
        if(val == 0) val = 1;
        else val = 0;
        
    }
    else
    {
        $('.de_tai_doi_tuong').each(function(index, element) {
            
            if($(this).hasClass('atv'))
            {
                val = 1;
                return;
            }
        });
        if(val == 0) val = 1;
        else val = 0;
        
        for(var i in mang)
        {
            if(mang[i] == 'was_approved_article') delete mang[i];
            if(mang[i] == 'was_approved_comment') delete mang[i];
        }
        
        if (mang_gia_tri[val] == 'yes') {
            $('.de_tai_doi_tuong').addClass('atv');
        }
        else {
            $('.de_tai_doi_tuong').removeClass('atv');
        }
    }
    
    for(var tmp in mang)
    {
        if (mang_gia_tri[val] == 'yes') {
            bFlagHas = true;
            $('.'+mang[tmp]).addClass('atv');
        }
        else {
            bFlagHas = true;
            $('.'+mang[tmp]).removeClass('atv');
        }
    }
    
    return false;
}
function chon_tat_ca()
{
    var field = document.getElementById('jForm');
    var chon = 1;
    for (i = 0; i < field.length; i++)
    {
        if(field[i].type == 'checkbox' && field[i].checked == true)
        {
            chon = 0;
            break;
        }
    }
    if(chon == 1)
    {
        document.getElementById('div_chon_tat_ca').innerHTML = 'Bỏ chọn tất cả';
        for (i = 0; i < field.length; i++)
        {
            if(field[i].type == 'checkbox') field[i].checked = true;
        }
    }
    else
    {
        document.getElementById('div_chon_tat_ca').innerHTML = 'Bỏ chọn tất cả';
        for (i = 0; i < field.length; i++)
        {
            if(field[i].type == 'checkbox') field[i].checked = false;
        }
    }
}

function initSubmitPermission()
{
    console.log('1111111');
    $('#js-sbm-permission').unbind('click').click(function() {
        var objSelect = $(this);
        var sParam = '';
        var i = 0;
        var tmps = [], tmps_key = [];
        var ten = '', tmp;
        var ton_tai = false;
        $('.cl_khu_vuc').each(function() {
            ten = $(this).attr('class').replace(' cl_khu_vuc', '');
            ten = ten.replace('ck ','');
            if ($(this).hasClass('atv')) {
                ten = ten.replace(' atv','');
                if(tmps[ten] == undefined) {
                    tmps[ten] = {};  
                    tmps[ten]['access'] = '';  
                    tmps[ten]['deny'] = '';  
                } 
                tmps[ten]['access'] += $(this).attr('id').replace(ten + '_', '') + ',';
            }
            else {
                if(tmps[ten] == undefined) {
                    tmps[ten] = {};  
                    tmps[ten]['access'] = '';  
                    tmps[ten]['deny'] = '';  
                }
                tmps[ten]['deny'] += $(this).attr('id').replace(ten + '_', '') + ',';
            }
        });
        
        for (ten in tmps) {
            tmp = tmps[ten]['access'];
            i = tmp.length-1;
            tmp = tmp.substr(0, i);
            //$('#' + ten).val(tmp);
            sParam += '&val[category]['+ten+'][access]=' + tmp;
            tmp = tmps[ten]['deny'];
            i = tmp.length-1;
            tmp = tmp.substr(0, i);
            sParam += '&val[category]['+ten+'][deny]=' + tmp;
        }
        
        //Lấy thông tin các quyền được chọn
        $('.js-child-per').each(function() {
            var obj = $(this);
            var iTmp = 0;
            if (obj.hasClass('md-checked')) {
                iTmp = 1;
            }
            sParam += '&val[list]['+obj.attr('data-value')+']=' + iTmp;
        });
        
        sParam += '&val[priority]=' + $('#priority').val();   
        sParam += '&val[id]=' + $('#js-obj-id').val();
        sParam += '&val[otype]=' + $('#js-obj-type').val();
        
        objSelect.addClass('js-button-wait');
        
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.setPermissionModify' + sParam;
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
                alert('Lỗi hệ thống');
                objSelect.removeClass('js-button-wait');
            },
            success: function (result) {
                if(isset(result.status) && result.status == 'success') {
                    alert('Cập nhật thành công');
                    objSelect.removeClass('js-button-wait');
                }
                else {
                    var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                    alert(messg);
                    objSelect.removeClass('js-button-wait');
                }
            }
        });
        return false;
    });
}

$(function(){
    $('.cl_khu_vuc').click(function(){
        chon_khu_vuc($(this).attr('id'));
    });
    chon_khu_vuc_mac_dinh();
    initSubmitPermission();
    //riêng cho trang admin
    $('.js-tab-vendor').each(function(){
       $(this).unbind('click').click(function(){
           var links = $(this).data('link');
           if (links == 'none') {
               $('.vendor-list').show(500).removeClass('none');
               $('.js-tab-vendor').removeClass('atv');
               $(this).addClass('atv');
           }
           else 
                window.location = links;
       });
    });
});
