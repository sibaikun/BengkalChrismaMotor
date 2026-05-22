@extends('layouts.app')
@section('title','Konfirmasi Edit Nota')
@section('content')

<div class="card" style="max-width:680px">
    <div class="card-title">✅ Konfirmasi Perubahan Nota</div>

    <div class="alert alert-warning">
        ⚠️ Periksa kembali data di bawah. Setelah dikonfirmasi, stok barang lama akan
        <strong>dikembalikan</strong> dan stok baru akan <strong>dikurangi</strong> otomatis.
    </div>

    {{-- Info Nota --}}
    <div style="background:#f8f9fa;border-radius:6px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#555">
        Nomor Nota: <strong>{{ $nota->nomor_nota }}</strong>
    </div>

    {{-- Info Customer --}}
    <table style="margin-bottom:20px">
        <tr>
            <td style="padding:4px 16px 4px 0;color:#888;font-size:13px;width:120px">Customer</td>
            <td><strong>{{ $request->nama_customer }}</strong></td>
        </tr>
        @if($request->no_hp)
        <tr>
            <td style="padding:4px 16px 4px 0;color:#888;font-size:13px">No. HP</td>
            <td>{{ $request->no_hp }}</td>
        </tr>
        @endif
        @if($request->plat_nomor)
        <tr>
            <td style="padding:4px 16px 4px 0;color:#888;font-size:13px">Plat Nomor</td>
            <td>{{ $request->plat_nomor }}</td>
        </tr>
        @endif
    </table>

    {{-- Barang --}}
    @if(count($items) > 0)
    <div style="margin-bottom:20px">
        <div style="font-weight:600;margin-bottom:8px;color:#555">📦 Barang:</div>
        <table>
            <thead>
                <tr><th>Nama Barang</th><th>Qty</th><th>Harga Satuan</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['barang']->nama }}</td>
                <td>{{ $item['qty'] }} {{ $item['barang']->satuan }}</td>
                <td>Rp {{ number_format($item['barang']->harga_jual,0,',','.') }}</td>
                <td>Rp {{ number_format($item['subtotal'],0,',','.') }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Servis --}}
    @if($servisDipilih->count() > 0)
    <div style="margin-bottom:20px">
        <div style="font-weight:600;margin-bottom:8px;color:#555">⚙️ Jasa Servis:</div>
        @foreach($servisDipilih as $s)
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f0;font-size:14px">
            <span>{{ $s->nama }}</span>
            <span>Rp {{ number_format($s->harga,0,',','.') }}</span>
        </div>
        @endforeach
    </div>
    @endif

    @if($request->catatan)
    <div style="padding:10px;background:#f8f9fa;border-radius:6px;font-size:13px;color:#555;margin-bottom:16px">
        <strong>Catatan:</strong> {{ $request->catatan }}
    </div>
    @endif

    <div style="font-size:20px;font-weight:700;color:#1a1a2e;border-top:2px solid #e0e0e0;padding-top:12px;margin-bottom:24px">
        Total: Rp {{ number_format($total,0,',','.') }}
    </div>

    {{-- Form simpan perubahan --}}
    <form method="POST" action="{{ route('nota.update', $nota) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="nama_customer" value="{{ $request->nama_customer }}">
        <input type="hidden" name="no_hp"         value="{{ $request->no_hp }}">
        <input type="hidden" name="plat_nomor"    value="{{ $request->plat_nomor }}">
        <input type="hidden" name="catatan"       value="{{ $request->catatan }}">

        @if($request->items)
            @foreach($request->items as $i => $item)
                @if(!empty($item['id']) && !empty($item['qty']))
                    <input type="hidden" name="items[{{ $i }}][id]"  value="{{ $item['id'] }}">
                    <input type="hidden" name="items[{{ $i }}][qty]" value="{{ $item['qty'] }}">
                @endif
            @endforeach
        @endif

        @if($request->servis_ids)
            @foreach($request->servis_ids as $sid)
                <input type="hidden" name="servis_ids[]" value="{{ $sid }}">
            @endforeach
        @endif

        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-success" style="padding:12px 28px;font-size:15px">
                ✅ Konfirmasi Perubahan
            </button>
            <a href="{{ route('nota.edit', $nota) }}" class="btn btn-secondary" style="padding:12px 20px">
                ← Kembali Edit
            </a>
        </div>
    </form>
</div>

@endsection