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
                            <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmVoid('{{ $n->id }}','{{ $n->nomor_nota }}','{{ addslashes($n->nama_customer) }}')">
                                Void
                            </button>
                            <form id="void-form-{{ $n->id }}" method="POST"
                                  action="{{ route('nota.void',$n) }}" style="display:none">
                                @csrf @method('PATCH')
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

{{-- Custom Void Modal --}}
<div id="void-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);backdrop-filter:blur(3px);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:28px 28px 22px;width:100%;max-width:380px;box-shadow:0 12px 40px rgba(0,0,0,.2);animation:popIn .18s ease;margin:0 16px">

        {{-- Icon --}}
        <div style="text-align:center;margin-bottom:14px">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:54px;height:54px;border-radius:50%;background:#fde8e8">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
        </div>

        {{-- Text --}}
        <h3 style="text-align:center;font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:6px">Void Nota Ini?</h3>
        <p style="text-align:center;font-size:13px;color:#666;margin-bottom:10px;line-height:1.6">
            Nota <strong id="void-nomor" style="color:#1a1a2e"></strong><br>
            atas nama <strong id="void-customer" style="color:#1a1a2e"></strong>
        </p>

        {{-- Warning box --}}
        <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:8px;padding:10px 14px;margin-bottom:20px;font-size:12px;color:#7a6000;line-height:1.6">
            ⚠️ Stok barang akan <strong>dikembalikan</strong> otomatis.<br>
            Nota tetap tersimpan sebagai history dan <strong>tidak bisa dibatalkan</strong>.
        </div>

        {{-- Buttons --}}
        <div style="display:flex;gap:10px">
            <button onclick="closeVoidModal()"
                    style="flex:1;padding:10px;border:1.5px solid #ddd;background:#fff;border-radius:8px;font-size:14px;font-weight:500;color:#555;cursor:pointer;transition:.15s"
                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'">
                Batal
            </button>
            <button onclick="submitVoid()"
                    style="flex:1;padding:10px;border:none;background:#e74c3c;border-radius:8px;font-size:14px;font-weight:600;color:#fff;cursor:pointer;transition:.15s"
                    onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                Ya, Void
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes popIn{from{transform:scale(.88);opacity:0}to{transform:scale(1);opacity:1}}
.row-void{background:#f7f7f8!important;opacity:.55;filter:grayscale(35%);transition:opacity .2s}
.row-void td{color:#999!important}
.row-void:hover{opacity:.8}
.void-stamp{display:inline-block;margin-left:8px;padding:1px 8px;font-size:10px;font-weight:700;letter-spacing:1px;color:#c0392b;border:1.5px solid #c0392b;border-radius:4px;transform:rotate(-6deg);vertical-align:middle;opacity:.85}
</style>
@endpush

@push('scripts')
<script>
let voidId = null;

function confirmVoid(id, nomor, customer) {
    voidId = id;
    document.getElementById('void-nomor').textContent = nomor;
    document.getElementById('void-customer').textContent = customer;
    const overlay = document.getElementById('void-overlay');
    overlay.style.display = 'flex';
}

function closeVoidModal() {
    document.getElementById('void-overlay').style.display = 'none';
    voidId = null;
}

function submitVoid() {
    if (voidId) document.getElementById('void-form-' + voidId).submit();
}

document.getElementById('void-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeVoidModal();
});
</script>
@endpush

@endsection