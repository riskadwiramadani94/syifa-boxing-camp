<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Konversi data lama: integer → string (supaya tidak hilang)
        DB::table('galeris')
            ->whereNotNull('juara')
            ->update(['juara' => DB::raw("CAST(juara AS TEXT)")]);

        // 2. Ubah tipe kolom juara dari tinyInteger ke string
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('juara')->nullable()->change();
        });

        // 3. Tambah kolom baru
        Schema::table('galeris', function (Blueprint $table) {
            $table->boolean('juara_umum')->default(false)->after('juara');
            $table->boolean('petinju_terbaik')->default(false)->after('juara_umum');
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn(['juara_umum', 'petinju_terbaik']);
            $table->unsignedTinyInteger('juara')->nullable()->change();
        });
    }
};
