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
        Schema::table('stoks', function (Blueprint $table) {
            $table->decimal('stok_dijual', 12, 2)->default(0)->after('jumlah_stok');
        });
    }

    public function down(): void
    {
        Schema::table('stoks', function (Blueprint $table) {
            $table->dropColumn('stok_dijual');
        });
    }
};
