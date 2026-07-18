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
    Schema::create('bbms', function (Blueprint $table) {
        $table->id();
        $table->string('nama_bbm');
        $table->double('jumlah_liter');
        $table->double('km');
        $table->double('harga');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bbms');
    }
};
