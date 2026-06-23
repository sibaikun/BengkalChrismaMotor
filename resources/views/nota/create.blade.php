@extends('layouts.app')
@section('title','Buat Nota Baru')
@push('styles')
<style>
.item-row{display:grid;grid-template-columns:1fr auto auto;gap:8px;align-items:center;margin-bottom:8px;border:1px solid #eee;border-radius:8px;padding:10px 12px;background:#fafbfc}
.item-row .item-info{display:flex;flex-direction:column;gap:2px;min-width:0}
.item-row .item-info .nama{font-size:13px;font-weight:600;color:#1a1a2e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.item-row .item-info .placeholder{font-size:13px;color:#aaa}
.item-row .item-info .harga{font-size:11px;color:#888}
.item-row .qty-input{width:70px;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;text-align:center}
.pilih-btn{padding:7px 12px;background:#3498db;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;white-space:nowrap}
.pilih-btn.ganti{background:#f0f0f0;color:#555}
.remove-btn{padding:6px 12px;background:#e74c3c;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:16px;line-height:1;flex-shrink:0}
.servis-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-top:8px}
.servis-card{border:1.5px solid #ddd;border-radius:8px;padding:12px;cursor:pointer;transition:.15s;display:flex;align-items:center;gap:10px}
.servis-card:hover{border-color:#3498db;background:#f0f7ff}
.servis-card.selected{border-color:#27ae60;background:#eafaf1}
.servis-card label{cursor:pointer;flex:1}
.servis-card .harga{font-size:12px;color:#888;display:block;margin-top:2px}

/* ===== Modal Pilih Barang ===== */
#modal-pilih-barang .modal-box{background:#fff;border-radius:12px;padding:24px;width:100%;max-width:680px;max-height:85vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.pb-search{width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:8px;font-size:14px;margin-bottom:12px}
.pb-kategori-bar{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px}
.pb-kategori-chip{padding:5px 12px;border:1.5px solid #ddd;border-radius:16px;font-size:12px;cursor:pointer;background:#fff;color:#666;white-space:nowrap}
.pb-kategori-chip:hover{border-color:#3498db}
.pb-kategori-chip.active{background:#1a1a2e;border-color:#1a1a2e;color:#fff}
.pb-grid{overflow-y:auto;flex:1;display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;padding-right:4px}
.pb-card{border:1.5px solid #e0e0e0;border-radius:8px;padding:12px;cursor:pointer;transition:.15s}
.pb-card:hover{border-color:#3498db;background:#f0f7ff}
.pb-card.habis{opacity:.45;cursor:not-allowed;pointer-events:none}
.pb-card .pb-nama{font-size:13px;font-weight:600;color:#1a1a2e;margin-bottom:4px;line-height:1.3}
.pb-card .pb-harga{font-size:13px;color:#27ae60;font-weight:600}
.pb-card .pb-stok{font-size:11px;color:#999;margin-top:2px}
.pb-empty{grid-column:1/-1;text-align:center;color:#aaa;padding:40px 0;font-size:13px}
</style>
@endpush
@section('content')

<form method="POST" action="{{ route('nota.preview') }}" id="nota-form">
@csrf

<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start">

    {{-- KIRI --}}
    <div>

        {{-- Data Customer --}}
        <div class="card">
            <div class="card-title">👤 Data Customer</div>
            <div class="form-row three">
                <div class="form-group">
                    <label>Nama Customer *</label>
                    <input type="text" name="nama_customer" value="{{ old('nama_customer') }}"
                           placeholder="Nama lengkap" required>
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xx...">
                </div>
                <div class="form-group">
                    <label>Plat Nomor</label>
                    <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" placeholder="G 1234 AB">
                </div>
            </div>
        </div>

        {{-- Barang --}}
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <div class="card-title" style="margin:0;border:none">📦 Barang yang Dibeli</div>
                <button type="button" class="btn btn-success btn-sm" onclick="bukaModalBarangBaru()">+ Tambah Barang Baru</button>
            </div>
            <div id="items-container"></div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()" style="margin-top:8px">
                + Tambah Baris Barang
            </button>
        </div>

        {{-- Servis --}}
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <div class="card-title" style="margin:0;border:none">⚙️ Jasa Servis</div>
                <button type="button" class="btn btn-success btn-sm" onclick="bukaModalServis()">+ Tambah Servis Baru</button>
            </div>
            @if($servisList->isEmpty())
                <p style="color:#aaa;font-size:13px">Belum ada jasa servis. Klik tombol di atas untuk menambah.</p>
            @else
            @php $oldServisIds = old('servis_ids', []); @endphp
            <div class="servis-grid" id="servis-grid">
                @foreach($servisList as $s)
                @php $isChecked = in_array((string)$s->id, array_map('strval', $oldServisIds)); @endphp
                <div class="servis-card {{ $isChecked ? 'selected' : '' }}" id="sc-{{ $s->id }}" onclick="toggleServis({{ $s->id }}, {{ $s->harga }})">
                    <input type="checkbox" name="servis_ids[]" value="{{ $s->id }}"
                           id="srv-{{ $s->id }}" style="width:auto" onchange="hitungTotal()"
                           {{ $isChecked ? 'checked' : '' }}>
                    <label for="srv-{{ $s->id }}">
                        <strong>{{ $s->nama }}</strong>
                        <span class="harga">Rp {{ number_format($s->harga,0,',','.') }}</span>
                    </label>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Catatan --}}
        <div class="card">
            <div class="form-group" style="margin:0">
                <label>Catatan <span style="color:#aaa;font-weight:400">(opsional)</span></label>
                <textarea name="catatan" rows="2"
                          placeholder="Keluhan, permintaan khusus...">{{ old('catatan') }}</textarea>
            </div>
        </div>

    </div>

    {{-- KANAN: Ringkasan --}}
    <div>
        <div class="card" style="position:sticky;top:20px">
            <div class="card-title">🧮 Ringkasan</div>
            <div id="summary" style="min-height:60px;font-size:13px;color:#888;margin-bottom:12px">
                <em>Pilih barang / servis...</em>
            </div>
            <div style="font-size:18px;font-weight:700;color:#1a1a2e;border-top:2px solid #f0f0f0;padding-top:12px">
                Total: Rp <span id="total-display">0</span>
            </div>
            <button type="submit" class="btn btn-primary"
                    style="width:100%;margin-top:16px;padding:12px;font-size:15px">
                👁️ Preview & Konfirmasi
            </button>
            <a href="{{ route('nota.index') }}" class="btn btn-secondary"
               style="width:100%;margin-top:8px;text-align:center;display:block;padding:10px">
                Batal
            </a>
        </div>
    </div>

</div>
</form>

{{-- ===================== MODAL PILIH BARANG (picker) ===================== --}}
<div id="modal-pilih-barang" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
            <h3 style="font-size:16px;color:#1a1a2e">📦 Pilih Barang</h3>
            <button onclick="tutupModalPilihBarang()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888">×</button>
        </div>
        <input type="text" id="pb-search" class="pb-search" placeholder="🔍 Cari nama barang...">
        <div class="pb-kategori-bar" id="pb-kategori-bar"></div>
        <div class="pb-grid" id="pb-grid"></div>
    </div>
</div>

{{-- ===================== MODAL TAMBAH SERVIS ===================== --}}
<div id="modal-servis" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,.3)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h3 style="font-size:16px;color:#1a1a2e">⚙️ Tambah Jasa Servis Baru</h3>
            <button onclick="tutupModalServis()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888">×</button>
        </div>
        <div id="modal-servis-error" class="alert alert-error" style="display:none"></div>
        <div class="form-group">
            <label>Nama Jasa Servis *</label>
            <input type="text" id="ms-nama" placeholder="Contoh: Ganti Kampas Rem">
        </div>
        <div class="form-group">
            <label>Harga (Rp) *</label>
            <input type="number" id="ms-harga" placeholder="Contoh: 25000" min="0">
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" id="ms-keterangan" placeholder="Opsional">
        </div>
        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="button" class="btn btn-success" onclick="simpanServis()" id="btn-simpan-servis">💾 Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="tutupModalServis()">Batal</button>
        </div>
    </div>
</div>

{{-- ===================== MODAL TAMBAH BARANG BARU ===================== --}}
<div id="modal-barang" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:12px;padding:28px;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,.3)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
            <h3 style="font-size:16px;color:#1a1a2e">📦 Tambah Barang Baru</h3>
            <button onclick="tutupModalBarang()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888">×</button>
        </div>
        <div id="modal-barang-error" class="alert alert-error" style="display:none"></div>
        <div class="form-row">
            <div class="form-group">
                <label>Kode Barang *</label>
                <input type="text" id="mb-kode" placeholder="Contoh: SPR-010">
            </div>
            <div class="form-group">
                <label>Kategori *</label>
                <select id="mb-kategori">
                    <option value="">— Pilih —</option>
                    @foreach(\App\Models\Kategori::all() as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Nama Barang *</label>
            <input type="text" id="mb-nama" placeholder="Contoh: Kampas Rem Depan Honda Beat">
        </div>
        <div class="form-row three">
            <div class="form-group">
                <label>Stok *</label>
                <input type="number" id="mb-stok" value="0" min="0">
            </div>
            <div class="form-group">
                <label>Harga Beli *</label>
                <input type="number" id="mb-harga-beli" value="0" min="0">
            </div>
            <div class="form-group">
                <label>Harga Jual *</label>
                <input type="number" id="mb-harga-jual" value="0" min="0">
            </div>
        </div>
        <div class="form-group" style="max-width:180px">
            <label>Satuan</label>
            <input type="text" id="mb-satuan" value="pcs" placeholder="pcs / botol / set">
        </div>
        <div style="display:flex;gap:10px;margin-top:4px">
            <button type="button" class="btn btn-success" onclick="simpanBarang()" id="btn-simpan-barang">💾 Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="tutupModalBarang()">Batal</button>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
// Data barang & kategori dikirim dari server
const barangs   = @json($barangs->keyBy('id'));
const kategoris = @json(\App\Models\Kategori::all(['id','nama']));

let rowIdx = 0;
let pbTargetRow = null;     // row element yang sedang minta dipilihkan barang
let pbKategoriAktif = '';   // '' = semua kategori

// ============ RENDER ITEM ROW ============
function buatItemRow(idx, barangId = '', qty = 1) {
    const d = document.createElement('div');
    d.className = 'item-row';
    d.dataset.idx = idx;
    renderRowContent(d, barangId, qty);
    return d;
}

function renderRowContent(rowEl, barangId, qty) {
    const idx = rowEl.dataset.idx;
    const b = barangId ? barangs[barangId] : null;

    rowEl.innerHTML = `
        <input type="hidden" name="items[${idx}][id]" class="item-id-input" value="${barangId || ''}">
        <div class="item-info">
            ${b
                ? `<span class="nama">${b.nama}</span><span class="harga">Rp ${Number(b.harga_jual).toLocaleString('id-ID')} • stok ${b.stok} ${b.satuan}</span>`
                : `<span class="placeholder">Belum ada barang dipilih</span>`
            }
        </div>
        <input type="number" name="items[${idx}][qty]" value="${qty}" min="1"
               class="qty-input" placeholder="Qty" onchange="hitungTotal()">
        <div style="display:flex;gap:6px">
            <button type="button" class="pilih-btn ${b ? 'ganti' : ''}" onclick="bukaModalPilihBarang(this.closest('.item-row'))">
                ${b ? 'Ganti' : 'Pilih Barang'}
            </button>
            <button type="button" class="remove-btn" onclick="removeRow(this)">×</button>
        </div>`;
}

function addRow(barangId = '', qty = 1) {
    const c = document.getElementById('items-container');
    const row = buatItemRow(rowIdx, barangId, qty);
    c.appendChild(row);
    rowIdx++;
    hitungTotal();
    return row;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
        hitungTotal();
    }
}

// ============ MODAL PILIH BARANG (picker) ============
function bukaModalPilihBarang(rowEl) {
    pbTargetRow = rowEl;
    pbKategoriAktif = '';
    document.getElementById('pb-search').value = '';
    renderKategoriBar();
    renderGridBarang();
    document.getElementById('modal-pilih-barang').style.display = 'flex';
    setTimeout(() => document.getElementById('pb-search').focus(), 50);
}

function tutupModalPilihBarang() {
    document.getElementById('modal-pilih-barang').style.display = 'none';
    pbTargetRow = null;
}

function renderKategoriBar() {
    const bar = document.getElementById('pb-kategori-bar');
    let html = `<div class="pb-kategori-chip ${pbKategoriAktif === '' ? 'active' : ''}" onclick="pilihKategori('')">Semua</div>`;
    kategoris.forEach(k => {
        html += `<div class="pb-kategori-chip ${pbKategoriAktif === String(k.id) ? 'active' : ''}" onclick="pilihKategori('${k.id}')">${k.nama}</div>`;
    });
    bar.innerHTML = html;
}

function pilihKategori(id) {
    pbKategoriAktif = id;
    renderKategoriBar();
    renderGridBarang();
}

function renderGridBarang() {
    const grid    = document.getElementById('pb-grid');
    const keyword = document.getElementById('pb-search').value.trim().toLowerCase();

    const filtered = Object.values(barangs).filter(b => {
        const cocokNama     = !keyword || b.nama.toLowerCase().includes(keyword);
        const cocokKategori = !pbKategoriAktif || String(b.kategori_id) === String(pbKategoriAktif);
        return cocokNama && cocokKategori;
    });

    if (filtered.length === 0) {
        grid.innerHTML = `<div class="pb-empty">Barang tidak ditemukan.</div>`;
        return;
    }

    grid.innerHTML = filtered.map(b => `
        <div class="pb-card ${b.stok <= 0 ? 'habis' : ''}" onclick="pilihBarangDariModal(${b.id})">
            <div class="pb-nama">${b.nama}</div>
            <div class="pb-harga">Rp ${Number(b.harga_jual).toLocaleString('id-ID')}</div>
            <div class="pb-stok">${b.stok <= 0 ? 'Stok habis' : 'Stok: ' + b.stok + ' ' + b.satuan}</div>
        </div>
    `).join('');
}

function pilihBarangDariModal(barangId) {
    const b = barangs[barangId];
    if (!b || b.stok <= 0 || !pbTargetRow) return;

    const qtyInput = pbTargetRow.querySelector('.qty-input');
    const qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;

    renderRowContent(pbTargetRow, barangId, qty);
    tutupModalPilihBarang();
    hitungTotal();
}

document.getElementById('pb-search').addEventListener('input', renderGridBarang);
document.getElementById('modal-pilih-barang').addEventListener('click', function(e) {
    if (e.target === this) tutupModalPilihBarang();
});

// ============ SERVIS ============
function toggleServis(id) {
    const cb   = document.getElementById('srv-' + id);
    const card = document.getElementById('sc-' + id);
    cb.checked = !cb.checked;
    card.classList.toggle('selected', cb.checked);
    hitungTotal();
}

// ============ TOTAL ============
function hitungTotal() {
    let total = 0;
    let html  = '';

    document.querySelectorAll('.item-row').forEach(row => {
        const idInput = row.querySelector('.item-id-input');
        const qty     = parseInt(row.querySelector('.qty-input').value) || 0;
        if (!idInput.value) return;
        const b = barangs[idInput.value];
        if (!b) return;
        const sub = b.harga_jual * qty;
        total += sub;
        html += `<div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span>${b.nama} ×${qty}</span>
                    <span>Rp ${sub.toLocaleString('id-ID')}</span>
                 </div>`;
    });

    document.querySelectorAll('[name="servis_ids[]"]:checked').forEach(cb => {
        const lbl   = document.querySelector(`label[for="${cb.id}"] strong`);
        const hargaEl = document.querySelector(`#sc-${cb.value} .harga`);
        const hargaNum = hargaEl ? parseInt(hargaEl.textContent.replace(/[^\d]/g,'')) : 0;
        total += hargaNum;
        html += `<div style="display:flex;justify-content:space-between;margin-bottom:4px;color:#3498db">
                    <span>⚙️ ${lbl ? lbl.textContent : ''}</span>
                    <span>Rp ${hargaNum.toLocaleString('id-ID')}</span>
                 </div>`;
    });

    document.getElementById('summary').innerHTML = html || '<em style="color:#aaa">Pilih barang / servis...</em>';
    document.getElementById('total-display').textContent = total.toLocaleString('id-ID');
}

// ============ INIT: render baris dari old('items') kalau ada ============
(function initRows() {
    const oldItems = @json(old('items', []));
    if (oldItems && oldItems.length > 0) {
        oldItems.forEach(oi => {
            if (oi && oi.id) addRow(oi.id, oi.qty || 1);
        });
    }
    if (document.querySelectorAll('.item-row').length === 0) {
        addRow(); // minimal 1 baris kosong
    }
    hitungTotal();
})();
</script>

{{-- ===================== SCRIPT MODAL SERVIS & BARANG BARU ===================== --}}
<script>
// ===== MODAL SERVIS =====
function bukaModalServis() {
    document.getElementById('modal-servis').style.display = 'flex';
    document.getElementById('ms-nama').focus();
}
function tutupModalServis() {
    document.getElementById('modal-servis').style.display = 'none';
    document.getElementById('ms-nama').value = '';
    document.getElementById('ms-harga').value = '';
    document.getElementById('ms-keterangan').value = '';
    document.getElementById('modal-servis-error').style.display = 'none';
}
async function simpanServis() {
    const nama       = document.getElementById('ms-nama').value.trim();
    const harga      = document.getElementById('ms-harga').value.trim();
    const keterangan = document.getElementById('ms-keterangan').value.trim();
    const errEl      = document.getElementById('modal-servis-error');

    if (!nama || !harga) {
        errEl.textContent = 'Nama dan harga wajib diisi.';
        errEl.style.display = 'block';
        return;
    }

    const btn = document.getElementById('btn-simpan-servis');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    try {
        const res = await fetch('{{ route("servis.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nama, harga, keterangan, aktif: true })
        });

        const data = await res.json();

        if (!res.ok) {
            errEl.textContent = data.message || 'Gagal menyimpan servis.';
            errEl.style.display = 'block';
            return;
        }

        const grid = document.getElementById('servis-grid');
        const div  = document.createElement('div');
        div.className  = 'servis-card';
        div.id         = 'sc-' + data.id;
        div.onclick    = () => toggleServis(data.id, data.harga);
        div.innerHTML  = `
            <input type="checkbox" name="servis_ids[]" value="${data.id}"
                   id="srv-${data.id}" style="width:auto" onchange="hitungTotal()">
            <label for="srv-${data.id}">
                <strong>${data.nama}</strong>
                <span class="harga">Rp ${Number(data.harga).toLocaleString('id-ID')}</span>
            </label>`;
        grid.appendChild(div);

        tutupModalServis();
    } catch(e) {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
        errEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.textContent = '💾 Simpan';
    }
}

// ===== MODAL BARANG BARU =====
function bukaModalBarangBaru() {
    document.getElementById('modal-barang').style.display = 'flex';
    document.getElementById('mb-nama').focus();
}
function tutupModalBarang() {
    document.getElementById('modal-barang').style.display = 'none';
    ['mb-kode','mb-nama','mb-satuan'].forEach(id => document.getElementById(id).value = id === 'mb-satuan' ? 'pcs' : '');
    ['mb-stok','mb-harga-beli','mb-harga-jual'].forEach(id => document.getElementById(id).value = 0);
    document.getElementById('mb-kategori').value = '';
    document.getElementById('modal-barang-error').style.display = 'none';
}
async function simpanBarang() {
    const kode       = document.getElementById('mb-kode').value.trim();
    const kategori   = document.getElementById('mb-kategori').value;
    const nama       = document.getElementById('mb-nama').value.trim();
    const stok       = document.getElementById('mb-stok').value;
    const harga_beli = document.getElementById('mb-harga-beli').value;
    const harga_jual = document.getElementById('mb-harga-jual').value;
    const satuan     = document.getElementById('mb-satuan').value.trim() || 'pcs';
    const errEl      = document.getElementById('modal-barang-error');

    if (!kode || !kategori || !nama) {
        errEl.textContent = 'Kode, kategori, dan nama barang wajib diisi.';
        errEl.style.display = 'block';
        return;
    }

    const btn = document.getElementById('btn-simpan-barang');
    btn.disabled = true;
    btn.textContent = 'Menyimpan...';

    try {
        const res = await fetch('{{ route("barang.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ kode, kategori_id: kategori, nama, stok, harga_beli, harga_jual, satuan })
        });

        const data = await res.json();

        if (!res.ok) {
            const pesanError = data.errors
                ? Object.values(data.errors).flat().join(', ')
                : (data.message || 'Gagal menyimpan barang.');
            errEl.textContent = pesanError;
            errEl.style.display = 'block';
            return;
        }

        // Tambahkan ke object barangs supaya langsung muncul di modal picker
        barangs[data.id] = data;
        renderGridBarang();

        tutupModalBarang();
    } catch(e) {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
        errEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.textContent = '💾 Simpan';
    }
}

document.getElementById('modal-servis').addEventListener('click', function(e) {
    if (e.target === this) tutupModalServis();
});
document.getElementById('modal-barang').addEventListener('click', function(e) {
    if (e.target === this) tutupModalBarang();
});
</script>
@endpush