#Tweet Map		
[![Build Status](https://api.travis-ci.org/yapadubpadoo/tweet-map.svg)](https://travis-ci.org/yapadubpadoo/tweet-map)

Tweet-map alllow user to search for any city and display Twitter messages around that city on Google map.

This project use

- Laravel 5.1
https://github.com/laravel/framework

- Twitter API for Laravel 4/5
https://github.com/thujohn/twitter

- gmap.js
https://hpneo.github.io/gmaps/

- Bootstrap
http://getbootstrap.com/

##Setup

###Install 3rd party libraries
Pull code and goto root project directory, run the following command
```shell
composer install
```
###Environment settings
Create Laravel .env file at project folder to prepare Database settings, read more http://laravel.com/docs/5.0/configuration#introduction

###Database migration
At root project directory, run the following command
```shell
php artisan migrate
```

###Application configuration
There is a configuration file for Tweet-Map application located at "tweet-map/config/twitter_map.php" that allow to adjust
```php
<?php 

return [
	'twitter_search_radius_in_km'	=>	50,
	'tweets_caches_life_time_in_minutes' => 60,
	'number_of_search_pagination' => 2 // number of pages for loop trough search result
];
```

###Twitter configuration
Create a configuration file in "tweet-map/config" by rename
```text
ttwitter.template.php
```
to
```text
ttwitter.php
```
Configure the settings with your Twitter Appication detail
```php
<?php

// You can find the keys here : https://apps.twitter.com/

return [
	'debug'               => true,

	'API_URL'             => 'api.twitter.com',
	'UPLOAD_URL'          => 'upload.twitter.com',
	'API_VERSION'         => '1.1',
	'AUTHENTICATE_URL'    => 'https://api.twitter.com/oauth/authenticate',
	'AUTHORIZE_URL'       => 'https://api.twitter.com/oauth/authorize',
	'ACCESS_TOKEN_URL'    => 'https://api.twitter.com/oauth/access_token',
	'REQUEST_TOKEN_URL'   => 'https://api.twitter.com/oauth/request_token',
	'USE_SSL'             => true,

	'CONSUMER_KEY'        => '<your comsumer key>',
	'CONSUMER_SECRET'     => '<your consumer secret>',
	'ACCESS_TOKEN'        => '<your access token>',
	'ACCESS_TOKEN_SECRET' => '<your access token secret>',
];
```
##Testing

Test cases can be found at https://github.com/yapadubpadoo/tweet-map/tree/master/tests

Build history can be found at https://travis-ci.org/yapadubpadoo/tweet-map
