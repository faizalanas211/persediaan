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

            // drop foreign key dulu
            $table->dropForeign(['pegawai_id']);

            // baru drop kolomnya
            $table->dropColumn('pegawai_id');
        });
    }

    public function down(): void
    {
        Schema::table('permintaan_atk', function (Blueprint $table) {

            // restore kolom
            $table->foreignId('pegawai_id')
                  ->after('id')
                  ->constrained('pegawai')
                  ->onDelete('restrict');
        });
    }
};
