var oInfowindow;
var aMarker = [];
var oGeocoder;
var oGMap;

$(function(){
    initTrangsportAction();
});

/*  TRANG HOẠT ĐỘNG */
function initTrangsportAction(){
    mapTransportAction();
    showActivityUser();
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
    oGMap = new google.maps.Map(document.getElementById('map-transport-action'), {
        center: {
            /* VỊ TRI MẶC ĐỊNH */
            lat     : 21.014243794,
            lng     : 105.7952894},
            /* *
            lat     : 10.823032,
            lng     : 106.644287},
            /* */
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
    );

    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    oGMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    oGMap.addListener('bounds_changed', function() {
        searchBox.setBounds(oGMap.getBounds());
    });
    
    oInfowindow = new google.maps.InfoWindow();

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
        oGMap.fitBounds(bounds);
    });
    //////////////////////////////////
    /*
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
        oGMap
    );
    */
}

function showActivityUser()
{
    if (typeof(dataActivity) != 'undefined' && !empty(dataActivity)) {
        console.log(dataActivity);
        for(i in dataActivity) {
            var dataLocation = dataActivity[i];
            for (j in dataLocation) {
                var type_job = 'Người mua hàng';
                var icon_status = '//img.' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/shopper-';
                if (i == 1) {
                    type_job = 'Người giao hàng';
                    icon_status = '//img.' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/shipper-';
                }
                icon_status = icon_status + '0' + dataLocation[j].transport_status.status + '.png';
                console.log(icon_status);
                var myLatLng = {lat: parseFloat(dataLocation[j].map_location.location.lat), lng: parseFloat(dataLocation[j].map_location.location.lng)};
                var content_info = '' +
                '<div id="content">'+
                    '<div id="siteNotice">'+
                    '</div>'+
                    //'<div class="mylabel">'+dataLocation[j].info.fullname+'</div>'+
                    '<h1 id="secondHeading" class="secondHeading">'+dataLocation[j].info.fullname+'</h1>'+
                    '<div id="bodyContent">'+
                        '<p>'+type_job+'</p>' +
                        '<p>' +
                            '<div class="js-view-detail" data-id="'+dataLocation[j].info.id+'" data-job="'+i+'" style="cursor: pointer;" data-name="'+dataLocation[j].info.fullname+'" data-lat="'+parseFloat(dataLocation[j].map_location.location.lat)+'" data-lng="'+parseFloat(dataLocation[j].map_location.location.lng)+'">'+
                                'Xem chi tiết'+
                            '</div>'+
                        '</p>' +
                    '</div>'+
                    '</div>';
                placeMarkerByJob(myLatLng, i, content_info, icon_status);
            }
        }
        
        var options = {
            imagePath: '//img.' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/groupmarker/m',
            gridSize: 30
        };

        var markerCluster = new MarkerClusterer(oGMap, aMarker, options);
        
        
    }
}

function initInteractionView()
{
    $('.js-view-detail').unbind('click').click(function(){
        var user_id = $(this).attr('data-id')*1;
        var user_name = $(this).attr('data-name');
        var job = $(this).attr('data-job')*1;
        var lat = $(this).attr('data-lat');
        var lng = $(this).attr('data-lng');
        lat = parseFloat(lat);
        lng = parseFloat(lng);
        if (user_id) {
            //Hiển thị popup thông tin theo loại thành viên
            var html = '';
            if (job == 1) {
                html = '\
                <div class="container page-transport-view-popup pad20 js-popup-view-detail-user" style="width: 1200px">\
                    <div class="content-box panel-shadow mgbt20">\
                        <div class="box-title">\
                            Thông tin: '+user_name+'\
                        </div>\
                        <div class="box-inner">\
                            <div class="row30">\
                                <div class="col6">\
                                    <div id="map_view_detail"></div>\
                                </div>\
                                <div class="col3 padleft10">\
                                    <div class="view-action-detail">\
                                        <div class="row20 line-bottom padleft10">\
                                            Lộ trình hiện tại:\
                                        </div>\
                                        <div class="row20 container-detail action-info-container">\
                                            <div class="list-action-notify" id="js-list-action-direction">\
                                                <div class="item-notify">\
                                                    Hiện tại không có lộ trình.\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="col3 padleft10">\
                                    <div class="view-action-detail">\
                                        <div class="row20 line-bottom padleft10">\
                                            Hoạt động gần đây:\
                                        </div>\
                                        <div class="row20 container-detail action-info-container">\
                                            <div class="list-action-notify" id="js-list-action-notify">\
                                                <div class="item-notify">\
                                                    Không có hoạt động nào\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="row30 padtop20">\
                                <div class="row30">\
                                    <div class="col9"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-cancel-action-apply-order">Đóng</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>';
            }
            else {
                html = '\
                <div class="container page-transport-view-popup pad20 js-popup-view-detail-user" style="width: 800px">\
                    <div class="content-box panel-shadow mgbt20">\
                        <div class="box-title">\
                            Thông tin: '+user_name+'\
                        </div>\
                        <div class="box-inner">\
                            <div class="row30">\
                                <div class="col8">\
                                    <div id="map_view_detail"></div>\
                                </div>\
                                <div class="col4 padleft10">\
                                    <div class="view-action-detail">\
                                        <div class="row20 line-bottom padleft10">\
                                            Hoạt động gần đây:\
                                        </div>\
                                        <div class="row20 container-detail action-info-container">\
                                            <div class="list-action-notify" id="js-list-action-notify">\
                                                <div class="item-notify">\
                                                    Không có hoạt động nào\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="row30 padtop20">\
                                <div class="row30">\
                                    <div class="col9"></div>\
                                    <div class="col3 padleft10">\
                                        <div class="button-default" id="js-cancel-action-apply-order">Đóng</div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                </div>';
            }
            
            insertPopupCrm(html, ['#js-cancel-action-apply-order', '#js-submit-action-apply-order'],'.js-popup-view-detail-user', true);
                
            //Gọi ajax lấy thông tin
            sParams = '&'+ getParam('sGlobalTokenName')+'[call]=app.getRecentActivity'+'&val[user_id]='+user_id+'&val[job_type]='+job +'&val[lat]='+lat +'&val[lng]='+lng;
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
                        var zoomMap = 16;
                        if (job == 1) {
                            zoomMap = 12;
                        }
                        
                        //Vẽ map sau khi chay ajax xong
                        var map_detail = new google.maps.Map(document.getElementById('map_view_detail'), {
                            center: {
                                /* VỊ TRI MẶC ĐỊNH *
                                lat     : 21.014243794,
                                lng     : 105.7952894},
                                /* */
                                lat     : lat,
                                lng     : lng},
                                /* */
                                zoom: zoomMap,
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            }
                        );
                        
                        //show marker vị trí hiện tại
                        var myLatLng = {lat: lat, lng: lng};
                        var icon_current = '//img.' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/point-2-2.png';
                        //var icon_current = '//img' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/location-2-2.png';
                        var marker = new google.maps.Marker({
                            position: myLatLng,
                            map: map_detail,
                            title: 'Vị trí hiện tại',
                            icon: icon_current,
                        });

                        
                        var html_content = '';
                        //show activity
                        var aActivity = result.data.activity;
                        if (empty(aActivity)) {
                            //show empty
                            html_content += '<div class="item-notify">\
                                                Không có hoạt động nào\
                                            </div>';
                        }
                        else {
                            var aDataActivity = aActivity.data;
                            if (empty(aDataActivity)) {
                                //show empty
                                html_content += '<div class="item-notify">\
                                                Không có hoạt động nào\
                                            </div>';
                            }
                            else {
                                for (i in aDataActivity) {
                                    html_content += '<div class="item-notify">'+aDataActivity[i].detail+'</div>';
                                }
                            }
                        }
                        $('#js-list-action-notify').html(html_content);
                        
                        if (job == 1) {
                            //show route
                            html_content = '';
                            var aRoute = result.data.route;
                            if (empty(aRoute)) {
                                //show empty
                                html_content += '<div class="item-notify">\
                                                     Hiện tại không có lộ trình.\
                                                </div>';
                            }
                            else {
                                var aFirst = {};
                                var aLast = {};
                                var aMid = [];
                                var cnt = 0;
                                var length = Object.keys(aRoute).length;
                                
                                for (i in aRoute) {
                                    cnt++;
                                    var tmp = {lat: parseFloat(aRoute[i].lat), lng: parseFloat(aRoute[i].long)};
                                    if (cnt == 1) {
                                        aFirst = tmp;
                                    }
                                    else if (cnt == length) {
                                        aLast = tmp;
                                    }
                                    else {
                                        aMid.push(tmp);
                                    }
                                    
                                    html_content += '<div class="item-notify">'+aRoute[i].info.text+'</div>';
                                }
                                
                                showRoutemap(
                                      //bắt đầu
                                    aFirst,
                                     //kết thúc
                                    aLast,
                                     //danh sách điểm
                                    aMid,
                                    map_detail
                                );
                            }
                            
                            $('#js-list-action-direction').html(html_content);
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

function placeMarkerByJob(location, job, content_info, icon_marker) {
    if (typeof(icon_marker) == 'undefined' || icon_marker == '') {
        if (job == 1) {
            icon_marker = '//img.' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/shipper-01.png';
            //icon_marker = '//img' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/location-2-2.png';
        }
        else {
            icon_marker = '//img.' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/shopper-01.png';
            //icon_marker = '//img' + oParams['sJsHostname'] + '/styles/web/cms/images/transport/location-1-2.png';
        }
    }
    
    var marker;
    marker = new google.maps.Marker({
        position: location,
        map: oGMap,
        icon: icon_marker,
    });
    aMarker.push(marker);
    marker.addListener('click', function() {
        //xóa các infowindow trước đó
        oInfowindow.close();
        //oInfowindow.setContent(content_info);
        oInfowindow.setOptions({
            content: content_info,
            maxWidth:400
        });
        oInfowindow.open(oGMap, marker);
        initInteractionView();
    });
}

function showRoutemap(begin, end, listPointArr, map){
    var directionsService = new google.maps.DirectionsService;
    var directionsDisplay = new google.maps.DirectionsRenderer({
        draggable: true,
        map: map,
        //panel: document.getElementById('right-panel')
    });
    
    directionsDisplay.addListener('directions_changed', function() {
        //computeTotalDistance(directionsDisplay.getDirections());
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