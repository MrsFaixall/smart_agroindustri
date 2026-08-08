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
        Schema::table('penjualan_buahs', function (Blueprint $table) {
            $table->string('tracking_token')->nullable()->unique()->after('status');
            $table->string('grade')->nullable()->after('jenis_kentang_id');
            $table->text('routing_path')->nullable()->after('tracking_token');
            $table->string('estimasi_waktu')->nullable()->after('routing_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_buahs', function (Blueprint $table) {
            $table->dropColumn(['tracking_token', 'grade', 'routing_path', 'estimasi_waktu']);
        });
    }
};
