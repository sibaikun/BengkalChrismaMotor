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

    <table>
        <thead>
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
                    <form method="POST" action="{{ route('barang.destroy',$b) }}" style="display:inline"
                          onsubmit="return confirm('Hapus barang ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:20px">Barang tidak ditemukan</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination">{{ $barangs->links() }}</div>
</div>

@endsection