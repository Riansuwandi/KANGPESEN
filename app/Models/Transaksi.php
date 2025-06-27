<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'total_harga',
        'status',
        'tanggal_bayar',
    ];

    protected function casts(): array
    {
        return [
            'total_harga' => 'decimal:2',
            'tanggal_bayar' => 'datetime',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
