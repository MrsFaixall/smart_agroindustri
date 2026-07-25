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
        Schema::create('penjualan_buahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('koperasi_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pembeli_id')->constrained('users')->onDelete('cascade'); // mitra or konsumen
            $table->foreignId('jenis_kentang_id')->constrained('jenis_kentangs')->onDelete('cascade');
            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('total_harga', 15, 2);
            $table->date('tanggal_transaksi');
            $table->enum('status', ['lunas', 'belum lunas'])->default('belum lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_buahs');
    }
};
