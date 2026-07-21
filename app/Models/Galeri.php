<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    use HasUuids;

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    protected $table = 'galeris';

    protected $fillable = [
        'judul',
        'kategori',
        'juara',
        'juara_umum',
        'petinju_terbaik',
        'daftar_juara',
        'foto',
        'tahun',
        'keterangan',
        'event_id',
        'is_video_only',
    ];

    protected $casts = [
        'tahun'           => 'integer',
        'juara'           => 'string',
        'juara_umum'      => 'boolean',
        'petinju_terbaik' => 'boolean',
        'foto'            => 'array',
        'daftar_juara'    => 'array',
        'is_video_only'   => 'boolean',
    ];

    /**
     * Kembalikan array angka juara dari string "1,1,3,3" → [1,1,3,3]
     */
    public function juaraArray(): array
    {
        if (! $this->juara) return [];
        return array_values(array_filter(
            array_map('intval', array_map('trim', explode(',', $this->juara))),
            fn ($v) => $v > 0
        ));
    }

    /**
     * Jumlah medali = jumlah angka di field juara
     */
    public function jumlahMedali(): int
    {
        return count($this->juaraArray());
    }

    /**
     * Jumlah prestasi = medali + juara_umum (1) + petinju_terbaik (1)
     */
    public function jumlahPrestasi(): int
    {
        return $this->jumlahMedali()
            + ($this->juara_umum      ? 1 : 0)
            + ($this->petinju_terbaik ? 1 : 0);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
