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
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('pendaftaran_members')->onDelete('cascade');
            $table->year('tahun');
            $table->tinyInteger('bulan'); // 1-12
            $table->boolean('sudah_bayar')->default(false);
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['anggota_id', 'tahun', 'bulan']); // 1 record per anggota per bulan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas');
    }
};
