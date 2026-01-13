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
        Schema::create('permintaan_atk', function (Blueprint $table) {
            $table->id();

            // Pegawai yang meminta
            $table->foreignId('pegawai_id')
                ->constrained('pegawai')
                ->onDelete('restrict');

            $table->date('tanggal_permintaan');
            $table->string('keperluan');
            $table->text('keterangan')->nullable();

            // Admin yang mencatat
            $table->foreignId('dicatat_oleh')
                  ->constrained('users')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_atk');
    }
};
