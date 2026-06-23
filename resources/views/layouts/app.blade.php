<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Dashboard') — Chrisma Motor</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f2f5;color:#333;display:flex;min-height:100vh}

/* ── Sidebar Ceramic ── */
.sidebar{
    width:240px;
    background:#f5efe6;
    color:#4a3f35;
    display:flex;
    flex-direction:column;
    flex-shrink:0;
    min-height:100vh;
    border-right:1px solid #e0d5c8;
}
.sidebar-brand{
    padding:20px 16px;
    font-size:16px;
    font-weight:700;
    color:#3b2f24;
    border-bottom:1px solid #e0d5c8;
    background:#ede4d8;
}
.sidebar-brand small{
    display:block;
    font-size:11px;
    font-weight:400;
    color:#9c8878;
    margin-top:2px;
}

.sidebar nav a{
    display:flex;
    align-items:center;
    gap:10px;
    padding:11px 18px;
    color:#6b5a4e;
    text-decoration:none;
    font-size:14px;
    transition:all .2s ease;
    border-left:3px solid transparent;
    position:relative;
    overflow:hidden;
}
.sidebar nav a:hover{
    background:linear-gradient(135deg,rgba(255,255,255,.55) 0%,rgba(220,200,180,.35) 50%,rgba(255,255,255,.2) 100%);
    color:#3b2f24;
    border-left-color:#c4956a;
    backdrop-filter:blur(4px);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.7),inset 0 -1px 0 rgba(180,140,100,.15);
}
.sidebar nav a.active{
    background:linear-gradient(135deg,rgba(255,255,255,.75) 0%,rgba(230,210,185,.6) 40%,rgba(255,252,248,.5) 100%);
    color:#3b2f24;
    font-weight:600;
    border-left-color:#a0704a;
    backdrop-filter:blur(6px);
    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.9),
        inset 0 -1px 0 rgba(160,112,74,.2),
        inset 1px 0 0 rgba(255,255,255,.6),
        0 2px 8px rgba(160,112,74,.12);
}
/* kilap shimmer di active */
.sidebar nav a.active::before{
    content:'';
    position:absolute;
    top:0;left:0;right:0;
    height:40%;
    background:linear-gradient(180deg,rgba(255,255,255,.45) 0%,rgba(255,255,255,0) 100%);
    border-radius:0 0 50% 50%;
    pointer-events:none;
}
/* kilap highlight hover */
.sidebar nav a:hover::before{
    content:'';
    position:absolute;
    top:0;left:0;right:0;
    height:40%;
    background:linear-gradient(180deg,rgba(255,255,255,.3) 0%,rgba(255,255,255,0) 100%);
    pointer-events:none;
}
.sidebar nav a .ico{font-size:16px;width:20px;text-align:center}
.sidebar nav .group-label{
    padding:14px 18px 4px;
    font-size:10px;
    text-transform:uppercase;
    color:#b09a8a;
    letter-spacing:.08em;
    font-weight:600;
}

.sidebar-footer{margin-top:auto;padding:16px;border-top:1px solid #e0d5c8}
.sidebar-footer form button{
    width:100%;
    padding:9px;
    background:#c0392b;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:13px;
    transition:.15s;
}
.sidebar-footer form button:hover{background:#a93226}

/* ── Main ── */
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.topbar{
    background:#faf6f1;
    padding:12px 24px;
    border-bottom:1px solid #e0d5c8;
    display:flex;
    align-items:center;
    gap:12px;
}
.topbar h1{font-size:18px;font-weight:600;color:#3b2f24;flex:1}
.topbar .user{font-size:13px;color:#9c8878;background:#ede4d8;padding:5px 12px;border-radius:20px}
.content{padding:24px;flex:1;overflow-y:auto}

/* ── Cards ── */
.card{background:#fff;border-radius:10px;padding:20px;margin-bottom:20px;box-shadow:0 1px 4px rgba(0,0,0,.06);border:1px solid #ede4d8}
.card-title{font-size:15px;font-weight:600;margin-bottom:16px;color:#3b2f24;padding-bottom:8px;border-bottom:1px solid #f0e8df}

/* ── Stat cards ── */
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px}
.stat{background:#fff;border-radius:10px;padding:18px;box-shadow:0 1px 4px rgba(0,0,0,.06);border-left:4px solid #c4956a;border-top:1px solid #ede4d8;border-right:1px solid #ede4d8;border-bottom:1px solid #ede4d8}
.stat.green{border-left-color:#6aaa7a}
.stat.orange{border-left-color:#d4845a}
.stat.red{border-left-color:#c0574a}
.stat .val{font-size:26px;font-weight:700;color:#3b2f24}
.stat .lbl{font-size:12px;color:#9c8878;margin-top:4px}

/* ── Table ── */
table{width:100%;border-collapse:collapse;font-size:14px}
thead th{background:#faf0e8;padding:10px 12px;text-align:left;font-weight:600;color:#6b5a4e;border-bottom:2px solid #e0d5c8}
tbody td{padding:10px 12px;border-bottom:1px solid #f5efe6;vertical-align:middle}
tbody tr:hover{background:#fdf8f4}

/* ── Buttons ── */
.btn{display:inline-block;padding:8px 16px;border-radius:6px;font-size:13px;text-decoration:none;border:none;cursor:pointer;font-weight:500;transition:.15s}
.btn-primary{background:#3498db;color:#fff}.btn-primary:hover{background:#2980b9}
.btn-success{background:#27ae60;color:#fff}.btn-success:hover{background:#219a52}
.btn-warning{background:#e67e22;color:#fff}.btn-warning:hover{background:#d35400}
.btn-danger{background:#e74c3c;color:#fff}.btn-danger:hover{background:#c0392b}
.btn-secondary{background:#95a5a6;color:#fff}.btn-secondary:hover{background:#7f8c8d}
.btn-sm{padding:5px 10px;font-size:12px}

/* ── Forms ── */
.form-group{margin-bottom:16px}
.form-group label{display:block;margin-bottom:5px;font-size:13px;font-weight:500;color:#6b5a4e}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:inherit;transition:.15s;background:#fdfaf7}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#c4956a;box-shadow:0 0 0 3px rgba(196,149,106,.15)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-row.three{grid-template-columns:1fr 1fr 1fr}

/* ── Alerts ── */
.alert{padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:14px}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
.alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
.alert-warning{background:#fff3cd;color:#856404;border:1px solid #ffeeba}

/* ── Badge ── */
.badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
.badge-success{background:#d4edda;color:#155724}
.badge-danger{background:#f8d7da;color:#721c24}
.badge-warning{background:#fff3cd;color:#856404}

/* ── Pagination ── */
.pagination{margin-top:16px;display:flex;gap:4px;flex-wrap:wrap}
.pagination a,.pagination span{padding:6px 12px;border:1px solid #e0d5c8;border-radius:5px;font-size:13px;text-decoration:none;color:#6b5a4e}
.pagination a:hover{background:#f5efe6}
.pagination .active span{background:#a0704a;color:#fff;border-color:#a0704a}

@media print{.sidebar,.topbar,.no-print{display:none!important}.main{overflow:visible}}
</style>
@stack('styles')
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        🔧 Chrisma Motor
        <small>Subah, Batang</small>
    </div>
    <nav>
        <div class="group-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active':'' }}">
            <span class="ico">📊</span> Dashboard
        </a>

        <div class="group-label">Inventori</div>
        <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active':'' }}">
            <span class="ico">🏷️</span> Kategori
        </a>
        <a href="{{ route('barang.index') }}"
           class="{{ request()->routeIs('barang.index','barang.create','barang.edit','barang.store') ? 'active':'' }}">
            <span class="ico">📦</span> Stok Barang
        </a>

        <div class="group-label">Pengaturan</div>
        <a href="{{ route('servis.index') }}" class="{{ request()->routeIs('servis.*') ? 'active':'' }}">
            <span class="ico">⚙️</span> Jasa Servis
        </a>

        <div class="group-label">Transaksi</div>
        <a href="{{ route('nota.create') }}" class="{{ request()->routeIs('nota.create') ? 'active':'' }}">
            <span class="ico">➕</span> Buat Nota
        </a>
        <a href="{{ route('nota.index') }}" class="{{ request()->routeIs('nota.index') ? 'active':'' }}">
            <span class="ico">🧾</span> Riwayat Nota
        </a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <h1>@yield('title','Dashboard')</h1>
        <span class="user">👤 {{ auth()->user()->name }}</span>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>