<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!DB::table('site_settings')->where('key', 'foto_beranda')->exists()) {
            DB::table('site_settings')->insert([
                'key'        => 'foto_beranda',
                'value'      => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'foto_beranda')->delete();
    }
};
