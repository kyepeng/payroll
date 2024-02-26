<?php

use Illuminate\Support\Facades\Auth;
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
Route::group(['middleware' => ['auth','role:admin']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('dashboards','DashboardController');
});

Route::group(['middleware' => ['auth','role:user']], function() {
    Route::resource('timesheets','TimesheetController');
});

Route::get('/', function () {
    return redirect()->route('login');
});