<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE kategori_kentangs MODIFY COLUMN tipe_komoditas ENUM('benih', 'konsumsi', 'olahan') NOT NULL DEFAULT 'konsumsi'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Careful when rolling back, as 'olahan' might be in use.
        // We'll just reset back to two options.
        DB::table('kategori_kentangs')->where('tipe_komoditas', 'olahan')->update(['tipe_komoditas' => 'konsumsi']);
        DB::statement("ALTER TABLE kategori_kentangs MODIFY COLUMN tipe_komoditas ENUM('benih', 'konsumsi') NOT NULL DEFAULT 'konsumsi'");
    }
};
