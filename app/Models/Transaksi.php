<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasUuids;

    public function uniqueIds(): array { return ['uuid']; }
    public function getRouteKeyName(): string { return 'uuid'; }
    protected $fillable = [
        'tanggal',
        'jenis',
        'tipe_pemasukan',
        'nama_pemberi',
        'keperluan',
        'nominal',
        'nama_barang',
        'jumlah_barang',
        'bukti_foto',
        'keterangan_tambahan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'jumlah_barang' => 'integer',
    ];
}
