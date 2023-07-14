<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class table_cpuController extends Controller
{
    public function table_cpu_page(){
        return view('table/table_cpu');
    }
    public function export_excel(){
        return view('export/export_cpu');
    }
}
