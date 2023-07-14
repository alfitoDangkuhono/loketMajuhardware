<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class table_hpController extends Controller
{
    public function table_page(){
        return view('table/table_hp');
    }
    public function export_excel(){
        return view('export/export_gadget');
    }
}
