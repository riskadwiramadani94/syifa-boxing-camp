<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasUuids;

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    protected $fillable = [
        'judul',
        'slug',
        'tanggal',
        'lokasi',
        'maps_link',
        'status',
        'foto',
        'deskripsi',
        'is_active',
        'harga_tiket',
        'promo_tiket',
        'harga_atlet',
        'include_atlet',
        'wa_pendaftaran',
    ];

    protected $casts = [
        'tanggal'        => 'date',
        'is_active'      => 'boolean',
        'wa_pendaftaran' => 'array',
    ];

    // Auto-generate slug dari judul
    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->judul);
            }
        });
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'selesai' => 'Telah Selesai',
            'dibuka'  => 'Pendaftaran Dibuka',
            'segera'  => 'Segera Hadir',
            default   => '-',
        };
    }

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }
}
