<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Daftar tabel yang perlu ditambah kolom uuid
     */
    private array $tables = [
        'events',
        'galeris',
        'jadwal_latihans',
        'kas',
        'pelatih',
        'transaksis',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            // Tambah kolom uuid jika belum ada
            if (!Schema::hasColumn($table, 'uuid')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->uuid('uuid')->nullable()->after('id');
                });
            }

            // Isi uuid untuk baris yang belum punya
            DB::table($table)
                ->whereNull('uuid')
                ->orderBy('id')
                ->each(function ($row) use ($table) {
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update(['uuid' => Str::uuid()]);
                });

            // Set uuid jadi not null + unique
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->uuid('uuid')->nullable(false)->unique()->change();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasColumn($table, 'uuid')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropColumn('uuid');
                });
            }
        }
    }
};
