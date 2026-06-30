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
     * Dipoll dashboard: kembalikan video & teks terbaru dari DB supaya TV
     * otomatis berganti tanpa perlu refresh halaman saat admin upload baru.
     */
    public function content()
    {
        return response()->json([
            'video' => DB::table('video')->value('video'),
            'texts' => DB::table('text_db')->pluck('text'),
        ]);
    }

    /**
     * Panel angka gabungan untuk semua jenis (dipoll dashboard).
     * Return JSON { jenis: { kode, no } } — 1 request untuk semua kolom
     * (sebelumnya 4 request terpisah via .load HTML).
     */
    public function panelAll()
    {
        $result = [];
        foreach (self::KODE as $jenis => $kode) {
            $row = DB::table('table_no_antrian')
                ->where('st', 'sudah')
                ->where('jenis', $jenis)
                ->orderByDesc('no_antrian')
                ->first();

            $result[$jenis] = [
                'kode' => $kode,
                'no'   => sprintf('%02d', $row->cntr ?? 0),
            ];
        }

        return response()->json($result);
    }

    /**
     * Dipoll dashboard: ambil 1 antrian berstatus "sudah dipanggil" tapi belum
     * diumumkan (dipanggil=0). Return JSON untuk diputar suaranya.
     *
     * Baris langsung di-claim (dipanggil=1) secara atomik di dalam transaksi
     * + lockForUpdate agar poll konkuren (dari dashboard yang sama maupun TV
     * lain) tidak mengambil baris yang sama -> mencegah suara diputar dobel.
     */
    public function nextCall()
    {
        return DB::transaction(function () {
            $row = DB::table('table_no_antrian')
                ->where('st', 'sudah')
                ->where('dipanggil', 0)
                ->orderBy('called_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (! $row) {
                return response()->json(['empty' => true]);
            }

            DB::table('table_no_antrian')
                ->where('id', $row->id)
                ->update(['dipanggil' => 1]);

            return response()->json([
                'id'         => $row->id,
                'no_antrian' => (int) $row->no_antrian,
                'jenis'      => $row->jenis,
                'kode'       => self::KODE[$row->jenis] ?? '?',
            ]);
        });
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
