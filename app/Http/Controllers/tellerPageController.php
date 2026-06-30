<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TellerPageController extends Controller
{
    /**
     * Konfigurasi loket: kode huruf tiap jenis.
     */
    private const LOKET = [
        'Laptop'  => ['kode' => 'L'],
        'Gadget'  => ['kode' => 'G'],
        'CPU'     => ['kode' => 'C'],
        'Printer' => ['kode' => 'P'],
    ];

    /**
     * 4 route publik (uknown_7..10) tetap dipertahankan agar shortcut
     * kiosk tidak berubah, namun didelegasikan ke satu method.
     */
    public function loketLaptop()
    {
        return $this->teler('Laptop');
    }

    public function loketGadget()
    {
        return $this->teler('Gadget');
    }

    public function loketCpu()
    {
        return $this->teler('CPU');
    }

    public function loketPrinter()
    {
        return $this->teler('Printer');
    }

    private function teler(string $jenis)
    {
        $kode = self::LOKET[$jenis]['kode'];

        // Ambil antrian yang belum dipanggil (st='') urut paling awal.
        $ticket = DB::table('table_no_antrian')
            ->where('st', '')
            ->where('jenis', $jenis)
            ->orderBy('id')
            ->first();

        $no = $ticket->no_antrian ?? 0;

        return view('pageteller.teller', compact('jenis', 'kode', 'no'));
    }

    /**
     * Aksi tombol "Panggil" dari teller.
     *
     * Hanya menandai antrian terlama sebagai "sudah" + dipanggil=0 (menunggu
     * diumumkan). Suara panggilan TIDAK diputar di mesin teller melainkan
     * diputar terpusat di dashboard antrian (TV umum).
     */
    public function call(Request $request)
    {
        $data = $request->validate([
            'jenis' => ['required', Rule::in(array_keys(self::LOKET))],
        ]);

        $ticket = DB::table('table_no_antrian')
            ->where('st', '')
            ->where('jenis', $data['jenis'])
            ->orderBy('id')
            ->first();

        if (! $ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrian untuk dipanggil.',
            ], 404);
        }

        DB::table('table_no_antrian')->where('id', $ticket->id)->update([
            'st'        => 'sudah',
            'dipanggil' => 0,
            'called_at' => now(),
        ]);

        return response()->json([
            'success'    => true,
            'id'         => $ticket->id,
            'no_antrian' => $ticket->no_antrian,
            'jenis'      => $data['jenis'],
        ]);
    }

    /**
     * Panel refresh "No mendatang" / "No selesai" di halaman teller.
     */
    public function refresh(string $jenis)
    {
        $kode = self::LOKET[$jenis]['kode'] ?? '?';

        $belum = DB::table('table_no_antrian')
            ->where('st', '')
            ->where('jenis', $jenis)
            ->orderBy('no_antrian')
            ->first();

        $sudah = DB::table('table_no_antrian')
            ->where('st', 'sudah')
            ->where('jenis', $jenis)
            ->orderByDesc('no_antrian')
            ->first();

        $noDtBelum = $belum->cntr ?? 0;
        $noDtSudah = $sudah ? ($sudah->cntr - 1) : 0;

        return view('pageteller.refreshfunction.refresh', compact('kode', 'noDtBelum', 'noDtSudah'));
    }
}
