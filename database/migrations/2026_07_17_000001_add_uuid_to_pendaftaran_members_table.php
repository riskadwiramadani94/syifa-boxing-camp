<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom uuid jika belum ada
        if (!Schema::hasColumn('pendaftaran_members', 'uuid')) {
            Schema::table('pendaftaran_members', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Isi uuid untuk data yang sudah ada tapi belum punya uuid
        DB::table('pendaftaran_members')
            ->whereNull('uuid')
            ->orderBy('id')
            ->each(function ($row) {
                DB::table('pendaftaran_members')
                    ->where('id', $row->id)
                    ->update(['uuid' => Str::uuid()]);
            });

        // Set kolom uuid jadi unique dan not null
        Schema::table('pendaftaran_members', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran_members', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
