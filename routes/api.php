<?php

use App\Http\Controllers\MasterguruController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MasterMapelController;
use App\Http\Controllers\MastersiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('autentikasi')->group(function () {
    Route::post("masuk", [AutentikasiController::class, 'loginCheck']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get("/home", [DashboardController::class, 'getProfile']);
    });

    Route::prefix('master-guru')->group(function () {
        Route::post("/list", [MasterguruController::class, 'listGuru']);
        Route::post("/tambah-data", [MasterguruController::class, 'addMaster']);
        Route::get("/data-support/role", [MasterguruController::class, 'supportRole']);
        Route::put("/update-data/{id}", [MasterguruController::class, 'updateGuru']);
        Route::delete("/hapus-data/{id}", [MasterguruController::class, 'hapus']);
        Route::get("/export-data", [MasterguruController::class, 'exportData']);
        Route::get("/download-template", [MasterguruController::class, 'template']);
        Route::post("/import-data", [MasterguruController::class, 'import']);
    });

    Route::prefix('master-jurusan')->group(function () {
        Route::get("/list", [JurusanController::class, 'listJurusan']);
        Route::post("/tambah-data", [JurusanController::class, 'addJurusan']);
        Route::put("/update-data/{id}", [JurusanController::class, 'update']);
        Route::delete("/hapus-data/{id}", [JurusanController::class, 'hapus']);
    });

    Route::prefix('master-siswa')->group(function() {
        Route::post("/list", [MastersiswaController::class, 'listSiswa']);
        Route::get("/data-support/role", [MastersiswaController::class, 'supportRole']);
        Route::post("/tambah-data", [MastersiswaController::class, 'addMaster']);
        Route::put("/update-data/{id}", [MastersiswaController::class, 'updateSiswa']);
        Route::put("/delete-data/{id}", [MastersiswaController::class, 'deleteSiswa']);
        Route::get("/export-data", [MastersiswaController::class, 'exportData']);
        Route::post("/import-data", [MastersiswaController::class, 'importData']);
        Route::get("/download-template", [MastersiswaController::class, 'template']);
    });

    Route::prefix('master-mapel')->group(function() {
        Route::post("/list", );
        Route::get("/data-support/role", [MasterMapelController::class, 'supportRole']);
    });
});
