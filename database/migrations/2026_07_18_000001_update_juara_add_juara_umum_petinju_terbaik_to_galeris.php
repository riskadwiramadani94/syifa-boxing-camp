<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1. Ubah tipe kolom juara ke string terlebih dahulu
        //    PostgreSQL butuh USING agar data integer lama otomatis dikonversi ke text.
        //    MySQL cukup pakai ->change().
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE galeris ALTER COLUMN juara TYPE varchar(255) USING juara::varchar');
        } else {
            Schema::table('galeris', function (Blueprint $table) {
                $table->string('juara')->nullable()->change();
            });
        }

        // 2. Tambah kolom baru (skip kalau sudah ada, untuk keamanan re-run)
        Schema::table('galeris', function (Blueprint $table) {
            if (! Schema::hasColumn('galeris', 'juara_umum')) {
                $table->boolean('juara_umum')->default(false)->after('juara');
            }
            if (! Schema::hasColumn('galeris', 'petinju_terbaik')) {
                $table->boolean('petinju_terbaik')->default(false)->after('juara_umum');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            if (Schema::hasColumn('galeris', 'juara_umum')) {
                $table->dropColumn('juara_umum');
            }
            if (Schema::hasColumn('galeris', 'petinju_terbaik')) {
                $table->dropColumn('petinju_terbaik');
            }
        });

        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE galeris ALTER COLUMN juara TYPE smallint USING juara::smallint');
        } else {
            Schema::table('galeris', function (Blueprint $table) {
                $table->unsignedTinyInteger('juara')->nullable()->change();
            });
        }
    }
};
