<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script src="{{url('/js/gmap/gmaps.js')}}"></script>
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
  <style type="text/css">
    html, body {
      height: 100%;
      width: 100%;
    }
    #gmap {
      width: 100%;
      height: 100%;
      background: #ddd;
      position:absolute;top:0;left:0;
    }

    .overlap {
      position:relative;
    }

    #search-wrapper {
      margin-left: 10px;
      margin-top: 10px;
    }

    #history-wrapper {
      
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div id="gmap"></div>
  <div id="control-wrapper" class="container overlap"> 
    <div class="row">
      <div id="" class="col-xs-12 col-sm-6 col-md-8">
        <div id="search-wrapper" class="input-group">
          <input type="text" class="form-control" placeholder="Search for place">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button">Search</button>
          </span>
        </div>
      </div>
      <div id="history-wrapper" class="col-xs-6 col-md-4"><div>History</div></div>
    </div>
  </div>
  <script>
    var map = new GMaps({
      el: '#gmap',
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