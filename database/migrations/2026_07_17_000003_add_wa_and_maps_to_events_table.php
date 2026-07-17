<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('wa_pendaftaran')->nullable()->after('include_atlet')
                  ->comment('Nomor WA pendaftaran event, array max 3');
            $table->string('maps_link')->nullable()->after('wa_pendaftaran')
                  ->comment('Link Google Maps lokasi event');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['wa_pendaftaran', 'maps_link']);
        });
    }
};
