<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class antrianController extends Controller
{
    public function antrian_no_page(){
        return view('antrian_no/antrian');
    }
    public function load(){
        return view('antrian_no/function');
    }

}
