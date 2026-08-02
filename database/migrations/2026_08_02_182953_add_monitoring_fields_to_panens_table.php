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
        Schema::table('panens', function (Blueprint $table) {
            $table->foreignId('penanaman_id')->nullable()->constrained('penanaman_benihs')->onDelete('set null')->after('gudang_id');
            $table->double('jumlah_busuk_kg')->nullable()->default(0)->after('jumlah_kg');
            $table->double('jumlah_gagal_kg')->nullable()->default(0)->after('jumlah_busuk_kg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panens', function (Blueprint $table) {
            $table->dropForeign(['penanaman_id']);
            $table->dropColumn(['penanaman_id', 'jumlah_busuk_kg', 'jumlah_gagal_kg']);
        });
    }
};
