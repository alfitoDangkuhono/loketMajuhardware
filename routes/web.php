<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

      //CONNECT DATABASE AND CREATE MODELS
use App\Models\table_no_antrian;

      //FUNCTION CONTROLLLER
use App\Http\Controllers\uploadController;
use App\Http\Controllers\contactController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\antrianController;
use App\Http\Controllers\clientController;

      //TELLER
use App\Http\Controllers\tellerPageController;

      //TABLE CONTROLLER
use App\Http\Controllers\table_hpController;
use App\Http\Controllers\table_laptopController;
use App\Http\Controllers\table_cpuController;
use App\Http\Controllers\table_printerController;




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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//    // return view('login/login');
//    // return view('home/home');
//    return view('admin/admin');
// });


   

// Route::get('/home/home',[clientController::class,'home_Page']);
// Route::get('/home', [HomeController::class, 'index'])->name('home');


      //LOGOUT PAGE
Route::get('/',[homeController::class,'index']);
Route::get('/logout',function(){
      \Auth::logout();
      return redirect('/home');
      });

      //ADMIN PAGE
//Page Main Href code
Route::get('/admin/admin',[homeController::class,'index']);
//Route::get('/login/login',[homeController::class,'login_Page']);
//Route::get('/logout/logout',[homeController::class,'index']);
Route::get('/home', [homeController::class, 'index']);

      //TABEL PAGE
Route::get('uknown_1',[table_hpController::class,'table_page'])->name('table_hp');
Route::get('uknown_2',[table_laptopController::class,'table_laptop_page'])->name('table_laptop');
Route::get('uknown_3',[table_cpuController::class,'table_cpu_page'])->name('table_cpu');
Route::get('uknown_4',[table_printerController::class,'table_printer_page'])->name('table_printer');
Auth::routes();

      //UPLOAD VIDEO CODE
Route::get('uplod',[uploadController::class,'upload_Page'])->name('uplod');
Route::post('plos',[uploadController::class,'video_uplod'])->name('upload');
Route::Get('reset_video',[uploadController::class,'deletvidio'])->name('deletVideo');
      
      //ANTRIAN PAGE
Route::get('antrian',[antrianController::class,'antrian_no_Page'])->name('antri_1');
Route::get('load',[antrianController::class,'load'])->name('load');

      //PAGE TOMBOL CLIENT
Route::get('uknown_5',[clientController::class,'page_konsumen'])->name('client');


      //RESET NO ANTRIAN BUTTON
Route::Get('reset',[homeController::class,'delete'])->name('reset_Button');

      //PRINT NO ANTTRIAN 
Route::get('/cetak_no/cetak_laptop',[clientController::class,'print_Laptop'])->name('cetak_laptop');
Route::get('/cetak_no/cetak_CPU',[clientController::class,'print_CPU'])->name('cetak_CPU');
Route::get('/cetak_no/cetak_Gadget',[clientController::class,'print_Gadget'])->name('cetak_Gadget');
Route::get('/cetak_no/cetak_Printer',[clientController::class,'print_Printer'])->name('cetak_Printer');

      //TELLER PAGE
Route::get('uknown_7',[tellerPageController::class,'teler_Laptop'])->name('teler_laptop');
Route::get('uknown_8',[tellerPageController::class,'teler_Printer'])->name('teler_printer');
Route::get('uknown_9',[tellerPageController::class,'teler_Gadget'])->name('teler_gadget');
Route::get('uknown_10',[tellerPageController::class,'teler_CPU'])->name('teler_cpu');

      //CONVERT EXCEL
Route::get('convert_L',[table_laptopController::class,'export_excel'])->name('convert_L');
Route::get('convert_G',[table_hpController::class,'export_excel'])->name('convert_G');
Route::get('convert_C',[table_cpuController::class,'export_excel'])->name('convert_C');
Route::get('convert_P',[table_printerController::class,'export_excel'])->name('convert_P');