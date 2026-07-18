<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stoks', function (Blueprint $table) {
            $table->foreignId('panen_id')
                ->nullable()
                ->after('jenis_kentang_id')
                ->constrained()
                ->nullOnDelete();
            $table->unique('panen_id');
        });

        // Panen lama belum pernah membuat stok. Buat satu stok per panen agar
        // jumlah pada gudang dan dashboard langsung sesuai data sebelumnya.
        DB::table('panens')->orderBy('id')->each(function (object $panen) {
            DB::table('stoks')->insert([
                'gudang_id' => $panen->gudang_id,
                'jenis_kentang_id' => $panen->jenis_kentang_id,
                'panen_id' => $panen->id,
                'jumlah_stok' => $panen->jumlah_kg,
                'created_at' => $panen->created_at,
                'updated_at' => $panen->updated_at,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('stoks', function (Blueprint $table) {
            $table->dropForeign(['panen_id']);
            $table->dropUnique(['panen_id']);
            $table->dropColumn('panen_id');
        });
    }
};
