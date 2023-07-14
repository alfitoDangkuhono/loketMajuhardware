<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DateTime;

//use\App\Models\table_no_antrian;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_no_antrian', function (Blueprint $table) {
            $table->id();
            $table->int('no_antrian');
            $table->string('huruf');
            $table->string('jenis');       
            $table->date('waktu');
            $table->date('tgl');
            $table->string('st');
            $table->int('cntr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('table_no_antrian');
    }
};
