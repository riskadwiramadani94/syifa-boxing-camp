<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['key' => 'hero_badge',       'value' => 'Pusat Latihan Tinju Terbaik Sejak 1998'],
            ['key' => 'hero_judul',       'value' => 'Melatih Atlet, Mencetak Juara, Harum Bangsa!'],
            ['key' => 'hero_desc',        'value' => 'Syifa Boxing Camp hadir untuk membina dan mengembangkan atlet tinju menuju prestasi terbaik. Bersama kami, raih potensimu!'],
            ['key' => 'stat_member',      'value' => '500+'],
            ['key' => 'stat_tahun',       'value' => '25+'],
            ['key' => 'stat_prestasi',    'value' => '50+'],
            ['key' => 'stat_medali',      'value' => '50+'],
        ];

        foreach ($defaults as $item) {
            if (!DB::table('site_settings')->where('key', $item['key'])->exists()) {
                DB::table('site_settings')->insert(array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        $keys = ['hero_badge', 'hero_judul', 'hero_desc', 'stat_member', 'stat_tahun', 'stat_prestasi', 'stat_medali'];
        DB::table('site_settings')->whereIn('key', $keys)->delete();
    }
};
