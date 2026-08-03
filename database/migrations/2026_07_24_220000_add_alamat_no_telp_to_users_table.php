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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_telp')) {
                $table->string('no_telp')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_telp');
            }
        });

        // Expand ENUM role to include 'konsumen'
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'petani', 'koperasi', 'super admin', 'konsumen', 'mitra') NOT NULL DEFAULT 'petani'");
            DB::statement("UPDATE users SET role = 'konsumen' WHERE role = 'staf'");
        } catch (\Exception $e) {
            // Fallback for sqlite if used in testing
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'no_telp')) {
                $table->dropColumn('no_telp');
            }
            if (Schema::hasColumn('users', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
