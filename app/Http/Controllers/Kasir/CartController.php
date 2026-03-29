<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Hanya tampilkan menu yang stoknya lebih dari 0
        $menus = Menu::where('stok', '>', 0)->get();
        $cart = session()->get('cart', []);
        return view('kasir.dashboard', compact('menus', 'cart'));
    }

    public function add(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['jumlah']++;
        } else {
            $cart[$id] = [
                "nama" => $menu->nama_menu,
                "jumlah" => 1,
                "harga" => $menu->harga,
                "foto" => $menu->foto
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function decrease($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            if($cart[$id]['jumlah'] > 1) {
                $cart[$id]['jumlah']--;
            } else {
                unset($cart[$id]);
            }
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Jumlah menu dikurangi');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu dihapus dari keranjang');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang telah dibersihkan.');
    }

    public function storeTransaction(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) return back()->with('error', 'Keranjang masih kosong!');

        // 1. Hitung Subtotal dari Cart
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }

        // 2. Hitung Pajak 10% & Total Akhir
        $pajak = $subtotal * 0.10;
        $total_akhir = $subtotal + $pajak;

        // 3. Validasi Uang Bayar
        if ($request->bayar < $total_akhir) {
            return back()->with('error', 'Uang kurang! Total tagihan: Rp ' . number_format($total_akhir, 0, ',', '.'));
        }

        // 4. SIMPAN KE TABEL TRANSACTIONS DULU (Agar dapat ID)
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'nama_pelanggan' => $request->nama_pelanggan ?? 'Pelanggan Umum',
            'total_harga' => $total_akhir,
            'pajak' => $pajak,
            'bayar' => $request->bayar,
            'kembalian' => $request->bayar - $total_akhir,
        ]);

        // 5. SIMPAN KE DETAIL & KURANGI STOK
        foreach($cart as $id => $item) {
            // Simpan detail
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'menu_id' => $id,
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['harga'] * $item['jumlah'],
            ]);

            // Kurangi stok menu di database
            $menu = Menu::find($id);
            if ($menu) {
                $menu->stok = $menu->stok - $item['jumlah'];
                $menu->save();
            }
        }

        // 6. Ambil data lengkap (Eager Load) untuk Struk Pop-up
        $struk = Transaction::with('transactionDetails.menu')->find($transaction->id);

        // 7. Bersihkan keranjang
        session()->forget('cart');

        return back()->with('success', 'Transaksi Berhasil!')
                     ->with('struk', $struk);
    }
}
