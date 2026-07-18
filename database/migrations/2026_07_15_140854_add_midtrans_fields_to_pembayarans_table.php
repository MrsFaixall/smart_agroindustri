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
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('midtrans_order_id')->nullable()->unique()->after('snap_token');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('payment_type', 50)->nullable()->after('midtrans_transaction_id');
            $table->text('pdf_url')->nullable()->after('payment_type');

            // make metode_pembayaran_id nullable for Midtrans payments
            $table->unsignedBigInteger('metode_pembayaran_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'payment_type',
                'pdf_url'
            ]);
            $table->unsignedBigInteger('metode_pembayaran_id')->nullable(false)->change();
        });
    }
};
