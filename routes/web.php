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


Route::get('/', function () {
    if(Auth::user()->user_status == 2){
        return redirect()->route('pemilikLapangan.dashboard');
    }else if(Auth::user()->user_status == 3){
        return redirect()->route('penyewaLapangan.dashboard');
    }
})->middleware(['auth']);


Route::group(['prefix' => 'pemilik-lapangan/'], function(){
    Route::post('register', 'Auth\AuthController@createPemilikLapangan')->name('pemilikLapangan.register');
    
    Route::group(['prefix' => '', 'middleware' => 'fieldOwner'], function(){
        Route::get('dashboard', 'HomeController@pemilikLapanganHome')->name('pemilikLapangan.dashboard');
        
        Route::post('get-data-lapangan-pemilik/{lapangan_id}', 'LapanganController@getDataLapanganPemilik')->name('pemilikLapangan.getDataLapanganPemilik');
        Route::get('get-court-lapangan-status/{lapangan_id}/{court}', 'LapanganController@getStatusCourtLapangan')->name('pemilikLapangan.statusCourtLapanganStatus');
        Route::post('update-lapangan-court-status/{id}', 'LapanganController@updateCourtLapanganStatus')->name('pemilikLapangan.updateCourtLapanganStatus');

        route::post('update-status-pembayaran/{pembayaran_id}', 'PembayaranController@updateStatusPembayaranPenyewa')->name('pemilikLapangan.updateStatusPembayaran');

        Route::get('get-profil/{penggunaPenyewaId}/{date}/{pembayaranId}', 'ProfilController@getPenyewaLapanganProfil')->name('pemilikLapangan.getPenyewaProfil');
    
        Route::get('list-payment-method', 'PembayaranController@listPaymentMethodPemilikLapangan')->name('pemilikLapangan.listPaymentMethodPemilikLapangan');
        Route::post('update-payment-method', 'PembayaranController@updatePaymentMethodPemilikLapangan')->name('pemilikLapangan.updatePaymentMethodPemilikLapangan');

        Route::get('manajemen-paket-sewa-bulanan', 'LapanganController@manajemenPaketBulananPemilik')->name('pemilikLapangan.manajemenPaketBulananPemilikLapangan');
        Route::post('update-or-create-paket-sewa-bulanan', 'LapanganController@updateOrCreatePaketBulananPemilik')->name('pemilikLapangan.updateOrCreatePaketBulananPemilik');

        Route::get('profil', 'ProfilController@pemilikLapanganProfil')->name('pemilikLapangan.profil');
        Route::post('update-profil-lapangan', 'ProfilController@pemilikLapanganUpdateProfil')->name('pemilikLapangan.updateProfil');

        Route::get('riwayat-penyewaan', 'RiwayatController@pemilikLapanganRiwayatPenyewaan')->name('pemilikLapangan.riwayatPenyewaan');
        Route::post('data-riwayat-penyewaan/', 'RiwayatController@getDataRiwayatPenyewaanPemilikLapangan')->name('pemilikLapangan.getDataRiwayatPenyewaanPemilikLapangan');

        Route::get('riwayat-penyewaan-total-pemasukan', 'RiwayatController@pemilikLapanganRiwayatTotalPemasukan')->name('pemilikLapangan.pemilikLapanganRiwayatTotalPemasukan');
        Route::post('data-riwayat-penyewaan-total-pemasukan/', 'RiwayatController@getDataRiwayatTotalPemasukanPemilikLapangan')->name('pemilikLapangan.getDataRiwayatTotalPemasukanPemilikLapangan');

        Route::get('riwayat-penyewaan-pengguna-booking-terbanyak', 'RiwayatController@pemilikLapanganRiwayatPenggunaBookingTerbanyak')->name('pemilikLapangan.pemilikLapanganRiwayatPenggunaBookingTerbanyak');
        Route::post('data-riwayat-penyewaan-pengguna-booking-terbanyak/', 'RiwayatController@getDataRiwayatPenggunaBookingTerbanyakPemilikLapangan')->name('pemilikLapangan.getDataRiwayatPenggunaBookingTerbanyakPemilikLapangan');

        Route::get('riwayat-penyewaan-booking-jam-terbanyak', 'RiwayatController@pemilikLapanganRiwayatBookingJamTerbanyak')->name('pemilikLapangan.pemilikLapanganRiwayatBookingJamTerbanyak');
        Route::post('data-riwayat-penyewaan-booking-jam-terbanyak/', 'RiwayatController@getDataRiwayatBookingJamTerbanyakPemilikLapangan')->name('pemilikLapangan.getDataRiwayatBookingJamTerbanyakPemilikLapangan');
    });
});
 
Route::group(['prefix' => 'penyewa-lapangan/'], function(){
    Route::post('register', 'Auth\AuthController@createPenyewaLapangan')->name('penyewaLapangan.register');
    
    Route::group(['prefix' => '', 'middleware' => 'tenantUser'], function(){
        Route::get('dashboard', 'HomeController@penyewaLapanganHome')->name('penyewaLapangan.dashboard');

        Route::post('get-all-data-lapangan/{idLapangan}', 'LapanganController@getAllDataLapangan')->name('penyewaLapangan.getAllDataLapangan');

        Route::get('get-data-lapangan', 'LapanganController@getDataLapangan')->name('penyewaLapangan.getDataLapangan');
        Route::get('get-lapangan-picture/{id}', 'LapanganController@getLapanganPicture')->name('penyewaLapangan.getLapanganPicture');

        Route::get('profil-lapangan/{id}/{lapanganName}', 'LapanganController@profilLapangan')->name('penyewaLapangan.profilLapangan');
        Route::post('profil-lapangan/{idLapangan}', 'LapanganController@getDataProfilLapangan')->name('penyewaLapangan.getDataProfilLapangan');

        Route::get('pesan-lapangan-bulanan/{id}/{lapanganName}', 'LapanganController@pesanLapanganBulanan')->name('penyewaLapangan.pesanLapanganBulanan');
        Route::post('store-pesan-lapangan-bulanan', 'BookingController@storeBookingLapanganBulanan')->name('penyewaLapangan.storeBookingLapanganBulanan');

        Route::get('pesan-lapangan-per-jam/{id}/{lapanganName}', 'LapanganController@pesanLapanganPerJam')->name('penyewaLapangan.pesanLapanganPerJam');
        Route::post('store-pesan-lapangan-per-jam', 'BookingController@storeBookingLapanganPerJam')->name('penyewaLapangan.storeBookingLapanganPerJam');

        route::get('get-data-daftar-jenis-pembayaran/{lapanganId}', 'PembayaranController@getDaftarJenisPembayaran')->name('penyewaLapangan.getDaftarJenisPembayaran');
        route::get('menunggu-pembayaran', 'PembayaranController@menungguPembayaranPenyewaIndex')->name('penyewaLapangan.menungguPembayaranPenyewaIndex');
        route::get('get-pembayaran-detail', 'PembayaranController@getPembayaranDetail')->name('penyewaLapangan.getPembayaranDetail');
        route::post('batalkan-pembayaran', 'PembayaranController@batalkanPembayaran')->name('penyewaLapangan.batalkanPembayaran');
        route::post('simpan-bukti-pembayaran', 'PembayaranController@simpanBuktiPembayaran')->name('penyewaLapangan.simpanBuktiPembayaran');
        route::post('payments/midtrans-notification', 'PaymentCallbackController@receive')->name('penyewaLapangan.midtransNotificationReceive');
        
        Route::get('edit-profil', 'ProfilController@penyewaLapanganProfil')->name('penyewaLapangan.editProfil');
    
        Route::get('riwayat-penyewaan', 'RiwayatController@penyewaLapanganRiwayatPenyewaan')->name('penyewaLapangan.riwayatPenyewaan');
        Route::post('data-riwayat-penyewaan', 'RiwayatController@getDataRiwayatPenyewaLapangan')->name('penyewaLapangan.getDataRiwayatPenyewaLapangan');

        Route::get('get-invoice/{pembayaranId}', 'RiwayatController@getPenyewaLapanganInvoice')->name('penyewaLapangan.getInvoice');
    });
});