<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meja_id',
        'status', // 'pending', 'confirmed', 'completed'
        'total_harga',
        'waktu_konfirmasi',
        'waktu_selesai',
        'kompensasi_pudding',
        'makanan_terlambat',
    ];

    protected function casts(): array
    {
        return [
            'total_harga' => 'decimal:2',
            'waktu_konfirmasi' => 'datetime',
            'waktu_selesai' => 'datetime',
            'kompensasi_pudding' => 'boolean',
            'makanan_terlambat' => 'boolean',
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

    // Check if order is late (more than 20 minutes)
    public function isLate()
    {
        if (!$this->waktu_konfirmasi) {
            return false;
        }

        try {
            return $this->waktu_konfirmasi->addMinutes(20)->isPast() && !$this->waktu_selesai;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Get remaining time before order is considered late
    public function getRemainingTime()
    {
        if (!$this->waktu_konfirmasi) {
            return null;
        }

        try {
            $deadline = $this->waktu_konfirmasi->addMinutes(20);
            $now = Carbon::now();

            if ($deadline->isPast()) {
                return 0;
            }

            return $deadline->diffInMinutes($now);
        } catch (\Exception $e) {
            return null;
        }
    }
}
