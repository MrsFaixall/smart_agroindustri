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
        Schema::create('penawaran_panens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('koperasi_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_kentang_id')->constrained('jenis_kentangs')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('gudangs')->onDelete('cascade');
            $table->double('jumlah_kg');
            $table->decimal('harga_tawaran_petani', 15, 2);
            $table->decimal('harga_tawaran_koperasi', 15, 2)->nullable();
            $table->string('status')->default('menunggu'); // menunggu, dinegosiasi, disetujui, ditolak
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran_panens');
    }
};
