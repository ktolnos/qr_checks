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

use App\Http\Controllers\CheckController;

Route::get('/', function () {
    return redirect('/products');
});

Route::get('/checks', 'CheckController@index');
Route::get('/checks/add', 'CheckController@add');
Route::post('/checks/confirm', 'CheckController@confirm');
Route::get('/checks/store', 'CheckController@store');
Route::get('/checks/show/{id}', 'CheckController@show');
Route::post('/checks/update', 'CheckController@update');


Route::get('/products', 'ProductController@index');
Route::post('/products/update', 'ProductController@update');
Route::get('/products/add', 'ProductController@add');
Route::post('/products/add', 'ProductController@add');
Route::post('/products/addTags', 'ProductController@addTags');



Route::get('/tags', 'TagController@index');
Route::post('/tags/store', 'TagController@store');

Route::get('/payments', 'PaymentController@index');
Route::get('/payments/add', 'PaymentController@addPayment');
Route::post('/payments/add', 'PaymentController@addPayment');
Route::get('/pay/for/check/{id}', 'PaymentController@add');
Route::post('/pay/for/check/{id}', 'PaymentController@add');
Route::post('/payments/update', 'PaymentController@update');

Route::get('/users', 'UserController@index');
Route::get('/users/add', 'UserController@add');
Route::post('/users/store', 'UserController@store')->name('user.store');
Route::post('/users/update', 'UserController@update')->name('user.update');







Auth::routes();

Route::get('/home', function() {return redirect('/');})->name('home');
