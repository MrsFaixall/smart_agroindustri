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
        // 1. Add 'mitra' to users role enum using raw statement
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super admin', 'admin', 'konsumen', 'koperasi', 'petani', 'mitra') NOT NULL DEFAULT 'petani'");

        // 2. Add 'kategori' to jenis_kentangs
        Schema::table('jenis_kentangs', function (Blueprint $table) {
            if (!Schema::hasColumn('jenis_kentangs', 'kategori')) {
                $table->enum('kategori', ['benih', 'buah_konsumsi'])->default('buah_konsumsi')->after('nama_jenis');
            }
        });

        // 3. Add 'jenis_gudang' to gudangs
        Schema::table('gudangs', function (Blueprint $table) {
            if (!Schema::hasColumn('gudangs', 'jenis_gudang')) {
                $table->enum('jenis_gudang', ['petani', 'koperasi'])->default('petani')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudangs', function (Blueprint $table) {
            if (Schema::hasColumn('gudangs', 'jenis_gudang')) {
                $table->dropColumn('jenis_gudang');
            }
        });

        Schema::table('jenis_kentangs', function (Blueprint $table) {
            if (Schema::hasColumn('jenis_kentangs', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });

        // Reverting enum without 'mitra'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super admin', 'admin', 'konsumen', 'koperasi', 'petani') NOT NULL DEFAULT 'petani'");
    }
};
