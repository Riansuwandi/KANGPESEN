<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Meja;

class TableController extends Controller
{
    public function updateStatus(Request $request, Meja $meja)
    {
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengelola status meja');
        }

        $request->validate([
            'status' => 'required|in:kosong,digunakan,perluDiBersihkan',
        ]);

        $meja->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status meja berhasil diperbarui');
    }
}
