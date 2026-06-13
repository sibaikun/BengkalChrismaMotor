@extends('layouts.app')
@section('title', 'Ringkasan Harga')
@section('content')

{{-- Summary Cards --}}
<div class="stats" style="margin-bottom:24px">
    <div class="stat">
        <div class="val">Rp {{ number_format($totalBeli,0,',','.') }}</div>
        <div class="lbl">💰 Total Modal (Harga Beli × Stok)</div>
    </div>
    <div class="stat green">
        <div class="val">Rp {{ number_format($totalJual,0,',','.') }}</div>
        <div class="lbl">🏷️ Total Nilai Jual (Harga Jual × Stok)</div>
    </div>
    <div class="stat {{ $totalProfit >= 0 ? 'orange' : 'red' }}">
        <div class="val">Rp {{ number_format($totalProfit,0,',','.') }}</div>
        <div class="lbl">📈 Estimasi Profit (jika semua terjual)</div>
    </div>
</div>

    {{-- Two Column Layout --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

        {{-- KIRI: Harga Beli --}}
        <div class="card">
            <div class="card-title">💰 Harga Beli (Modal)</div>
            <div style="overflow-y:auto;max-height:480px">
                <table>
                    <thead style="position:sticky;top:0;z-index:1">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th style="text-align:right">Harga Beli</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $b)
                        <tr>
                            <td>
                                {{ $b->nama }}
                                @if($b->kategori)
                                    <br><small style="color:#999">{{ $b->kategori->nama }}</small>
                                @endif
                            </td>
                            <td>{{ $b->stok }} {{ $b->satuan }}</td>
                            <td style="text-align:right">Rp {{ number_format($b->harga_beli,0,',','.') }}</td>
                            <td style="text-align:right;font-weight:600">
                                Rp {{ number_format($b->harga_beli * $b->stok,0,',','.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:#999;padding:20px">Belum ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <table>
                <tfoot>
                    <tr style="background:#f8f9fa;font-weight:700">
                        <td colspan="3" style="padding:10px 12px">TOTAL MODAL</td>
                        <td style="text-align:right;padding:10px 12px;color:#2980b9">
                            Rp {{ number_format($totalBeli,0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- KANAN: Harga Jual --}}
        <div class="card">
            <div class="card-title">🏷️ Harga Jual</div>
            <div style="overflow-y:auto;max-height:480px">
                <table>
                    <thead style="position:sticky;top:0;z-index:1">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th style="text-align:right">Harga Jual</th>
                            <th style="text-align:right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $b)
                        <tr>
                            <td>
                                {{ $b->nama }}
                                @if($b->kategori)
                                    <br><small style="color:#999">{{ $b->kategori->nama }}</small>
                                @endif
                            </td>
                            <td>{{ $b->stok }} {{ $b->satuan }}</td>
                            <td style="text-align:right">Rp {{ number_format($b->harga_jual,0,',','.') }}</td>
                            <td style="text-align:right;font-weight:600">
                                Rp {{ number_format($b->harga_jual * $b->stok,0,',','.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:#999;padding:20px">Belum ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <table>
                <tfoot>
                    <tr style="background:#f8f9fa;font-weight:700">
                        <td colspan="3" style="padding:10px 12px">TOTAL NILAI JUAL</td>
                        <td style="text-align:right;padding:10px 12px;color:#27ae60">
                            Rp {{ number_format($totalJual,0,',','.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

{{-- Responsive: stack on small screen --}}
@push('styles')
<style>
    @media(max-width:860px){
        div[style*="grid-template-columns:1fr 1fr"]{
            grid-template-columns:1fr !important;
        }
    }
</style>
@endpush

@endsection