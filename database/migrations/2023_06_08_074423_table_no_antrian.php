<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// use DateTime;

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
            $table->integer('no_antrian');
            $table->string('huruf')->nullable();
            $table->string('jenis');
            $table->dateTime('tgl')->nullable();
            $table->time('waktu')->nullable();
            $table->string('st')->default('');
            $table->integer('cntr')->default(0);
            $table->boolean('dipanggil')->default(0);
            $table->timestamp('called_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_no_antrian');
    }
};
