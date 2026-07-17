<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Pelatih extends Model
{
    use HasUuids;

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    protected $table = 'pelatih';

    protected $fillable = [
        'nama_lengkap',
        'foto',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'foto'   => 'array',
        'urutan' => 'integer',
        'aktif'  => 'boolean',
    ];
}
