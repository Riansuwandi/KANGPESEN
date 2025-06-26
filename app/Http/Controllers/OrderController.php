<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Transaksi;
use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        // Get available tables
        $availableTables = Meja::where('status', 'kosong')->get();

        return view('confirm-order', compact('pesanan', 'availableTables'));
    }

    public function processConfirmOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'meja_id' => 'required|exists:mejas,id',
        ]);

        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$pesanan) {
            return redirect('/')->with('error', 'Tidak ada pesanan yang ditemukan');
        }

        // Check if selected table is still available
        $meja = Meja::where('id', $request->meja_id)
            ->where('status', 'kosong')
            ->first();

        if (!$meja) {
            return back()->with('error', 'Meja yang dipilih sudah tidak tersedia');
        }

        // Prepare update data
        $updateData = [
            'status' => 'confirmed',
            'meja_id' => $meja->id,
        ];

        // Add waktu_konfirmasi only if column exists
        try {
            $updateData['waktu_konfirmasi'] = Carbon::now();
        } catch (\Exception $e) {
            // Column doesn't exist yet, continue without it
        }

        // Update pesanan
        $pesanan->update($updateData);

        // Update status meja
        $meja->update(['status' => 'digunakan']);

        // Buat transaksi
        Transaksi::create([
            'pesanan_id' => $pesanan->id,
            'total_harga' => $pesanan->total_harga,
            'status' => 'completed',
            'tanggal_bayar' => now(),
        ]);

        // Set session for order tracking
        session(['current_order_id' => $pesanan->id]);

        return redirect('/order-status')->with('success', 'Pesanan berhasil dikonfirmasi. Meja ' . $meja->nomor_meja . ' telah dipesan untuk Anda.');
    }

    public function orderStatus()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $pesanan = Pesanan::with(['items.menu', 'meja'])
            ->where('user_id', Auth::id())
            ->where('status', 'confirmed')
            ->first();

        if (!$pesanan) {
            return redirect('/')->with('error', 'Tidak ada pesanan aktif yang ditemukan');
        }

        // Check if order is late and set compensation - with safety check
        try {
            if (method_exists($pesanan, 'isLate') && $pesanan->isLate() && !($pesanan->kompensasi_pudding ?? false)) {
                $pesanan->update([
                    'kompensasi_pudding' => true,
                    'makanan_terlambat' => true,
                ]);
            }
        } catch (\Exception $e) {
            // Handle case where columns don't exist yet
            \Log::warning('Error updating compensation: ' . $e->getMessage());
        }

        return view('order-status', compact('pesanan'));
    }

    public function updateItemStatus(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'item_id' => 'required|exists:pesanan_items,id',
            'makanan_datang' => 'required|boolean',
        ]);

        $item = PesananItem::whereHas('pesanan', function($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'confirmed');
        })->findOrFail($request->item_id);

        $item->update(['makanan_datang' => $request->makanan_datang]);

        return response()->json(['success' => true, 'message' => 'Status makanan berhasil diupdate']);
    }

    public function completeOrder()
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
        $pesanan->update([
            'status' => 'completed',
            'waktu_selesai' => Carbon::now(),
        ]);

        // Update status meja
        if ($pesanan->meja) {
            $pesanan->meja->update(['status' => 'perluDiBersihkan']);
        }

        // Clear session
        session()->forget('current_order_id');

        // Logout otomatis
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('success', 'Pesanan selesai. Terima kasih!');
    }

    public function finishOrder()
    {
        // Alias untuk completeOrder untuk backward compatibility
        return $this->completeOrder();
    }
}
