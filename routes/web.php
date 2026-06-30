<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TableCpuController;
use App\Http\Controllers\TableHpController;
use App\Http\Controllers\TableLaptopController;
use App\Http\Controllers\TablePrinterController;
use App\Http\Controllers\TellerPageController;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// LANDING & AUTH (PUBLIK)
Route::get('/', [AuthController::class, 'face'])->name('interface');
Route::get('/login', [HomeController::class, 'index'])->name('login');

// Logout ditangani Auth::routes() (POST) → trait AuthenticatesUsers::logout()
// yang sudah invalidate session + regenerate CSRF token lalu redirect ke /.

Auth::routes();

// ROUTE YANG DIPROTEKSI (wajib login + anti-cache)
Route::middleware(['auth', 'nocache'])->group(function () {

    // ADMIN DASHBOARD
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('reset', [HomeController::class, 'resetAntrian'])->name('reset_button');

    // UPLOAD VIDEO & RUNNING TEXT
    Route::get('uplod', [UploadController::class, 'videoPage'])->name('uplod');
    Route::post('plos', [UploadController::class, 'uploadVideo'])->name('upload');
    Route::get('reset_video', [UploadController::class, 'deleteVideo'])->name('deletVideo');
    Route::get('plod', [UploadController::class, 'textPage'])->name('upload_text');
    Route::post('plood', [UploadController::class, 'uploadText'])->name('upload_txt');
    Route::get('deltext', [UploadController::class, 'deleteText'])->name('delettext');

    // EXPORT EXCEL/PDF PER JENIS
    Route::get('convert_L', [TableLaptopController::class, 'export'])->name('convert_L');
    Route::get('convert_G', [TableHpController::class, 'export'])->name('convert_G');
    Route::get('convert_C', [TableCpuController::class, 'export'])->name('convert_C');
    Route::get('convert_P', [TablePrinterController::class, 'export'])->name('convert_P');
});

// PANEL REFRESH RIWAYAT (jQuery .load)
Route::get('mango_L', [TableLaptopController::class, 'refresh'])->name('mango_L');
Route::get('mango_G', [TableHpController::class, 'refresh'])->name('mango_G');
Route::get('mango_C', [TableCpuController::class, 'refresh'])->name('mango_C');
Route::get('mango_P', [TablePrinterController::class, 'refresh'])->name('mango_P');
// TELLER (LOKET)
Route::get('uknown_7', [TellerPageController::class, 'loketLaptop'])->name('teler_laptop');
Route::get('uknown_8', [TellerPageController::class, 'loketPrinter'])->name('teler_printer');
Route::get('uknown_9', [TellerPageController::class, 'loketGadget'])->name('teler_gadget');
Route::get('uknown_10', [TellerPageController::class, 'loketCpu'])->name('teler_cpu');
Route::post('teller/call', [TellerPageController::class, 'call'])->name('teller.call');
Route::get('move/{jenis}', [TellerPageController::class, 'refresh'])->name('move');

// RIWAYAT ANTRIAN PER JENIS
Route::get('uknown_1', [TableHpController::class, 'index'])->name('table_hp');
Route::get('uknown_2', [TableLaptopController::class, 'index'])->name('table_laptop');
Route::get('uknown_3', [TableCpuController::class, 'index'])->name('table_cpu');
Route::get('uknown_4', [TablePrinterController::class, 'index'])->name('table_printer');

// ROUTE PUBLIK (TV ANTRIAN & KIOS CUSTOMER) - tanpa login
Route::get('antrian', [AntrianController::class, 'index'])->name('antri_1');
Route::get('antrian/next-call', [AntrianController::class, 'nextCall'])->name('antrian.next_call');
Route::post('antrian/mark-announced/{id}', [AntrianController::class, 'markAnnounced'])->name('antrian.mark_announced');
Route::get('load/{jenis}', [AntrianController::class, 'panelAngka'])->name('load');

Route::get('uknown_5', [ClientController::class, 'index'])->name('client');
Route::get('/cetak_no/cetak_laptop', [ClientController::class, 'cetakLaptop'])->name('cetak_laptop');
Route::get('/cetak_no/cetak_CPU', [ClientController::class, 'cetakCpu'])->name('cetak_CPU');
Route::get('/cetak_no/cetak_Gadget', [ClientController::class, 'cetakGadget'])->name('cetak_Gadget');
Route::get('/cetak_no/cetak_Printer', [ClientController::class, 'cetakPrinter'])->name('cetak_Printer');
