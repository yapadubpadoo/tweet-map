<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Thujohn\Twitter\Facades\Twitter;
use Config;
use App\TweetsCaches;

class TwitterSearch extends Model
{
    public static function searchWithGEO($place_name, $lat, $lon)
    {
    	$miles = self::convertKilometersToMiles(Config::get('twitter_map.twitter_search_radius_in_km'));
    	$parameters = [
    		'q' => $place_name,
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
                if($raw_tweet->geo !== null && is_array($raw_tweet->geo->coordinates)) {
                    $tweet_cache = new TweetsCaches;
                    $tweet_cache->seach_location_hash = $seach_location_hash;
                    $tweet_cache->expire_at = $expire_at;
                    $tweet_cache->tweet_id = $raw_tweet->id;
                    $tweet_cache->tweet_lat = $raw_tweet->geo->coordinates[0];
                    $tweet_cache->tweet_lon = $raw_tweet->geo->coordinates[1];
                    $tweet_cache->tweet_message =  $raw_tweet->text;
                    $tweet_cache->tweet_at = $raw_tweet->created_at;
                    $tweet_cache->user_id = $raw_tweet->user->id;
                    $tweet_cache->user_name = $raw_tweet->user->name;
                    $tweet_cache->profile_image_url = $raw_tweet->user->profile_image_url;
                    $tweet_cache->save();
                    $cached_tweets[] = $tweet_cache;
                }
            }
        }
        return $cached_tweets;
    }


    public static function getTweetsFromGeo($place_name, $lat, $lon)
    {
        $seach_location_hash = md5($lat.', '.$lon);
        $tweets = TweetsCaches::getTweetsFromCacheByLocationHash($seach_location_hash);
        // no cache or tweets caches are expired
        // search from API and then store to tweets_caches
        if (count($tweets) == 0) {
            $raw_tweets = TwitterSearch::searchWithGEO($place_name, $lat, $lon);
            $tweets = TwitterSearch::cacheTweets($seach_location_hash, $raw_tweets);
        }
        return $tweets;
    }
}
