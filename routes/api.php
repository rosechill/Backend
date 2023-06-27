<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstrukturController;
use App\Http\Controllers\JadwalHarianController;
use App\Http\Controllers\JadwalUmumController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\memberController;
use App\Http\Controllers\pegawaiController;
use App\Http\Controllers\PromoRegulerController;
use App\Http\Controllers\TaktivasiController;
use App\Http\Controllers\TdepositKelasController;
use App\Http\Controllers\Tdeposit_RegulerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//auth
Route::post('login', [authController::class, 'login']);
Route::post('updatePassword', [authController::class, 'updatePassword']);
Route::post('logout', [authController::class, 'logout']);

//pegawai
Route::get('pegawai/index', [pegawaiController::class, 'index']);
Route::post('pegawai/store', [pegawaiController::class, 'store']);
Route::get('pegawai/show/{id}', [pegawaiController::class, 'show']);
Route::put('pegawai/update/{id}', [pegawaiController::class, 'update']);
Route::delete('pegawai/destroy/{id}', [pegawaiController::class, 'destroy']);

//member
Route::get('member/index', [memberController::class, 'index']);
Route::get('memberKadaluarsa', [memberController::class, 'indexKadaluarsa']);
Route::post('member/store', [memberController::class, 'store']);
Route::get('member/show/{id}', [memberController::class, 'show']);
Route::put('member/update/{id}', [memberController::class, 'update']);
Route::delete('member/destroy/{id}', [memberController::class, 'destroy']);
Route::post('member/resetPassword/{id}', [memberController::class, 'resetPassword']);
Route::get('member/generateMemberCard/{id}', [memberController::class, 'generateMemberCard']);

//instruktur
Route::get('instruktur/index', [instrukturController::class, 'index']);
Route::post('instruktur/store', [instrukturController::class, 'store']);
Route::get('instruktur/show/{id}', [instrukturController::class, 'show']);
Route::put('instruktur/update/{id}', [instrukturController::class, 'update']);
Route::delete('instruktur/destroy/{id}', [instrukturController::class, 'destroy']);
Route::post('instruktur/resetTerlambat',[instrukturController::class, 'resetTerlambat'] );

//kelas
Route::get('kelas/index', [kelasController::class, 'index']);
Route::post('kelas/store', [kelasController::class, 'store']);
Route::get('kelas/show/{id}', [kelasController::class, 'show']);
Route::put('kelas/update/{id}', [kelasController::class, 'update']);
Route::delete('kelas/destroy/{id}', [kelasController::class, 'destroy']);

//jadwal umum
Route::get('jadwal_umum/index', [jadwalUmumController   ::class, 'index']);
Route::post('jadwal_umum/store', [jadwalUmumController  ::class, 'store']);
Route::put('jadwal_umum/update/{id}', [jadwalUmumController ::class, 'update']);
Route::delete('jadwal_umum/destroy/{id}', [jadwalUmumController ::class, 'destroy']);
Route::get('jadwal_umum/show/{id}', [jadwalUmumController::class, 'show']);

//jadwal harian
Route::get('jadwal_harian/index', [jadwalHarianController   ::class, 'index']);
Route::post('jadwal_harian/generateWeek', [jadwalHarianController   ::class, 'generateWeek']);
Route::put('jadwal_harian/update/{id}', [jadwalHarianController ::class, 'update']);
Route::delete('jadwal_harian/destroy/{id}', [jadwalHarianController ::class, 'destroy']);
Route::get('jadwal_harian/show/{id}', [jadwalHarianController::class, 'show']);

//transaksi aktivasi
Route::get('taktivasi/index', [TaktivasiController::class, 'index']);
Route::post('taktivasi/store', [TaktivasiController::class, 'store']);
Route::put('taktivasi/update/{id}', [TaktivasiController::class, 'update']);
Route::delete('taktivasi/destroy/{id}', [TaktivasiController::class, 'destroy']);
Route::get('taktivasi/show/{id}', [TaktivasiController::class, 'show']);

//promo reguler
route::get('promo_reguler/index', [PromoRegulerController::class, 'index']);
Route::post('promo_reguler/store', [PromoRegulerController::class, 'store']);
Route::put('promo_reguler/update/{id}', [PromoRegulerController::class, 'update']);
Route::delete('promo_reguler/destroy/{id}', [PromoRegulerController::class, 'destroy']);
Route::get('promo_reguler/show/{id}', [PromoRegulerController::class, 'show']);

//promo kelas
Route::get('promo_kelas', 'App\Http\Controllers\PromoKelasController@index');
Route::post('promo_kelas', 'App\Http\Controllers\PromoKelasController@store');

//tdeposit reguler
Route::get('tdeposit_reguler', 'App\Http\Controllers\TdepositRegulerController@index');
Route::post('tdeposit_reguler', 'App\Http\Controllers\TdepositRegulerController@store');
Route::delete('tdeposit_reguler', 'App\Http\Controllers\TdepositRegulerController@destroy');

//tdeposit kelas
Route::get('tdeposit_kelas', 'App\Http\Controllers\TdepositKelasController@index');
Route::get('tdeposit_kelasKadaluarsa', [TdepositKelasController::class, 'indexKadaluarsa']);
Route::post('tdeposit_kelas', 'App\Http\Controllers\TdepositKelasController@store');
Route::delete('tdeposit_kelas/destroy/{id}', 'App\Http\Controllers\TdepositKelasController@destroy');

//deposit kelas
Route::get('deposit_kelas', 'App\Http\Controllers\DepositKelasController@index');
Route::post('deposit_kelas', 'App\Http\Controllers\DepositKelasController@store');
Route::delete('deposit_kelas', 'App\Http\Controllers\DepositKelasController@destroy');

//izin instruktur
Route::get('instruktur_izin', 'App\Http\Controllers\InstrukturIzinController@index');
Route::post('instruktur_izin', 'App\Http\Controllers\InstrukturIzinController@store');
Route::put('instruktur_izin/confirm/{id}', 'App\Http\Controllers\InstrukturIzinController@update');
Route::get('instruktur_izin/indexIdInstruktur/{id}', 'App\Http\Controllers\InstrukturIzinController@show');

//booking kelas
Route::get('booking_kelas', 'App\Http\Controllers\BookingKelasController@index');
Route::post('booking_kelas', 'App\Http\Controllers\BookingKelasController@store');
Route::delete('booking_kelas/destroy/{id}', 'App\Http\Controllers\BookingKelasController@destroy');
Route::put('booking_kelas/presensi/{id}','App\Http\Controllers\BookingKelasController@presensi');

//booking kelas History
Route::get('booking_kelas_history', 'App\Http\Controllers\BookingKelasHistoryController@index');
Route::post('booking_kelas_history', 'App\Http\Controllers\BookingKelasHistoryController@store');
Route::delete('booking_kelas_history', 'App\Http\Controllers\BookingKelasHistoryController@delete');

//booking gym
Route::get('gym', 'App\Http\Controllers\GymController@index');
Route::post('gym', 'App\Http\Controllers\GymController@store');
Route::delete('gym/destroy/{id}', 'App\Http\Controllers\GymController@destroy');
Route::put('gym/presensi/{id}','App\Http\Controllers\GymController@presensi');


//booking gym
Route::get('booking_gym', 'App\Http\Controllers\BookingGymController@index');
Route::post('booking_gym', 'App\Http\Controllers\BookingGymController@store');
Route::delete('booking_gym/destroy/{id}', 'App\Http\Controllers\BookingGymController@destroy');
Route::put('booking_gym/presensi/{id}','App\Http\Controllers\BookingGymController@presensi');

//booking gym History
Route::get('booking_gym_history', 'App\Http\Controllers\BookingGymHistoryController@index');
Route::post('booking_gym_history', 'App\Http\Controllers\BookingGymHistoryController@store');
Route::delete('booking_gym_history', 'App\Http\Controllers\BookingGymHistoryController@delete');

//laporan
Route::get('Laporan_Gym', 'App\Http\Controllers\LaporanController@laporanGym');
Route::get('Laporan_Kelas', 'App\Http\Controllers\LaporanController@laporanKelas');
Route::get('Laporan_Instruktur', 'App\Http\Controllers\LaporanController@laporanInstruktur');
Route::get('Laporan_Pendapatan', 'App\Http\Controllers\LaporanController@laporanPendapatan');

//presensi instruktur
Route::get('presensi_instruktur', 'App\Http\Controllers\PresensiInstrukturController@showSchedule');
Route::post('presensi_instruktur/{id}', 'App\Http\Controllers\PresensiInstrukturController@store');