@extends('layouts.app')
@section('title','Detail Nota')
@section('content')

<div class="card" style="max-width:680px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px" class="no-print">
        <div class="card-title" style="margin:0;border:none">🧾 Detail Nota</div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('nota.cetak',$nota) }}" target="_blank" class="btn btn-primary">🖨️ Cetak Nota</a>
            @if(!$nota->is_void)
                <button type="button" class="btn btn-warning" onclick="openVoidModal()">🚫 Void Nota</button>
                <form id="void-form" action="{{ route('nota.void',$nota) }}" method="POST" style="display:none">
                    @csrf @method('PATCH')
                </form>
            @else
                <span class="btn btn-secondary" style="cursor:default">🚫 Sudah Void</span>
            @endif
            <a href="{{ route('nota.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>

    {{-- Badge void --}}
    @if($nota->is_void)
    <div style="background:#fde8e8;border:1px solid #f5c6cb;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#721c24">
        🚫 Nota ini telah di-<strong>void</strong> pada {{ $nota->voided_at?->format('d/m/Y H:i') }}. Stok barang sudah dikembalikan.
    </div>
    @endif

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
            @php
                $namaBarang   = $item->nama_barang ?? optional($item->barang)->nama ?? '(Barang telah dihapus)';
                $satuanBarang = optional($item->barang)->satuan ?? '';
            @endphp
            <tr>
                <td>
                    {{ $namaBarang }}
                    @if(!$item->barang)
                        <span style="font-size:11px;color:#aaa;margin-left:4px">(dihapus)</span>
                    @endif
                </td>
                <td>{{ $item->qty }} {{ $satuanBarang }}</td>
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
        @php
            $namaServis = $ns->nama_servis
                ?? optional($ns->servis)->nama
                ?? '(Servis telah dihapus)';
        @endphp
        <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f0f0f0;font-size:14px">
            <span>
                {{ $namaServis }}
                @if(!$ns->servis)
                    <span style="font-size:11px;color:#aaa;margin-left:4px">(dihapus)</span>
                @endif
            </span>
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

{{-- Void Modal --}}
<div id="void-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);backdrop-filter:blur(3px);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:28px 28px 22px;width:100%;max-width:380px;box-shadow:0 12px 40px rgba(0,0,0,.2);animation:popIn .18s ease;margin:0 16px">

        <div style="text-align:center;margin-bottom:14px">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:54px;height:54px;border-radius:50%;background:#fde8e8">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
        </div>

        <h3 style="text-align:center;font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:6px">Void Nota Ini?</h3>
        <p style="text-align:center;font-size:13px;color:#666;margin-bottom:10px;line-height:1.6">
            Nota <strong>{{ $nota->nomor_nota }}</strong><br>
            atas nama <strong>{{ $nota->nama_customer }}</strong>
        </p>

        <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:10px 14px;margin-bottom:20px;font-size:12px;color:#7a6000;line-height:1.6">
            ⚠️ Stok barang akan <strong>dikembalikan</strong> otomatis.<br>
            Nota tetap tersimpan sebagai history dan <strong>tidak bisa dibatalkan</strong>.
        </div>

        <div style="display:flex;gap:10px">
            <button onclick="closeVoidModal()"
                    style="flex:1;padding:10px;border:1.5px solid #ddd;background:#fff;border-radius:8px;font-size:14px;font-weight:500;color:#555;cursor:pointer"
                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'">
                Batal
            </button>
            <button onclick="document.getElementById('void-form').submit()"
                    style="flex:1;padding:10px;border:none;background:#e74c3c;border-radius:8px;font-size:14px;font-weight:600;color:#fff;cursor:pointer"
                    onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                Ya, Void
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes popIn{from{transform:scale(.88);opacity:0}to{transform:scale(1);opacity:1}}
</style>
@endpush

@push('scripts')
<script>
function openVoidModal()  { document.getElementById('void-overlay').style.display = 'flex'; }
function closeVoidModal() { document.getElementById('void-overlay').style.display = 'none'; }
document.getElementById('void-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeVoidModal();
});
</script>
@endpush

@endsection