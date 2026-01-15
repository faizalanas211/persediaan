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

            // hapus kolom lama
            $table->dropColumn([
                'nama_pemohon',
                'nip_pemohon',
                'bagian_pemohon',
            ]);
        });

        Schema::table('permintaan_atk', function (Blueprint $table) {

            // tambah ulang setelah id
            $table->string('nama_pemohon')->after('id');
            $table->string('nip_pemohon')->nullable()->after('nama_pemohon');
            $table->string('bagian_pemohon')->nullable()->after('nip_pemohon');
        });
    }

    public function down(): void
    {
        Schema::table('permintaan_atk', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pemohon',
                'nip_pemohon',
                'bagian_pemohon',
            ]);
        });
    }
};
