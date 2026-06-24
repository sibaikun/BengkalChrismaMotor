@extends('layouts.app')
@section('title','Stok Barang')
@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div class="card-title" style="margin:0;border:none">📦 Daftar Stok Barang</div>
        <a href="{{ route('barang.create') }}" class="btn btn-success">+ Tambah Barang</a>
    </div>

    {{-- Filter pencarian --}}
    <form method="GET" style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / kode..."
               style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;width:220px">
        <select name="kategori_id"
                style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected':'' }}>
                    {{ $k->nama }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-secondary" type="submit">🔍 Cari</button>
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    <div style="overflow-y:auto;max-height:480px">
        <table>
            <thead style="position:sticky;top:0;z-index:1">
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($barangs as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><code style="background:#f0f0f0;padding:2px 6px;border-radius:4px;font-size:12px">{{ $b->kode }}</code></td>
                    <td>{{ $b->nama }}</td>
                    <td>{{ $b->kategori->nama }}</td>
                    <td>
                        <span class="badge {{ $b->stok == 0 ? 'badge-danger' : ($b->stok <= 5 ? 'badge-warning' : 'badge-success') }}">
                            {{ $b->stok }} {{ $b->satuan }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($b->harga_jual,0,',','.') }}</td>
                    <td>
                        <a href="{{ route('barang.edit',$b) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete('{{ $b->id }}','{{ addslashes($b->nama) }}','{{ $b->kode }}')">
                            Hapus
                        </button>
                        <form id="del-form-{{ $b->id }}" method="POST"
                              action="{{ route('barang.destroy',$b) }}" style="display:none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#aaa;padding:20px">Barang tidak ditemukan</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $barangs->links() }}</div>
</div>

{{-- Custom Delete Modal --}}
<div id="del-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);backdrop-filter:blur(3px);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:28px 28px 22px;width:100%;max-width:380px;box-shadow:0 12px 40px rgba(0,0,0,.2);animation:popIn .18s ease;margin:0 16px">

        <div style="text-align:center;margin-bottom:14px">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:54px;height:54px;border-radius:50%;background:#fde8e8">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
        </div>

        <h3 style="text-align:center;font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:6px">Hapus Barang?</h3>
        <p style="text-align:center;font-size:13px;color:#666;margin-bottom:10px;line-height:1.6">
            <code id="del-kode" style="background:#f0f0f0;padding:1px 7px;border-radius:4px;font-size:12px"></code><br>
            <strong id="del-name" style="color:#1a1a2e"></strong>
        </p>

        <div style="background:#fde8e8;border:1px solid #f5c6cb;border-radius:8px;padding:10px 14px;margin-bottom:20px;font-size:12px;color:#721c24;line-height:1.6">
            ⚠️ Data barang akan <strong>dihapus permanen</strong> dari database.<br>Tindakan ini tidak dapat dibatalkan.
        </div>

        <div style="display:flex;gap:10px">
            <button onclick="closeModal()"
                    style="flex:1;padding:10px;border:1.5px solid #ddd;background:#fff;border-radius:8px;font-size:14px;font-weight:500;color:#555;cursor:pointer;transition:.15s"
                    onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'">
                Batal
            </button>
            <button onclick="submitDelete()"
                    style="flex:1;padding:10px;border:none;background:#e74c3c;border-radius:8px;font-size:14px;font-weight:600;color:#fff;cursor:pointer;transition:.15s"
                    onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                Ya, Hapus
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
let targetId = null;

function confirmDelete(id, name, kode) {
    targetId = id;
    document.getElementById('del-name').textContent = name;
    document.getElementById('del-kode').textContent = kode;
    document.getElementById('del-overlay').style.display = 'flex';
}

function closeModal() {
    document.getElementById('del-overlay').style.display = 'none';
    targetId = null;
}

function submitDelete() {
    if (targetId) document.getElementById('del-form-' + targetId).submit();
}

document.getElementById('del-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush

@endsection