<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meja_id',
        'status', // 'pending', 'confirmed', 'completed'
        'total_harga',
    ];

    protected function casts(): array
    {
        return [
            'total_harga' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class);
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }
}