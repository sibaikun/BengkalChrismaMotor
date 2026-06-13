<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title','Dashboard') — Chrisma Motor</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#f0f2f5;color:#333;display:flex;min-height:100vh}

/* Sidebar */
.sidebar{width:240px;background:#1a1a2e;color:#ccc;display:flex;flex-direction:column;flex-shrink:0;min-height:100vh}
.sidebar-brand{padding:20px 16px;font-size:16px;font-weight:700;color:#fff;border-bottom:1px solid #2d2d4e}
.sidebar-brand small{display:block;font-size:11px;font-weight:400;color:#888;margin-top:2px}
.sidebar nav a{display:flex;align-items:center;gap:10px;padding:11px 18px;color:#aaa;text-decoration:none;font-size:14px;transition:background .15s,color .15s}
.sidebar nav a:hover,.sidebar nav a.active{background:#16213e;color:#fff}
.sidebar nav a .ico{font-size:16px;width:20px;text-align:center}
.sidebar nav .group-label{padding:14px 18px 4px;font-size:11px;text-transform:uppercase;color:#555;letter-spacing:.06em}
.sidebar-footer{margin-top:auto;padding:16px}
.sidebar-footer form button{width:100%;padding:9px;background:#c0392b;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px}

/* Main */
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.topbar{background:#fff;padding:12px 24px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px}
.topbar h1{font-size:18px;font-weight:600;color:#1a1a2e;flex:1}
.topbar .user{font-size:13px;color:#666}
.content{padding:24px;flex:1;overflow-y:auto}

/* Cards */
.card{background:#fff;border-radius:10px;padding:20px;margin-bottom:20px;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.card-title{font-size:15px;font-weight:600;margin-bottom:16px;color:#1a1a2e;padding-bottom:8px;border-bottom:1px solid #f0f0f0}

/* Stat cards */
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px}
.stat{background:#fff;border-radius:10px;padding:18px;box-shadow:0 1px 4px rgba(0,0,0,.06);border-left:4px solid #3498db}
.stat.green{border-color:#27ae60}.stat.orange{border-color:#e67e22}.stat.red{border-color:#e74c3c}
.stat .val{font-size:26px;font-weight:700;color:#1a1a2e}
.stat .lbl{font-size:12px;color:#888;margin-top:4px}

/* Table */
table{width:100%;border-collapse:collapse;font-size:14px}
thead th{background:#f8f9fa;padding:10px 12px;text-align:left;font-weight:600;color:#555;border-bottom:2px solid #e5e7eb}
tbody td{padding:10px 12px;border-bottom:1px solid #f0f0f0;vertical-align:middle}
tbody tr:hover{background:#fafafa}

/* Buttons */
.btn{display:inline-block;padding:8px 16px;border-radius:6px;font-size:13px;text-decoration:none;border:none;cursor:pointer;font-weight:500;transition:.15s}
.btn-primary{background:#3498db;color:#fff}.btn-primary:hover{background:#2980b9}
.btn-success{background:#27ae60;color:#fff}.btn-success:hover{background:#219a52}
.btn-warning{background:#e67e22;color:#fff}.btn-warning:hover{background:#d35400}
.btn-danger{background:#e74c3c;color:#fff}.btn-danger:hover{background:#c0392b}
.btn-secondary{background:#95a5a6;color:#fff}.btn-secondary:hover{background:#7f8c8d}
.btn-sm{padding:5px 10px;font-size:12px}

/* Forms */
.form-group{margin-bottom:16px}
.form-group label{display:block;margin-bottom:5px;font-size:13px;font-weight:500;color:#555}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;font-family:inherit;transition:.15s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#3498db;box-shadow:0 0 0 3px rgba(52,152,219,.15)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-row.three{grid-template-columns:1fr 1fr 1fr}

/* Alerts */
.alert{padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:14px}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
.alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
.alert-warning{background:#fff3cd;color:#856404;border:1px solid #ffeeba}

/* Badge */
.badge{display:inline-block;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600}
.badge-success{background:#d4edda;color:#155724}
.badge-danger{background:#f8d7da;color:#721c24}
.badge-warning{background:#fff3cd;color:#856404}

/* Pagination */
.pagination{margin-top:16px;display:flex;gap:4px;flex-wrap:wrap}
.pagination a,.pagination span{padding:6px 12px;border:1px solid #ddd;border-radius:5px;font-size:13px;text-decoration:none;color:#333}
.pagination .active span{background:#3498db;color:#fff;border-color:#3498db}

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
        {{-- <a href="{{ route('barang.ringkasan') }}" 
        class="{{ request()->routeIs('barang.ringkasan') ? 'active':'' }}">
            <span class="ico">📊</span> Ringkasan Harga
        </a> --}}

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