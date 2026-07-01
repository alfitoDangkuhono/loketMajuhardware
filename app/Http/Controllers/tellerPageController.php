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

        $ticket = DB::table('table_no_antrian')
            ->where('st', '')
            ->where('jenis', $jenis)
            ->whereDate('tgl', now()->toDateString())
            ->orderBy('id')
            ->first();

        $no = $ticket->no_antrian ?? 0;

        // Nilai awal panel (No mendatang / No selesai) dirender sekali di
        // server, selanjutnya diupdate via polling JSON di teller.blade.php.
        [$noDtBelum, $noDtSudah] = $this->computeRefreshNumbers($jenis);

        return view('pageteller.teller', compact('jenis', 'kode', 'no', 'noDtBelum', 'noDtSudah'));
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
            ->whereDate('tgl', now()->toDateString())
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
     * Hitung nomor "No mendatang" & "No selesai" untuk sebuah jenis.
     * Dipakai oleh teler() (render awal) & refresh() (polling JSON).
     *
     * - No mendatang = tiket menunggu KEDUA yang nyata ada di database
     *   (yang akan dipanggil setelah nomor sekarang). Jika kurang dari 2
     *   tiket menunggu -> 0 (tidak ada antrian mendatang yang tersedia).
     * - No selesai   = tiket terakhir yang dipanggil (sedang/terakhir dilayani).
     */
    private function computeRefreshNumbers(string $jenis): array
    {
        $menunggu = DB::table('table_no_antrian')
            ->where('st', '')
            ->where('jenis', $jenis)
            ->whereDate('tgl', now()->toDateString())
            ->orderBy('no_antrian')
            ->take(2)
            ->get();

        $sudah = DB::table('table_no_antrian')
            ->where('st', 'sudah')
            ->where('jenis', $jenis)
            ->whereDate('tgl', now()->toDateString())
            ->orderByDesc('no_antrian')
            ->first();

        $noDtBelum = $menunggu->count() >= 2 ? $menunggu[1]->cntr : 0;
        $noDtSudah = $sudah->cntr ?? 0;

        return [$noDtBelum, $noDtSudah];
    }

    /**
     * Panel refresh "No mendatang" / "No selesai" di halaman teller.
     * Return JSON (bukan HTML) supaya payload kecil & parsing cepat.
     */
    public function refresh(string $jenis)
    {
        [$noDtBelum, $noDtSudah] = $this->computeRefreshNumbers($jenis);

        return response()->json([
            'mendatang' => sprintf('%02d', $noDtBelum),
            'selesai'   => sprintf('%02d', $noDtSudah),
        ]);
    }
}
