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
        Schema::table('pengajuan_benihs', function (Blueprint $table) {
            $table->enum('tipe_pengajuan', ['meminta', 'membeli'])->default('meminta')->after('jumlah_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_benihs', function (Blueprint $table) {
            $table->dropColumn('tipe_pengajuan');
        });
    }
};
