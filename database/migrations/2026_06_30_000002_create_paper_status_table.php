<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paper_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tickets_printed')->default(0);
            $table->timestamp('last_replaced_at')->nullable();
            $table->timestamps();
        });

        // Seed 1 baris default agar mudah di-update (selalu id=1).
        DB::table('paper_status')->insert([
            'tickets_printed'  => 0,
            'last_replaced_at' => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('paper_status');
    }
};
