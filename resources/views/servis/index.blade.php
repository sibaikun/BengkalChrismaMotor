@extends('layouts.app')
@section('title','Jasa Servis')
@section('content')

<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div class="card-title" style="margin:0;border:none">⚙️ Daftar Jasa Servis</div>
        <a href="{{ route('servis.create') }}" class="btn btn-success">+ Tambah Servis</a>
    </div>

    <div style="overflow-y:auto;max-height:480px">
        <table>
            <thead style="position:sticky;top:0;z-index:1">
                <tr>
                    <th>#</th>
                    <th>Nama Jasa Servis</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($servisList as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>Rp {{ number_format($s->harga,0,',','.') }}</td>
                    <td>
                        <span class="badge {{ $s->aktif ? 'badge-success' : 'badge-danger' }}">
                            {{ $s->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="color:#888;font-size:13px">{{ $s->keterangan ?? '-' }}</td>
                    <td>
                        <a href="{{ route('servis.edit',$s) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete('{{ $s->id }}','{{ addslashes($s->nama) }}')">
                            Hapus
                        </button>
                        <form id="del-form-{{ $s->id }}" method="POST"
                              action="{{ route('servis.destroy',$s) }}" style="display:none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:#aaa;padding:20px">Belum ada data servis</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">{{ $servisList->links() }}</div>
</div>

{{-- Custom Delete Modal --}}
<div id="del-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);backdrop-filter:blur(3px);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:14px;padding:28px 28px 22px;width:100%;max-width:360px;box-shadow:0 12px 40px rgba(0,0,0,.2);animation:popIn .18s ease;position:relative;margin:0 16px">

        {{-- Icon --}}
        <div style="text-align:center;margin-bottom:14px">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:54px;height:54px;border-radius:50%;background:#fde8e8">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#e74c3c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
        </div>

        {{-- Text --}}
        <h3 style="text-align:center;font-size:16px;font-weight:700;color:#1a1a2e;margin-bottom:6px">Hapus Jasa Servis?</h3>
        <p style="text-align:center;font-size:13px;color:#888;margin-bottom:20px;line-height:1.5">
            Kamu akan menghapus <strong id="del-name" style="color:#333"></strong>.<br>Tindakan ini tidak dapat dibatalkan.
        </p>

        {{-- Buttons --}}
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

function confirmDelete(id, name) {
    targetId = id;
    document.getElementById('del-name').textContent = name;
    const overlay = document.getElementById('del-overlay');
    overlay.style.display = 'flex';
}

function closeModal() {
    document.getElementById('del-overlay').style.display = 'none';
    targetId = null;
}

function submitDelete() {
    if (targetId) document.getElementById('del-form-' + targetId).submit();
}

// Tutup modal kalau klik di luar box
document.getElementById('del-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush

@endsection