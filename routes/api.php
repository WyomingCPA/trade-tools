<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::get('/logout', 'AuthController@logout');
//Роуты акций
Route::group(['prefix' => 'stock', 'middleware' => 'auth:sanctum'], function () {
	Route::post('all', ['uses' => 'StockController@all']);
	//Route::get('rub', ['uses' => 'StockController@stockRub']);
	Route::post('rub', ['as' => 'stock.rub', 'uses' => 'StockController@stockRub']);
	Route::post('usd', ['uses' => 'StockController@stockUsd']);	
	Route::get('favorites', ['uses' => 'StockController@favorite']);
	Route::post('favorite', ['uses' => 'StockController@favoriteStock']);
	Route::post('indicator-tutci/{id}', ['uses' => 'StockController@tutci']);

	Route::post('unfavorite', ['uses' => 'StockController@unFavoriteStock']);
	Route::post('set-dividends', ['uses' => 'StockController@setDividends']);
	Route::get('dividends', ['uses' => 'StockController@dividends']);
	
});
//Роуты фондов
Route::group(['prefix' => 'etf', 'middleware' => 'auth:sanctum'], function () {
	Route::get('all', ['uses' => 'EtfController@all']);
	Route::get('favorites', ['uses' => 'EtfController@favorite']);	
	Route::post('favorite', ['uses' => 'EtfController@favoriteEtf']);
	Route::post('unfavorite', ['uses' => 'EtfController@unFavoriteEtf']);
	Route::get('mini-charts/{id}', ['uses' => 'EtfController@miniCandleCharts']);
});
//Роуты Фьючерсов
Route::group(['prefix' => 'futures', 'middleware' => 'auth:sanctum'], function () {
	Route::get('all', ['uses' => 'FuturesController@index']);
	Route::post('/get-all-futures', 'FuturesController@getAllFutures');
	Route::post('favorite', ['uses' => 'FuturesController@setFavorite']);
	Route::get('favorites', ['uses' => 'FuturesController@getFavorite']);
	Route::post('unfavorite', ['uses' => 'FuturesController@unFavoriteFutures']);		
});

//Роуты облигаций
Route::group(['prefix' => 'bond', 'middleware' => 'auth:sanctum'], function () {
	Route::post('all', ['uses' => 'BondController@all']);
	Route::post('favorite', ['uses' => 'BondController@favoriteBond']);
	Route::get('favorites', ['uses' => 'BondController@favorites']);
	Route::post('unfavorite', ['uses' => 'BondController@unFavoriteBond']);
	Route::get('new', ['uses' => 'BondController@newBond']);
	Route::get('trash', ['uses' => 'BondController@trash']);
	Route::post('trash', ['uses' => 'BondController@trashBond']);
	Route::post('untrash', ['uses' => 'BondController@untrashBond']);
});
//Роуты сделок
Route::group(['prefix' => 'orders', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/index', 'OrderController@index');
	Route::post('/today', 'OrderController@today');
	
	Route::get('stop-orders/{id}', ['uses' => 'OrderController@stopOrders']);
	Route::get('spot-orders/{id}', ['uses' => 'OrderController@spotOrders']);
	Route::get('spot-detil/{id}', ['uses' => 'OrderController@spotDetail']);
	Route::get('chart-orders-15/{id}', ['uses' => 'OrderController@chart15Orders']);
	
	Route::post('set-success', 'OrderController@setSuccess');
	Route::post('set-fail', 'OrderController@setFail');
	Route::post('set-nothing', 'OrderController@setNothing');
	Route::post('delete', 'OrderController@delete');
	Route::post('start-script-super-trend-5min', 'OrderController@startScriptSuperTrend5min');
	Route::post('stop-script-super-trend-5min', 'OrderController@stopScriptSuperTrend5min');
	Route::post('start-script-check-stop-order', 'OrderController@startScriptCheckStopOrder');
	Route::get('/last-error', ['uses' => 'OrderController@lastError']);
});
//Роуты test strategy
Route::group(['prefix' => 'test-strategy', 'middleware' => 'auth:sanctum'], function () {
	Route::get('/index', 'TestStrategyController@index');
	Route::get('/create', 'TestStrategyController@create');
	Route::post('/store', 'TestStrategyController@store');
	Route::post('/get-candle-test', 'TestStrategyController@getCandleTest');
	Route::post('/set-orders-test', 'TestStrategyController@setOrdersTest');
	Route::post('/delete-orders-test', 'TestStrategyController@deleteOrdersTest');
	Route::post('/delete-strategy-test', 'TestStrategyController@deleteStrategyTest');	
	Route::get('strategy-chart/{id}', ['uses' => 'TestStrategyController@chartStrategy']);
	Route::get('open-orders/{id}', ['uses' => 'TestStrategyController@openOrders']);
});
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], function () {
	Route::get('/index', 'DashboardController@index');
	Route::post('/delete-all-candles', 'CandleController@deleteAll');
	Route::post('/delete-all-orders', 'OrderController@deleteAll');
});

//Роуты личных финансов
Route::group(['prefix' => 'finance', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/store', 'CheckController@store');
	Route::post('/delete', 'CheckController@delete');
    Route::post('/set-balance', 'CheckController@setBalance');
    Route::get('/', 'CheckController@index');
});
//Роуты торговых идей
Route::group(['prefix' => 'ideas', 'middleware' => 'auth:sanctum'], function () {
	Route::get('/index', 'IdeasController@index');
	Route::get('/edit/{id}', 'IdeasController@edit');
	Route::post('/update', 'IdeasController@update');
	Route::post('/delete', 'IdeasController@delete');
	Route::get('/get-idea/{id}', 'CalculateController@getIdea');
	Route::get('/description/{id}', 'IdeasController@getDescription');
    Route::post('/store', 'IdeasController@store');
	Route::post('/calculate-lots', 'CalculateController@calculateLotsForStock');
});

//Роуты для алготорговли
Route::group(['prefix' => 'console',], function () {
	Route::post('/store', 'ConsoleLogController@store');
	Route::get('last-events-console', ['uses' => 'ConsoleLogController@getLastEventsConsole']);
});

//Роуты для алготорговли
Route::group(['prefix' => 'trader',], function () {
	Route::post('/check-script', 'OrderController@checkScript');
    Route::post('/store', 'OrderController@store');
	Route::post('/add_spot', 'OrderController@addSpot');
	Route::post('/store-all-futures', 'FuturesController@storeAllFutures');
	Route::get('favorites', ['uses' => 'FuturesController@getFavoriteNotAuth']);
	Route::get('favorites-stock', ['uses' => 'StockController@getFavoriteNotAuth']);
});