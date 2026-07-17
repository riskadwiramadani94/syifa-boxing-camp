<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Tambah kolom baru setelah slug
            $table->date('tanggal_mulai')->nullable()->after('slug');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });

        // Migrasi data lama: isi tanggal_mulai & tanggal_selesai dari kolom tanggal
        DB::table('events')->whereNotNull('tanggal')->get()->each(function ($event) {
            DB::table('events')->where('id', $event->id)->update([
                'tanggal_mulai'   => $event->tanggal,
                'tanggal_selesai' => $event->tanggal,
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            // Hapus kolom tanggal lama
            $table->dropColumn('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('slug');
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
