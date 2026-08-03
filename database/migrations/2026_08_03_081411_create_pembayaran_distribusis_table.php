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
        Schema::create('pembayaran_distribusis', function (Blueprint $table) {
                        $table->id();
            $table->foreignId('distribusi_benih_id')->constrained('distribusi_benihs')->cascadeOnDelete();
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayarans')->nullOnDelete();
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_pembayaran');
            $table->string('status')->default('pending');
            $table->text('catatan')->nullable();
            
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('pdf_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_distribusis');
    }
};
