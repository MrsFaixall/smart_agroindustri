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
        Schema::table('stoks', function (Blueprint $table) {
            $table->decimal('alokasi_pt_camp', 10, 2)->default(0)->after('stok_dijual');
            $table->decimal('alokasi_konsumen', 10, 2)->default(0)->after('alokasi_pt_camp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stoks', function (Blueprint $table) {
            $table->dropColumn('alokasi_pt_camp');
            $table->dropColumn('alokasi_konsumen');
        });
    }
};
