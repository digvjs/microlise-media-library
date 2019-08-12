<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// List Media
Route::get('media', 'MediaController@index');

// List single media
Route::get('media/{id}', 'MediaController@show');

// Create new media
Route::post('media', 'MediaController@store');

// Update article
Route::put('media', 'MediaController@update');

// Delete article
Route::delete('media/{id}', 'MediaController@destroy');


// List Media Types
Route::get('mediatypes', 'MediaTypesController@index');

