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
        // Add new column
        Schema::table('jenis_kentangs', function (Blueprint $table) {
            $table->foreignId('kategori_kentang_id')->nullable()->constrained('kategori_kentangs');
        });

        // Migrate data
        DB::table('jenis_kentangs')->where('kategori', 'benih_hulu')->update(['kategori_kentang_id' => 1]);
        DB::table('jenis_kentangs')->where('kategori', 'kentang_konsumsi')->update(['kategori_kentang_id' => 2]);

        // Drop old column
        Schema::table('jenis_kentangs', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_kentangs', function (Blueprint $table) {
            $table->enum('kategori', ['benih_hulu', 'kentang_konsumsi'])->default('kentang_konsumsi');
        });

        DB::table('jenis_kentangs')->where('kategori_kentang_id', 1)->update(['kategori' => 'benih_hulu']);
        DB::table('jenis_kentangs')->where('kategori_kentang_id', 2)->update(['kategori' => 'kentang_konsumsi']);

        Schema::table('jenis_kentangs', function (Blueprint $table) {
            $table->dropForeign(['kategori_kentang_id']);
            $table->dropColumn('kategori_kentang_id');
        });
    }
};
