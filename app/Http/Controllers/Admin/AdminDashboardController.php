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
        $range     = $request->query('range', 'all');
        $date_from = $request->query('date_from');
        $date_to   = $request->query('date_to');

        // Jika ada custom date, override range ke 'custom'
        if ($date_from || $date_to) {
            $range = 'custom';
        }

        // Helper closure untuk apply filter ke query manapun
        $applyFilter = function ($q) use ($range, $date_from, $date_to) {
            if ($range == 'today') {
                $q->whereDate('created_at', today());
            } elseif ($range == 'yesterday') {
                $q->whereDate('created_at', today()->subDay());
            } elseif ($range == 'week') {
                $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($range == 'month') {
                $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($range == 'custom') {
                if ($date_from) $q->whereDate('created_at', '>=', $date_from);
                if ($date_to)   $q->whereDate('created_at', '<=', $date_to);
            }
            return $q;
        };

        // 1. Total Pendapatan (query terpisah agar tidak kena limit)
        $total_pendapatan = $applyFilter(Transaction::query())->sum('total_harga') ?? 0;
        $total_transaksi  = $applyFilter(Transaction::query())->count();

        // 2. Ambil Riwayat Transaksi (limit 50 terbaru, eager load kasir)
        $transactions = $applyFilter(Transaction::query())
            ->with('user')
            ->latest()
            ->limit(50)
            ->get();

        // 3. Cari Menu Terlaris
        $terlarisQuery = TransactionDetail::select('menu_id', DB::raw('SUM(jumlah) as total_sold'))
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id');

        if ($range == 'today') {
            $terlarisQuery->whereDate('transactions.created_at', today());
        } elseif ($range == 'yesterday') {
            $terlarisQuery->whereDate('transactions.created_at', today()->subDay());
        } elseif ($range == 'week') {
            $terlarisQuery->whereBetween('transactions.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($range == 'month') {
            $terlarisQuery->whereMonth('transactions.created_at', now()->month);
        } elseif ($range == 'custom') {
            if ($date_from) $terlarisQuery->whereDate('transactions.created_at', '>=', $date_from);
            if ($date_to)   $terlarisQuery->whereDate('transactions.created_at', '<=', $date_to);
        }

        $terlaris = $terlarisQuery->groupBy('menu_id')->orderBy('total_sold', 'desc')->first();

        $nama_menu_terlaris = 'Belum ada data';
        if ($terlaris) {
            $menu = Menu::find($terlaris->menu_id);
            $nama_menu_terlaris = $menu ? $menu->nama_menu : 'Menu telah dihapus';
        }

        // 4. Hitung menu yang stoknya menipis
        $stok_menipis = Menu::where('stok', '<', 10)->where('stok', '>', 0)->count();

        // 5. Ambil semua menu
        $menus = Menu::orderByRaw("FIELD(kategori, 'mie', 'snack', 'minuman', 'paket') ASC")
                     ->orderBy('nama_menu', 'asc')
                     ->get();

        return view('admin.dashboard', compact(
            'total_pendapatan',
            'total_transaksi',
            'nama_menu_terlaris',
            'stok_menipis',
            'menus',
            'transactions',
            'range',
            'date_from',
            'date_to'
        ));
    }
}
