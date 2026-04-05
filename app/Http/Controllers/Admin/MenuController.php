<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga'     => 'required|numeric',
            'kategori'  => 'required',
            'deskripsi' => 'nullable',
            'foto'      => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('menu', 'public');
        }

        Menu::create([
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
            'kategori'  => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'stok'      => $request->stok ?? 0,
            'foto'      => $path,
        ]);

        return redirect()->back()->with('success', 'Menu berhasil ditambah!');
    }

    public function updateStok(Request $request, $id)
    {
        $request->validate(['stok' => 'required|numeric|min:0']);
        $menu = Menu::findOrFail($id);
        $menu->update(['stok' => $request->stok]);

        return back()->with('success', 'Stok ' . $menu->nama_menu . ' diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus relasi agar tidak error foreign key
        DB::table('transaction_details')->where('menu_id', $id)->delete();

        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();
        return back()->with('success', 'Menu dan riwayatnya berhasil dihapus!');
    }
}
