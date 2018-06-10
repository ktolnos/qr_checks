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
    return view('app');
});

Route::get('/checks', 'CheckController@show');
Route::get('/checks/add', 'CheckController@add');
Route::post('/checks/confirm', 'CheckController@confirm');
Route::get('/checks/store', 'CheckController@store');


Route::get('/products', 'ProductController@index');



