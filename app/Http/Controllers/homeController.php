<?php

namespace App\Http\Controllers;

use App\Models\table_no_antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // $email=['email'];
        // $password=['password'];
        // $divisi_admin='admin';
        // if (Auth::attempt(['email' => $email, 'password' => $password, 'divisi'=>$divisi_admin])) {
        //     return redirect()->intended('admin/admin');
        // }
        
        return view('admin/admin');
       // return view('login/login');
       // return view('/home');
    }



    public function delete(){    
        DB::table('table_no_antrian')->truncate();                                          // -----> AUTOMATIC CODE  QUERY DELETE/RESET NO_ANTRIAN <------- \\
        return redirect('/home');
    }
    
   
}
