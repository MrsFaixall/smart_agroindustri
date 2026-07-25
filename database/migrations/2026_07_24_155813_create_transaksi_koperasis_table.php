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
        Schema::create('transaksi_koperasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('koperasi_id');
            // pihak_kedua bisa mitra (PT Champ), konsumen, atau petani
            $table->unsignedBigInteger('pihak_kedua_id'); 
            $table->unsignedBigInteger('jenis_kentang_id');
            
            $table->enum('tipe_transaksi', [
                'pengadaan_benih', // PT Champ -> Koperasi
                'distribusi_benih', // Koperasi -> Petani
                'penjualan_buah'    // Koperasi -> PT Champ / Konsumen
            ]);
            
            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('total_harga', 15, 2);
            $table->date('tanggal_transaksi');
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            
            $table->timestamps();

            $table->foreign('koperasi_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pihak_kedua_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jenis_kentang_id')->references('id')->on('jenis_kentangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_koperasis');
    }
};
