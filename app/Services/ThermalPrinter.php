<?php

namespace App\Services;

use Exception;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ThermalPrinter
{
    /**
     * Cetak tiket antrian ke printer thermal.
     *
     * Mode yang didukung (lihat config printing.mode):
     *   - 'share'    : via Windows share name (default, butuh sharing diaktifkan)
     *   - 'usb'      : tulis raw bytes langsung ke port USB (USB001, USB002, ...) - TANPA share
     *   - 'com'      : tulis ke COM port (mis. COM3) untuk printer serial / virtual COM
     *   - 'network'  : via TCP ke IP:port printer (port raw umumnya 9100)
     *   - 'file'     : tulis ke file path (Linux: /dev/usb/lp0)
     *
     * @param  array  $data  ['huruf', 'nomor', 'jenis', 'tgl']
     * @return bool  true jika berhasil cetak; false jika gagal (-> fallback browser)
     */
    public static function printTicket(array $data): bool
    {
        $mode  = config('printing.mode', 'share');
        $name  = config('printing.name');
        $width = (int) config('printing.width', 32);

        // Jika target tidak dikonfigurasi di .env -> langsung fallback
        if (empty($name)) {
            return false;
        }

        $printer = null;

        try {
            $connector = self::buildConnector($mode, $name);
            $printer   = new Printer($connector);

            $header = config('printing.header');
            $footer = config('printing.footer');
            $kode   = $data['huruf'] . ' ' . str_pad((string) $data['nomor'], 2, '0', STR_PAD_LEFT);

            // Header toko
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            foreach (explode("\n", $header) as $line) {
                $printer->text($line . "\n");
            }
            $printer->text("\n");

            // Loket
            $printer->text('Loket : ' . $data['jenis'] . "\n");
            $printer->text("\n");

            // Nomor antrian besar
            $printer->setTextSize(3, 3);
            $printer->setEmphasis(true);
            $printer->text($kode . "\n");
            $printer->setEmphasis(false);
            $printer->setTextSize(1, 1);
            $printer->text("\n");

            // Tanggal & waktu
            $printer->text($data['tgl'] . "\n");
            $printer->text("\n");

            // Footer
            foreach (explode("\n", $footer) as $line) {
                $printer->text($line . "\n");
            }
            $printer->text("\n");

            // Garis pemutus + cut
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text(str_repeat('-', max($width, 24)) . "\n");
            $printer->cut(Printer::CUT_FULL, 3);

            $printer->close();
            $printer = null;

            return true;
        } catch (Exception $e) {
            // Pastikan resource tertutup walau gagal
            if ($printer !== null) {
                try { $printer->close(); } catch (Exception $e) {}
            }
            logger()->warning('Thermal print gagal [' . $mode . ']: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Bangun connector sesuai mode.
     */
    protected static function buildConnector(string $mode, string $name)
    {
        switch (strtolower($mode)) {
            case 'usb':
            case 'com':
            case 'file':
                // Tulis raw bytes langsung ke path/port (USB001, COM3, /dev/usb/lp0, ...)
                return new FilePrintConnector($name);

            case 'network':
                // Format: "IP:PORT" atau hanya "IP" (default port 9100)
                $parts = explode(':', $name);
                $host  = trim($parts[0]);
                $port  = isset($parts[1]) ? (int) $parts[1] : 9100;
                return new NetworkPrintConnector($host, $port);

            case 'share':
            default:
                // Windows share name (bisa lokal atau \\komputer\printer)
                return new WindowsPrintConnector($name);
        }
    }
}
