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
        Schema::dropIfExists('transaksi_koperasis');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-creation of generic table is not needed as we separated it permanently
    }
};
