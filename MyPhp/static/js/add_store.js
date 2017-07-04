var infowindow;
var marker;
var geocoder;


$(function(){
    mapTransportDetail();
    $('#js-btn-submit').unbind('click').click(function(){
        $(this).addClass('js-button-wait');
        $('#frm_add').submit();
    });
});

function sbm_frm()
{
    //validate input
    var isSubmit = true;
    if ($('#js-vendor-id').val()*1 < 1) {
        alert('Không có thông tin nhà cung cấp');
        isSubmit = false;
    }
    //else if ( empty($('#js-name-store').val())) {
//        alert('Vui lòng nhập tên kho hàng');
//        isSubmit = false;
//    }
    else if ( empty($('#pac-input').val())) {
        alert('Vui lòng nhập địa chỉ kho hàng');
        isSubmit = false;
    }
    else if ( empty($('#js-lat-location').val()) || empty($('#js-lng-location').val())) {
        alert('Chưa xác định được thông tin địa điểm');
        isSubmit = false;
    }
    
    if (!isSubmit) {
        $('#js-btn-submit').removeClass('js-button-wait');
        return false;
    }
    
}

function mapTransportDetail(){
    var map = new google.maps.Map(document.getElementById('js-map-vendor-store'), {
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
    
    //show map by lat and long
    var loc_lat = $('#js-lat-location').val();
    var loc_lng = $('#js-lng-location').val();
    if (!empty(loc_lat) && !empty(loc_lng)) {
        var myLatlng = new google.maps.LatLng(loc_lat, loc_lng);
        placeMarker(myLatlng, map);
        if (marker) {
            map.setCenter(marker.getPosition());
        }
    }
    
    var markers = [];
    
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        markers.forEach(function(tmp_marker) {
          tmp_marker.setMap(null);
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
    //sự kiện click vào map chọn long - lat
    google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng, map);
    });
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
  //getAddress(location);
  infowindow.open(map,marker);
  //show info 
  $('#js-lat-location').val(location.lat());
  $('#js-lng-location').val(location.lng());
  
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
