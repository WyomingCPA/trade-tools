<?php

use Illuminate\Support\Facades\Route;

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



Auth::routes();

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
	Route::get('table-list', function () {
		return view('pages.table_list');
	})->name('table');

	Route::get('typography', function () {
		return view('pages.typography');
	})->name('typography');

	Route::get('icons', function () {
		return view('pages.icons');
	})->name('icons');

	Route::get('map', function () {
		return view('pages.map');
	})->name('map');

	Route::get('notifications', function () {
		return view('pages.notifications');
	})->name('notifications');

	Route::get('rtl-support', function () {
		return view('pages.language');
	})->name('language');

	Route::get('upgrade', function () {
		return view('pages.upgrade');
	})->name('upgrade');
});

Route::group(['prefix' => 'parent', 'middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});
//Роуты облигаций.
Route::group(['prefix' => 'bond', 'middleware' => 'auth'], function () {
	//Route::get('/', ['as' => 'dashboard_parent', 'uses' => 'ParentController@index']);
	Route::get('new', ['as' => 'bond.new', 'uses' => 'BondController@newBond']);
	Route::get('all', ['as' => 'bond.all', 'uses' => 'BondController@all']);

	Route::get('favorites', ['as' => 'bond.favorites', 'uses' => 'BondController@favorites']);
	Route::post('favorite', ['as' => 'bond.favorites.post', 'uses' => 'BondController@favoriteBond']);
	Route::post('unfavorite', ['as' => 'bond.unfavorite', 'uses' => 'BondController@unFavoriteBond']);
	Route::get('trash', ['as' => 'bond.trash', 'uses' => 'BondController@trash']);
	Route::post('trash', ['as' => 'bond.trash.post', 'uses' => 'BondController@trashBond']);
	Route::post('untrash', ['as' => 'bond.untrash', 'uses' => 'BondController@untrashBond']);

});
//Роуты акций.
Route::group(['prefix' => 'stock', 'middleware' => 'auth'], function () {
	Route::get('all', ['as' => 'stock.all', 'uses' => 'StockController@all']);
	Route::get('rub', ['as' => 'stock.rub', 'uses' => 'StockController@stockRub']);
	Route::get('usd', ['as' => 'stock.usd', 'uses' => 'StockController@stockUsd']);
	
	Route::get('new', ['as' => 'stock.new', 'uses' => 'StockController@newStock']);
	Route::get('favorites', ['as' => 'stock.favorites', 'uses' => 'StockController@favorite']);
	Route::get('action/{id}', ['as' => 'stock.emachart', 'uses' => 'StockController@action']);

    Route::post('order', ['as' => 'stock.action.order', 'uses' => 'StockController@order']);
	Route::post('favorite', ['as' => 'stock.favorites.post', 'uses' => 'StockController@favoriteStock']);
	Route::post('unfavorite', ['as' => 'stock.unfavorite', 'uses' => 'StockController@unFavoriteStock']);

	Route::get('test', ['as' => 'stock.test', 'uses' => 'StockController@test']);	
});
Route::group(['prefix' => 'stock'], function () {
	Route::get('emachart/{id}', ['as' => 'stock.emachart', 'uses' => 'StockController@emachart']);
	Route::get('emachart-today/{id}', ['as' => 'stock.emachart-today', 'uses' => 'StockController@emachartToday']);		
});
//Роуты журнала
Route::group(['prefix' => 'journal', 'middleware' => 'auth'], function () {
	Route::get('', ['as' => 'journal', 'uses' => 'JournalController@index']);
	Route::post('calculate', ['as' => 'calculate', 'uses' => 'JournalController@calculate']);
	Route::post('delete', ['as' => 'delete', 'uses' => 'JournalController@delete']);
});


