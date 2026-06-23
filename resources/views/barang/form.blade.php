@extends('layouts.app')
@section('title', $barang ? 'Edit Barang' : 'Tambah Barang')
@section('content')

<div class="card" style="max-width:680px">
    <div class="card-title">{{ $barang ? '✏️ Edit Barang' : '➕ Tambah Barang Baru' }}</div>

    <form method="POST" action="{{ $barang ? route('barang.update',$barang) : route('barang.store') }}">
        @csrf
        @if($barang) @method('PUT') @endif

        <div class="form-row">
            <div class="form-group">
                <label>Kode Barang *</label>
                <input type="text" name="kode" value="{{ old('kode', $barang->kode ?? '') }}"
                       placeholder="Contoh: OLI-003" required>
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <select name="kategori_id" required>
                    <option value="">— Pilih Kategori —</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}"
                            {{ old('kategori_id', $barang->kategori_id ?? '') == $k->id ? 'selected':'' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Nama Barang *</label>
            <input type="text" name="nama" value="{{ old('nama', $barang->nama ?? '') }}"
                   placeholder="Contoh: Oli Mesin Federal 0.8L" required>
        </div>

        <div class="form-row three">
            <div class="form-group">
                <label>Stok *</label>
                <input type="number" name="stok" value="{{ old('stok', $barang->stok ?? 0) }}" min="0" inputmode="numeric" required>
            </div>
            <div class="form-group">
                <label>Harga Beli (Rp) *</label>
                <input type="number" name="harga_beli" value="{{ old('harga_beli', $barang->harga_beli ?? 0) }}" min="0" inputmode="numeric" required>
            </div>
            <div class="form-group">
                
                <label>Harga Jual (Rp) *</label>
                <input type="number" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual ?? 0) }}" min="0" inputmode="numeric" required>
            </div>
        </div>

        <div class="form-group" style="max-width:200px">
            <label>Satuan</label>
            <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan ?? 'pcs') }}"
                   placeholder="pcs / botol / set">
        </div>

        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" rows="3" style="resize:vertical"
                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $barang->keterangan ?? '') }}</textarea>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-success">💾 Simpan</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection