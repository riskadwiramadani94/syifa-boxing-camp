<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->string('tipe_konten', 20)
                ->default('foto')
                ->after('is_video_only')
                ->comment('foto | video | foto_video');
        });

        // Backfill data lama berdasarkan kondisi yang ada
        $videoExts = ['mp4', 'mov', 'avi', 'webm', 'mkv', 'wmv', 'flv'];

        $galeris = DB::table('galeris')->get();

        foreach ($galeris as $galeri) {
            $foto = json_decode($galeri->foto ?? '[]', true);
            if (!is_array($foto)) $foto = [];

            $hasVideo = false;
            $hasFoto  = false;

            foreach ($foto as $file) {
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($ext, $videoExts)) {
                    $hasVideo = true;
                } elseif (
                    str_contains($file, 'youtube') ||
                    str_contains($file, 'youtu.be') ||
                    str_contains($file, 'instagram')
                ) {
                    $hasVideo = true;
                } else {
                    $hasFoto = true;
                }
            }

            if ($hasVideo && $hasFoto) {
                $tipe = 'foto_video';
            } elseif ($hasVideo) {
                $tipe = 'video';
            } else {
                // foto saja, atau kosong
                $tipe = 'foto';
            }

            DB::table('galeris')
                ->where('id', $galeri->id)
                ->update(['tipe_konten' => $tipe]);
        }
    }

    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            $table->dropColumn('tipe_konten');
        });
    }
};
