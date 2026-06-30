<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    /**
     * Halaman landing / menu utama.
     */
    public function face()
    {
        return view('interface/interface');
    }
}
