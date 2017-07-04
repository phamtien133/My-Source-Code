function init()
{
    $('.js-view-order').click(function(){
       var id = $(this).data('id')*1;
       if (id > 0) {
           var sLink = '/app/order/detail/?id=' + id;
           window.location = sLink;
       }
    });
}

$(function(){
    init();
});