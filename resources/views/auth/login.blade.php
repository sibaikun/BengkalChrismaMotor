<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Chrisma Motor</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);min-height:100vh;display:flex;align-items:center;justify-content:center}
.card{background:#fff;border-radius:14px;padding:40px 36px;width:100%;max-width:380px;box-shadow:0 20px 60px rgba(0,0,0,.4)}
.brand{text-align:center;margin-bottom:28px}
.brand .icon{font-size:42px;margin-bottom:8px}
.brand h1{font-size:22px;font-weight:700;color:#1a1a2e}
.brand p{font-size:13px;color:#888;margin-top:4px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:5px}
.form-group input{width:100%;padding:10px 14px;border:1.5px solid #ddd;border-radius:8px;font-size:14px;transition:.15s}
.form-group input:focus{outline:none;border-color:#3498db;box-shadow:0 0 0 3px rgba(52,152,219,.15)}
.remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#666;margin-bottom:20px}
.remember input{width:auto}
.btn-login{width:100%;padding:12px;background:#1a1a2e;color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:600;cursor:pointer;transition:.15s}
.btn-login:hover{background:#16213e}
.alert{background:#f8d7da;color:#721c24;padding:10px 14px;border-radius:6px;font-size:13px;margin-bottom:16px}
.footer{text-align:center;margin-top:20px;font-size:12px;color:#aaa}
</style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="icon">🔧</div>
        <h1>Chrisma Motor</h1>
        <p>Sistem Inventori Bengkel — Subah</p>
    </div>

    @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@chrismamotor.com" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <div class="remember">
            <input type="checkbox" name="remember" id="rem">
            <label for="rem">Ingat saya</label>
        </div>
        <button type="submit" class="btn-login">🔐 Masuk</button>
    </form>
    <div class="footer">Chrisma Motor Subah &copy; {{ date('Y') }}</div>
</div>
</body>
</html>