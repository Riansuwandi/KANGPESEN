<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Menu;
use App\Models\Meja;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default users
        User::create([
            'username' => 'staff',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'username' => 'tamu',
            'password' => Hash::make('password'),
            'role' => 'tamu',
        ]);

        // Create sample menus
        $menus = [
            // Food
            [
                'nama' => 'Nasi Kucing',
                'foto' => null,
                'harga' => 5000,
                'desc' => 'Nasi kucing cah kang cing cung cong',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Nasi Gudeg',
                'foto' => null,
                'harga' => 15000,
                'desc' => 'Nasi gudeg spesial Yogyakarta',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Ayam Geprek',
                'foto' => null,
                'harga' => 12000,
                'desc' => 'Ayam geprek pedas mantap',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Soto Ayam',
                'foto' => null,
                'harga' => 10000,
                'desc' => 'Soto ayam kuah bening',
                'jenis' => 'food',
            ],
            
            // Drinks
            [
                'nama' => 'Es Teh Manis',
                'foto' => null,
                'harga' => 3000,
                'desc' => 'Es teh manis segar',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Jus Jeruk',
                'foto' => null,
                'harga' => 8000,
                'desc' => 'Jus jeruk segar tanpa gula',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Kopi Tubruk',
                'foto' => null,
                'harga' => 5000,
                'desc' => 'Kopi tubruk tradisional',
                'jenis' => 'drink',
            ],
            
            // Snacks
            [
                'nama' => 'Keripik Singkong',
                'foto' => null,
                'harga' => 7000,
                'desc' => 'Keripik singkong renyah',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Pisang Goreng',
                'foto' => null,
                'harga' => 6000,
                'desc' => 'Pisang goreng krispy',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Tempe Mendoan',
                'foto' => null,
                'harga' => 8000,
                'desc' => 'Tempe mendoan khas Banyumas',
                'jenis' => 'snack',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        // Create sample tables
        for ($i = 1; $i <= 10; $i++) {
            Meja::create([
                'nomor_meja' => 'Meja ' . $i,
                'status' => 'kosong',
            ]);
        }
    }
}