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
    Route::post('login', 'Auth\AuthController@loginPemilikLapangan');
    Route::get('login', 'Auth\AuthController@loginForm')->name('login');
    Route::post('logout', 'Auth\AuthController@logout')->name('logout');

    Route::get('register', 'Auth\AuthController@registrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');
    
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
    Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');

    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
});


Route::group(['prefix' => '', 'middleware' => 'userStatus'], function(){
    Route::get('/', 'HomeController@index')->name('index');
});


Route::group(['prefix' => 'pemilik-lapangan/'], function(){
    Route::post('register', 'Auth\AuthController@createPemilikLapangan')->name('pemilikLapangan.register');
    
    Route::group(['prefix' => '', 'middleware' => 'userStatus'], function(){
        Route::get('dashboard', 'HomeController@pemilikLapanganHome')->name('pemilikLapangan.dashboard');
        
        Route::post('get-data-lapangan-pemilik/{lapangan_id}', 'LapanganController@getDataLapanganPemilik')->name('pemilikLapangan.getDataLapanganPemilik');
        Route::get('get-court-lapangan-status/{lapangan_id}/{court}', 'LapanganController@getStatusCourtLapangan')->name('pemilikLapangan.statusCourtLapanganStatus');
        Route::post('update-lapangan-court-status/{id}', 'LapanganController@updateCourtLapanganStatus')->name('pemilikLapangan.updateCourtLapanganStatus');

        Route::get('get-profil/{penggunaPenyewaId}/{date}', 'ProfilController@getPenyewaLapanganProfil')->name('pemilikLapangan.getPenyewaProfil');
    
        Route::get('profil', 'ProfilController@pemilikLapanganProfil')->name('pemilikLapangan.profil');
        Route::post('update-profil', 'ProfilController@pemilikLapanganUpdateProfil')->name('pemilikLapangan.updateProfil');

        Route::get('riwayat-penyewaan', 'RiwayatController@pemilikLapanganRiwayatPenyewaan')->name('pemilikLapangan.riwayatPenyewaan');
    });
});
 
Route::group(['prefix' => 'penyewa-lapangan/'], function(){
    Route::post('register', 'Auth\AuthController@createPenyewaLapangan')->name('penyewaLapangan.register');
    
    Route::group(['prefix' => '', 'middleware' => 'userStatus'], function(){
        Route::get('dashboard', 'HomeController@penyewaLapanganHome')->name('penyewaLapangan.dashboard');

        Route::post('get-all-data-lapangan/{idLapangan}', 'LapanganController@getAllDataLapangan')->name('penyewaLapangan.getAllDataLapangan');

        Route::get('get-data-lapangan', 'LapanganController@getDataLapangan')->name('penyewaLapangan.getDataLapangan');
        Route::get('get-lapangan-picture/{id}', 'LapanganController@getLapanganPicture')->name('penyewaLapangan.getLapanganPicture');

        Route::get('profil-lapangan/{id}/{lapanganName}', 'LapanganController@profilLapangan')->name('penyewaLapangan.profilLapangan');
        Route::get('pesan-lapangan/{id}/{lapanganName}', 'LapanganController@pesanLapangan')->name('penyewaLapangan.pesanLapangan');
        Route::post('store-pesan-lapangan', 'BookingController@storeBookingLapangan')->name('penyewaLapangan.storeBookingLapangan');
    
        route::get('pembayaran', 'BookingController@bookValid')->name('pembayaran');
        
        Route::get('edit-profil', 'ProfilController@penyewaLapanganProfil')->name('penyewaLapangan.editProfil');
    
        Route::get('riwayat-penyewaan', 'RiwayatController@penyewaLapanganRiwayatPenyewaan')->name('penyewaLapangan.riwayatPenyewaan');
        Route::post('data-riwayat-penyewaan', 'RiwayatController@getDataRiwayatPenyewaLapangan')->name('penyewaLapangan.getDataRiwayatPenyewaLapangan');
    });
});

