<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

Route::group(['middleware' => 'auth'], function() {
	Route::prefix('statistik')->name('statistik.')->group(function() {
		Route::post('/', [App\Http\Controllers\HomeController::class, 'statistik'])->name('index');
	});

	Route::prefix('rekap')->name('rekap.')->group(function() {
		Route::get('/', [App\Http\Controllers\RekapController::class, 'index'])->name('index');
		Route::post('/date-range', [App\Http\Controllers\RekapController::class,'filter_date'])->name('date-range');
		Route::post('/filter-tahun', [App\Http\Controllers\RekapController::class,'filter_tahun'])->name('filter-tahun');
		Route::post('/rekap-denumerator', [App\Http\Controllers\RekapController::class,'rekap_denumerator'])->name('rekap-denumerator');
	});

	Route::resource('pasien', App\Http\Controllers\PasienFromOtherDbController::class);
	Route::prefix('pasien')->name('pasien.')->group(function() {
		Route::post('/data-ruang', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_ruang'])->name('data-ruang');
		Route::post('/data-diagnosa', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_diagnosa'])->name('data-diagnosa');
		Route::post('/data-jenis-operasi', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_jenis_operasi'])->name('data-jenis-operasi');
		Route::post('/data-tindakan-operasi', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_tindakan_operasi'])->name('data-tindakan-operasi');
		Route::post('/data-lama-operasi', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_lama_operasi'])->name('data-lama-operasi');
		Route::post('/data-asa-score', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_asa_score'])->name('data-asa-score');
		Route::post('/data-risk-score', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_risk_score'])->name('data-risk-score');
		Route::post('/data-alat-digunakan', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_alat_digunakan'])->name('data-alat-digunakan');
		Route::post('/data-kegiatan-sensus', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_kegiatan_sensus'])->name('data-kegiatan-sensus');
		Route::post('/data-jenis-kuman', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_jenis_kuman'])->name('data-jenis-kuman');
		Route::post('/data-antibiotik', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_antibiotik'])->name('data-antibiotik');
		Route::post('/data-transmisi', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_transmisi'])->name('data-transmisi');
		Route::post('/data-infeksi-rs-lain', [App\Http\Controllers\PasienFromOtherDbController::class, 'data_infeksi_rs_lain'])->name('data-infeksi-rs-lain');

		Route::get('/data/{id}',[App\Http\Controllers\PasienFromOtherDbController::class, 'get_detail_with_id'])->name('detail-with-id');
	});

	Route::prefix('master')->name('master.')->group(function () {
		
		Route::prefix('charts')->name('charts.')->group(function () {
			Route::get('/hais-chart', [App\Http\Controllers\ChartController::class, 'hais_chart'])->name('hais-chart');
		});

		Route::prefix('kategori-antibiotik')->name('kategori-antibiotik.')->group(function () {
			Route::resource('r-kategori-antibiotik', App\Http\Controllers\KategoriAntibiotikController::class);
		});

		Route::prefix('alat-digunakan')->name('alat-digunakan.')->group(function () {
			Route::resource('r-alat-digunakan', App\Http\Controllers\AlatDigunakanController::class);
		});

		Route::prefix('antibiotik')->name('antibiotik.')->group(function() {
			Route::resource('r-antibiotik', App\Http\Controllers\AntibiotikController::class);
			Route::post('/simpan-antibiotik-tmp', [App\Http\Controllers\AntibiotikController::class, 'simpan_antibiotik_tmp'])->name('simpan-antibiotik-tmp');
			Route::delete('/reset-antibiotik-tmp/{no_rm}', [App\Http\Controllers\AntibiotikController::class, 'reset_antibiotik_tmp'])->name('reset-antibiotik-tmp');
		});

		Route::prefix('asa-score')->name('asa-score.')->group(function() {
			Route::resource('r-asa-score', App\Http\Controllers\AsaScoreController::class);
		});

		Route::prefix('risk-score')->name('risk-score.')->group(function() {
			Route::resource('r-risk-score', App\Http\Controllers\RiskScoreController::class);
		});

		Route::prefix('jenis-kuman')->name('jenis-kuman.')->group(function() {
			Route::resource('r-jenis-kuman', App\Http\Controllers\JenisKumanController::class);
		});

		Route::prefix('jenis-operasi')->name('jenis-operasi.')->group(function() {
			Route::resource('r-jenis-operasi', App\Http\Controllers\JenisOperasiController::class);
		});

		Route::prefix('tindakan-operasi')->name('tindakan-operasi.')->group(function() {
			Route::resource('r-tindakan-operasi', App\Http\Controllers\TindakanOperasiController::class);
		});

		Route::prefix('kegiatan-sensus')->name('kegiatan-sensus.')->group(function() {
			Route::resource('r-kegiatan-sensus', App\Http\Controllers\KegiatanSensusController::class);
		});

		Route::prefix('lama-operasi')->name('lama-operasi.')->group(function() {
			Route::resource('r-lama-operasi', App\Http\Controllers\LamaOperasiController::class);
		});

		Route::prefix('transmisi')->name('transmisi.')->group(function() {
			Route::resource('r-transmisi', App\Http\Controllers\TransmisiController::class);
		});

		Route::prefix('ruang')->name('ruang.')->group(function() {
			Route::resource('r-ruang', App\Http\Controllers\RuangController::class);
		});
	});
});


