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
        Schema::create('penanaman_benihs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('gudangs')->onDelete('cascade');
            $table->foreignId('jenis_kentang_id')->constrained('jenis_kentangs')->onDelete('cascade');
            $table->double('jumlah_tanam_kg');
            $table->date('tanggal_tanam');
            $table->date('estimasi_panen');
            $table->enum('status', ['aktif', 'selesai', 'gagal'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanaman_benihs');
    }
};
