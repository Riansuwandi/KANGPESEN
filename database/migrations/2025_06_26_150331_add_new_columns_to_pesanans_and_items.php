<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update pesanans table
        if (!Schema::hasColumn('pesanans', 'waktu_konfirmasi')) {
            Schema::table('pesanans', function (Blueprint $table) {
                $table->timestamp('waktu_konfirmasi')->nullable()->after('total_harga');
                $table->timestamp('waktu_selesai')->nullable()->after('waktu_konfirmasi');
                $table->boolean('kompensasi_pudding')->default(false)->after('waktu_selesai');
                $table->boolean('makanan_terlambat')->default(false)->after('kompensasi_pudding');
            });
        }

        // Update pesanan_items table
        if (!Schema::hasColumn('pesanan_items', 'makanan_datang')) {
            Schema::table('pesanan_items', function (Blueprint $table) {
                $table->boolean('makanan_datang')->default(false)->after('subtotal');
            });
        }
    }

    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            if (Schema::hasColumn('pesanans', 'waktu_konfirmasi')) {
                $table->dropColumn(['waktu_konfirmasi', 'waktu_selesai', 'kompensasi_pudding', 'makanan_terlambat']);
            }
        });

        Schema::table('pesanan_items', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan_items', 'makanan_datang')) {
                $table->dropColumn('makanan_datang');
            }
        });
    }
};
