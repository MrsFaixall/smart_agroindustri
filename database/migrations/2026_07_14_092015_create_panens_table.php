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
    Schema::create('panens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jenis_kentang_id')->constrained();
        $table->foreignId('gudang_id')->constrained();
        $table->double('jumlah_kg');
        $table->date('tanggal_panen');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panens');
    }
};
