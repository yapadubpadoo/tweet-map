<?php

use \stdClass;
use App\TwitterSearch;

class TwitterSearchTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testConvert50KilometersToMiles()
    {
        $miles = TwitterSearch::convertKilometersToMiles(50);
        $this->assertEquals(31, $miles);
    }

    // public function testSearchWithValidGEOCodeShouldReturnTweets()
    // {
    //     // Siam Paragon
    //     $tweets = TwitterSearch::searchWithGEO($lat = 13.7468299, $lon = 100.5327397);
    //     $this->assertObjectHasAttributes('statuses', $tweets);
    // }

    public function testCacheTweetsFromRawTweetsShouldReturnCachedTweets()
    {
        // create dummy tweets
        // not all of attributes are presented, only madatory attributes to save to db
        $raw_tweets = new stdClass();
        $raw_tweets->statuses = [];

        $raw_tweets->statuses[0] = new stdClass();
        $raw_tweets->statuses[0]->created_at = 'Mon Oct 26 18:01:13 +0000 2015';
        $raw_tweets->statuses[0]->id = '658704951820972033';
        $raw_tweets->statuses[0]->text = 'this is a tweet message (1)';
        $raw_tweets->statuses[0]->user = new stdClass();
        $raw_tweets->statuses[0]->user->id = 370263661;
        $raw_tweets->statuses[0]->user->name = 'ニシャナン';
        $raw_tweets->statuses[0]->user->profile_image_url = 'http://pbs.twimg.com/profile_images/619541315290673153/geXPytAT_normal.jpg';
        $raw_tweets->statuses[0]->geo = new stdClass();
        $raw_tweets->statuses[0]->geo->coordinates = [13.87003723, 100.54855017];

        // retweeted message contains no geo itself
        $raw_tweets->statuses[1] = new stdClass();
        $raw_tweets->statuses[1]->created_at = 'Mon Oct 26 18:01:08 +0000 2015';
        $raw_tweets->statuses[1]->id = '658704931197620226';
        $raw_tweets->statuses[1]->text = 'this is another tweet message (2)';
        $raw_tweets->statuses[1]->user = new stdClass();
        $raw_tweets->statuses[1]->user->id = 1490459922;
        $raw_tweets->statuses[1]->user->name = 'PGunnJ';
        $raw_tweets->statuses[1]->user->profile_image_url = 'http://pbs.twimg.com/profile_images/658598352666296321/2Z66OTb3_normal.jpg';
        $raw_tweets->statuses[1]->geo = null;

        $seach_location_hash = md5('13.87003723, 100.54855017');
        $cached_tweets = TwitterSearch::cacheTweets($seach_location_hash, $raw_tweets);
        $this->assertEquals(2, count($cached_tweets));
    }
}
