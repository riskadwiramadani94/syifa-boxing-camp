<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasUuids;

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    protected $table = 'kas';

    protected $fillable = [
        'anggota_id',
        'tahun',
        'bulan',
        'sudah_bayar',
        'jumlah',
        'jumlah_dibayar',
        'status_bayar',
        'keterangan',
    ];

    protected $casts = [
        'sudah_bayar'    => 'boolean',
        'jumlah'         => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'tahun'          => 'integer',
        'bulan'          => 'integer',
    ];

    public function anggota()
    {
        return $this->belongsTo(PendaftaranMember::class, 'anggota_id');
    }

    public function riwayat()
    {
        return $this->hasMany(KasRiwayat::class, 'kas_id')->orderBy('tanggal_bayar');
    }

    /**
     * Hitung ulang jumlah_dibayar dan status_bayar dari riwayat,
     * lalu simpan. Dipanggil setiap kali ada pembayaran baru.
     */
    public function recalculate(): void
    {
        $total = $this->riwayat()->sum('jumlah');

        // Status ditentukan dari entri riwayat terakhir
        $lastStatus = $this->riwayat()->latest('tanggal_bayar')->value('status') ?? 'belum';

        $this->update([
            'jumlah_dibayar' => $total,
            'status_bayar'   => $lastStatus,
            'sudah_bayar'    => $lastStatus === 'lunas',
        ]);
    }

    public static array $namaBulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
}
