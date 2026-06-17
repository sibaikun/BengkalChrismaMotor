@extends('layouts.app')
@section('title', $servis ? 'Edit Servis' : 'Tambah Servis')
@section('content')

<div class="card" style="max-width:540px">
    <div class="card-title">{{ $servis ? '✏️ Edit Jasa Servis' : '➕ Tambah Jasa Servis' }}</div>

    <form method="POST" action="{{ $servis ? route('servis.update', $servis) : route('servis.store') }}">
        @csrf
        @if($servis) @method('PUT') @endif

        <div class="form-group">
            <label>Nama Jasa Servis *</label>
            <input type="text" name="nama" value="{{ old('nama', $servis->nama ?? '') }}"
                   placeholder="Contoh: Ganti Oli Mesin" required autofocus>
        </div>

        <div class="form-group" style="max-width:240px">
            <label>Harga (Rp) *</label>
            <input type="number" name="harga" value="{{ old('harga', $servis->harga ?? 0) }}" min="0" required>
        </div>

       <div class="form-group">
            <label>Status</label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:normal;margin-top:4px">
                <input type="checkbox" name="aktif" style="width:auto"
                    {{ old('aktif', ($servis->aktif ?? true) ? '1' : '') ? 'checked' : '' }}>
                <span>
                    <span style="font-size:13px">Servis Aktif</span>
                    <span style="font-size:11px;color:#999;display:block;margin-top:1px">Jika aktif, jasa ini akan muncul sebagai pilihan saat membuat nota</span>
                </span>
            </label>
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $servis->keterangan ?? '') }}</textarea>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-success">💾 Simpan</button>
            <a href="{{ route('servis.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection