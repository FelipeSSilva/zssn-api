<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(array('prefix' => 'api'), function () {

    Route::get('/', function () {
        return response()->json(['message' => 'ZSSN API', 'status' => 'Connected!']);;
    });

    Route::resource('survivors', 'SurvivorsController');
    Route::post('survivors/{survivor_reporter_id}/reportInfection/{survivor_infected_id}', [
        'as'=> 'survivors.reportInfection',
        'uses' => 'SurvivorsController@reportInfection'])->where(['survivor_reporter_id' => '[0-9]+', 'survivor_infected_id' => '[0-9]+']);
});


Route::get('/', function () {
    return redirect('api');
});


