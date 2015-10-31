<?php

use App\TweetsCaches;

class TweetsCachesTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        DB::table('tweets_caches')->truncate();

        $lat = 13.7468299;
        $lon = 100.5327397;
        $location_hash = md5('13.7468299, 100.5327397');

        $tweet = new TweetsCaches;
        $tweet->tweet_id = 1;
        $tweet->seach_location_hash = $location_hash;
        $tweet->tweet_lat = $lat;
        $tweet->tweet_lon = $lon;
        $tweet->tweet_message = 'This message is expired';
        $tweet->expire_at = '2015-10-31 12:00:00';
        $tweet->save();

        $tweet = new TweetsCaches;
        $tweet->tweet_id = 2;
        $tweet->seach_location_hash = $location_hash;
        $tweet->tweet_lat = $lat;
        $tweet->tweet_lon = $lon;
        $tweet->tweet_message = 'This message is expired';
        $tweet->expire_at = '2015-10-31 12:15:00';
        $tweet->save();

        $tweet = new TweetsCaches;
        $tweet->tweet_id = 3;
        $tweet->seach_location_hash = $location_hash;
        $tweet->tweet_lat = $lat;
        $tweet->tweet_lon = $lon;
        $tweet->tweet_message = 'This message is valid';
        $tweet->expire_at = date('Y-m-d H:i:s', time()+(60*60));
        $tweet->save();
    }

    public function testGetTweetsFromCacheByLocationHashShouldReturn1NonExpiredTweet()
    {
        $seach_location_hash = md5('13.7468299, 100.5327397');
        $tweets = TweetsCaches::getTweetsFromCacheByLocationHash($seach_location_hash);
        $this->assertEquals(3, $tweets[0]->tweet_id);
    }

    public function testGetTweetsFromCacheWithInvalidLocationHashShouldReturnCollentionWithNoLength()
    {
        $seach_location_hash = md5('10.1124, 103.1187');
        $tweets = TweetsCaches::getTweetsFromCacheByLocationHash($seach_location_hash);
        $this->assertEquals(0, count($tweets));
    }
}
