<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gudangs', function (Blueprint $table) {
            $table->double('kapasitas_max')->default(50000)->after('longitude');
            $table->string('status')->default('Aktif')->after('kapasitas_max');
        });
    }

    public function down(): void
    {
        Schema::table('gudangs', function (Blueprint $table) {
            $table->dropColumn(['kapasitas_max', 'status']);
        });
    }
};
