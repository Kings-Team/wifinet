<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ubah_paket', function (Blueprint $table) {
            $table->id();
            $table->enum('status_proses', ['Berhasil', 'Gagal'])->nullable();
            $table->text('keterangan_proses')->nullable();
            $table->enum('status_visit', ['Perlu', 'Tidak Perlu'])->nullable();
            $table->timestamps();
        });
    }

    /**     
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubah_paket');
    }
};
