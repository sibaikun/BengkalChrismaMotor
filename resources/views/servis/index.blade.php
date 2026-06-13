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
                        <form method="POST" action="{{ route('servis.destroy',$s) }}" style="display:inline"
                              onsubmit="return confirm('Hapus servis ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
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

@endsection