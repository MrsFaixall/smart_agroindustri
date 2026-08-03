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
        Schema::create('kategori_kentangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->enum('tipe_komoditas', ['benih', 'konsumsi'])->default('konsumsi');
            $table->timestamps();
        });

        // Insert default categories
        DB::table('kategori_kentangs')->insert([
            ['id' => 1, 'nama_kategori' => 'Benih Hulu', 'tipe_komoditas' => 'benih', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_kategori' => 'Kentang Konsumsi', 'tipe_komoditas' => 'konsumsi', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_kentangs');
    }
};
