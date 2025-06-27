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

        // Create sample menus from previous project
        $menus = [
            // Food Items
            [
                'nama' => 'Ayam Bakar',
                'foto' => null,
                'harga' => 29000,
                'desc' => 'Ayam yang di bakar penuh perasaan',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Ayam Goreng',
                'foto' => null,
                'harga' => 27000,
                'desc' => 'Crispy di luar, enak di dalam',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Ayam Serundeng',
                'foto' => null,
                'harga' => 28000,
                'desc' => 'Ayam ditabur kelapa biar nggak kesepian',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Bebek Goreng',
                'foto' => null,
                'harga' => 32000,
                'desc' => 'Bebek yang digoweng',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Beef Burger',
                'foto' => null,
                'harga' => 35000,
                'desc' => 'Daging tumpuk dalam roti',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Beef Ramen',
                'foto' => null,
                'harga' => 28000,
                'desc' => 'Rameng dikasih beef',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Chicken Burger',
                'foto' => null,
                'harga' => 30000,
                'desc' => 'Pitik dan roti',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Chicken Gimbap',
                'foto' => null,
                'harga' => 25000,
                'desc' => 'nori dan gulungan nasi',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Chicken Rameng',
                'foto' => null,
                'harga' => 25000,
                'desc' => 'Rameng dikasih pitik',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Curry Katsu',
                'foto' => null,
                'harga' => 28000,
                'desc' => 'Nasi kari dan katsu',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Curry Ramen',
                'foto' => null,
                'harga' => 24000,
                'desc' => 'Ramen + kari = mantul',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Fish & Chips',
                'foto' => null,
                'harga' => 25000,
                'desc' => 'Ikan dan kentang, couple goals',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Mentai Rice',
                'foto' => null,
                'harga' => 24000,
                'desc' => 'Nasi yang dimanjain saus mentai',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Mie Goreng',
                'foto' => null,
                'harga' => 20000,
                'desc' => 'Mie yang di gowengg',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Nasgor Seafood',
                'foto' => null,
                'harga' => 29000,
                'desc' => 'Nasi goweng dan lautt',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Nasi Goreng',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Nasi goweng lokal',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Pasta Bolognese',
                'foto' => null,
                'harga' => 26000,
                'desc' => 'Pasta dikasih Bolonyeisss',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Roasted Chicken',
                'foto' => null,
                'harga' => 26000,
                'desc' => 'Pitik yang di roasted',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Salmon Sushi',
                'foto' => null,
                'harga' => 29000,
                'desc' => 'Ikan salmon di nasi',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Spaghetti Bolognese',
                'foto' => null,
                'harga' => 26000,
                'desc' => 'Spaghetti dikasih Bolonyeiss',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Spaghetti Carbonara',
                'foto' => null,
                'harga' => 26000,
                'desc' => 'Spaghetti dikasih saus creamy',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Teriyaki',
                'foto' => null,
                'harga' => 24000,
                'desc' => 'Ayam saos teriyakkki',
                'jenis' => 'food',
            ],
            [
                'nama' => 'Wonton',
                'foto' => null,
                'harga' => 19000,
                'desc' => 'Pangsit pedas basahhh',
                'jenis' => 'food',
            ],

            // Drink Items
            [
                'nama' => 'Caramel Hazelnut',
                'foto' => null,
                'harga' => 23000,
                'desc' => 'Kopi manis, kayak crush',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Choco Milkshake',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Susu coklat yang dikocok dengan cinta',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Hot Americano',
                'foto' => null,
                'harga' => 20000,
                'desc' => 'Kopi panas buat yang hatinya dingin',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Hot Cappucino',
                'foto' => null,
                'harga' => 21000,
                'desc' => 'Ballerina Cappucino Mi Mi Mi',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Hot Choco',
                'foto' => null,
                'harga' => 19000,
                'desc' => 'Coklat panas untuk sayangku',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Hot Espresso',
                'foto' => null,
                'harga' => 20000,
                'desc' => 'Kopi ini ni behhh',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Hot Matcha',
                'foto' => null,
                'harga' => 23000,
                'desc' => 'Hot Matcha cihh',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Hot Redvelvet',
                'foto' => null,
                'harga' => 21000,
                'desc' => 'Warna merah, warna cinta',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Lychee Tea',
                'foto' => null,
                'harga' => 17000,
                'desc' => 'Perpaduan lychee dan tea',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Matcha Espresso',
                'foto' => null,
                'harga' => 25000,
                'desc' => 'Ketika matcha dan kopi nikah siri',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Mojito',
                'foto' => null,
                'harga' => 20000,
                'desc' => 'Minuman segerrrrr',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Redvelvet Milkshake',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Redvelvet yang dikocok',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Strawberry Matcha',
                'foto' => null,
                'harga' => 24000,
                'desc' => 'Matcha yang naksir strawberry',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Thai Tea',
                'foto' => null,
                'harga' => 18000,
                'desc' => 'Teh manis dari Thailand',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Vanilla Latte',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Kopi susu yang manis di awal',
                'jenis' => 'drink',
            ],
            [
                'nama' => 'Vanilla Milkshake',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Vanilla yang dikocok',
                'jenis' => 'drink',
            ],

            // Snack Items
            [
                'nama' => 'Baked Sausage',
                'foto' => null,
                'harga' => 17000,
                'desc' => 'Crispy di luar, kenyal di dalam',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Choco Cromboloni',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Cromboloni dikasih coklat',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Cinnamon Roll',
                'foto' => null,
                'harga' => 18000,
                'desc' => 'Roti beraroma kayu manis',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Cookies',
                'foto' => null,
                'harga' => 16000,
                'desc' => 'Cookies and session',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Croissant',
                'foto' => null,
                'harga' => 18000,
                'desc' => 'Roti lipat-lipat penuh rahasia',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Ebi Fry',
                'foto' => null,
                'harga' => 19000,
                'desc' => 'Udang yang nyemplung ke kolam minyak',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Enoki Crispy',
                'foto' => null,
                'harga' => 18000,
                'desc' => 'Enoki yang digowengg',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Jamur Crispy',
                'foto' => null,
                'harga' => 15000,
                'desc' => 'Jamur yang ngga jamuran',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Matcha Cromboloni',
                'foto' => null,
                'harga' => 22000,
                'desc' => 'Cromboloni Matcha Cihh',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Pudding',
                'foto' => null,
                'harga' => 15000,
                'desc' => 'Kenyal di luar, lumer di dalam',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Waffle Fries',
                'foto' => null,
                'harga' => 17000,
                'desc' => 'Kentang kriuk yang high style',
                'jenis' => 'snack',
            ],
            [
                'nama' => 'Wings Fries',
                'foto' => null,
                'harga' => 27000,
                'desc' => 'Wings dan Fries',
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
