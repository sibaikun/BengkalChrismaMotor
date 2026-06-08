@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<div class="stats">
    <div class="stat">
        <div class="val">{{ $totalBarang }}</div>
        <div class="lbl">Total Jenis Barang</div>
    </div>
    <div class="stat green">
        <div class="val">{{ $totalNota }}</div>
        <div class="lbl">Total Nota</div>
    </div>
    <div class="stat orange">
        <div class="val">Rp {{ number_format($pendapatanBulanIni,0,',','.') }}</div>
        <div class="lbl">Pendapatan Bulan Ini</div>
    </div>
    <div class="stat red">
        <div class="val">{{ $stokMenipis }}</div>
        <div class="lbl">Stok Menipis (≤5)</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card" style="display:flex;flex-direction:column">
        <div class="card-title">🧾 Nota Terbaru</div>
        <div style="overflow-y:auto;max-height:300px">
            <table>
                <thead>
                    <tr><th>Nomor</th><th>Customer</th><th>Total</th></tr>
                </thead>
                <tbody>
                @forelse($notaTerbaru as $n)
                    <tr>
                        <td><a href="{{ route('nota.show',$n) }}" style="color:#3498db;text-decoration:none">{{ $n->nomor_nota }}</a></td>
                        <td>{{ $n->nama_customer }}</td>
                        <td>Rp {{ number_format($n->total,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align:center;color:#aaa;padding:20px">Belum ada nota</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="display:flex;flex-direction:column">
        <div class="card-title">⚠️ Stok Menipis</div>
        <div style="overflow-y:auto;max-height:300px">
            @forelse($barangMenipis as $b)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f0f0f0">
                    <div>
                        <div style="font-size:14px;font-weight:500">{{ $b->nama }}</div>
                        <div style="font-size:12px;color:#888">{{ $b->kategori->nama }}</div>
                    </div>
                    <span class="badge {{ $b->stok == 0 ? 'badge-danger' : 'badge-warning' }}">
                        {{ $b->stok }} {{ $b->satuan }}
                    </span>
                </div>
            @empty
                <p style="color:#27ae60;font-size:14px;padding:10px 0">✅ Semua stok aman</p>
            @endforelse
        </div>
    </div>
</div>

@endsection