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
        Schema::create('stok_opname_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stok_opname_id')
                  ->constrained('stok_opname')
                  ->cascadeOnDelete();

            $table->foreignId('barang_id')
                  ->constrained('barang_atk')
                  ->restrictOnDelete();

            // Data sistem
            $table->integer('stok_sistem')->default(0);

            // Input fisik gudang
            $table->integer('stok_fisik')->nullable();

            // stok_fisik - stok_sistem
            $table->integer('selisih')->nullable();

            $table->string('keterangan')->nullable();

            $table->timestamps();

            // 1 barang hanya sekali per opname
            $table->unique(['stok_opname_id', 'barang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opname_detail');
    }
};
