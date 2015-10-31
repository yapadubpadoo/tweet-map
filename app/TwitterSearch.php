<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Thujohn\Twitter\Facades\Twitter;
use Config;
use App\TweetsCaches;

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

    public static function cacheTweets($seach_location_hash, $raw_tweets)
    {
        $cached_tweets = [];
        $expire_at = date('Y-m-d H:i:s', time()+(60*60)); // next 1 hour 
        if (isset($raw_tweets->statuses)) {
            foreach ($raw_tweets->statuses as $raw_tweet) {
                $tweet = new TweetsCaches;
                $tweet->seach_location_hash = $seach_location_hash;
                $tweet->expire_at = $expire_at;
                $tweet->tweet_id = $raw_tweet->id;
                
                if($raw_tweet->geo !== null && is_array($raw_tweet->geo)) {
                    $tweet->tweet_lat = $raw_tweet->geo[0];
                    $tweet->tweet_lon = $raw_tweet->geo[1];
                }
                $tweet->tweet_message =  $raw_tweet->text;
                $tweet->tweet_at = $raw_tweet->created_at;
                $tweet->user_id = $raw_tweet->user->id;
                $tweet->user_name = $raw_tweet->user->name;
                $tweet->profile_image_url = $raw_tweet->user->profile_image_url;
                $tweet->save();
                $cached_tweets[] = $tweet;
            }
        }
        return $cached_tweets;
    }


    public static function getTweetsFromGeo($lat, $lon)
    {
        $seach_location_hash = md5($lat.', '.$lon);
        $tweets = TweetsCaches::getTweetsFromCacheByLocationHash($seach_location_hash);
        // no cache or tweets caches are expired
        // search from API and the store to tweets_caches
        if (count($tweets) == 0) {
            $raw_tweets = Twitter::getSearch($parameters);
            $tweets = TwitterSearch::cacheTweets($seach_location_hash, $raw_tweets);
        }
        return $tweets;
    }
}
