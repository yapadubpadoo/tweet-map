<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TweetsCaches extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tweets_caches';

    public static function getTweetsFromCacheByLocationHash($location_hash)
    {
        return self::where('seach_location_hash', $location_hash)
            ->where('expire_at', '>=', date('Y-m-d H:i:s'))->get();
    }
}
