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
        Schema::table('metode_pembayarans', function (Blueprint $table) {
            $table->string('kategori')->default('Transfer Bank')->after('user_id'); // e.g., 'Transfer Bank', 'QRIS', 'E-Wallet'
            $table->string('qr_image')->nullable()->after('no_rekening');
            // 'bank' column can be used as 'provider' (e.g., 'BCA', 'OVO', 'Gopay')
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metode_pembayarans', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'qr_image']);
        });
    }
};
