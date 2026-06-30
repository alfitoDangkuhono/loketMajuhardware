<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Halaman dashboard admin (diproteksi middleware auth).
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $counts = [
            'Laptop'  => DB::table('table_no_antrian')->where('jenis', 'Laptop')->count(),
            'Gadget'  => DB::table('table_no_antrian')->where('jenis', 'Gadget')->count(),
            'CPU'     => DB::table('table_no_antrian')->where('jenis', 'CPU')->count(),
            'Printer' => DB::table('table_no_antrian')->where('jenis', 'Printer')->count(),
        ];

        return view('admin.admin', compact('counts'));
    }

    /**
     * Reset seluruh nomor antrian (truncate).
     */
    public function resetAntrian()
    {
        DB::table('table_no_antrian')->truncate();

        return redirect('/home');
    }
}
