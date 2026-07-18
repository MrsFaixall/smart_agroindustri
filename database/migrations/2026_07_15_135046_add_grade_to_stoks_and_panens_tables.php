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
            $table->string('grade', 10)->default('A')->after('jenis_kentang_id');
        });

        Schema::table('panens', function (Blueprint $table) {
            $table->string('grade', 10)->default('A')->after('jenis_kentang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stoks', function (Blueprint $table) {
            $table->dropColumn('grade');
        });

        Schema::table('panens', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
    }
};
