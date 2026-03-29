<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap filter dari URL (default: all)
        $range = $request->query('range', 'all');
        $query = Transaction::query();

        // 1. Logika Filter Pendapatan
        if ($range == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($range == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($range == 'month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $total_pendapatan = $query->sum('total_harga') ?? 0;

        // Ambil data transaksi untuk tabel riwayat (limit 10 terbaru)
        $transactions = $query->latest()->limit(10)->get();

        // 2. Cari Menu Terlaris
        $terlarisQuery = TransactionDetail::select('menu_id', DB::raw('SUM(jumlah) as total_sold'))
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id');

        if ($range == 'today') {
            $terlarisQuery->whereDate('transactions.created_at', today());
        } elseif ($range == 'week') {
            $terlarisQuery->whereBetween('transactions.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($range == 'month') {
            $terlarisQuery->whereMonth('transactions.created_at', now()->month);
        }

        $terlaris = $terlarisQuery->groupBy('menu_id')->orderBy('total_sold', 'desc')->first();

        $nama_menu_terlaris = 'Belum ada data';
        if ($terlaris) {
            $menu = Menu::find($terlaris->menu_id);
            $nama_menu_terlaris = $menu ? $menu->nama_menu : 'Menu telah dihapus';
        }

        // 3. Hitung menu yang stoknya menipis
        $stok_menipis = Menu::where('stok', '<', 10)->where('stok', '>', 0)->count();

        // 4. Ambil semua menu (DIURUTKAN BERDASARKAN KATEGORI AGAR RAPI)
        $menus = Menu::orderByRaw("FIELD(kategori, 'mie', 'snack', 'minuman', 'paket') ASC")
                     ->orderBy('nama_menu', 'asc')
                     ->get();

        return view('admin.dashboard', compact(
            'total_pendapatan',
            'nama_menu_terlaris',
            'stok_menipis',
            'menus',
            'transactions',
            'range'
        ));
    }
}
