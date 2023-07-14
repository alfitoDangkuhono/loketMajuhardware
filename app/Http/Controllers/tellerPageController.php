<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tellerPageController extends Controller
{
    public function teler_Laptop(){
        return view('pageteller/laptopteler');
    }
    public function teler_Printer(){
        return view('pageteller/printerteller');
    }
    public function teler_Gadget(){
        return view('pageteller/gadgetteller');
    }
    public function teler_CPU(){
        return view('pageteller/CPUteller');
    }
    public function getpost(){
        // print_r($request);
        return view('pageteller/update');
    }
   
}
