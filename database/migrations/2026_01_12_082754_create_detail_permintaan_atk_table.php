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
        Schema::create('detail_permintaan_atk', function (Blueprint $table) {
            $table->id();

            $table->foreignId('permintaan_id')
                  ->constrained('permintaan_atk')
                  ->onDelete('cascade');

            $table->foreignId('barang_id')
                  ->constrained('barang_atk')
                  ->onDelete('restrict');

            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_permintaan_atk');
    }
};
