
$(function(){
    $('#js-add-pers-user').unbind('click').click(function(){

    });

    $('.js-sort-by').each(function(){
       $(this).unbind('click').click(function(){
           var sort = $(this).data('sort');
           if (typeof(sort) == 'number' && typeof(sort_path) != 'undefined') {
               var tag = $(this).find('div.js-icon-sort');
               var hasSort = tag.hasClass('i3');
               if (hasSort) {
                   sort = sort + 1;
               }
               sort_link = sort_path +'&sap_xep=' + sort;
               window.location = sort_link;
           }
           return false;
       });
    });

    $('.js-view-user').each(function(){
       $(this).unbind('click').click(function(){
           var id= $(this).data('id');
           id = id*1;
           if (id > 0 && typeof(type) != 'undefined') {
               var view_link = '/user/view/?type='+ type +'&id=' + id;
               window.location = view_link;
           }
           return false;
       });
    });

    //Dành riêng khi serach tại trang danh sách bài viết
    $('#js-btn-search').unbind('click').click(function(){
        var keyword = $('#js-ctn-search').val();
        if (keyword.length > 0) {
            window.location = search_path + '&q=' + keyword;
        }
    });

    $('.js-delete-member').unbind('click').click(function(){
        var _id = $(this).attr('data-id');
        if (confirm('Đây là thao tác không thể phục hồi. Bạn có chắc muốn xóa thành viên này?')) {
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=user.deleteUser';
            sParams += '&val[id]='+_id;
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
                    window.location.reload();
                }
            });
        }
    });
});