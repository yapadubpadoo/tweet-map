<?php

use App\TwitterSearch;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/map', function () {
    return view('map');
});

Route::get('/search/tweet', function () {
    $search_data = Request::all();
    return response()->json(
        TwitterSearch::getTweetsFromGeo($search_data['place'], $search_data['lat'], $search_data['lon'])
    );
});
