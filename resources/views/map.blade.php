<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script src="{{url('/js/gmap/gmaps.js')}}"></script>
  <style type="text/css">
    html, body {
      height: 100%;
      width: 100%;
    }
    #map {
      width: 100%;
      height: 100%;
      background: #ddd;
    }
  </style>
</head>
<body>
  <div id="map"></div>
  <script>
    var map = new GMaps({
      el: '#map',
      lat: 13.7468299,
      lng: 100.5327397
    });
    map.setZoom(12);
    GMaps.geocode({
      address: $('#address').val(),
      callback: function(results, status) {
        if (status == 'OK') {
          var latlng = results[0].geometry.location;
          map.setCenter(latlng.lat(), latlng.lng());
          map.addMarker({
            lat: latlng.lat(),
            lng: latlng.lng()
          });
        }
      }
    });
  </script>
</body>
</html>