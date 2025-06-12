<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_meja',
        'status', // 'kosong', 'digunakan', 'perluDiBersihkan'
    ];

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}