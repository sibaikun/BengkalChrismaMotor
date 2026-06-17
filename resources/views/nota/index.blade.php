@extends('layouts.app')
@section('title','Riwayat Nota')
@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div class="card-title" style="margin:0;border:none">🧾 Riwayat Nota Transaksi</div>
        <a href="{{ route('nota.create') }}" class="btn btn-success">+ Buat Nota Baru</a>
    </div>

    {{-- Filter pencarian --}}
    <form method="GET" style="display:flex;gap:10px;margin-bottom:16px">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama customer / no nota..."
               style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;flex:1;max-width:320px">
        <button class="btn btn-secondary" type="submit">🔍 Cari</button>
        <a href="{{ route('nota.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    <div style="overflow-y:auto;max-height:480px">
        <table>
            <thead style="position:sticky;top:0;z-index:1">
                <tr>
                    <th>No. Nota</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Plat No.</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($notas as $n)
                <tr class="{{ $n->is_void ? 'row-void' : '' }}">
                    <td>
                        <strong style="color:#1a1a2e">{{ $n->nomor_nota }}</strong>
                        @if($n->is_void)
                            <span class="void-stamp">VOID</span>
                        @endif
                    </td>
                    <td style="font-size:13px;color:#666">{{ $n->tanggal->format('d/m/Y H:i') }}</td>
                    <td>{{ $n->nama_customer }}</td>
                    <td style="font-size:13px">{{ $n->plat_nomor ?? '-' }}</td>
                    <td><strong>Rp {{ number_format($n->total,0,',','.') }}</strong></td>
                    <td>
                        <a href="{{ route('nota.show',$n) }}" class="btn btn-primary btn-sm">Detail</a>
                        <a href="{{ route('nota.cetak',$n) }}" target="_blank" class="btn btn-secondary btn-sm">Cetak</a>
                        @if(!$n->is_void)
                            <form method="POST" action="{{ route('nota.void',$n) }}" style="display:inline"
                                onsubmit="return confirm('Void nota {{ $n->nomor_nota }}? Stok barang akan dikembalikan dan nota tidak bisa diedit.')">
                                @csrf @method('PATCH')
                                <button class="btn btn-danger btn-sm">Void</button>
                            </form>
                        @else
                            <span class="badge badge-danger" style="padding:5px 10px">VOID</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#aaa;padding:24px">Belum ada nota</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $notas->links() }}</div>
</div>

<style>
    .row-void {
        background: #f7f7f8 !important;
        opacity: 0.55;
        filter: grayscale(35%);
        transition: opacity 0.2s;
    }
    .row-void td {
        color: #999 !important;
    }
    .row-void:hover {
        opacity: 0.8;
    }
    .void-stamp {
        display: inline-block;
        margin-left: 8px;
        padding: 1px 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #c0392b;
        border: 1.5px solid #c0392b;
        border-radius: 4px;
        transform: rotate(-6deg);
        vertical-align: middle;
        opacity: 0.85;
    }
</style>

@endsection