<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Nota;
use App\Models\Servis;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang        = Barang::count();
        $stokMenipis        = Barang::where('stok', '<=', 5)->count();
        $totalNota          = Nota::count();
        $pendapatanBulanIni = Nota::whereMonth('tanggal', now()->month)
                                  ->whereYear('tanggal', now()->year)
                                  ->sum('total');
        $notaTerbaru        = Nota::latest()->take(5)->get();
        $barangMenipis      = Barang::where('stok', '<=', 5)->with('kategori')->get();

        return view('dashboard', compact(
            'totalBarang',
            'stokMenipis',
            'totalNota',
            'pendapatanBulanIni',
            'notaTerbaru',
            'barangMenipis'
        ));
    }
}