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

    <table>
        <thead>
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
            <tr>
                <td><strong style="color:#1a1a2e">{{ $n->nomor_nota }}</strong></td>
                <td style="font-size:13px;color:#666">{{ $n->tanggal->format('d/m/Y H:i') }}</td>
                <td>{{ $n->nama_customer }}</td>
                <td style="font-size:13px">{{ $n->plat_nomor ?? '-' }}</td>
                <td><strong>Rp {{ number_format($n->total,0,',','.') }}</strong></td>
                <td>
                    <a href="{{ route('nota.show',$n) }}" class="btn btn-primary btn-sm">Lihat</a>
                    <a href="{{ route('nota.edit',$n) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('nota.cetak',$n) }}" target="_blank" class="btn btn-secondary btn-sm">Cetak</a>
                    <form method="POST" action="{{ route('nota.destroy',$n) }}" style="display:inline"
                          onsubmit="return confirm('Hapus nota ini? Stok barang akan dikembalikan.')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:24px">Belum ada nota</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination">{{ $notas->links() }}</div>
</div>

@endsection