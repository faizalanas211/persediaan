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
        Schema::table('stok_opname_detail', function (Blueprint $table) {
            $table->integer('total_masuk')->default(0)->after('stok_sistem');
            $table->integer('total_keluar')->default(0)->after('total_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok_opname_detail', function (Blueprint $table) {
            //
        });
    }
};
