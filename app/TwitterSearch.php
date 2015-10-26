<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Thujohn\Twitter\Facades\Twitter;
use Config;

class TwitterSearch extends Model
{
    public static function searchWithGEO($lat, $lon)
    {
    	$miles = self::convertKilometersToMiles(Config::get('twitter_map.twitter_search_radius_in_km'));
    	$parameters = [
    		'q' => '',
    		'geocode' => $lat.','.$lon.','.$miles.'mi'
    	];
    	$tweets = Twitter::getSearch($parameters);
    	return $tweets;
    }

    public static function convertKilometersToMiles($km)
    {
    	return intval($km * 0.621371);
    }
}
