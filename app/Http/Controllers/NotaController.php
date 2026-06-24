<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Barang;
use App\Models\Servis;
use App\Models\NotaItem;
use App\Models\NotaServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    // READ - daftar semua nota
    public function index(Request $request)
    {
        $query = Nota::latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_customer', 'like', '%' . $request->search . '%')
                  ->orWhere('nomor_nota', 'like', '%' . $request->search . '%');
            });
        }

        $notas = $query->paginate(15)->withQueryString();
        return view('nota.index', compact('notas'));
    }

    // CREATE - form buat nota baru
    public function create()
    {
        $barangs    = Barang::where('stok', '>', 0)->with('kategori')->get();
        $servisList = Servis::where('aktif', true)->get();
        return view('nota.create', compact('barangs', 'servisList'));
    }

    // PREVIEW - konfirmasi sebelum simpan
    public function preview(Request $request)
    {
        $request->validate(['nama_customer' => 'required|string']);

        $items = [];
        if ($request->items) {
            foreach ($request->items as $item) {
                if (empty($item['id']) || empty($item['qty'])) continue;
                $barang = Barang::find($item['id']);
                if (!$barang) continue;
                if ($item['qty'] > $barang->stok) {
                    return back()->withInput()
                        ->with('error', "Stok {$barang->nama} tidak cukup. Tersisa {$barang->stok} {$barang->satuan}.");
                }
                $items[] = [
                    'barang'   => $barang,
                    'qty'      => $item['qty'],
                    'subtotal' => $barang->harga_jual * $item['qty'],
                ];
            }
        }

        $servisDipilih = collect();
        if ($request->servis_ids) {
            $servisDipilih = Servis::whereIn('id', $request->servis_ids)->get();
        }

        $total = collect($items)->sum('subtotal') + $servisDipilih->sum('harga');

        return view('nota.preview', compact('items', 'servisDipilih', 'total', 'request'));
    }

    // STORE - simpan nota baru + kurangi stok
    public function store(Request $request)
    {
        $request->validate(['nama_customer' => 'required|string']);

        DB::transaction(function () use ($request) {
            $jumlahHariIni = Nota::whereDate('created_at', today())->count();
            $nomor = 'CM-' . date('Ymd') . '-' . str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);

            $nota = Nota::create([
                'nomor_nota'    => $nomor,
                'nama_customer' => $request->nama_customer,
                'no_hp'         => $request->no_hp,
                'plat_nomor'    => $request->plat_nomor,
                'catatan'       => $request->catatan,
                'total'         => 0,
                'tanggal'       => now(),
            ]);

            $total = 0;

            if ($request->items) {
                foreach ($request->items as $item) {
                    if (empty($item['id']) || empty($item['qty'])) continue;
                    $barang   = Barang::findOrFail($item['id']);
                    $subtotal = $barang->harga_jual * $item['qty'];
                    NotaItem::create([
                        'nota_id'      => $nota->id,
                        'barang_id'    => $barang->id,
                        'nama_barang'  => $barang->nama,
                        'qty'          => $item['qty'],
                        'harga_satuan' => $barang->harga_jual,
                        'subtotal'     => $subtotal,
                    ]);
                    $barang->decrement('stok', $item['qty']);
                    $total += $subtotal;
                }
            }

            if ($request->servis_ids) {
                foreach ($request->servis_ids as $sid) {
                    $servis = Servis::find($sid);
                    if (!$servis) continue;
                    NotaServis::create([
                        'nota_id'    => $nota->id,
                        'servis_id'  => $servis->id,
                        'nama_servis'=> $servis->nama,
                        'harga'      => $servis->harga,
                    ]);
                    $total += $servis->harga;
                }
            }

            $nota->update(['total' => $total]);
            session(['nota_baru_id' => $nota->id]);
        });

        $id = session()->pull('nota_baru_id');
        return redirect()->route('nota.show', $id)
            ->with('success', 'Nota berhasil disimpan! Stok barang sudah dikurangi.');
    }

    // READ - detail satu nota
    public function show(Nota $nota)
    {
        $nota->load('items.barang', 'servisList.servis');
        return view('nota.show', compact('nota'));
    }

    // CETAK - halaman print
    public function cetak(Nota $nota)
    {
        $nota->load('items.barang', 'servisList.servis');
        return view('nota.cetak', compact('nota'));
    }

    // VOID - tandai nota sebagai void + kembalikan stok
    public function void(Nota $nota)
    {
        if ($nota->is_void) {
            return back()->with('error', 'Nota ' . $nota->nomor_nota . ' sudah di-void sebelumnya.');
        }

        DB::transaction(function () use ($nota) {
            // Kembalikan stok barang
            foreach ($nota->items as $item) {
                $item->barang->increment('stok', $item->qty);
            }

            $nota->update([
                'is_void'   => true,
                'voided_at' => now(),
            ]);
        });

        return back()->with('success', 'Nota ' . $nota->nomor_nota . ' berhasil di-void. Stok barang sudah dikembalikan.');
    }
}