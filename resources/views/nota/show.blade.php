@extends('layouts.app')
@section('title','Detail Nota')
@section('content')

<div class="card" style="max-width:680px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px" class="no-print">
        <div class="card-title" style="margin:0;border:none">🧾 Detail Nota</div>
        <div style="display:flex;gap:8px">
                    <a href="{{ route('nota.cetak',$nota) }}" target="_blank" class="btn btn-primary">🖨️ Cetak Nota</a>
                    @if(!$nota->is_void)
                    <form action="{{ route('nota.void',$nota) }}" method="POST" onsubmit="return confirm('Yakin ingin void nota ini? Stok barang akan dikembalikan.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning">🚫 Void Nota</button>
                    </form>
                    @else
                    <span class="btn btn-secondary" style="cursor:default">🚫 Sudah Void</span>
                    @endif
                    <a href="{{ route('nota.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>

    {{-- Info nota --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:20px;font-size:14px">
        <div><span style="color:#888">Nomor Nota:</span> <strong>{{ $nota->nomor_nota }}</strong></div>
        <div><span style="color:#888">Tanggal:</span> {{ $nota->tanggal->format('d/m/Y H:i') }}</div>
        <div><span style="color:#888">Customer:</span> {{ $nota->nama_customer }}</div>
        @if($nota->no_hp)
        <div><span style="color:#888">No. HP:</span> {{ $nota->no_hp }}</div>
        @endif
        @if($nota->plat_nomor)
        <div><span style="color:#888">Plat Nomor:</span> {{ $nota->plat_nomor }}</div>
        @endif
    </div>

    {{-- Barang --}}
    @if($nota->items->count())
    <div style="margin-bottom:20px">
        <div style="font-weight:600;margin-bottom:8px;color:#555">📦 Barang:</div>
        <table>
            <thead>
                <tr><th>Nama Barang</th><th>Qty</th><th>Harga Satuan</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
            @foreach($nota->items as $item)
            <tr>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->qty }} {{ $item->barang->satuan }}</td>
                <td>Rp {{ number_format($item->harga_satuan,0,',','.') }}</td>
                <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Servis --}}
    @if($nota->servisList->count())
    <div style="margin-bottom:20px">
        <div style="font-weight:600;margin-bottom:8px;color:#555">⚙️ Jasa Servis:</div>
        @foreach($nota->servisList as $ns)
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f0;font-size:14px">
            <span>{{ $ns->servis->nama }}</span>
            <span>Rp {{ number_format($ns->harga,0,',','.') }}</span>
        </div>
        @endforeach
    </div>
    @endif

    @if($nota->catatan)
    <div style="padding:10px;background:#f8f9fa;border-radius:6px;font-size:13px;color:#555;margin-bottom:16px">
        <strong>Catatan:</strong> {{ $nota->catatan }}
    </div>
    @endif

    <div style="font-size:20px;font-weight:700;color:#1a1a2e;text-align:right;border-top:2px solid #e0e0e0;padding-top:12px">
        Total: Rp {{ number_format($nota->total,0,',','.') }}
    </div>
</div>

@endsection