<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TablePrinterController extends Controller
{
    private const JENIS      = 'Printer';
    private const MANGO_URL  = 'mango_P';
    private const EXPORT_URL = 'convert_P';
    private const TITLE      = 'Printer';

    public function index()
    {
        return view('table.table', ['mangoUrl' => self::MANGO_URL, 'title' => self::TITLE]);
    }

    public function refresh()
    {
        $rows = DB::table('table_no_antrian')->where('jenis', self::JENIS)->orderBy('id')->get();

        return view('table.tabfresh.tab', [
            'rows'      => $rows,
            'exportUrl' => self::EXPORT_URL,
            'title'     => self::TITLE,
        ]);
    }

    public function export()
    {
        $rows = DB::table('table_no_antrian')->where('jenis', self::JENIS)->orderBy('id')->get();

        return view('export.export', ['rows' => $rows, 'title' => self::TITLE]);
    }
}
