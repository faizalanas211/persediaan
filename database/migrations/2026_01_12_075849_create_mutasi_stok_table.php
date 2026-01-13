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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')
                  ->constrained('barang_atk')
                  ->onDelete('cascade');

            $table->enum('jenis_mutasi', ['masuk', 'keluar', 'penyesuaian']);
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->text('keterangan')->nullable();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
