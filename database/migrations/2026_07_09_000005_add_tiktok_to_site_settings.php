<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah TikTok jika belum ada
        if (!DB::table('site_settings')->where('key', 'tiktok')->exists()) {
            DB::table('site_settings')->insert([
                'key'        => 'tiktok',
                'value'      => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->where('key', 'tiktok')->delete();
    }
};
