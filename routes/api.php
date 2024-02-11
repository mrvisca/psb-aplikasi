<?php

use App\Http\Controllers\MasterguruController;
use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DashboardController;
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
});
