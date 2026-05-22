@extends('layouts.app')
@section('title','Kategori')
@section('content')

<div class="card" style="max-width:600px">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <div class="card-title" style="margin:0;border:none">🏷️ Kategori Barang</div>
        <a href="{{ route('kategori.create') }}" class="btn btn-success">+ Tambah Kategori</a>
    </div>

    <table>
        <thead>
            <tr><th>#</th><th>Nama Kategori</th><th>Jumlah Barang</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($kategoris as $k)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $k->nama }}</td>
                <td><span class="badge badge-success">{{ $k->barangs_count }} barang</span></td>
                <td>
                    <a href="{{ route('kategori.edit',$k) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('kategori.destroy',$k) }}" style="display:inline"
                          onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" style="text-align:center;color:#aaa;padding:20px">Belum ada kategori</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection