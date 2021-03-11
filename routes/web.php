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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', 'PagesController@root')->name('root')->middleware('verified');
// Route::get('/', 'PagesController@root')->name('root');
Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');

// 在之前的路由里加上一个 verify 参数
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth', 'verified']], function() {

  Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
  Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
  Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
  Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
  Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
  Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

  Route::post('products/{product}/favor', 'ProductsController@favor')->name('products.favor');
  Route::delete('products/{product}/disfavor', 'ProductsController@disfavor')->name('products.disfavor');
  Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');

  Route::get('cart', 'CartController@index')->name('cart.index');
  Route::post('cart', 'CartController@add')->name('cart.add');
  Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
});
Route::get('products/{product}', 'ProductsController@show')->name('products.show');
