<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemasangan', function (Blueprint $table) {
            $table->unsignedBigInteger('transaksi_id')->after('id');
            $table->unsignedBigInteger('mitra_id')->after('transaksi_id');
            $table->foreign('transaksi_id')->references('id')->on('transaksi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mitra_id')->references('id')->on('mitra')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemasangan', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn('transaksi_id');
            $table->dropForeign(['mitra_id']);
            $table->dropColumn('mitra_id');
        });
    }
};
