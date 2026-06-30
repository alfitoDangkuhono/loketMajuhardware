<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Mapping jenis antrian -> kode huruf.
     */
    private const KODE = [
        'Laptop'  => 'L',
        'Gadget'  => 'G',
        'CPU'     => 'C',
        'Printer' => 'P',
    ];

    /**
     * Halaman kiosk customer (pilihan jenis antrian).
     */
    public function index()
    {
        return view('client.client');
    }

    public function cetakLaptop()
    {
        return $this->cetakTicket('Laptop');
    }

    public function cetakGadget()
    {
        return $this->cetakTicket('Gadget');
    }

    public function cetakCpu()
    {
        return $this->cetakTicket('CPU');
    }

    public function cetakPrinter()
    {
        return $this->cetakTicket('Printer');
    }

    /**
     * Generate & simpan tiket antrian baru, lalu tampilkan view cetak.
     * Semua akses DB ada di controller (bukan di view).
     */
    private function cetakTicket(string $jenis)
    {
        $huruf = self::KODE[$jenis];
        $now   = now();

        $nomor = DB::table('table_no_antrian')->where('jenis', $jenis)->count() + 1;

        DB::table('table_no_antrian')->insert([
            'no_antrian' => $nomor,
            'huruf'      => $huruf,
            'jenis'      => $jenis,
            'st'         => '',
            'tgl'        => $now->toDateTimeString(),
            'waktu'      => $now->format('H:i'),
            'cntr'       => $nomor,
        ]);

        return view('cetak_no.cetak', [
            'huruf' => $huruf,
            'nomor' => $nomor,
            'jenis' => $jenis,
            'tgl'   => $now->toDateTimeString(),
        ]);
    }
}
