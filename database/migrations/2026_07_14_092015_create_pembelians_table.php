<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('pembelians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('petani_id')->constrained('users');
        $table->foreignId('pengepul_id')->constrained('users');
        $table->decimal('total_harga', 15, 2);
        $table->string('status'); // Contoh: 'lunas', 'belum lunas'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};
