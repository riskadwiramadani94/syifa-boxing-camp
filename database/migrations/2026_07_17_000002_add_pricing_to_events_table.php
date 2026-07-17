<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('harga_tiket')->nullable()->after('deskripsi')
                  ->comment('Harga tiket penonton dalam rupiah');
            $table->string('promo_tiket')->nullable()->after('harga_tiket')
                  ->comment('Keterangan promo tiket, contoh: Beli 2 Gratis 1');
            $table->unsignedInteger('harga_atlet')->nullable()->after('promo_tiket')
                  ->comment('Biaya pendaftaran atlet dalam rupiah');
            $table->string('include_atlet')->nullable()->after('harga_atlet')
                  ->comment('Yang didapat atlet, pisahkan dengan koma');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['harga_tiket', 'promo_tiket', 'harga_atlet', 'include_atlet']);
        });
    }
};
