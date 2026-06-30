<?php

namespace App\Http\Controllers;

use App\Models\PaperStatus;
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

        $paper = PaperStatus::current();
        $paperPercent = PaperStatus::remainingPercent();
        $paperIsLow   = PaperStatus::isLow();

        return view('admin.admin', compact(
            'counts',
            'paper',
            'paperPercent',
            'paperIsLow'
        ));
    }

    /**
     * Reset seluruh nomor antrian (truncate).
     */
    public function resetAntrian()
    {
        DB::table('table_no_antrian')->truncate();

        return redirect('/home');
    }

    /**
     * Reset counter kertas setelah operator mengganti roll.
     */
    public function resetPaper()
    {
        PaperStatus::reset();

        return redirect('/home')->with('paper_reset', true);
    }
}

