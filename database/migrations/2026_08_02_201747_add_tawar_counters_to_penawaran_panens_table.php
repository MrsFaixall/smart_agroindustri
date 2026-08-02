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
        Schema::table('penawaran_panens', function (Blueprint $table) {
            $table->integer('jumlah_tawar_petani')->default(0)->after('harga_tawaran_koperasi');
            $table->integer('jumlah_tawar_koperasi')->default(0)->after('jumlah_tawar_petani');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran_panens', function (Blueprint $table) {
            $table->dropColumn(['jumlah_tawar_petani', 'jumlah_tawar_koperasi']);
        });
    }
};
