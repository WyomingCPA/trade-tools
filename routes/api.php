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
Route::group(['prefix' => 'orders',], function () {
    Route::post('/index', 'OrderController@index');
	Route::get('stop-orders/{id}', ['uses' => 'OrderController@stopOrders']);
	Route::get('chart-orders/{id}', ['uses' => 'OrderController@chartOrders']);
});
//Роуты личных финансов
Route::group(['prefix' => 'finance', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/store', 'CheckController@store');
	Route::post('/delete', 'CheckController@delete');
    Route::post('/set-balance', 'CheckController@setBalance');
    Route::get('/', 'CheckController@index');
});