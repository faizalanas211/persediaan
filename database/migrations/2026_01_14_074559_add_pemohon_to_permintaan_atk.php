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
        Schema::table('permintaan_atk', function (Blueprint $table) {
            $table->string('nama_pemohon');
            $table->string('nip_pemohon')->nullable();
            $table->string('bagian_pemohon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_atk', function (Blueprint $table) {
            //
        });
    }
};
