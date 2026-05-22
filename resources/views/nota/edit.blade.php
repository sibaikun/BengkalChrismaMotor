@extends('layouts.app')
@section('title','Edit Nota')
@push('styles')
<style>
.item-row{display:grid;grid-template-columns:2fr 1fr auto;gap:8px;align-items:center;margin-bottom:8px}
.item-row select,.item-row input{padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;width:100%}
.remove-btn{padding:6px 12px;background:#e74c3c;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:16px;flex-shrink:0}
.servis-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-top:8px}
.servis-card{border:1.5px solid #ddd;border-radius:8px;padding:12px;cursor:pointer;transition:.15s;display:flex;align-items:center;gap:10px}
.servis-card:hover{border-color:#3498db;background:#f0f7ff}
.servis-card.selected{border-color:#27ae60;background:#eafaf1}
.servis-card label{cursor:pointer;flex:1}
.servis-card .harga{font-size:12px;color:#888;display:block;margin-top:2px}
</style>
@endpush
@section('content')

<form method="POST" action="{{ route('nota.preview.edit', $nota) }}">
@csrf

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">

    {{-- KIRI --}}
    <div>

        {{-- Info nota (readonly) --}}
        <div class="alert alert-warning">
            ✏️ Mengedit nota <strong>{{ $nota->nomor_nota }}</strong>.
            Stok barang lama akan dikembalikan dan diganti dengan data baru.
        </div>

        {{-- Data Customer --}}
        <div class="card">
            <div class="card-title">👤 Data Customer</div>
            <div class="form-row three">
                <div class="form-group">
                    <label>Nama Customer *</label>
                    <input type="text" name="nama_customer"
                           value="{{ old('nama_customer', $nota->nama_customer) }}" required>
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp"
                           value="{{ old('no_hp', $nota->no_hp) }}" placeholder="08xx...">
                </div>
                <div class="form-group">
                    <label>Plat Nomor</label>
                    <input type="text" name="plat_nomor"
                           value="{{ old('plat_nomor', $nota->plat_nomor) }}" placeholder="H 1234 AB">
                </div>
            </div>
        </div>

        {{-- Barang --}}
        <div class="card">
            <div class="card-title">📦 Barang</div>
            <div id="items-container">
                @if($nota->items->count())
                    @foreach($nota->items as $i => $item)
                    <div class="item-row">
                        <select name="items[{{ $i }}][id]" class="item-select" onchange="hitungTotal()">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}"
                                        data-harga="{{ $b->harga_jual }}"
                                        data-nama="{{ $b->nama }}"
                                        data-satuan="{{ $b->satuan }}"
                                        {{ $item->barang_id == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama }} (stok: {{ $b->stok_edit }} {{ $b->satuan }}) — Rp {{ number_format($b->harga_jual,0,',','.') }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="items[{{ $i }}][qty]"
                               value="{{ $item->qty }}" min="1"
                               class="qty-input" onchange="hitungTotal()">
                        <button type="button" class="remove-btn" onclick="removeRow(this)">×</button>
                    </div>
                    @endforeach
                @else
                    <div class="item-row">
                        <select name="items[0][id]" class="item-select" onchange="hitungTotal()">
                            <option value="">— Pilih Barang —</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}"
                                        data-harga="{{ $b->harga_jual }}"
                                        data-nama="{{ $b->nama }}"
                                        data-satuan="{{ $b->satuan }}">
                                    {{ $b->nama }} (stok: {{ $b->stok_edit }} {{ $b->satuan }}) — Rp {{ number_format($b->harga_jual,0,',','.') }}
                                </option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][qty]" value="1" min="1"
                               class="qty-input" onchange="hitungTotal()">
                        <button type="button" class="remove-btn" onclick="removeRow(this)">×</button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-secondary btn-sm"
                    onclick="addRow()" style="margin-top:8px">
                + Tambah Barang Lagi
            </button>
        </div>

        {{-- Servis --}}
        <div class="card">
            <div class="card-title">⚙️ Jasa Servis <span style="font-weight:400;color:#aaa;font-size:13px">(opsional)</span></div>
            <div class="servis-grid">
                @foreach($servisList as $s)
                <div class="servis-card {{ in_array($s->id, $servisIdTerpilih) ? 'selected':'' }}"
                     id="sc-{{ $s->id }}" onclick="toggleServis({{ $s->id }})">
                    <input type="checkbox" name="servis_ids[]" value="{{ $s->id }}"
                           id="srv-{{ $s->id }}" style="width:auto"
                           {{ in_array($s->id, $servisIdTerpilih) ? 'checked':'' }}
                           onchange="hitungTotal()">
                    <label for="srv-{{ $s->id }}">
                        <strong>{{ $s->nama }}</strong>
                        <span class="harga">Rp {{ number_format($s->harga,0,',','.') }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Catatan --}}
        <div class="card">
            <div class="form-group" style="margin:0">
                <label>Catatan <span style="color:#aaa;font-weight:400">(opsional)</span></label>
                <textarea name="catatan" rows="2">{{ old('catatan', $nota->catatan) }}</textarea>
            </div>
        </div>

    </div>

    {{-- KANAN: Ringkasan --}}
    <div>
        <div class="card" style="position:sticky;top:20px">
            <div class="card-title">🧮 Ringkasan</div>
            <div id="summary" style="min-height:60px;font-size:13px;color:#888;margin-bottom:12px">
                <em>Memuat...</em>
            </div>
            <div style="font-size:18px;font-weight:700;color:#1a1a2e;border-top:2px solid #f0f0f0;padding-top:12px">
                Total: Rp <span id="total-display">0</span>
            </div>
            <button type="submit" class="btn btn-warning"
                    style="width:100%;margin-top:16px;padding:12px;font-size:15px">
                👁️ Preview & Konfirmasi Edit
            </button>
            <a href="{{ route('nota.show', $nota) }}" class="btn btn-secondary"
               style="width:100%;margin-top:8px;text-align:center;display:block;padding:10px">
                Batal
            </a>
        </div>
    </div>

</div>
</form>

@endsection
@push('scripts')
<script>
const barangs = @json($barangs->keyBy('id'));
let rowIdx = {{ $nota->items->count() > 0 ? $nota->items->count() : 1 }};

function addRow() {
    const c    = document.getElementById('items-container');
    const d    = document.createElement('div');
    d.className = 'item-row';
    const opts = Object.values(barangs).map(b =>
        `<option value="${b.id}" data-harga="${b.harga_jual}" data-nama="${b.nama}" data-satuan="${b.satuan}">
            ${b.nama} (stok: ${b.stok_edit} ${b.satuan}) — Rp ${Number(b.harga_jual).toLocaleString('id-ID')}
        </option>`
    ).join('');
    d.innerHTML = `
        <select name="items[${rowIdx}][id]" class="item-select" onchange="hitungTotal()">
            <option value="">— Pilih Barang —</option>${opts}
        </select>
        <input type="number" name="items[${rowIdx}][qty]" value="1" min="1"
               class="qty-input" onchange="hitungTotal()">
        <button type="button" class="remove-btn" onclick="removeRow(this)">×</button>`;
    c.appendChild(d);
    rowIdx++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        hitungTotal();
    }
}

function toggleServis(id) {
    const cb   = document.getElementById('srv-' + id);
    const card = document.getElementById('sc-' + id);
    cb.checked = !cb.checked;
    card.classList.toggle('selected', cb.checked);
    hitungTotal();
}

function hitungTotal() {
    let total = 0;
    let html  = '';

    document.querySelectorAll('.item-row').forEach(row => {
        const sel = row.querySelector('.item-select');
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;
        if (!sel.value) return;
        const opt   = sel.options[sel.selectedIndex];
        const harga = parseFloat(opt.dataset.harga) || 0;
        const nama  = opt.dataset.nama || '-';
        const sub   = harga * qty;
        total += sub;
        html += `<div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span>${nama} ×${qty}</span>
                    <span>Rp ${sub.toLocaleString('id-ID')}</span>
                 </div>`;
    });

    document.querySelectorAll('[name="servis_ids[]"]:checked').forEach(cb => {
        const lbl     = document.querySelector(`label[for="${cb.id}"] strong`);
        const hargaEl = document.querySelector(`#sc-${cb.value} .harga`);
        const hargaNum = hargaEl ? parseInt(hargaEl.textContent.replace(/[^\d]/g, '')) : 0;
        total += hargaNum;
        html += `<div style="display:flex;justify-content:space-between;margin-bottom:4px;color:#3498db">
                    <span>⚙️ ${lbl ? lbl.textContent : ''}</span>
                    <span>Rp ${hargaNum.toLocaleString('id-ID')}</span>
                 </div>`;
    });

    document.getElementById('summary').innerHTML = html || '<em style="color:#aaa">Pilih barang / servis...</em>';
    document.getElementById('total-display').textContent = total.toLocaleString('id-ID');
}

// Hitung total saat halaman pertama dimuat
hitungTotal();
</script>
@endpush