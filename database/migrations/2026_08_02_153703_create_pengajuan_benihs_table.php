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
        Schema::create('pengajuan_benihs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('petani_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('koperasi_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_kentang_id')->constrained('jenis_kentangs')->onDelete('cascade');
            $table->decimal('jumlah_kg', 10, 2);
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->date('tanggal_pengajuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_benihs');
    }
};
