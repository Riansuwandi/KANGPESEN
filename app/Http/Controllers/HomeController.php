<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'food');
        $menus = Menu::where('jenis', $kategori)->get();
        
        $pesanan = null;
        if (Auth::check()) {
            $pesanan = Pesanan::with('items.menu')
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->first();
        }

        return view('home', compact('menus', 'kategori', 'pesanan'));
    }

    public function addToOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        
        // Cari atau buat pesanan yang masih pending
        $pesanan = Pesanan::firstOrCreate([
            'user_id' => Auth::id(),
            'status' => 'pending'
        ], [
            'total_harga' => 0
        ]);

        // Cek apakah item sudah ada di pesanan
        $existingItem = PesananItem::where('pesanan_id', $pesanan->id)
            ->where('menu_id', $menu->id)
            ->first();

        if ($existingItem) {
            $existingItem->jumlah += 1;
            $existingItem->subtotal = $existingItem->jumlah * $existingItem->harga_satuan;
            $existingItem->save();
        } else {
            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'menu_id' => $menu->id,
                'jumlah' => 1,
                'harga_satuan' => $menu->harga,
                'subtotal' => $menu->harga,
            ]);
        }

        // Update total harga pesanan
        $pesanan->total_harga = $pesanan->items()->sum('subtotal');
        $pesanan->save();

        return back()->with('success', 'Item berhasil ditambahkan ke pesanan');
    }

    public function showTableStatus()
    {
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengakses halaman ini');
        }

        $mejas = Meja::all();
        return view('table-status', compact('mejas'));
    }
}