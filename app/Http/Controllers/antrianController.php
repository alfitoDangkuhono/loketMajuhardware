<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{
    /**
     * Mapping jenis -> kode huruf antrian.
     */
    private const KODE = [
        'Laptop'  => 'L',
        'Gadget'  => 'G',
        'CPU'     => 'C',
        'Printer' => 'P',
    ];

    /**
     * Tampilan dashboard antrian (TV umum) - pemutar suara panggilan terpusat.
     * Data video promosi & running text diambil di controller, bukan di view.
     */
    public function index()
    {
        $videos = DB::table('video')->get();
        $texts  = DB::table('text_db')->get();

        return view('antrian_no.antrian', compact('videos', 'texts'));
    }

    /**
     * Panel angka per jenis pada dashboard (dipoll jQuery).
     */
    public function panelAngka(string $jenis)
    {
        $kode = self::KODE[$jenis] ?? '?';

        $row = DB::table('table_no_antrian')
            ->where('st', 'sudah')
            ->where('jenis', $jenis)
            ->orderByDesc('no_antrian')
            ->first();

        $dt = $row->cntr ?? 0;

        return view('antrian_no.freshfuntc.fresh', compact('kode', 'dt'));
    }

    /**
     * Dipoll dashboard: ambil 1 antrian berstatus "sudah dipanggil" tapi belum
     * diumumkan (dipanggil=0). Return JSON untuk diputar suaranya.
     */
    public function nextCall()
    {
        $row = DB::table('table_no_antrian')
            ->where('st', 'sudah')
            ->where('dipanggil', 0)
            ->orderBy('called_at')
            ->orderBy('id')
            ->first();

        if (! $row) {
            return response()->json(['empty' => true]);
        }

        return response()->json([
            'id'         => $row->id,
            'no_antrian' => (int) $row->no_antrian,
            'jenis'      => $row->jenis,
            'kode'       => self::KODE[$row->jenis] ?? '?',
        ]);
    }

    /**
     * Tandai sebuah antrian sudah diumumkan agar tidak diputar dobel.
     */
    public function markAnnounced($id)
    {
        DB::table('table_no_antrian')
            ->where('id', $id)
            ->update(['dipanggil' => 1]);

        return response()->json(['success' => true]);
    }
}
