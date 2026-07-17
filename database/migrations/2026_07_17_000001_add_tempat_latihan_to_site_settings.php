<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['key' => 'nama_tempat_latihan', 'value' => 'GOR Padjadjaran, Kota Bandung, Jawa Barat'],
            ['key' => 'maps_url',            'value' => ''],
        ];

        foreach ($defaults as $item) {
            DB::table('site_settings')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', ['nama_tempat_latihan', 'maps_url'])->delete();
    }
};
