<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->timestamp('waktu_konfirmasi')->nullable()->after('total_harga');
            $table->timestamp('waktu_selesai')->nullable()->after('waktu_konfirmasi');
            $table->boolean('kompensasi_pudding')->default(false)->after('waktu_selesai');
            $table->boolean('makanan_terlambat')->default(false)->after('kompensasi_pudding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['waktu_konfirmasi', 'waktu_selesai', 'kompensasi_pudding', 'makanan_terlambat']);
        });
    }
};
