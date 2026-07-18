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
        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreignId('jenis_kentang_id')->nullable()->after('pengepul_id')->constrained('jenis_kentangs')->nullOnDelete();
            $table->double('jumlah_kg')->default(0)->after('jenis_kentang_id');
            $table->date('tanggal_pembelian')->nullable()->after('jumlah_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['jenis_kentang_id']);
            $table->dropColumn(['jenis_kentang_id', 'jumlah_kg', 'tanggal_pembelian']);
        });
    }
};
