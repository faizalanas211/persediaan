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
        Schema::table('mutasi_stok', function (Blueprint $table) {
            $table->integer('stok_awal')->after('jumlah');
            $table->integer('stok_akhir')->after('stok_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_stok', function (Blueprint $table) {
            //
        });
    }
};
