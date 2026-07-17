<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PendaftaranMember extends Model
{
    use HasUuids;

    protected $table = 'pendaftaran_members';

    /**
     * Kolom yang dipakai sebagai UUID (bukan primary key).
     */
    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    /**
     * Pakai uuid sebagai route key agar URL = /uuid/edit
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $fillable = [
        'nama_lengkap',
        'no_telepon',
        'email',
        'kelas',
        'berat_badan',
        'tanggal_lahir',
        'jenis_kelamin',
        'bukti_pembayaran',
        'status',
        'catatan_admin',
        'aktif',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_badan'   => 'integer',
        'aktif'         => 'boolean',
    ];

    public function kas()
    {
        return $this->hasMany(Kas::class, 'anggota_id');
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'Menunggu Verifikasi',
            'terverifikasi' => 'Terverifikasi',
            'ditolak'      => 'Ditolak',
            default        => '-',
        };
    }
}
