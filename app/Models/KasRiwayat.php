<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasRiwayat extends Model
{
    protected $table = 'kas_riwayat';

    protected $fillable = [
        'kas_id',
        'jumlah',
        'status',
        'keterangan',
        'tanggal_bayar',
    ];

    protected $casts = [
        'jumlah'        => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'kas_id');
    }
}
