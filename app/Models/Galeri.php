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
        'foto',
        'tahun',
        'keterangan',
        'event_id',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'juara' => 'integer',
        'foto'  => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
