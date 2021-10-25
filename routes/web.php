<?php

use App\Genre;
use App\Record;
use App\User;
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

Route::get('/', function () {
//    return view('welcome');
    return "fgshdfvGDVF SFUIVfS";
});

Route::view('/','home');
Route::get('shop', 'ShopController@index');
Route::get('shop/{id}', 'ShopController@show');
Route::view('contact-us', 'contact');

// New version with prefix and group
Route::prefix('admin')->group(function () {
    Route::redirect('/', '/admin/records');
    Route::get('records', 'Admin\RecordController@index');
});

Route::prefix('api')->group(function(){
    Route::get('users',function(){
        return User::get();
    });
    Route::get('records',function(){
        return Record::with('Genre')->get();
    });
    Route::get('genres',function(){
        return Genre::with('Record')->get();
    });
});

