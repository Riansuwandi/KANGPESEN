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
            // Get active order (pending or confirmed)
            $pesanan = Pesanan::with(['items.menu', 'meja'])
                ->where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'confirmed'])
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

        // Check if user already has confirmed order
        $confirmedOrder = Pesanan::where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->first();

        if ($confirmedOrder) {
            return back()->with('error', 'Anda sudah memiliki pesanan aktif. Selesaikan pesanan terlebih dahulu atau lihat status pesanan.');
        }

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
                'makanan_datang' => false,
            ]);
        }

        // Update total harga pesanan
        $pesanan->total_harga = $pesanan->items()->sum('subtotal');
        $pesanan->save();

        // Set session untuk tracking
        session(['cart_updated' => true]);

        return back()->with('success', 'Item berhasil ditambahkan ke pesanan');
    }

    public function removeFromOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'item_id' => 'required|exists:pesanan_items,id',
        ]);

        $item = PesananItem::whereHas('pesanan', function($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'pending');
        })->findOrFail($request->item_id);

        $pesanan = $item->pesanan;

        if ($item->jumlah > 1) {
            // Kurangi jumlah
            $item->jumlah -= 1;
            $item->subtotal = $item->jumlah * $item->harga_satuan;
            $item->save();
            $message = 'Jumlah item berhasil dikurangi';
        } else {
            // Hapus item jika jumlah = 1
            $item->delete();
            $message = 'Item berhasil dihapus dari pesanan';
        }

        // Update total harga pesanan
        $pesanan->total_harga = $pesanan->items()->sum('subtotal');
        $pesanan->save();

        // Jika tidak ada item lagi, hapus pesanan
        if ($pesanan->items()->count() == 0) {
            $pesanan->delete();
            return back()->with('success', 'Pesanan kosong telah dihapus');
        }

        return back()->with('success', $message);
    }

    public function clearOrder()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($pesanan) {
            $pesanan->items()->delete();
            $pesanan->delete();
            return back()->with('success', 'Pesanan berhasil dibersihkan');
        }

        return back()->with('error', 'Tidak ada pesanan yang ditemukan');
    }

    public function showTableStatus()
    {
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengakses halaman ini');
        }

        $mejas = Meja::all();

        // Get late orders for staff notification - with safety check
        $lateOrders = collect(); // Empty collection as default

        try {
            $lateOrders = Pesanan::with(['user', 'meja'])
                ->where('status', 'confirmed')
                ->where('makanan_terlambat', true)
                ->get();
        } catch (\Exception $e) {
            // If columns don't exist yet, continue with empty collection
            \Log::warning('Column makanan_terlambat not found: ' . $e->getMessage());
        }

        return view('table-status', compact('mejas', 'lateOrders'));
    }
}
