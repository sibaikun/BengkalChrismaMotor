<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // READ - tampilkan semua kategori
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->get();
        return view('kategori.index', compact('kategoris'));
    }

    // CREATE - form tambah
    public function create()
    {
        return view('kategori.form', ['kategori' => null]);
    }

    // CREATE - simpan ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama',
        ]);

        Kategori::create($request->only('nama'));

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // UPDATE - form edit
    public function edit(Kategori $kategori)
    {
        return view('kategori.form', compact('kategori'));
    }

    // UPDATE - simpan perubahan
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategoris,nama,' . $kategori->id,
        ]);

        $kategori->update($request->only('nama'));

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diupdate.');
    }

    // DELETE - hapus kategori
    public function destroy(Kategori $kategori)
    {
        if ($kategori->barangs()->count() > 0) {
            return back()->with('error', 'Tidak bisa dihapus, masih ada barang di kategori ini.');
        }

        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}