<?php

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
}
