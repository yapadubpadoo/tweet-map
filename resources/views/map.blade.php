<!DOCTYPE html>
<html>
<head>
  <title>Tweet Map</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{url('/img/Twitter.ico')}}" type="image/icon" sizes="16x16">
  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script src="{{url('/js/gmap/gmaps.js')}}"></script>
  <script src="{{url('/js/js-cookie-master/src/js.cookie.js')}}"></script>
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
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

    #search-history-wrapper, #history-list {

      margin-top: 10px;
    }

    .history-item {
      cursor: pointer;
    }

    @media (max-width:800px) {
      #control-wrapper {
        /*margin-top: 30px;*/
      }
    }

    @media (max-width:414px) {
      #control-wrapper {
        /*margin-top: 60px;*/
      }
    }
  </style>
</head>
<body>
  <div id="gmap"></div>
  <div id="control-wrapper" class="container overlap">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <form id="search-form">
          <div id="search-wrapper" class="input-group">
              <input id="city-input" type="text" class="form-control" placeholder="Search for Tweets in city" autocomplete="off">
              <span class="input-group-btn">
                <button id="search-button" class="btn btn-default" type="submit">Search</button>
                <button id="history-button" class="btn btn-default" type="button" onclick="toggleSearchHistory();">History</button>
              </span>
          </div>
        <form>
      </div>
    </div>
  </div>
  <div id="search-history-wrapper" class="container" style="display:none;">
    <div class="col-xs-12 col-md-12">
      <div><button id="search-button" class="btn btn-default" type="button" onclick="toggleSearchHistory();">Back to Tweets</button></div>
      <table id="history-list" class="table table-striped">
      </table>
    </div>
  </div>
  <script>
    var map;
    $(document).ready(function(){
      map = new GMaps({
        el: '#gmap',
        lat: 13.7468299,
        lng: 100.5327397,
        zoomControl: false,
        mapTypeControl: false
      });
      map.setZoom(12);

      $('#search-form').submit(function(e){
        e.preventDefault();
        // loading marker
        $('#search-button').html('Loading');
        searchForTweets();
      });
    });
    
    function searchForTweets() {
      GMaps.geocode({
        address: $('#city-input').val(),
        callback: function(results, status) {
          map.removeMarkers();
          if (status == 'OK') {
            var place = results[0].formatted_address;
            var latlng = results[0].geometry.location;
            var lat = latlng.lat();
            var lon = latlng.lng();
            var search_data = {
              place: place,
              lat: lat,
              lon: lon
            };
            $('#city-input').val(place);
            saveSearchHistory(place);
            $.getJSON('/search/tweet', search_data, function(tweets){
                $.each(tweets, function(index, tweet){
                  addTweetMarker(tweet);
                });
                setTimeout(function(){
                  map.fitZoom();
                  map.setCenter(lat, lon);
                  $('#search-button').html('Search');
                }, 100);
            });
          }
        }
      });
    }
    

    function addTweetMarker(tweet) {
      var icon = '/img/Twitter.png';
      var lat = tweet.tweet_lat;
      var lng = tweet.tweet_lon;
      var title = tweet.tweet_at;
      var content = '<img src="'+tweet.profile_image_url+'" style="padding:10px;" align="left">'
        +tweet.tweet_message+'<br><i>'+tweet.tweet_at+'</i>';

      map.addMarker({
        icon: icon,
        lat: lat,
        lng: lng,
        title: title,
        infoWindow: {
          content : content
        }
      });
    }

    function saveSearchHistory(location) {
      var search_history = Cookies.getJSON('tweet-map-history');
      console.log(search_history);
      if (search_history==undefined) {
        search_history = [];
      }
      if (search_history.indexOf(location)<0) {
        console.log('pushing ... '+location);
        search_history.push(location);
      }
      Cookies.set('tweet-map-history', search_history, { 
        expires: 1 
      });
    }

    function toggleSearchHistory() {
      $('#gmap').toggle();
      $('#control-wrapper').toggle();
      $('#search-history-wrapper').toggle();

      // check whether search-history-wrapper being display or not
      if(($('#search-history-wrapper').css('display')) == 'block') {
        var search_history = Cookies.getJSON('tweet-map-history');
        console.log('reading search history');
        console.log(search_history);
        $('#history-list').html('');
        $(search_history).each(function(index, location){
          var history_id = 'search-history-'+index;
          $('#history-list').append('<tr class="history-item"id="'+history_id+'"><td>'+location+'</td></tr>');
          $('#'+history_id).data("location-name", location);
          $('#'+history_id).click(function(){
            var search_location = $(this).data("location-name");
            // simulate search with existing functions, current flow
            $('#city-input').val(search_location);
            $('#search-button').click();
            toggleSearchHistory();
          });
        });
      }
    }
  </script>
</body>
</html>
