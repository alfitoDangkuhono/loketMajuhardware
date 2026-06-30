<?php

namespace App\Http\Controllers;

use App\Models\PaperStatus;
use App\Services\ThermalPrinter;
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
        $paperCritical = PaperStatus::isCritical();
        $paperPercent  = PaperStatus::remainingPercent();

        return view('client.client', compact('paperCritical', 'paperPercent'));
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
     * Generate & simpan tiket antrian baru.
     * - Coba cetak otomatis ke thermal USB (ESC/POS).
     * - Jika gagal / tidak dikonfigurasi -> tampilkan preview browser (window.print).
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

        $payload = [
            'huruf' => $huruf,
            'nomor' => $nomor,
            'jenis' => $jenis,
            'tgl'   => $now->toDateTimeString(),
        ];

        // Coba cetak ke thermal. Jika true -> tidak perlu dialog browser.
        $printed = ThermalPrinter::printTicket($payload);

        return view('cetak_no.cetak', array_merge($payload, [
            'auto_printed' => $printed,
        ]));
    }
}

