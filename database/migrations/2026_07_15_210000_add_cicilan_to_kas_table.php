<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom jumlah_dibayar dan status_bayar ke tabel kas
        Schema::table('kas', function (Blueprint $table) {
            $table->decimal('jumlah_dibayar', 10, 2)->default(0)->after('jumlah');
            $table->string('status_bayar')->default('belum')->after('jumlah_dibayar'); // belum | cicil | lunas
        });

        // 2. Buat tabel kas_riwayat
        Schema::create('kas_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kas_id')->constrained('kas')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->string('status')->default('cicil'); // cicil | lunas
            $table->string('keterangan')->nullable();
            $table->date('tanggal_bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_riwayat');

        Schema::table('kas', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dibayar', 'status_bayar']);
        });
    }
};
