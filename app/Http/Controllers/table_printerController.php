<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class table_printerController extends Controller
{
    public function table_printer_page(){
        return view('table/table_printer');
    }
    public function export_excel(){
        return view('export/export_printer');
    }
}
