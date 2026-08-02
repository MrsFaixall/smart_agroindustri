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
        Schema::table('gudangs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
        });

        // Update enum to include mitra
        DB::statement("ALTER TABLE gudangs MODIFY COLUMN jenis_gudang ENUM('petani', 'koperasi', 'mitra') NOT NULL DEFAULT 'petani'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE gudangs MODIFY COLUMN jenis_gudang ENUM('petani', 'koperasi') NOT NULL DEFAULT 'petani'");
        
        Schema::table('gudangs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
