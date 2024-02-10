<?php

use App\Http\Controllers\MasterguruController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AutentikasiController;
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
