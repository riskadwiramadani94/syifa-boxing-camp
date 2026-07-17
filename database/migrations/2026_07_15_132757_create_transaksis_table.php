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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('jenis'); // 'Pemasukan' atau 'Pengeluaran'
            $table->string('tipe_pemasukan')->nullable(); // 'Uang' atau 'Barang'
            $table->string('nama_pemberi')->nullable();
            $table->string('keperluan')->nullable();
            $table->decimal('nominal', 15, 2)->default(0)->nullable();
            $table->string('nama_barang')->nullable();
            $table->integer('jumlah_barang')->nullable();
            $table->string('bukti_foto')->nullable();
            $table->text('keterangan_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
