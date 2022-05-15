<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


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

Route::group(['prefix' => '/'], function(){
    Route::post('login', 'Auth\LoginController@login');
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');

    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
});


Route::group(['prefix' => '', 'middleware' => 'roleUser'], function () {
    Route::get('/', 'HomeController@index')->name('index');
});


Route::group(['prefix' => 'pemilik-lapangan/'], function(){
    Route::post('register', 'Auth\RegisterController@login')->name('pemilikLapangan.register');
    
    Route::group(['prefix' => '', 'middleware' => 'roleUser'], function () {
        Route::get('dashboard', 'HomeController@pemilikLapanganHome')->name('pemilikLapangan.dashboard');
    
        Route::get('profil', 'HomeController@pemilikLapanganProfil')->name('pemilikLapangan.profil');
    
        Route::get('riwayat-penyewaan', 'HomeController@pemilikLapanganRiwayatPenyewaan')->name('pemilikLapangan.riwayatPenyewaan');
    });
});
 
Route::group(['prefix' => 'penyewa-lapangan/'], function(){
    Route::post('register', 'Auth\RegisterController@login')->name('penyewaLapangan.register');
    
    Route::group(['prefix' => '', 'middleware' => 'roleUser'], function () {
        Route::get('dashboard', 'HomeController@pemilikLapanganHome')->name('penyewaLapangan.dashboard');
    
        Route::get('profil', 'HomeController@pemilikLapanganProfil')->name('penyewaLapangan.profil');
    
        Route::get('riwayat-penyewaan', 'HomeController@pemilikLapanganRiwayatPenyewaan')->name('penyewaLapangan.riwayatPenyewaan');
    });
});





// Route::get('admin/home', 'HomeController@adminHome')->name('admin.home')->middleware('is_admin');

