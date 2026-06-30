<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TableCpuController extends Controller
{
    private const JENIS      = 'CPU';
    private const MANGO_URL  = 'mango_C';
    private const EXPORT_URL = 'convert_C';
    private const TITLE      = 'CPU';

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
