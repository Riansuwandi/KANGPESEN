<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function create()
    {
        // Check staff permission
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengelola menu');
        }

        return view('menu.create');
    }

    public function store(Request $request)
    {
        // Check staff permission
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengelola menu');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|numeric|min:0',
            'desc' => 'required|string',
            'jenis' => 'required|in:food,drink,snack',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('menu-photos', 'public');
            $validated['foto'] = $fotoPath;
        }

        Menu::create($validated);

        return redirect('/')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Menu $menu)
    {
        // Check staff permission
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengelola menu');
        }

        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        // Check staff permission
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengelola menu');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|numeric|min:0',
            'desc' => 'required|string',
            'jenis' => 'required|in:food,drink,snack',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }

            // Upload new foto
            $fotoPath = $request->file('foto')->store('menu-photos', 'public');
            $validated['foto'] = $fotoPath;
        }

        $menu->update($validated);

        return redirect('/')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu)
    {
        // Check staff permission
        if (!Auth::check() || !Auth::user()->isStaff()) {
            return redirect('/')->with('error', 'Hanya staff yang dapat mengelola menu');
        }

        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus');
    }
}
