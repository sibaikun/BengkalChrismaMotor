<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    // READ - tampilkan semua servis
    public function index()
    {
        $servisList = Servis::latest()->paginate(15);
        return view('servis.index', compact('servisList'));
    }

    // CREATE - form tambah
    public function create()
    {
        return view('servis.form', ['servis' => null]);
    }

    // CREATE - simpan ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:200',
            'harga'      => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $servis = Servis::create([
            'nama'       => $request->nama,
            'harga'      => $request->harga,
            'aktif'      => $request->has('aktif'),
            'keterangan' => $request->keterangan,
        ]);

        if ($request->expectsJson()) {
            return response()->json($servis, 201);
        }

        return redirect()->route('servis.index')
                        ->with('success', 'Jasa servis berhasil ditambahkan.');
    }

    // UPDATE - form edit
    public function edit(Servis $servis)
    {
        return view('servis.form', compact('servis'));
    }

    // UPDATE - simpan perubahan
    public function update(Request $request, Servis $servis)
    {
        $request->validate([
            'nama'       => 'required|string|max:200',
            'harga'      => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $servis->update([
            'nama'       => $request->nama,
            'harga'      => $request->harga,
            'aktif'      => $request->has('aktif'),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('servis.index')
                         ->with('success', 'Jasa servis berhasil diupdate.');
    }

    // DELETE - hapus servis
    public function destroy(Servis $servis)
    {
        $servis->delete();
        return back()->with('success', 'Jasa servis berhasil dihapus.');
    }
}