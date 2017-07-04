var aProduct = null;
var iCount = 0;
$(function() {
    
    $('#js-btn-submit').unbind('click').click(function(){
        //$(this).unbind('click');
        $('#frm_add').submit();
    });
    
    $('#js-program-type').change(function(){
        var value = $(this).val();
        value =value*1;
        if (value == 1) {
            $('#js-vendor-list').removeClass('none');
        }
        else {
            $('#js-vendor-list').addClass('none');
        }
    });
    
    $('#js-select-obj-apply').change(function(){
        var value = $(this).val();
        value =value*1;
        if (value == 0) {
            $('#js-conds-order').removeClass('none');
            $('#js-conds-product').addClass('none');
        }
        else if (value == 1){
            $('#js-conds-order').addClass('none');
            $('#js-conds-product').removeClass('none');
            initSearchProduct();
        }
        else {
            $('#js-conds-order').addClass('none');
            $('#js-conds-product').addClass('none');
        }
    });
    changeCondsOrder();
    //get total product
    iCount = $('.js-dis-product').length;
    initSearchProduct();
    initDeleteProduct();
});

function initSearchProduct()
{
    /*  Suggest sản phẩm */
    $('.js-search-product').unbind('keyup').keyup(function(e){
        var inputSuggest    = $(this);
        var oListSuggest    = inputSuggest.parent('.suggest-marketing').find('.list-suggest');
        var type            = inputSuggest.data('type');
        var val             = inputSuggest.val();
        if (typeof(val) == 'undefined' || empty(val)) {
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
function showSuggest(obj){
    var keyword     = obj['keyword'];
    var type        = obj['type'];
    var objList     = obj['objList'];
    var objInput    = obj['objInput'];

    var content = '';
    /*  Xử lý AJAX xong trả về content */
    killRequest();
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=store.searchProduct' + '&key='+ keyword + '&sType=name';
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
                        content += '<div class="item-suggest" data-id="'+ i +'" data-influence-id="" data-name="" data-sku="">'+ data[i]['detail']['name'] +'</div>';
                    }
                    aProduct = data;
                }
                
            }
            objList.removeClass('js-button-wait');
            objList.find('.mCSB_container').html(content);
            if (content == '') {
                aProduct = null;
                objList.fadeOut(function(){
                    objList.addClass('none');
                });
            }
            

            objList.find('.item-suggest').unbind('click').click(function(){
                $(this).unbind('click');
                killRequest();
                iCount++;
                //add to product list
                var id = $(this).attr('data-id');
                var html = '';
                console.log(aProduct[id]);
                if (isset(aProduct[id])) {
                    html += '<div class="row30 mgbt20 js-dis-product" id="js-object-'+ iCount +'">\
                         <div class="col2">\
                            Tên sản phẩm:\
                         </div>\
                         <div class="col4">\
                            <label class="label-custom">'+ aProduct[id]['detail']['name'] +'</label>\
                         </div>\
                         <div class="col1">\
                            SKU:\
                         </div>\
                         <div class="col4">\
                            <label class="label-custom">'+ aProduct[id]['sku'] +'</label>\
                         </div>\
                         <input type="hidden" name="val[conds][product][list][id][]" value="'+ aProduct[id]['article_id'] +'">\
                         <input type="hidden" name="val[conds][product][list][influence_id][]" value="'+ aProduct[id]['id'] +'">\
                         <input type="hidden" name="val[conds][product][list][name][]" value="'+ aProduct[id]['detail']['name'] +'">\
                         <input type="hidden" name="val[conds][product][list][sku][]" value="'+ aProduct[id]['sku'] +'">\
                         <input type="hidden" name="val[conds][product][list][category_id][]" value="'+ aProduct[id]['detail']['category_id'] +'">\
                         <input type="hidden" name="val[conds][product][list][unit_id][]" value="'+ aProduct[id]['detail']['unit_id'] +'">\
                         <input type="hidden" name="val[conds][product][list][unit_name][]" value="'+ aProduct[id]['detail']['dvt_name'] +'">\
                         <div class="col1">\
                            <span class="fa fa-close right icon-wh js-delete-product" data-id="'+ iCount +'"></span>\
                         </div>\
                    </div>';
                }
                $('#js-product-list').append(html);
                objInput.val('');
                //objInput.attr('data-id', $(this).attr('data-id'));
                objList.fadeOut(function(){
                    objList.addClass('none');
                })
                initDeleteProduct();
            });
        }
    });
}

function initDeleteProduct()
{
    $('.js-delete-product').click(function(){
        var id = $(this).attr('data-id');
        id = id*1;
        document.getElementById('js-object-' + id).innerHTML = '';
        document.getElementById('js-object-' + id).style.display = "none";
    });
}

function changeCondsOrder()
{
    $('#js-conds-all-order').change(function(){
        if (this.checked == true) {
            //uncheck other
            var other = document.getElementById('js-conds-price-order');
            other.checked = false;
        }
    });
    $('#js-conds-price-order').change(function(){
        if (this.checked == true) {
            //uncheck other
            var other = document.getElementById('js-conds-all-order');
            other.checked = false;
        }
    });
}

function submitForm()
{
    
}