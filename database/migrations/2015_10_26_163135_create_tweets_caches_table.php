<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets_caches', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('seach_location_hash', 32);
            $table->string('seach_location_name', 255);
            $table->bigInteger('tweet_id');
            $table->decimal('tweet_lat', 10, 8);
            $table->decimal('tweet_lon', 11, 8);
            $table->string('tweet_message', 140);
            $table->bigInteger('user_id');
            $table->string('user_screen_name', 255);
            $table->text('profile_image_url');
            $table->dateTime('expire_at');
            // keys
            $table->unique(['seach_location_hash', 'tweet_id']);
            $table->index(['expire_at', 'seach_location_hash']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tweets_caches');
    }
}
