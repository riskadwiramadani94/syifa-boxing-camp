<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JadwalLatihan extends Model
{
    use HasUuids;

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Urutan hari untuk sorting
    public static array $urutan_hari = [
        'Senin'  => 1,
        'Selasa' => 2,
        'Rabu'   => 3,
        'Kamis'  => 4,
        'Jumat'  => 5,
        'Sabtu'  => 6,
        'Minggu' => 7,
    ];
}
