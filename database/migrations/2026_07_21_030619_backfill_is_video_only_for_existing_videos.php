<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Update semua record lama yang punya video file jadi is_video_only = true
     * supaya tetap muncul di halaman Video setelah penambahan kolom is_video_only
     */
    public function up(): void
    {
        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

        DB::table('galeris')
            ->where('is_video_only', false)
            ->where(function ($q) use ($videoExts) {
                foreach ($videoExts as $ext) {
                    $q->orWhereRaw("CAST(foto AS TEXT) LIKE '%." . $ext . "%'");
                }
                $q->orWhereRaw("CAST(foto AS TEXT) LIKE '%youtube%'");
                $q->orWhereRaw("CAST(foto AS TEXT) LIKE '%youtu.be%'");
            })
            ->update(['is_video_only' => true]);
    }

    public function down(): void
    {
        // Tidak perlu rollback data
    }
};
