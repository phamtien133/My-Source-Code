var myapp = angular.module("cms-app", ['ngMaterial', 'ngAnimate', 'ngTouch', 'ngMessages', 'chart.js']);
var name = 'Hoàng Giang Băng';
var phone = '0903.101.993';
var ab = '99';
var code = '#abcdef';
var time = '12:45:41 19/03/2015';
var sl = '100';
var monney = '100.000.000';
var payment = 'Chưa thanh toán';
var state = 'Không thể giao hàng';
var customer_group = 'VIP';
var ip = '192.168.1.1';
var ip_adr = 'Hanoi - Vietnam';
var sp_name = 'Máy xay đậu nành';
var sp_group = 'Nhóm SP';
var sp_categori = 'Máy làm sữa';
myapp.controller("main-ctrl", function($scope) {
    $scope.onChangeObject = function(id) {
        id = id*1;
        if (id > 0) {
            setStatus(id);
        }
    }
    
    /*  Menu left */
    function initMenuSlide(){
        var snapper = new Snap({
            element: document.getElementById('main-panel'),
            disable: 'right'
        });
        $('#js-menu-toggle').click(function(){
            if($('body').hasClass('snapjs-left')){
                snapper.close();    
            }else{
                snapper.open('left'); 
            }
        });
    };
    initMenuSlide();
    /*--- Header ---*/
    $scope.header = {
        page_title                  : 'sản phẩm',
        staff_name                  : name,
        more_inf                    : 'thêm sản phẩm',
        product                     : 'Nhà cung cấp',
    };
    $scope.setMenuIcon = 'menu';
    $scope.hasSearch = function(){
        return false;
    };
    $scope.hasMore = function(){
        return true;
    };
    $scope.hasProduct = function(){
        return true;
    }
}).controller("cms-ctrl", function($scope) {
    $scope.def = 'Nhấn "+" hoặc kéo thả để thêm vào quản lý chức năng';
    $scope.setIconFeaturesBar = 'close';
}).controller("overview-ctrl", function($scope) {
    $scope.statistic = {
        from                        : '8 th2 2014',
        to                          : '10 th3 2015',
    };
    $scope.days = [{ date : 'Hôm qua' },{ date : '7 ngày' },{ date : '1 tháng' },{ date : '3 tháng' },{ date : '6 tháng' }];
    $scope.box1 = {
        head                        : 'giá trị giao dịch trung bình',
        num1                        : '100.000đ',
        smalltitle                  : 'tổng số giao dịch',
        num2                        : '1.000',
        info                        : '10% so với ngày hôm trước',
        checkUpDown                 : 'down',
    };
    $scope.line = {
        cash                        : '25%',
        credit                      : '25%',
        discount                    : '15%',
        other                       : '35%',
    };
    $scope.box2 = {
        head                        : 'tổng doanh số',
    };
    $scope.box3 = {
        head                        : 'sản phẩm bán chạy',
    };
    $scope.rboxs = [
        {
            inf_row                 : 'Mũ bảo hiểm',
            more                    : 'CODE #2345',
            right                   : '1.000.000đ',
        },
        {
            inf_row                 : 'Mũ bảo hiểm',
            more                    : 'CODE #2345',
            right                   : '1.000.000đ',
        },
        {
            inf_row                 : 'Mũ bảo hiểm',
            more                    : 'CODE #2345',
            right                   : '1.000.000đ',
        }];
    $scope.box4 = {
        head                        : 'điểm thưởng',
        num1                        : '100.000đ',
        smalltitle                  : 'khách hàng nhận điểm',
        male                        : '10%',
        female                      : '90%',
        info                        : '10% so với ngày hôm trước',
        checkUpDown                 : 'up',
    };
    $scope.box5 = {
        head                        : 'Nhân viên chăm chỉ',
        color                       : 'org',
    };
    $scope.rb5s = [
        {
            num                     : '1',
            name                    : 'Phạm Diễm',
            monney                  : '5.000.000đ',
        },
        {
            num                     : '2',
            name                    : 'Phạm Diễm',
            monney                  : '5.000.000đ',
        },
        {
            num                     : '3',
            name                    : 'Phạm Diễm',
            monney                  : '5.000.000đ',
        },
        {
            num                     : '4',
            name                    : 'Phạm Diễm',
            monney                  : '5.000.000đ',
        },
        {
            num                     : '5',
            name                    : 'Phạm Diễm',
            monney                  : '5.000.000đ',
        }];
    $scope.box6 = {
        head                        : 'hành động mua hàng',
        add                         : '100',
        pay                         : '80',
        fin                         : '60',
    };
}).controller("order-ctrl", function($scope, $mdDialog) {
    $('.js-modal-hover').hover(function(){
        var obj = $(this);
        var checkExist = obj.find('.js-modal-content').length;
        if (checkExist > 0) {
            //xóa dữ liệu cũ (refresh)
            obj.find('.js-modal-content').remove();
            //return;
        };
        var typeModal = obj.attr('data-type-modal');
        var content = '';
        if (typeModal == 'order') {
            //call ajax get info
            var id = obj.attr('data-id')*1;
            if (id >0) {
                sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.getOrderDetail' + '&val[id]=' + id;
                killRequest();
                var xhr = $.ajax({
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
                        if (!empty(result)) {
                            var aStatusDeliver = result.general.select_status;
                            content += '<div class="js-modal-content">\
                                        <div class="header-modal">\
                                            Thông tin đơn hàng\
                                        </div>\
                                        <div class="inner-modal">\
                                            <div class="md1">\
                                                <div class="row20 line-bottom">\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Mã hoá đơn:</div>\
                                                        <div class="col7">'+ result.general.code +'</div>\
                                                    </div>\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Trạng thái:</div>\
                                                        <div class="col7">'+ aStatusDeliver[result.general.status_deliver] +'</div>\
                                                    </div>\
                                                </div>\
                                                <div class="row20 line-bottom">\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Ngày giờ đặt:</div>\
                                                        <div class="col7">'+ result.general.create_time +'</div>\
                                                    </div>\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Ngày giờ giao:</div>\
                                                        <div class="col7">'+ result.general.deliver_from_hour+'h-'+ result.general.deliver_to_hour+ 'h '+ result.general.deliver_day +'</div>\
                                                    </div>\
                                                </div>\
                                                <div class="row20 line-bottom">\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Người nhận:</div>\
                                                        <div class="col7">'+ result.general.customer.full_name +'</div>\
                                                    </div>\
                                                </div>\
                                                <div class="row20 line-bottom">\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Điện thoại:</div>\
                                                        <div class="col7">'+ result.general.customer.phone_number +'</div>\
                                                    </div>\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Email:</div>\
                                                        <div class="col7"></div>\
                                                    </div>\
                                                </div>\
                                                <div class="row20 line-bottom">\
                                                    <div class="col3 col3custom font-bold">Địa chỉ:</div>\
                                                    <div class="col9">'+ result.general.street +'</div>\
                                                </div>\
                                                <div class="row20 line-bottom">\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Tỉnh / Thành phố:</div>\
                                                        <div class="col7">'+ result.general.city_txt +'</div>\
                                                    </div>\
                                                    <div class="col6">\
                                                        <div class="col5 font-bold">Quận / Huyện:</div>\
                                                        <div class="col7">'+ result.general.district_txt +'</div>\
                                                    </div>\
                                                </div>\
                                                <div class="row20">\
                                                    <div class="col3 col3custom font-bold">Ghi chú:</div>\
                                                    <div class="col9">'+ result.general.note +'</div>\
                                                </div>';
                                        if (typeof(result.general.user_account) != 'undenfined' &&  !empty(result.general.user_account)) {
                                            content += '<div class="row20">';
                                            var title_tmp = '';
                                            for (i in result.general.user_account) {
                                                if (result.general.user_account[i].name_code == 'tien_mat') {
                                                    title_tmp = 'Tiền trong tài khoản:';
                                                }
                                                else if (result.general.user_account[i].name_code == 'tien_xu') {
                                                    title_tmp = 'Tài khoản Xu:';
                                                }
                                                else {
                                                    title_tmp = 'Tài khoản điểm:';
                                                }
                                                content +=  '<div class="col6">\
                                                        <div class="col5 font-bold">'+title_tmp+'</div>\
                                                        <div class="col7">'+ currencyFormat(result.general.user_account[i].point) + ' ' + result.general.user_account[i].name +' </div>\
                                                    </div>';
                                            }
                                            content += '</div>';
                                        }
                                        content +='</div>\
                                            <div class="md2 mgtop20">\
                                                <div class="row20">\
                                                    <div class="col4 font-bold">Sản phẩm</div>\
                                                    <div class="col2 font-bold txt-center">Số lượng</div>\
                                                    <div class="col2 font-bold txt-center">Khối lượng</div>\
                                                    <div class="col2 font-bold txt-center">Giá bán</div>\
                                                    <div class="col2 font-bold txt-right">Thành tiền</div>\
                                                </div>';
                                              for   (var i in result.detail.id) {
                                                  var cnt = i*1+1;
                                              content += '<div class="row20 line-bottom">\
                                                    <div class="col4">'+ cnt + '.' + result.detail.name[i] +'</div>\
                                                    <div class="col2 txt-center">' + result.detail.quantity[i] +'</div>\
                                                    <div class="col2 txt-center">' + result.detail.weight[i] +'</div>\
                                                    <div class="col2 txt-center">' + currencyFormat(result.detail.unit_price[i] - result.detail.price_discount[i]) +'</div>\
                                                    <div class="col2 txt-right">' + currencyFormat(result.detail.amount[i]) +'</div>\
                                                </div>';
                                              }
                                              
                                              content += '<div class="row20">\
                                                    <div class="col4 font-bold">Tổng</div>\
                                                    <div class="col2 txt-center">'+ result.general.total_product +'</div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2 txt-right">'+ currencyFormat(result.general.total_amount - result.general.surcharge) +'</div>\
                                                </div>\
                                                <div class="row20">\
                                                    <div class="col4 font-bold">Phí dịch vụ</div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2 txt-right">'+ currencyFormat(result.general.surcharge) +'</div>\
                                                </div>\
                                                <div class="row20">\
                                                    <div class="col4 font-bold">Tổng cộng</div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2"></div>\
                                                    <div class="col2 txt-right">'+ currencyFormat(result.general.total_amount) +'</div>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>';
                            obj.append(content);
                        }
                    }
                });
                
                requests.push(xhr);
            }
            
        }else if(typeModal == 'member'){
            content += '<div class="js-modal-content">\
                            <div class="header-modal">\
                                Thông tin đơn hàng\
                            </div>\
                            <div class="inner-modal">\
                                <div class="md1">\
                                    <div class="row20 line-bottom">\
                                        <div class="col6">\
                                            <div class="col5 font-bold">Mã khách hàng:</div>\
                                            <div class="col7">ABCD</div>\
                                        </div>\
                                        <div class="col6">\
                                            <div class="col5 font-bold">Họ tên:</div>\
                                            <div class="col7">Nguyễn Văn A</div>\
                                        </div>\
                                    </div>\
                                    <div class="row20 line-bottom">\
                                        <div class="col6">\
                                            <div class="col5 font-bold">Điện thoại:</div>\
                                            <div class="col7">12:12:12</div>\
                                        </div>\
                                        <div class="col6">\
                                            <div class="col5 font-bold">Email:</div>\
                                            <div class="col7">12:12:12</div>\
                                        </div>\
                                    </div>\
                                    <div class="row20 line-bottom">\
                                        <div class="col3 col3custom font-bold">Địa chỉ:</div>\
                                        <div class="col9">09090909090</div>\
                                    </div>\
                                    <div class="row20 line-bottom">\
                                        <div class="col6">\
                                            <div class="col5 font-bold">Tỉnh / Thành phố:</div>\
                                            <div class="col7">TP. HCM</div>\
                                        </div>\
                                        <div class="col6">\
                                            <div class="col5 font-bold">Quận / Huyện:</div>\
                                            <div class="col7">Bình Thạnh</div>\
                                        </div>\
                                    </div>\
                                    <div class="row20">\
                                        <div class="col3 col3custom font-bold">Lần đăng nhập cuối:</div>\
                                        <div class="col9">Thông tin ghi chú</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>';
        }
        //obj.append(content);
    })
    $scope.openRowOrder = function ($event, idObj){
        if($event.target.localName == 'select' || $event.target.localName == 'option'){
            return;
        }
        //hidden pagination
        $('.pagination').hide();
        $('#rowOrder' + idObj).find('.js-modal-content').remove();
        $('#js-show-ordr-info').addClass('js-button-wait');
        content = '';
        var jsRowOrder = $('#rowOrder' + idObj);
        $('.js-row-order').hide();
        jsRowOrder.show();
        
        // goi ajax
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=shop.showOrderDetail' + '&val[id]=' + idObj;
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
            },
            success: function (data) {
                $('#js-show-ordr-info').removeClass('js-button-wait');
                $('#js-show-ordr-info').html(data);
                initOrderDetail();
            }
        });
    };

    $scope.tab = {
        title_all                   : 'Tất cả',
        ab_all                      : ab,
        title_act                   : 'Đang xử lý',
        ab_act                      : ab,
        title_haspro                : 'Có hàng',
        ab_pro                      : ab,
        title_deliver               : 'Đang giao hàng',
        ab_deliver                  : ab,
        title_fin                   : 'Đã hoàn thành',
        ab_fin                      : ab,
        title_delstate              : 'Không thể giao hàng',
        ab_delstate                 : ab,
        title_return                : 'Hàng bị trả về',
        ab_return                   : ab,
        title_cancel                : 'Đã hủy',
        ab_cancel                   : ab
    };
}).controller("product-ctrl", function($scope, $mdDialog, $templateCache) {
    $scope.checkAllProduct = function (){
        
    }
    $scope.changeCat = function(){
        $('input[name="cat"]').val($scope.catProduct);
    }
    $scope.changeSup = function(){
        $('input[name="sup"]').val($scope.supProduct);
    }
    $scope.$on('$viewContentLoaded', function() {
      $templateCache.removeAll();
   });
    $scope.openUpdatePrice = function($event, idObj){
        //var className = $event.target.className;
        //        
        //        var listExist = ['md-container', 'md-button', 'md-select-icon', 'md-default-theme'];
        //        for(var i=0; i < listExist.length; i++){
        //            if (className.indexOf(listExist[i]) >= 0) {
        //                return false;
        //            };
        //        }
        content = '';
        //var jsRowOrder = $('#rowOrder' + idObj);
        // goi ajax
        sParams = '&'+ getParam('sGlobalTokenName') + '[call]=article.showUpdatePrice' + '&val[key]=' + idObj;

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
            },
            success: function (data) {
                setTimeout(function(){
                    $mdDialog.show({
                        template: data,
                        targetEvent: $event,
                        controller: 'DialogCtrl'
                    });
                }, 100);
            }
        });
        
    }
    $scope.ckAllLockCmt = function(){
        if($scope.ckalllockcmt == 'all') $scope.ckalllockcmt = 'active';
        else $scope.ckalllockcmt = 'all';
    }
    $scope.isActiveBigTab = function(index){
        return index === 'prd';
    }
    $scope.changeBigTab = function(tab){
        askq                        : 'Mum chưa?',
        $scope.isActiveBigTab = function(index){
            return index === tab;
        }
        $scope.showlist = '';
        if (tab == 'prd_comment'){
            $scope.showlist = 'cmt';
        }
        if (tab == 'prd_faqs'){
            $scope.showlist = 'faq';
        }
    }
    $scope.isActiveTab = function(index){
        return index === 'all';
    }
    $scope.changeTab = function(tab){
        $scope.isActiveTab = function(index){
            return index === tab;
        }
    }
    $('.product-catg').find('md-select').removeClass('none');
}).controller("add-ctrl", function($scope) {

}).controller("DialogCtrl", function($scope, $mdDialog) {
    scrollMenu();
    checkBoxCustom();
    expandGroup();

    $scope.closePopup = function(reload){
        if (typeof(reload) != 'undefined') {
            if (reload == 1)
                window.location.reload();
        }
        $mdDialog.cancel();
    }
    $scope.updatePrice = function(){
        var action = "$(this).ajaxSiteCall('article.updatePrice', 'afterUpdate(data)'); return false;";
        $('#frm_update_price').attr('onsubmit', action);
        $('#frm_update_price').submit();
        $('#frm_update_price').removeAttr('onsubmit');
        $('#js-update-price').unbind('click');
        $mdDialog.hide();
    }
    $scope.afterUpdate = function(data){
        if (data.status == 'error') {
            alert(data.message);
        }
        else if (data.status == 'success') {
            alert('Đã cập nhật thành công');
            location.reload();
        }
    }
}).controller("ChartCtrl", function($scope) {
    /*  Chart của trang chủ - Overview */
    if ($('.js-chart-overview').length > 0) {
        /*  Doanh số */
        var sales = $('#js-chart-sales').val();
        var data_row = [];
        var data_column = [];
        var data_trans = [];
        if (typeof(sales) != 'undefined' && sales != null) {
            sales = JSON.parse(sales);
            if (typeof(sales) == 'object') {
                for (i in sales) {
                    if (typeof(sales[i].date_dm) != 'undefine' && typeof(sales[i].don_hang) != 'undefine' && typeof(sales[i].tong_tien) != 'undefine') {
                        data_row.push(sales[i].date_dm);
                        data_column.push(sales[i].tong_tien);
                        data_trans.push(sales[i].don_hang);
                    }
                }
            }
        }
        //sales
        $scope.labelsDs = data_row;
        data_column_tmp = [];
        data_column_tmp.push(data_column);
        $scope.dataDs = data_column_tmp;
        
        //transaction
        $scope.labelsGd = data_row;
        data_column_tmp = [];
        data_column_tmp.push(data_trans);
        $scope.dataGd = data_column_tmp;
        
        //Access
        var access = $('#js-chart-access').val();
        var data_row = [];
        var data_visit = [];
        var data_view = [];
        if (typeof(access) != 'undefined' && access != null) {
            access = JSON.parse(access);
            if (typeof(access) == 'object') {
                for (i in sales) {
                    if (typeof(access[i].date_dm) != 'undefine' && typeof(access[i].visit) != 'undefine' && typeof(access[i].view) != 'undefine') {
                        data_row.push(access[i].date_dm);
                        data_visit.push(access[i].visit);
                        data_view.push(access[i].view);
                    }
                }
            }
            
        }
        
        $scope.labelsTc = data_row;
        data_column_tmp = [];
        data_column_tmp.push(data_visit);
        $scope.dataTc = data_column_tmp;
    }
    /*  Chart của trang payment */
    if($('.js-chart-payment').length > 0){
        /*  Màu dùng chung */
        $scope.colours = [
            {   /*  red */
                fillColor               : 'rgba(0,0,0,0)',
                strokeColor             : '#E50038',
                pointColor              : '#E50038',
                pointStrokeColor        : '#fff',
                pointHighlightFill      : '#fff',
                pointHighlightStroke    : '#E50038'
            },
            {   /*  green */
                fillColor               : 'rgba(0,0,0,0)',
                strokeColor             : '#35E45C',
                pointColor              : '#35E45C',
                pointStrokeColor        : '#fff',
                pointHighlightFill      : '#fff',
                pointHighlightStroke    : '#35E45C'
            },
            {   /*  orange */
                fillColor               : 'rgba(0,0,0,0)',
                strokeColor             : '#F88300',
                pointColor              : '#F88300',
                pointStrokeColor        : '#fff',
                pointHighlightFill      : '#fff',
                pointHighlightStroke    : '#F88300'
            },
            {   /*  Gray */
                fillColor               : 'rgba(0,0,0,0)',
                strokeColor             : '#999',
                pointColor              : '#999',
                pointStrokeColor        : '#fff',
                pointHighlightFill      : '#fff',
                pointHighlightStroke    : '#999'
            }
        ];

        /*  Bảng biểu đồ: Giá trị giao dịch */
        /*  1.1 Nạp vào */
        $scope.labelValInput    = ["01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08", "01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08"];
        $scope.seriesValInput   = ['Tiền mặt', 'Xu', 'Zen'];
        $scope.dataValInput     = [
            [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40],
            [28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19, 86, 27, 90],
            [86, 27, 90, 28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19]
        ];

        /*  1.2 SỬ dụng */
        $scope.labelValUse      = ["01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08", "01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08"];
        $scope.seriesValUse     = ['Tiền mặt', 'Xu', 'Zen'];
        $scope.dataValUse       = [
            [65, 59, 40, 65, 59, 80, 81, 56, 80, 81, 56, 55, 55, 40],
            [28, 48, 40, 19, 48, 40, 19, 86, 86, 27, 90, 28, 27, 90],
            [86, 27, 90, 28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19]
        ];

        /*  1.3 Còn lại */
        $scope.labelValRest     = ["01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08", "01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08"];
        $scope.seriesValRest    = ['Tiền mặt', 'Xu', 'Zen'];
        $scope.dataValRest      = [
            [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40],
            [28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19, 86, 27, 90],
            [86, 27, 90, 28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19]
        ];

        /*  Bảng biểu đồ: Lượt giao dịch */
        /*  2.1 Nạp vào */
        $scope.labelTransacInput    = ["01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08", "01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08"];
        $scope.seriesTransacInput   = ['Tiền mặt', 'Xu', 'Zen'];
        $scope.dataTransacInput     = [
            [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40],
            [28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19, 86, 27, 90],
            [86, 27, 90, 28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19]
        ];

        /*  2.2 SỬ dụng */
        $scope.labelTransacUse      = ["01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08", "01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08"];
        $scope.seriesTransacUse     = ['Tiền mặt', 'Xu', 'Zen'];
        $scope.dataTransacUse       = [
            [65, 59, 40, 65, 59, 80, 81, 56, 80, 81, 56, 55, 55, 40],
            [28, 48, 40, 19, 48, 40, 19, 86, 86, 27, 90, 28, 27, 90],
            [86, 27, 90, 28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19]
        ];

        /*  2.3 Còn lại */
        $scope.labelTransacRest     = ["01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08", "01/08", "02/08", "03/08", "04/08", "05/08", "06/08", "07/08"];
        $scope.seriesTransacRest    = ['Tiền mặt', 'Xu', 'Zen'];
        $scope.dataTransacRest      = [
            [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56, 55, 40],
            [28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19, 86, 27, 90],
            [86, 27, 90, 28, 48, 40, 19, 86, 27, 90, 28, 48, 40, 19]
        ];
    }
}).controller("DoughnutCtrl", function($scope){
    /*  Màu dùng chung */
    $scope.colours = [
        {   /*  red */
            fillColor               : 'rgba(0,0,0,0)',
            strokeColor             : '#E50038',
            pointColor              : '#E50038',
            pointStrokeColor        : '#fff',
            pointHighlightFill      : '#fff',
            pointHighlightStroke    : '#E50038'
        },
        {   /*  green */
            fillColor               : 'rgba(0,0,0,0)',
            strokeColor             : '#35E45C',
            pointColor              : '#35E45C',
            pointStrokeColor        : '#fff',
            pointHighlightFill      : '#fff',
            pointHighlightStroke    : '#35E45C'
        },
        {   /*  orange */
            fillColor               : 'rgba(0,0,0,0)',
            strokeColor             : '#F88300',
            pointColor              : '#F88300',
            pointStrokeColor        : '#fff',
            pointHighlightFill      : '#fff',
            pointHighlightStroke    : '#F88300'
        },
        {   /*  Gray */
            fillColor               : 'rgba(0,0,0,0)',
            strokeColor             : '#999',
            pointColor              : '#999',
            pointStrokeColor        : '#fff',
            pointHighlightFill      : '#fff',
            pointHighlightStroke    : '#999'
        },
        {   /*  Gray */
            fillColor               : 'rgba(0,0,0,0)',
            strokeColor             : '#d0d',
            pointColor              : '#d0d',
            pointStrokeColor        : '#fff',
            pointHighlightFill      : '#fff',
            pointHighlightStroke    : '#d0d'
        },
        {   /*  Gray */
            fillColor               : 'rgba(0,0,0,0)',
            strokeColor             : '#07d',
            pointColor              : '#07d',
            pointStrokeColor        : '#fff',
            pointHighlightFill      : '#fff',
            pointHighlightStroke    : '#07d'
        }
    ];

    /*  Pie chart loại tiền */
    $scope.labelTypeMoney   = ["Nạp tiền", "Sử dụng", "Còn lại", "Khác"];
    $scope.dataTypeMoney    = [300, 500, 100, 150];

    /*  Pie chart loại tiền */
    $scope.labelTypeCard    = ["Thẻ nội địa", "Thẻ Quốc tế", "Chuyển khoản Online", "Chuyển khoản ATM", "Chuyển khoản ngân hàng", "Thẻ điện thoại"];
    $scope.dataTypeCard     = [30, 20, 10, 30, 20, 10];

    console.log('13');
});


$.fn.ajaxSiteCall = function(sCall, sSuccessCall, sExtra, bNoForm, sType)
{
    if (empty(sType))
    {
        sType = 'POST';
    }
    var sUrl = getParam('sJsAjax');
    var sParams = '&' + getParam('sGlobalTokenName') + '[ajax]=true&' + getParam('sGlobalTokenName') + '[call]=' + sCall + (bNoForm ? '' : this.getForm());
    
    if (sExtra)
    {
        sParams += '&' + ltrim(sExtra, '&');
    }
    if (!sParams.match(/\[security_token\]/i))
    {
        sParams += '&' + getParam('sGlobalTokenName') + '[security_token]=' + oCore['log.security_token'];
    }
     
    xhr = $.ajax({
        crossDomain:true,
        xhrFields: {
            withCredentials: true
        },
        type: sType,
        url: sUrl,//getParam('sJsStatic') + "ajax.php",
        dataType: "json",    
        data: sParams,
        success: function(data){
            result = data;
            eval(sSuccessCall);
        }
    });
    requests.push(xhr);
}
$.ajaxSiteCall = function(sCall, sSuccessCall, sExtra, sType)
{
    return $.fn.ajaxSiteCall(sCall, sSuccessCall, sExtra, true, sType);
};
$.fn.getForm = function()
{
    var objForm = this.get(0);    
    var prefix = "";
    var submitDisabledElements = false;
    
    if (arguments.length > 1 && arguments[1] == true) {
        submitDisabledElements = true;
    }
    
    if(arguments.length > 2) {
        prefix = arguments[2];
    }

    var sXml = '';
    if (objForm && objForm.tagName == 'FORM') {
        var formElements = objForm.elements;        
        for(var i=0; i < formElements.length; i++) {
            if (!formElements[i].name) {
                continue;
            }
            
            if (formElements[i].name.substring(0, prefix.length) != prefix) {
                continue;
            }
            
            if (formElements[i].type && (formElements[i].type == 'radio' || formElements[i].type == 'checkbox') && formElements[i].checked == false) {
                continue;
            }
            
            if (formElements[i].disabled && formElements[i].disabled == true && submitDisabledElements == false) {
                continue;
            }
            
            var name = formElements[i].name;
            if (name) {                
                sXml += '&';
                if(formElements[i].type=='select-multiple') {
                    for (var j = 0; j < formElements[i].length; j++) {
                        if (formElements[i].options[j].selected == true) {
                            sXml += name+"="+encodeURIComponent(formElements[i].options[j].value)+"&";
                        }
                    }
                }
                else {
                    sXml += name+"="+encodeURIComponent(formElements[i].value);
                }
            }
        }
    }    

    if ( !sXml && objForm) {
        sXml += "&" + objForm.name + "="+ encodeURIComponent(objForm.value);
    }    
    
    return sXml;
}

/*  BỔ SUNG CỦA BACK-END GIAO VẬN */
myapp.controller("ChartReportShopperCtrl", function($scope) {
    var dataChartShopperTime = [];
    var dataChartShopperOrder = [];
    var dataChartShopperFree = [];
    if (typeof(chartReportShopper) != 'undefined' && !empty(chartReportShopper)) {
        for(var i in chartReportShopper) {
            dataChartShopperTime.push(chartReportShopper[i].time_txt);
            dataChartShopperOrder.push(chartReportShopper[i].order);
            dataChartShopperFree.push(chartReportShopper[i].free);
        }
    }
    $scope.labelsTrpOrder = dataChartShopperTime;
    $scope.dataTrpOrder = [
        dataChartShopperOrder
    ];

    $scope.labelsTrpFee = dataChartShopperTime;
    $scope.dataTrpFee = [
        dataChartShopperFree
    ];

    $('#chart-report-shopper, #chart-report-shopper .ch1').css('opacity', '1');
});

myapp.controller("ChartReportShipperCtrl", function($scope) {
    var dataChartShipperTime = [];
    var dataChartShipperOrder = [];
    var dataChartShipperDistance = [];
    var dataChartShipperFree = [];
    if (typeof(chartReportShipper) != 'undefined' && !empty(chartReportShipper)) {
        for(var i in chartReportShipper) {
            dataChartShipperTime.push(chartReportShipper[i].time_txt);
            dataChartShipperOrder.push(chartReportShipper[i].order);
            dataChartShipperDistance.push(chartReportShipper[i].distance);
            dataChartShipperFree.push(chartReportShipper[i].free);
        }
    }
    
    $scope.labelsTrpOrder = dataChartShipperTime;
    $scope.dataTrpOrder = [
        dataChartShipperOrder,
    ];

    $scope.labelsTrpFee = dataChartShipperTime;
    $scope.dataTrpFee = [
        dataChartShipperFree
    ];

    $scope.labelsTrpStreet = dataChartShipperTime;
    $scope.dataTrpStreet = [
        dataChartShipperDistance
    ];

    $('#chart-report-shipper, #chart-report-shipper .ch1').css('opacity', '1');
});