<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed nilai default
        $defaults = [
            ['key' => 'nama_sasana',    'value' => 'Syifa Boxing Camp'],
            ['key' => 'tagline',        'value' => 'Sasana Tinju Profesional'],
            ['key' => 'deskripsi',      'value' => 'Syifa Boxing Camp merupakan sebuah sasana tinju yang berlokasi di GOR Padjadjaran, Kota Bandung, Jawa Barat.'],
            ['key' => 'tahun_berdiri',  'value' => '1998'],
            ['key' => 'whatsapp',       'value' => '6281234567890'],
            ['key' => 'email',          'value' => 'info@syifaboxingcamp.com'],
            ['key' => 'alamat',         'value' => 'GOR Padjadjaran, Kota Bandung, Jawa Barat'],
            ['key' => 'maps_embed',     'value' => ''],
            ['key' => 'instagram',      'value' => ''],
            ['key' => 'facebook',       'value' => ''],
        ];

        foreach ($defaults as $item) {
            DB::table('site_settings')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
