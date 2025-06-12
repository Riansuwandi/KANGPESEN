<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'foto',
        'harga',
        'desc',
        'jenis', // 'food', 'drink', 'snack'
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
        ];
    }

    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class);
    }
}