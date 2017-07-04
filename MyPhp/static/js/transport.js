var infowindow;
var marker;
var geocoder;
var map;
function initAppTrangsport()
{
    $('.js-app-close-detail').click(function(){
        if (typeof(sBackLinks) != 'undefined' && !empty(sBackLinks)) {
            window.location = sBackLinks;
        }
    });
}

/*  GHI CHÚ:
    - JAVASCRIPT DÙNG CHUNG CHO TẤT CẢ TRANG GIAO VẬN BACK-END
    - VỚI MỖI TRANG SẼ DÙNG BIẾN ID CỦA TRANG ĐỂ LOAD ĐÚNG JAVASCRIPT CỦA TRANG ĐÓ
    */
$(function(){
    initAppTrangsport();

    /*  TRANG CHI TIẾT VẬN ĐƠN */
    if ($('#page-transport-order-detail').length > 0) {
        initOrderTransportDetail();
    };

    /*  Trang hoạt động */
    if ($('#page-transport-action').length ) {
        initTrangsportAction();
    };
    
    $('.pagi-select').change(function(){
        var val = $(this).val()*1;
        if (val > 0 && typeof(link_limit) != 'undefined' && link_limit != '') {
            //chuyển trang theo page size
            window.location = link_limit + '&page_size=' + val;
        }
    });
});

/*  TRANG CHI TIẾT VẬN ĐƠN */
function initOrderTransportDetail(){
    mapTransportDetail();
    initViewVerifyCode();
    $('#js-code-verify').click(function(){
        var order_id = $('#js-app-order-id').val()*1;
        if (order_id > 0) {
            var is_return = $('#js-is-return-order').val()*1;
            var html = '<div class="container page-transport-popup pad20 js-popup-code-verify" style="width: 500px">' +
                '<div class="content-box panel-shadow mgbt20">' +
                    '<div class="box-title">' +
                        'Thông tin mã xác nhận' +
                    '</div>' +
                    '<div class="box-inner">';
                    if (!is_return) {
                        html += '<div class="row30 padtb10">' +
                            '<div class="col9">' +
                                '<div class="sub-black-title">Người vận chuyển cấp cho người mua hàng</div>' +
                            '</div>' +
                            '<div class="col3">' +
                                '<div class="button-blue js-view-verify-code" data-type="ship_code">Xem</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row30 padtb10">' +
                            '<div class="col9">' +
                                '<div class="sub-black-title">Khách hàng cấp cho người vận chuyển</div>' +
                            '</div>' +
                            '<div class="col3">' +
                                '<div class="button-blue js-view-verify-code" data-type="customer_code">Xem</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row30 padtb10">' +
                            '<div class="col9">' +
                                '<div class="sub-black-title">Thay đổi thông tin đơn hàng</div>' +
                            '</div>' +
                            '<div class="col3">' +
                                '<div class="button-blue js-view-verify-code" data-type="adjust_order">Xem</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row30 padtb10 mgbt10 line-bottom">' +
                            '<div class="col9">' +
                                '<div class="sub-black-title">Thu ngân cấp khi người vận chuyển giao tiền</div>' +
                            '</div>' +
                            '<div class="col3">' +
                                '<div class="button-blue js-view-verify-code" data-type="receive_money" data-type_sub="order">Xem</div>' +
                            '</div>' +
                        '</div>';
                    }
                    else {
                        html += '<div class="row30 padtb10 mgbt10">' +
                            '<div class="col9">' +
                                '<div class="sub-black-title">Nhận hàng hoàn trả từ người vận chuyển</div>' +
                            '</div>' +
                            '<div class="col3">' +
                                '<div class="button-blue js-view-verify-code" data-type="return_order">Xem</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row30 padtb10 mgbt10 line-bottom">' +
                            '<div class="col9">' +
                                '<div class="sub-black-title">Mã khách hàng trả sản phẩm</div>' +
                            '</div>' +
                            '<div class="col3">' +
                                '<div class="button-blue js-view-verify-code" data-type="return_customer">Xem</div>' +
                            '</div>' +
                        '</div>';
                    }
                    html += '<div class="row30 padtop">' +
                            '<div class="row30">' +
                                '<div class="col9"></div>' +
                                '<div class="col3">' +
                                    '<div class="button-default" id="js-cancel-verify-code">Đóng</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
            insertPopupCrm(html, ['#js-cancel-verify-code'],'.js-popup-code-verify', true);
            initViewVerifyCode();
        }
    });
    
    $('#js-select-store').click(function(){
        //gọi ajax để lấy thông tin
        var vendor = $(this).attr('data-vendor')*1;
        var order_id = $('#js-app-order-id').val()*1;
        if (vendor > 0 && order_id > 0) {
            insertPopupCrm('\
            <div class="container page-transport-popup pad20 js-box-setting-store" style="width: 500px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Thông tin mã xác nhận\
                    </div>\
                    <div class="box-inner">\
                        <div class="row30 padtb10 line-bottom" id="js-store-container">\
                            <div class="col12 padlr10">\
                                <div class="sub-black-title">Loading ...</div>\
                            </div>\
                        </div>\
                        <div class="row30 padtop10">\
                            <div class="row30">\
                                <div class="col6">\
                                </div>\
                                <div class="col3 padright10">\
                                    <div class="button-blue none" id="js-setting-store">Chọn</div>\
                                </div>\
                                <div class="col3">\
                                    <div class="button-default" id="js-popup-setting-store">Đóng</div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div>', ['#js-popup-setting-store'],'.js-box-setting-store', true);
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.getStoreByVendor' + '&val[vid]=' +vendor;
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
                },
                success: function (result) {
                    if(isset(result.status) && result.status == 'success') {
                        console.log(result);
                         var html = '';
                        if (typeof(result.link) != 'undefined' && empty(result.data)) {
                            //chưa có kho nào được thiết lập
                            html += '<div class="col8">\
                                <div class="sub-black-title">Không tìm thấy Kho hàng nào. Vui lòng cập nhật kho hàng</div>\
                            </div>\
                            <div class="col4">\
                                <div class="button-blue" data-link="'+result.link+'" id="js-redirect-store">Cập nhật kho hàng</div>\
                            </div>';
                            $('#js-store-container').html(html);
                            $('#js-redirect-store').unbind('click').click(function(){
                                window.location = $(this).attr('data-link');
                            });
                        }
                        else {
                            var store_id = $('#js-app-store-id').val()*1;
                            html += '<div class="col4">\
                                <div class="sub-black-title">Kho hàng:</div>\
                            </div>\
                            <div class="col8">\
                                <select name="" id="js-sel-box-store">\
                                    <option value="-1">Chọn kho hàng</option>';
                            for(i in result.data) {
                                var selected = '';
                                if (store_id == result.data[i].id) {
                                    selected = 'selected="selected"'
                                }
                                html += '<option data-lat="'+result.data[i].lat+'" data-lng="'+result.data[i].lng+'" data-ad="'+result.data[i].address+'"  value="'+result.data[i].id+'" '+selected+'>'+result.data[i].name+'</option>';
                            }
                            
                            html += '</select>\
                            </div>';
                            $('#js-setting-store').removeClass('none');
                            $('#js-setting-store').unbind('click').click(function(){
                                settingStoreForOrder();
                            });
                            $('#js-store-container').html(html);
                        }
                    }
                    else {
                        var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                        alert(messg);
                    }
                }
            });
        }
    });
}

function settingStoreForOrder()
{
    obj = {};
    obj['val'] = $('#js-sel-box-store').val();
    var order_id = $('#js-app-order-id').val()*1;
    if (obj['val'] < 1) {
        alert('Vui lòng chọn kho hàng');
    }
    //Kiểm tra có thay đổi kho hàng so với trước đó ko
    var old_store = $('#js-app-store-id').val()*1;
    if (obj['val'] == old_store) {
        return false;
    }
    objSel = $("#js-sel-box-store option[value='"+obj['val']+"']");
    obj['lat'] = objSel.attr('data-lat');
    obj['lng'] = objSel.attr('data-lng');
    obj['address'] = objSel.attr('data-ad');
    //Ajax lưu thông tin
    $('#js-setting-store').addClass('js-button-wait');
    sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.settingStoreForOrder' + '&val[stid]='+ obj['val'] + '&val[oid]='+order_id;
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
            $('#js-setting-store').removeClass('js-button-wait');
        },
        success: function (result) {
            if(isset(result.status) && result.status == 'success') {
                alert('Đã cập nhật thành công');
                $('#js-popup-setting-store').click();
                //Lưu các thông tin hiển thị
                $('#js-app-store-id').val(obj['val']);
                $('#js-store-lat').val(obj['lat']);
                $('#js-store-lng').val(obj['lng']);
                
                showRoute();
            }
            else {
                var messg = isset(result.message) ? result.message : 'Lỗi hệ thống';
                alert(messg);
                $('#js-setting-store').removeClass('js-button-wait');
            }
        }
    });
}

function initViewVerifyCode()
{
    $('.js-view-verify-code').unbind('click').click(function(){
        var objSelect = $(this);
        var order_id = $('#js-app-order-id').val()*1;
        var shopper_id = $('#js-shopper-id').val()*1;
        var shipper_id = $('#js-shipper-id').val()*1;
        var type = objSelect.attr('data-type');
        var type_sub = objSelect.attr('data-type_sub');
        if (typeof(type_sub) == 'undefined') {
            type_sub = '';
        }
        if (order_id > 0 && typeof(type) != 'undefined' && type != '') {
            if (type == 'receive_money') {
                if (shipper_id < 1) {
                    alert('Không có thông tin người vận chuyển');
                    return false;
                }
            }
            else if (type == 'return_order' || type == 'return_customer') {
                
            }
            else {
                if (shopper_id < 1) {
                    //Đơn hàng chưa được nhận
                    alert('Đơn hàng chưa được nhận giao vận');
                    return false;
                }
            }
            objSelect.addClass('js-button-wait');
            //call ajax get code
            sParams = '&'+ getParam('sGlobalTokenName') + '[call]=app.getCodeVerify' + '&val[type]=' + type + '&val[order_id]='+ order_id + '&val[shopper_id]='+ shopper_id + '&val[shipper_id]='+ shipper_id + '&val[type_sub]=' + type_sub;
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
                        alert(result.data.title + ': ' + result.data.code_verify)
                    }
                    else {
                        var mssg = typeof(result.message) != 'undefined' ? result.message : 'Lỗi hệ thống';
                        alert(mssg);
                    }
                    objSelect.removeClass('js-button-wait');
                }
            });
        }
        
    });
}

function mapTransportDetail(){
    map = new google.maps.Map(document.getElementById('map-transport-order-detail'), {
        center: {
            /* VỊ TRI MẶC ĐỊNH*/
            lat     : 21.014243794,
            lng     : 105.7952894},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );
    geocoder = new google.maps.Geocoder();
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });
    
    var markers = [];
    
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        markers.forEach(function(marker) {
          marker.setMap(null);
        });
        markers = [];

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            placeMarker(place.geometry.location, map);
            
            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
    /* 
    google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng, map);
      });
    */
    //////////////////////////////////
    
    showRoute();
}

function showRoute()
{
    var cus_lat = $('#js-cus-lat').val()*1;
    var cus_lng = $('#js-cus-lng').val()*1;
    var store_lat = $('#js-store-lat').val()*1;
    var store_lng = $('#js-store-lng').val()*1;
    
    if (!empty(cus_lat) && !empty(cus_lng)) {
        //xóa các vị trí cũ
        if (marker) {
            marker.setMap(null);
            marker = null;
        }
        if (!empty(store_lat) && !empty(store_lng)) {
            console.log(2);
            showRoutemap(
                  //bắt đầu
                {lat: store_lat,lng: store_lng},
                 //kết thúc
                {lat: cus_lat, lng: cus_lng},
                 //danh sách điểm
                [
                    //{lat: 10.798171, lng: 106.689888},
                    //{lat: 10.794143, lng:106.690217}
                ],
                map
            );
        }
        else {
            //Chỉ show 1 tọa độ
            var myLatlng = new google.maps.LatLng(cus_lat, cus_lng);
            placeMarker(myLatlng, map);
        }
    }
}


function showMapToSelectAddress()
{
    google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng);
      });
}
function placeMarker(location, map) {
  if (infowindow) {
      infowindow.close();
  }
  if (marker) {
      marker.setMap(null);
      marker = null;
  }
  marker = new google.maps.Marker({
    position: location,
    map: map,
  });
  infowindow = new google.maps.InfoWindow({
    content: 'Latitude: ' + location.lat() +
    '<br>Longitude: ' + location.lng()
  });
  google.maps.event.addListener(infowindow, 'domready', function(){
        $(".gm-style-iw").next("div").hide();
    });
  getAddress(location);
  infowindow.open(map,marker);
}

function getAddress(latLng) {
    geocoder.geocode( {'latLng': latLng},
    function(results, status) {
        var address = '';
        if(status == google.maps.GeocoderStatus.OK) {
            if(results[0]) {
                address = results[0].formatted_address;
            }
            else {
                address = "No results";
            }
        }
        else {
            address = status;
        }
        console.log(address);
    });
}

function showRoutemap(begin, end, listPointArr, map){
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer({
        draggable: true,
        map: map,
        panel: document.getElementById('right-panel')
    });

    directionsDisplay.addListener('directions_changed', function() {
        computeTotalDistance(directionsDisplay.getDirections());
    });

    var listPoint = [];
    for (var i = 0; i < listPointArr.length; i++) {
        listPoint.push({
            location: listPointArr[i]
        })
    };
    displayRoute(begin, end, listPoint, directionsService,directionsDisplay);
}
function displayRoute(origin, destination, listPoint, service, display) {
    service.route({
        origin: origin,
        destination: destination,
        waypoints: listPoint,
        travelMode: google.maps.TravelMode.DRIVING,
        avoidTolls: true
    }, function(response, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            display.setDirections(response);
        } else {
            alert('Không thể hiển thị kết quả: ' + status);
        }
    });
}

function computeTotalDistance(result) {
    var total = 0;
    var myroute = result.routes[0];
    for (var i = 0; i < myroute.legs.length; i++) {
        total += myroute.legs[i].distance.value;
    }
    total = total / 1000;
    document.getElementById('total').innerHTML = total + ' km';
}

/*  TRANG HOẠT ĐỘNG */
function initTrangsportAction(){
    //mapTransportAction();

    $('#js-action-filter').click(function(){
        insertPopupCrm('\
            <div class="container page-transport-popup pad20 js-popup-action-filter" style="width: 500px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Theo dõi với bộ lọc\
                    </div>\
                    <div class="box-inner">\
                        <form action="#" id="" method="post">\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Vị trí</div>\
                                <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Bán kính</div>\
                                <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Đối tượng</div>\
                                <select name="" id="" style="width: 100%">\
                                    <option value="">Tất cả</option>\
                                    <option value="">Người vận chuyển</option>\
                                    <option value="">Người mua hàng</option>\
                                </select>\
                            </div>\
                            <div class="row30 padtop">\
                                <div class="row30">\
                                    <div class="col6"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-cancel-action-filter">Hủy</div>\
                                    </div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-blue" id="js-submit-action-filter">Áp dụng</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>', ['#js-cancel-action-filter', '#js-submit-action-filter'],'.js-popup-action-filter', true);
    });

    $('#js-action-apply-order').click(function(){
        insertPopupCrm('\
            <div class="container page-transport-popup pad20 js-popup-action-apply-order" style="width: 600px">\
                <div class="content-box panel-shadow mgbt20">\
                    <div class="box-title">\
                        Áp dụng đơn hàng\
                    </div>\
                    <div class="box-inner">\
                        <form action="#" id="" method="post">\
                            <div class="row30 padtb10">\
                                <div class="sub-black-title">Giá trị</div>\
                                <div class="row30">\
                                    <div class="col2 padright10">\
                                        <select name="" id="" style="width: 100%">\
                                            <option value="">>=</option>\
                                            <option value=""><=</option>\
                                        </select>\
                                    </div>\
                                    <div class="col4 padright10">\
                                        <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                    </div>\
                                    <div class="col2 padright10">\
                                        <select name="" id="" style="width: 100%">\
                                            <option value="">>=</option>\
                                            <option value=""><=</option>\
                                        </select>\
                                    </div>\
                                    <div class="col4">\
                                        <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="col6 padright10">\
                                    <div class="sub-black-title">Tặng tiền</div>\
                                    <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                </div>\
                                <div class="col6">\
                                    <div class="sub-black-title">Đơn vị</div>\
                                    <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                </div>\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="col6 padright10">\
                                    <div class="sub-black-title">Tặng tiền</div>\
                                    <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                </div>\
                                <div class="col6">\
                                    <div class="sub-black-title">Đơn vị</div>\
                                    <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                </div>\
                            </div>\
                            <div class="row30 padtb10">\
                                <div class="col6 padright10">\
                                    <div class="sub-black-title">Giảm giá</div>\
                                    <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                </div>\
                                <div class="col6">\
                                    <div class="sub-black-title">Đơn vị</div>\
                                    <select name="" id="" style="width: 100%">\
                                        <option value="">Xu</option>\
                                        <option value="">Tiền mặt</option>\
                                    </select>\
                                </div>\
                            </div>\
                            <div class="row30 padtb20 line-bottom mtbt20 ">\
                                <div class="col6 padright10">\
                                    <div class="sub-black-title">Phí vận chuyển</div>\
                                    <input name="" class="default-input" placeholder="Điền thông tin" type="text">\
                                </div>\
                                <div class="col6">\
                                    <div class="sub-black-title">Đơn vị</div>\
                                    <select name="" id="" style="width: 100%">\
                                        <option value="">Xu</option>\
                                        <option value="">Tiền mặt</option>\
                                    </select>\
                                </div>\
                            </div>\
                            <div class="row30 padtop20">\
                                <div class="row30">\
                                    <div class="col6"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-cancel-action-apply-order">Hủy</div>\
                                    </div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-blue" id="js-submit-action-apply-order">Áp dụng</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </form>\
                    </div>\
                </div>\
            </div>', ['#js-cancel-action-apply-order', '#js-submit-action-apply-order'],'.js-popup-action-apply-order', true);
    });
}
function mapTransportAction(){
    var map = new google.maps.Map(document.getElementById('map-transport-action'), {
        center: {
            /* VỊ TRI MẶC ĐỊNH*/
            lat     : 21.014243794,
            lng     : 105.7952894},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );

    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        markers.forEach(function(marker) {
          marker.setMap(null);
        });
        markers = [];

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
    //////////////////////////////////
    showRoutemap(
        //  bắt đầu
        {lat: 10.802031,lng: 106.682607},
        // kết thúc
        {lat: 10.791280, lng: 106.695109},
        // danh sách điểm
        [
            {lat: 10.798171, lng: 106.689888},
            {lat: 10.794143, lng:106.690217}
        ],
        map
    );
}