<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaperStatus extends Model
{
    protected $table = 'paper_status';

    protected $fillable = [
        'tickets_printed',
        'last_replaced_at',
    ];

    /**
     * Ambil baris tunggal (selalu id=1). Auto-create kalau belum ada.
     */
    public static function current(): self
    {
        $row = self::find(1);

        if (! $row) {
            $row = self::create([
                'tickets_printed'  => 0,
                'last_replaced_at' => now(),
            ]);
        }

        return $row;
    }

    /**
     * Tambah counter tiket tercetak (dipanggil setiap kali thermal sukses cetak).
     */
    public static function incrementPrinted(int $by = 1): void
    {
        self::current()->increment('tickets_printed', $by);
    }

    /**
     * Reset counter (admin klik "Kertas Diganti").
     */
    public static function reset(): void
    {
        self::where('id', 1)->update([
            'tickets_printed'  => 0,
            'last_replaced_at' => now(),
            'updated_at'       => now(),
        ]);
    }

    /**
     * Hitung persentase sisa kertas berdasarkan kapasitas.
     * Return: int 0-100.
     */
    public static function remainingPercent(): int
    {
        $capacity = (int) config('printing.capacity_tickets', 1200);
        $capacity = max($capacity, 1);

        $printed = self::current()->tickets_printed;
        $remaining = max($capacity - $printed, 0);

        return (int) round(($remaining / $capacity) * 100);
    }

    /**
     * Apakah sudah masuk threshold warning?
     */
    public static function isLow(): bool
    {
        $warn = (int) config('printing.warn_percent', 20);

        return self::remainingPercent() <= $warn;
    }

    /**
     * Apakah kritis (≤10%)? → banner di kiosk.
     */
    public static function isCritical(): bool
    {
        return self::remainingPercent() <= 10;
    }
}
