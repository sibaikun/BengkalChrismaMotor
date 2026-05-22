@extends('layouts.app')
@section('title','Buat Nota Baru')
@push('styles')
<style>
.item-row{display:grid;grid-template-columns:2fr 1fr auto;gap:8px;align-items:center;margin-bottom:8px}
.item-row select,.item-row input{padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:13px;width:100%}
.remove-btn{padding:6px 12px;background:#e74c3c;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:16px;line-height:1;flex-shrink:0}
.servis-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-top:8px}
.servis-card{border:1.5px solid #ddd;border-radius:8px;padding:12px;cursor:pointer;transition:.15s;display:flex;align-items:center;gap:10px}
.servis-card:hover{border-color:#3498db;background:#f0f7ff}
.servis-card.selected{border-color:#27ae60;background:#eafaf1}
.servis-card label{cursor:pointer;flex:1}
.servis-card .harga{font-size:12px;color:#888;display:block;margin-top:2px}
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
                    <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}" placeholder="H 1234 AB">
                </div>
            </div>
        </div>

        {{-- Barang --}}
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <div class="card-title" style="margin:0;border:none">📦 Barang yang Dibeli</div>
                <button type="button" class="btn btn-success btn-sm" onclick="bukaModalBarang()">+ Tambah Barang Baru</button>
            </div>
            <div id="items-container">
                <div class="item-row">
                    <select name="items[0][id]" class="item-select" onchange="hitungTotal()">
                        <option value="">— Pilih Barang —</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}"
                                    data-harga="{{ $b->harga_jual }}"
                                    data-nama="{{ $b->nama }}"
                                    data-satuan="{{ $b->satuan }}">
                                {{ $b->nama }} (stok: {{ $b->stok }} {{ $b->satuan }}) — Rp {{ number_format($b->harga_jual,0,',','.') }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][qty]" value="1" min="1"
                           class="qty-input" placeholder="Qty" onchange="hitungTotal()">
                    <button type="button" class="remove-btn" onclick="removeRow(this)">×</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addRow()" style="margin-top:8px">
                + Tambah Barang Lagi
            </button>
        </div>

        {{-- Servis --}}
        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                <div class="card-title" style="margin:0;border:none">⚙️ Jasa Servis <span style="font-weight:400;color:#aaa;font-size:13px">(opsional)</span></div>
                <button type="button" class="btn btn-success btn-sm" onclick="bukaModalServis()">+ Tambah Servis Baru</button>
            </div>
            @if($servisList->isEmpty())
                <p style="color:#aaa;font-size:13px">Belum ada jasa servis. Klik tombol di atas untuk menambah.</p>
            @else
            <div class="servis-grid" id="servis-grid">
                @foreach($servisList as $s)
                <div class="servis-card" id="sc-{{ $s->id }}" onclick="toggleServis({{ $s->id }}, {{ $s->harga }})">
                    <input type="checkbox" name="servis_ids[]" value="{{ $s->id }}"
                           id="srv-{{ $s->id }}" style="width:auto" onchange="hitungTotal()">
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

@endsection
@push('scripts')
<script>
const barangs = @json($barangs->keyBy('id'));
let rowIdx = 1;

function addRow() {
    const c = document.getElementById('items-container');
    const d = document.createElement('div');
    d.className = 'item-row';
    const opts = Object.values(barangs).map(b =>
        `<option value="${b.id}" data-harga="${b.harga_jual}" data-nama="${b.nama}" data-satuan="${b.satuan}">
            ${b.nama} (stok: ${b.stok} ${b.satuan}) — Rp ${Number(b.harga_jual).toLocaleString('id-ID')}
        </option>`
    ).join('');
    d.innerHTML = `
        <select name="items[${rowIdx}][id]" class="item-select" onchange="hitungTotal()">
            <option value="">— Pilih Barang —</option>${opts}
        </select>
        <input type="number" name="items[${rowIdx}][qty]" value="1" min="1"
               class="qty-input" placeholder="Qty" onchange="hitungTotal()">
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
        const opt    = sel.options[sel.selectedIndex];
        const harga  = parseFloat(opt.dataset.harga) || 0;
        const nama   = opt.dataset.nama || '-';
        const sub    = harga * qty;
        total += sub;
        html += `<div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span>${nama} ×${qty}</span>
                    <span>Rp ${sub.toLocaleString('id-ID')}</span>
                 </div>`;
    });

    document.querySelectorAll('[name="servis_ids[]"]:checked').forEach(cb => {
        const lbl   = document.querySelector(`label[for="${cb.id}"] strong`);
        const harga = parseInt(cb.value);
        const srv   = barangs[cb.value]; // ambil harga dari servis via dataset
        // ambil harga dari elemen .harga di card
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

hitungTotal();
</script>

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

{{-- ===================== MODAL TAMBAH BARANG ===================== --}}
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

        // Tambahkan card servis baru ke grid tanpa reload
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

// ===== MODAL BARANG =====
function bukaModalBarang() {
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

        // Tambahkan barang baru ke semua dropdown yang ada
        barangs[data.id] = data;
        document.querySelectorAll('.item-select').forEach(sel => {
            const opt = document.createElement('option');
            opt.value              = data.id;
            opt.dataset.harga      = data.harga_jual;
            opt.dataset.nama       = data.nama;
            opt.dataset.satuan     = data.satuan;
            opt.textContent        = `${data.nama} (stok: ${data.stok} ${data.satuan}) — Rp ${Number(data.harga_jual).toLocaleString('id-ID')}`;
            sel.appendChild(opt);
        });

        tutupModalBarang();
    } catch(e) {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
        errEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.textContent = '💾 Simpan';
    }
}

// Tutup modal kalau klik background
document.getElementById('modal-servis').addEventListener('click', function(e) {
    if (e.target === this) tutupModalServis();
});
document.getElementById('modal-barang').addEventListener('click', function(e) {
    if (e.target === this) tutupModalBarang();
});
</script>
@endpush