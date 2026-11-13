<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Chrisma Motor</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:'Segoe UI',system-ui,-apple-system,sans-serif;
    min-height:100vh;
    color:#fff;
    /* GANTI dengan foto bengkelmu */
    background:#1f2937 url('{{ asset('images/bengkelcm.jpg') }}') center/cover no-repeat fixed;
    position:relative;
}

/* Lapisan gelap: lebih pekat di kiri supaya teks mudah dibaca */
body::before{
    content:'';
    position:fixed;
    inset:0;
    background:linear-gradient(90deg,rgba(0,0,0,.7) 0%,rgba(0,0,0,.45) 60%,rgba(0,0,0,.6) 100%);
}

.wrapper{
    position:relative;
    min-height:100vh;
    max-width:1100px;
    margin:0 auto;
    padding:40px 24px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:60px;
    align-items:center;
}

/* Kiri: sambutan */
.welcome h1{font-size:52px;line-height:1.1;font-weight:800;margin-bottom:18px}
.welcome p{font-size:15px;line-height:1.6;color:rgba(255,255,255,.85);max-width:380px}
.welcome .info{margin-top:22px;font-size:13px;color:rgba(255,255,255,.7)}

/* Kanan: form */
.signin{max-width:380px;width:100%;justify-self:center}
.signin h2{font-size:30px;font-weight:700;text-align:center;margin-bottom:24px}

.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:13px;margin-bottom:6px;color:rgba(255,255,255,.9)}
.form-group input{
    width:100%;
    padding:11px 14px;
    border:none;
    border-radius:4px;
    font-size:14px;
    color:#111827;
    background:#fff;
}
.form-group input:focus{outline:2px solid #f59e0b}

.remember{display:flex;align-items:center;gap:8px;font-size:13px;margin:4px 0 20px}
.remember input{width:15px;height:15px;accent-color:#d9531e}

.btn-login{
    padding:11px 24px;
    background:#d9531e;
    color:#fff;
    border:none;
    border-radius:4px;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
}
.btn-login:hover{background:#b94416}

.alert{
    background:rgba(254,226,226,.95);
    color:#991b1b;
    padding:10px 14px;
    border-radius:4px;
    font-size:13px;
    margin-bottom:16px;
}

.footer-text{margin-top:28px;font-size:12px;color:rgba(255,255,255,.6);text-align:center}

/* HP / layar kecil: ditumpuk ke bawah */
@media(max-width:800px){
    .wrapper{grid-template-columns:1fr;gap:36px;padding:48px 24px}
    .welcome h1{font-size:38px}
    body::before{background:rgba(0,0,0,.65)}
}
</style>
</head>
<body>

<div class="wrapper">
    <div class="welcome">
        <h1>Selamat Datang<br>Kembali</h1>
        <p>Sistem inventori Chrisma Motor. Kelola stok sparepart, oli, dan transaksi bengkel dengan lebih rapi dan teratur.</p>
        <div class="info">Chrisma Motor — Subah</div>
    </div>

    <div class="signin">
        <h2>Masuk</h2>

        @if($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="remember">
                <input type="checkbox" name="remember" id="rem">
                <label for="rem">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="footer-text">Chrisma Motor Subah &copy; {{ date('Y') }}</div>
    </div>
</div>

</body>
</html>