<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // READ - tampilkan semua barang + filter pencarian
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $barangs   = $query->latest()->paginate(15)->withQueryString();
        $kategoris = Kategori::all();

        return view('barang.index', compact('barangs', 'kategoris'));
    }

    // CREATE - form tambah
    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.form', ['barang' => null, 'kategoris' => $kategoris]);
    }

    // CREATE - simpan ke database
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'kode'        => 'required|string|max:50|unique:barangs,kode',
            'nama'        => 'required|string|max:200',
            'stok'        => 'required|integer|min:0',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'satuan'      => 'required|string|max:20',
            'keterangan'  => 'nullable|string',
        ]);

        $barang = Barang::create($request->all());

        if ($request->expectsJson()) {
            return response()->json($barang->load('kategori'), 201);
        }

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan.');
    }

    // UPDATE - form edit
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.form', compact('barang', 'kategoris'));
    }

    // UPDATE - simpan perubahan
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'kode'        => 'required|string|max:50|unique:barangs,kode,' . $barang->id,
            'nama'        => 'required|string|max:200',
            'stok'        => 'required|integer|min:0',
            'harga_beli'  => 'required|numeric|min:0',
            'harga_jual'  => 'required|numeric|min:0',
            'satuan'      => 'required|string|max:20',
            'keterangan'  => 'nullable|string',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diupdate.');
    }

    // RINGKASAN - harga beli vs harga jual
    public function ringkasanHarga()
    {
        $barangs = Barang::with('kategori')->orderBy('nama')->get();

        $totalBeli   = $barangs->sum(fn($b) => $b->harga_beli * $b->stok);
        $totalJual   = $barangs->sum(fn($b) => $b->harga_jual * $b->stok);
        $totalProfit = $totalJual - $totalBeli;

        return view('barang.ringkasan', compact('barangs', 'totalBeli', 'totalJual', 'totalProfit'));
    }

    // DELETE - hapus barang
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}