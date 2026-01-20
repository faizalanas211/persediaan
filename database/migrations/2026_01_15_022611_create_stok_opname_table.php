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
        Schema::create('stok_opname', function (Blueprint $table) {
            $table->id();

            // Periode opname (pakai tanggal awal bulan)
            $table->date('periode_bulan');

            // Tanggal pelaksanaan opname
            $table->date('tanggal_opname');

            $table->text('keterangan')->nullable();

            // User yang melakukan opname
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // draft | final
            $table->enum('status', ['draft', 'final'])->default('draft');

            $table->timestamps();

            // 1 opname per bulan
            $table->unique('periode_bulan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opname');
    }
};
