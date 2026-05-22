@extends('layouts.app')
@section('title', $kategori ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')

<div class="card" style="max-width:400px">
    <div class="card-title">{{ $kategori ? '✏️ Edit Kategori' : '➕ Tambah Kategori' }}</div>

    <form method="POST" action="{{ $kategori ? route('kategori.update',$kategori) : route('kategori.store') }}">
        @csrf
        @if($kategori) @method('PUT') @endif

        <div class="form-group">
            <label>Nama Kategori *</label>
            <input type="text" name="nama" value="{{ old('nama', $kategori->nama ?? '') }}"
                   placeholder="Contoh: Oli & Pelumas" required autofocus>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-success">💾 Simpan</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection