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

/*Route::get('/', function () {
    return view('welcome');
});*/

//git config core.autocrlf false 稍后研究下此命令
//共享页面
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');
//用户注册
Route::get('/signup', 'UsersController@create')->name('signup');
Route::resource('users','UsersController');
//用户登录及退出
Route::get('/login','SessionsController@create')->name('login');
Route::post('/login','SessionsController@store')->name('login');
Route::delete('/logout','SessionsController@destroy')->name('logout');
//用户邮箱激活认证
Route::get('/signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

//用户邮箱找回密码功能
Route::get('/password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset','Auth\ResetPasswordController@reset')->name('password.update');