<?php

use App\Http\Controllers\MastersiswaController;
use App\Http\Controllers\MasterguruController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MasterkriteriaController;
use App\Http\Controllers\MastermapelController;
use App\Http\Controllers\MastertajarController;
use App\Http\Controllers\RaporController;
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
        Route::post("/tambah-data", [MastersiswaController::class, 'createUser']);
        Route::get("/data-support/jurusan", [MastersiswaController::class, 'listJurusan']);
        Route::put("/update-data/{id}", [MastersiswaController::class, 'updateData']);
        Route::delete("/hapus-data/{id}", [MastersiswaController::class, 'hapus']);
        Route::get("/export-data/export-xls", [MastersiswaController::class, 'exportData']);
        Route::get("/export-data/download-template", [MastersiswaController::class, 'template']);
        Route::post("/import-data/import-xls", [MastersiswaController::class, 'import']);
    });

    Route::prefix('master-mapel')->group(function () {
        Route::post("/list", [MastermapelController::class, 'listMapel']);
        Route::get("/data-support/kelas", [MastermapelController::class, 'kelasSupport']);
        Route::post("/tambah-data", [MastermapelController::class, 'addMapel']);
        Route::put("/update-data/{id}", [MastermapelController::class, 'updateData']);
        Route::delete("/hapus-data/{id}", [MastermapelController::class, 'deleteData']);
        Route::get("/export-data/export-xls", [MastermapelController::class, 'exportData']);
        Route::get("/export-data/download-template", [MastermapelController::class, 'template']);
        Route::post("/import-data/import-xls", [MastermapelController::class, 'import']);
    });

    Route::prefix('master-tahun-ajar')->group(function () {
        Route::get("/list", [MastertajarController::class, 'listTajar']);
        Route::post("/tambah-data", [MastertajarController::class, 'tambahData']);
        Route::put("/update-data/{id}", [MastertajarController::class, 'updateData']);
        Route::delete("/hapus-data/{id}", [MastertajarController::class, 'hapus']);
    });

    Route::prefix('data-nilai')->group(function () {
        Route::prefix('rapor-siswa')->group(function () {
            Route::get("/test", [RaporController::class, 'testRapor']); // Test Api untuk template dan detail rapor
            Route::post("/list", [RaporController::class, 'listRapor']);
            Route::get("/data-support/tajar", [RaporController::class, 'supportTajar']);
            Route::get("/export-data/export-xls", [RaporController::class, 'downloadTemplate']);
            Route::post("/import-data/import-xls", [RaporController::class, 'import']);
        });
    });


});
