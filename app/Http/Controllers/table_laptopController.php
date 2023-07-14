<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\table_no_antrian;
// use Maatwebsite\Excel\Facades\Excel;

use views\table\table_laptop;

class table_laptopController extends Controller
{
    public function table_laptop_page(){
        return view('table/table_laptop');
    }
    public function export_excel(){
        return view('export/export_laptop');
    }
  
}
