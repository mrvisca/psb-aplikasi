<?php

use App\Http\Controllers\MastersiswaController;
use App\Http\Controllers\MasterguruController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MasterkriteriaController;
use App\Http\Controllers\MastermapelController;
use App\Http\Controllers\MastertajarController;
use App\Http\Controllers\RaporController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/otentikasi/login',[AutentikasiController::class, 'loginView'])->name('login');
Route::get('/aplikasi/dashboard',[DashboardController::class, 'index'])->name('dashboard');
Route::get('/aplikasi/comingsoon-page',[DashboardController::class, 'pageConstruction'])->name('comingsoon');
Route::get('/aplikasi/master-guru',[MasterguruController::class, 'index'])->name('masterguru');
Route::get('/aplikasi/master-siswa',[MastersiswaController::class, 'index'])->name('mastersiswa');
Route::get('/aplikasi/master-jurusan',[JurusanController::class, 'index'])->name('masterjurusan');
Route::get('/aplikasi/master-mapel',[MastermapelController::class, 'index'])->name('masterpelajaran');
Route::get('/aplikasi/master-tajar',[MastertajarController::class, 'index'])->name('mastertajar');
Route::get('/aplikasi/master-kriteria',[MasterkriteriaController::class, 'index'])->name('masterkriteria');
Route::get('/aplikasi/data-nilai/rapor',[RaporController::class, 'index'])->name('data_nilai.rapor');
