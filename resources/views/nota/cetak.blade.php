<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Nota {{ $nota->nomor_nota }} — Chrisma Motor</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Courier New',monospace;font-size:12px;padding:10mm;max-width:80mm;margin:0 auto;color:#000}
.header{text-align:center;border-bottom:2px dashed #000;padding-bottom:8px;margin-bottom:8px}
.header h1{font-size:15px;letter-spacing:2px;font-weight:bold}
.header p{font-size:11px;margin-top:2px}
.info-table{width:100%;margin-bottom:8px;font-size:11px}
.info-table td{padding:1px 4px 1px 0;vertical-align:top}
.info-table td:first-child{color:#555;width:70px}
.divider{border:none;border-top:1px dashed #000;margin:6px 0}
.section-title{font-weight:bold;font-size:11px;margin-bottom:4px}
.item-table{width:100%;margin-bottom:4px}
.item-table tr td{padding:2px 0;vertical-align:top;font-size:11px}
.item-table .item-name{font-weight:500}
.item-table .item-detail{color:#444;font-size:10px}
.item-table .item-price{text-align:right;white-space:nowrap}
.total-row{display:flex;justify-content:space-between;font-size:14px;font-weight:bold;border-top:2px solid #000;padding-top:6px;margin-top:6px}
.footer{text-align:center;font-size:10px;margin-top:12px;border-top:1px dashed #000;padding-top:8px;line-height:1.6}
.btn-area{text-align:center;margin-bottom:16px;display:flex;gap:8px;justify-content:center}
.btn-area button{padding:8px 20px;border:none;border-radius:6px;cursor:pointer;font-size:13px;font-weight:600}
.btn-print{background:#1a1a2e;color:#fff}
.btn-close{background:#95a5a6;color:#fff}
@media print{.btn-area{display:none}body{padding:0;max-width:100%}}
</style>
</head>
<body>

<div class="btn-area">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Sekarang</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<div class="header">
    <h1>CHRISMA MOTOR</h1>
    <p>Jl. Raya Subah No.78</p>
    <p>Subah, Batang — Jawa Tengah</p>
</div>

<table class="info-table">
    <tr><td>No. Nota</td><td>: <strong>{{ $nota->nomor_nota }}</strong></td></tr>
    <tr><td>Tanggal</td><td>: {{ $nota->tanggal->format('d/m/Y H:i') }}</td></tr>
    <tr><td>Customer</td><td>: {{ $nota->nama_customer }}</td></tr>
    @if($nota->no_hp)
    <tr><td>No. HP</td><td>: {{ $nota->no_hp }}</td></tr>
    @endif
    @if($nota->plat_nomor)
    <tr><td>Plat No.</td><td>: {{ $nota->plat_nomor }}</td></tr>
    @endif
</table>

<hr class="divider">

@if($nota->items->count())
<div class="section-title">BARANG:</div>
<table class="item-table">
@foreach($nota->items as $item)
<tr>
    <td>
        <div class="item-name">{{ $item->barang->nama }}</div>
        <div class="item-detail">{{ $item->qty }} {{ $item->barang->satuan }} × Rp {{ number_format($item->harga_satuan,0,',','.') }}</div>
    </td>
    <td class="item-price">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
</tr>
@endforeach
</table>
@endif

@if($nota->servisList->count())
<hr class="divider">
<div class="section-title">JASA SERVIS:</div>
<table class="item-table">
@foreach($nota->servisList as $ns)
<tr>
    <td>{{ $ns->servis->nama }}</td>
    <td class="item-price">Rp {{ number_format($ns->harga,0,',','.') }}</td>
</tr>
@endforeach
</table>
@endif

@if($nota->catatan)
<hr class="divider">
<div style="font-size:10px;color:#555">Catatan: {{ $nota->catatan }}</div>
@endif

<div class="total-row">
    <span>TOTAL</span>
    <span>Rp {{ number_format($nota->total,0,',','.') }}</span>
</div>

<div class="footer">
    <p>Terima kasih sudah mempercayakan</p>
    <p>kendaraan Anda kepada kami!</p>
    <p style="margin-top:6px;font-weight:bold">★ CHRISMA MOTOR SUBAH ★</p>
</div>

</body>
</html>