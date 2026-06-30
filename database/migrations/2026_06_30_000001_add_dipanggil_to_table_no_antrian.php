<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom pendukung sistem pengumuman suara terpusat.
 *
 * - dipanggil  : 0 = antrian sudah dipanggil teller tapi belum diumumkan suaranya
 *                di dashboard antrian. 1 = sudah diumumkan.
 * - called_at  : waktu teller menekan tombol Panggil (urut antrian pengumuman).
 *
 * Dengan kolom ini suara panggilan cukup diputar di satu dashboard antrian
 * (TV umum), sehingga tidak lagi perlu speaker di tiap loket teller.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('table_no_antrian', function (Blueprint $table) {
            if (! Schema::hasColumn('table_no_antrian', 'dipanggil')) {
                $table->boolean('dipanggil')->default(0)->after('cntr');
            }
            if (! Schema::hasColumn('table_no_antrian', 'called_at')) {
                $table->timestamp('called_at')->nullable()->after('dipanggil');
            }
        });

        // Tandai semua data LAMA sudah pernah "diumumkan" agar dashboard
        // tidak memutar pengumuman masa lalu secara beruntun saat first load.
        // Hanya panggilan teller BARU (setelah migrate) yang dipanggil=0.
        DB::table('table_no_antrian')->where('st', 'sudah')->update(['dipanggil' => 1]);
    }

    public function down(): void
    {
        Schema::table('table_no_antrian', function (Blueprint $table) {
            $table->dropColumn(['dipanggil', 'called_at']);
        });
    }
};
