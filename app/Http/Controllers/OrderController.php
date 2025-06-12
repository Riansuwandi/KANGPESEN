<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function confirmOrder()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pesanan = Pesanan::with('items.menu')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$pesanan) {
            return redirect('/')->with('error', 'Tidak ada pesanan yang ditemukan');
        }

        return view('confirm-order', compact('pesanan'));
    }

    public function processConfirmOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$pesanan) {
            return redirect('/')->with('error', 'Tidak ada pesanan yang ditemukan');
        }

        // Cari meja kosong
        $meja = Meja::where('status', 'kosong')->first();
        if (!$meja) {
            return back()->with('error', 'Tidak ada meja yang tersedia');
        }

        // Update pesanan
        $pesanan->update([
            'status' => 'confirmed',
            'meja_id' => $meja->id,
        ]);

        // Update status meja
        $meja->update(['status' => 'digunakan']);

        // Buat transaksi
        Transaksi::create([
            'pesanan_id' => $pesanan->id,
            'total_harga' => $pesanan->total_harga,
            'status' => 'completed',
            'tanggal_bayar' => now(),
        ]);

        return redirect('/')->with('success', 'Pesanan berhasil dikonfirmasi. Silakan lakukan pembayaran di kasir.');
    }

    public function finishOrder()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pesanan = Pesanan::with('meja')
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->first();

        if (!$pesanan) {
            return redirect('/')->with('error', 'Tidak ada pesanan yang sedang berlangsung');
        }

        // Update status pesanan
        $pesanan->update(['status' => 'completed']);

        // Update status meja
        if ($pesanan->meja) {
            $pesanan->meja->update(['status' => 'perluDiBersihkan']);
        }

        // Logout otomatis
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('success', 'Pesanan selesai. Terima kasih!');
    }
}