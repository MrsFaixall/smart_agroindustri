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
        if (Schema::hasTable('pembelians') && Schema::hasColumn('pembelians', 'pengepul_id')) {
            Schema::table('pembelians', function (Blueprint $table) {
                $table->renameColumn('pengepul_id', 'koperasi_id');
            });
        }

        try {
            // 1. Temporarily allow both pengepul and koperasi in ENUM
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petani', 'pengepul', 'koperasi', 'super admin') NOT NULL DEFAULT 'petani'");
            // 2. Update existing data
            DB::table('users')->where('role', 'pengepul')->update(['role' => 'koperasi']);
            // 3. Finalize ENUM without pengepul
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petani', 'koperasi', 'super admin') NOT NULL DEFAULT 'petani'");
        } catch (\Throwable $e) {
            // Ignore DB statement error if driver doesn't support raw ALTER TABLE
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pembelians') && Schema::hasColumn('pembelians', 'koperasi_id')) {
            Schema::table('pembelians', function (Blueprint $table) {
                $table->renameColumn('koperasi_id', 'pengepul_id');
            });
        }

        try {
            DB::table('users')->where('role', 'koperasi')->update(['role' => 'pengepul']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petani', 'pengepul', 'super admin') NOT NULL DEFAULT 'petani'");
        } catch (\Throwable $e) {
            // Ignore DB statement error
        }
    }
};
