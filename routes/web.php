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

    Route::post('survivors/{survivor_reporter_id}/reportInfection/{survivor_infected_id}', [
        'as'=> 'survivors.reportInfection',
        'uses' => 'SurvivorsController@reportInfection'])->where(['survivor_reporter_id' => '[0-9]+', 'survivor_infected_id' => '[0-9]+']);
    Route::put('survivors/{survivor_offer_id}/tradeItems/{survivor_accept_id}', [
        'as'=> 'survivors.tradeItems',
        'uses' => 'SurvivorsController@tradeItems'])->where(['survivor_offer_id' => '[0-9]+', 'survivor_accept_id' => '[0-9]+']);
    Route::get('survivors/percentageInfected', 'SurvivorsController@percentageInfected')->name('survivors.percentageInfected');
    Route::get('survivors/percentageNonInfected', 'SurvivorsController@percentageNonInfected')->name('survivors.percentageNonInfected');
    Route::get('survivors/averageAmount', 'SurvivorsController@averageAmount')->name('survivors.averageAmount');
    Route::resource('survivors', 'SurvivorsController');
});


Route::get('/', function () {
    return redirect('api');
});


