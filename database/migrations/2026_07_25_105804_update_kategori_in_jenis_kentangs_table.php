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
        // 1. Expand enum column definition to include both old and new options
        DB::statement("ALTER TABLE jenis_kentangs MODIFY COLUMN kategori ENUM('benih', 'buah_konsumsi', 'benih_hulu', 'kentang_konsumsi') NOT NULL DEFAULT 'buah_konsumsi'");

        // 2. Update existing category values to match new keys
        DB::table('jenis_kentangs')->where('kategori', 'benih')->update(['kategori' => 'benih_hulu']);
        DB::table('jenis_kentangs')->where('kategori', 'buah_konsumsi')->update(['kategori' => 'kentang_konsumsi']);

        // 3. Restrict column enum values to the new keys only
        DB::statement("ALTER TABLE jenis_kentangs MODIFY COLUMN kategori ENUM('benih_hulu', 'kentang_konsumsi') NOT NULL DEFAULT 'kentang_konsumsi'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Revert enum definition to allow all options
        DB::statement("ALTER TABLE jenis_kentangs MODIFY COLUMN kategori ENUM('benih', 'buah_konsumsi', 'benih_hulu', 'kentang_konsumsi') NOT NULL DEFAULT 'buah_konsumsi'");

        // 2. Restore old category values
        DB::table('jenis_kentangs')->where('kategori', 'benih_hulu')->update(['kategori' => 'benih']);
        DB::table('jenis_kentangs')->where('kategori', 'kentang_konsumsi')->update(['kategori' => 'buah_konsumsi']);

        // 3. Restrict enum definition back to the old options only
        DB::statement("ALTER TABLE jenis_kentangs MODIFY COLUMN kategori ENUM('benih', 'buah_konsumsi') NOT NULL DEFAULT 'buah_konsumsi'");
    }
};
