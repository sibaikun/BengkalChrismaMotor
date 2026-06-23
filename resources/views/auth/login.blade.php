<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Chrisma Motor</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#87CEEB;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative}

/* Sky gradient */
.sky{position:fixed;inset:0;background:linear-gradient(to bottom,#4a90c4 0%,#87CEEB 40%,#b8d9f0 60%,#c8a882 80%,#8B6914 100%);z-index:0}

/* Clouds */
.cloud{position:fixed;background:#fff;border-radius:50px;opacity:.85;z-index:1;animation:cloudMove linear infinite}
.cloud::before,.cloud::after{content:'';position:absolute;background:#fff;border-radius:50%}
.c1{width:90px;height:28px;top:8%;animation-duration:35s;animation-delay:-10s}
.c1::before{width:40px;height:40px;top:-20px;left:12px}
.c1::after{width:28px;height:28px;top:-14px;left:42px}
.c2{width:60px;height:18px;top:15%;animation-duration:50s;animation-delay:-25s}
.c2::before{width:26px;height:26px;top:-14px;left:8px}
.c2::after{width:20px;height:20px;top:-10px;left:28px}
.c3{width:110px;height:32px;top:5%;animation-duration:42s;animation-delay:-5s}
.c3::before{width:50px;height:50px;top:-28px;left:15px}
.c3::after{width:36px;height:36px;top:-20px;left:55px}
@keyframes cloudMove{from{left:-200px}to{left:110%}}

/* Ground */
.ground{position:fixed;bottom:0;left:0;right:0;height:38%;z-index:2}
.road{position:absolute;bottom:0;left:0;right:0;height:60px;background:#5a5a5a}
.road-line{position:absolute;bottom:28px;height:4px;background:#f0c040;width:60px}
.road-line:nth-child(1){left:5%}
.road-line:nth-child(2){left:20%}
.road-line:nth-child(3){left:35%}
.road-line:nth-child(4){left:50%}
.road-line:nth-child(5){left:65%}
.road-line:nth-child(6){left:80%}
.sidewalk{position:absolute;bottom:60px;left:0;right:0;height:18px;background:#c8b89a}
.grass{position:absolute;bottom:78px;left:0;right:0;height:calc(100% - 78px);background:#5a8a3c}

/* Tower antenna */
.tower{position:fixed;left:8%;bottom:78px;z-index:4;width:14px}
.tower-pole{width:4px;height:130px;background:#888;margin:0 auto}
.tower-top{width:2px;height:40px;background:#777;margin:0 auto}
.tower-arm{position:absolute;height:2px;background:#888}
.tower-arm1{width:30px;top:30px;left:50%;transform:translateX(-50%)}
.tower-arm2{width:20px;top:60px;left:50%;transform:translateX(-50%)}
.tower-light{width:6px;height:6px;border-radius:50%;background:#e74c3c;margin:0 auto;animation:blink 1.2s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}

/* Masjid */
.masjid{position:fixed;right:5%;bottom:78px;z-index:4;width:80px}
.masjid-body{width:80px;height:55px;background:#e8e0d0;border-top-left-radius:4px;border-top-right-radius:4px}
.masjid-dome{width:50px;height:28px;background:#d4c9a0;border-radius:50% 50% 0 0;margin:0 auto}
.masjid-minaret{width:12px;height:60px;background:#d4c9a0;position:absolute;right:6px;bottom:0}
.masjid-minaret-top{width:14px;height:10px;border-radius:50% 50% 0 0;background:#c8b880;margin-left:-1px}
.masjid-window{width:14px;height:18px;border-radius:7px 7px 0 0;background:#8baec8;position:absolute;bottom:0;left:50%;transform:translateX(-50%)}

/* Bengkel bangunan */
.bengkel-wrap{position:fixed;left:50%;transform:translateX(-50%);bottom:78px;z-index:5;width:320px}
.bengkel{position:relative;width:320px}
.bengkel-roof{width:0;height:0;border-left:80px solid transparent;border-right:80px solid transparent;border-bottom:50px solid #8B4513;position:absolute;top:-50px;left:80px}
.bengkel-roof2{width:160px;height:30px;background:#a0522d;position:absolute;top:-30px;left:80px;border-radius:2px 2px 0 0}
.bengkel-main{width:320px;height:90px;background:#d4c4a0;border-radius:3px 3px 0 0;position:relative}
.bengkel-border{position:absolute;top:0;left:0;right:0;height:6px;background:#2ecc71}
.bengkel-signboard{position:absolute;top:10px;left:50%;transform:translateX(-50%);background:#f0c040;width:160px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:3px;border:2px solid #e0a800;z-index:1}
.bengkel-signboard span{font-size:11px;font-weight:700;color:#333;letter-spacing:.5px}
.bengkel-door{width:40px;height:55px;background:#8B6914;position:absolute;bottom:0;left:50%;transform:translateX(-50%);border-radius:3px 3px 0 0;border:2px solid #6b4f0a}
.door-handle{width:5px;height:5px;border-radius:50%;background:#f0c040;position:absolute;right:7px;top:50%;transform:translateY(-50%)}
.bengkel-window{width:40px;height:30px;background:#7ab8d4;position:absolute;bottom:30px;border:2px solid #888;border-radius:2px}
.win-left{left:40px}.win-right{right:40px}
.win-cross-h{position:absolute;width:100%;height:1.5px;background:#888;top:50%;transform:translateY(-50%)}
.win-cross-v{position:absolute;height:100%;width:1.5px;background:#888;left:50%;transform:translateX(-50%)}
.bengkel-sign2{position:absolute;top:52px;right:10px;width:55px;height:22px;background:#1a6fc4;display:flex;align-items:center;justify-content:center;border-radius:2px}
.bengkel-sign2 span{font-size:8px;font-weight:700;color:#fff}

/* Motor animasi */
.motor-wrap{position:fixed;z-index:6;bottom:62px;animation:motorRide linear infinite}
.motor-enter{animation-name:motorEnter;animation-duration:5s;animation-timing-function:linear;animation-fill-mode:forwards}
.motor-park{animation-name:motorPark;animation-duration:2s;animation-timing-function:ease-out;animation-fill-mode:forwards}
.motor-leave{animation-name:motorLeave;animation-duration:5s;animation-timing-function:linear;animation-fill-mode:forwards}
@keyframes motorEnter{from{left:-120px}to{left:calc(50% - 80px)}}
@keyframes motorPark{from{left:calc(50% - 80px)}to{left:calc(50% - 30px)}}
@keyframes motorLeave{from{left:calc(50% - 30px)}to{left:110%}}

/* Orang-orangan */
.person{position:fixed;bottom:78px;z-index:7;width:14px}
.person-head{width:10px;height:10px;border-radius:50%;background:#f0c8a0;margin:0 auto}
.person-body{width:8px;height:14px;background:#3498db;margin:0 auto;border-radius:2px}
.person-legs{display:flex;justify-content:center;gap:2px}
.person-leg{width:4px;height:10px;background:#2c3e50;border-radius:0 0 2px 2px;transform-origin:top center}
.person1{left:30%;animation:walk1 4s ease-in-out infinite}
.person2{left:60%;animation:walk2 3.5s ease-in-out infinite;animation-delay:-1.5s}
.person3{right:18%;animation:walk3 5s ease-in-out infinite;animation-delay:-2s}
@keyframes walk1{0%,100%{transform:translateX(0)}50%{transform:translateX(30px)}}
@keyframes walk2{0%,100%{transform:translateX(0)}50%{transform:translateX(-20px)}}
@keyframes walk3{0%,100%{transform:translateX(0)}50%{transform:translateX(15px)}}
.person-leg.left{animation:legL .4s ease-in-out infinite alternate}
.person-leg.right{animation:legR .4s ease-in-out infinite alternate}
@keyframes legL{from{transform:rotate(-20deg)}to{transform:rotate(20deg)}}
@keyframes legR{from{transform:rotate(20deg)}to{transform:rotate(-20deg)}}

/* Mekanik di depan bengkel */
.mechanic{position:fixed;bottom:78px;left:calc(50% + 40px);z-index:8;width:14px;animation:mechanicWork 2s ease-in-out infinite}
@keyframes mechanicWork{0%,100%{transform:translateY(0)}50%{transform:translateY(-3px)}}

/* Login card */
.login-card{position:relative;z-index:20;background:rgba(255,255,255,.95);border-radius:14px;padding:36px 32px;width:100%;max-width:360px;box-shadow:0 8px 40px rgba(0,0,0,.25);backdrop-filter:blur(4px)}
.brand{text-align:center;margin-bottom:24px}
.brand .icon{font-size:38px;margin-bottom:6px}
.brand h1{font-size:21px;font-weight:700;color:#1a1a2e}
.brand p{font-size:12px;color:#888;margin-top:3px}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:13px;font-weight:600;color:#444;margin-bottom:4px}
.form-group input{width:100%;padding:9px 13px;border:1.5px solid #ddd;border-radius:8px;font-size:14px;transition:.15s;background:#fafafa}
.form-group input:focus{outline:none;border-color:#3498db;box-shadow:0 0 0 3px rgba(52,152,219,.15);background:#fff}
.remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#666;margin-bottom:18px}
.remember input{width:auto}
.btn-login{width:100%;padding:11px;background:#1a1a2e;color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:600;cursor:pointer;transition:.15s}
.btn-login:hover{background:#16213e;transform:translateY(-1px)}
.btn-login:active{transform:translateY(0)}
.alert{background:#f8d7da;color:#721c24;padding:10px 13px;border-radius:6px;font-size:13px;margin-bottom:14px;border:1px solid #f5c6cb}
.footer-text{text-align:center;margin-top:16px;font-size:11px;color:#aaa}
</style>
</head>
<body>

<div class="sky"></div>

<!-- Awan -->
<div class="cloud c1"></div>
<div class="cloud c2"></div>
<div class="cloud c3"></div>

<!-- Ground layers -->
<div class="ground">
    <div class="grass"></div>
    <div class="sidewalk"></div>
    <div class="road">
        <div class="road-line"></div>
        <div class="road-line"></div>
        <div class="road-line"></div>
        <div class="road-line"></div>
        <div class="road-line"></div>
        <div class="road-line"></div>
    </div>
</div>

<!-- Menara antena -->
<div class="tower">
    <div class="tower-light"></div>
    <div class="tower-top"></div>
    <div class="tower-arm tower-arm1"></div>
    <div class="tower-arm tower-arm2"></div>
    <div class="tower-pole"></div>
</div>

<!-- Masjid -->
<div class="masjid" style="position:fixed;right:5%;bottom:78px;z-index:4">
    <div class="masjid-minaret" style="position:absolute;right:6px;bottom:55px">
        <div class="masjid-minaret-top"></div>
    </div>
    <div class="masjid-dome"></div>
    <div class="masjid-body">
        <div class="masjid-window"></div>
    </div>
</div>

<!-- Bengkel Chrisma Motor -->
<div class="bengkel-wrap">
    <div class="bengkel">
        <div class="bengkel-roof"></div>
        <div class="bengkel-roof2"></div>
        <div class="bengkel-main">
            <div class="bengkel-border"></div>
            <div class="bengkel-signboard"><span>CHRISMA MOTOR</span></div>
            <div class="bengkel-sign2"><span>FEDERAL OIL</span></div>
            <div class="bengkel-window win-left">
                <div class="win-cross-h"></div>
                <div class="win-cross-v"></div>
            </div>
            <div class="bengkel-window win-right">
                <div class="win-cross-h"></div>
                <div class="win-cross-v"></div>
            </div>
            <div class="bengkel-door">
                <div class="door-handle"></div>
            </div>
        </div>
    </div>
</div>

<!-- Motor datang servis lalu pergi -->
<div class="motor-wrap" id="motor" style="bottom:62px">
    <svg width="80" height="40" viewBox="0 0 80 40">
        <!-- badan motor -->
        <ellipse cx="40" cy="22" rx="28" ry="10" fill="#e74c3c"/>
        <!-- setang -->
        <rect x="55" y="12" width="14" height="3" rx="1.5" fill="#555"/>
        <rect x="66" y="10" width="3" height="8" rx="1.5" fill="#555"/>
        <!-- jok -->
        <rect x="28" y="12" width="22" height="6" rx="3" fill="#c0392b"/>
        <!-- knalpot -->
        <rect x="10" y="24" width="18" height="4" rx="2" fill="#888"/>
        <!-- roda depan -->
        <circle cx="62" cy="32" r="8" fill="#2c3e50" stroke="#1a252f" stroke-width="1.5"/>
        <circle cx="62" cy="32" r="3" fill="#95a5a6"/>
        <!-- roda belakang -->
        <circle cx="18" cy="32" r="8" fill="#2c3e50" stroke="#1a252f" stroke-width="1.5"/>
        <circle cx="18" cy="32" r="3" fill="#95a5a6"/>
        <!-- orang di motor -->
        <circle cx="44" cy="8" r="6" fill="#f0c8a0"/>
        <rect x="40" y="14" width="10" height="10" rx="2" fill="#3498db"/>
        <!-- helm -->
        <ellipse cx="44" cy="7" rx="7" ry="6" fill="#e74c3c" opacity=".85"/>
        <ellipse cx="44" cy="9" rx="5" ry="2.5" fill="#f9e4b7" opacity=".9"/>
    </svg>
</div>

<!-- Asap knalpot -->
<div id="smoke-wrap" style="position:fixed;bottom:75px;z-index:6;display:none">
    <div id="smoke1" style="position:absolute;width:8px;height:8px;border-radius:50%;background:rgba(200,200,200,.7);animation:smokeAnim 1s ease-out infinite"></div>
    <div id="smoke2" style="position:absolute;width:6px;height:6px;border-radius:50%;background:rgba(200,200,200,.5);animation:smokeAnim 1s ease-out infinite;animation-delay:.3s"></div>
</div>
<style>
@keyframes smokeAnim{0%{transform:translateY(0) scale(1);opacity:.7}100%{transform:translateY(-20px) scale(2.5);opacity:0}}
</style>

<!-- Orang-orangan berjalan -->
<div class="person person1">
    <div class="person-head"></div>
    <div class="person-body"></div>
    <div class="person-legs">
        <div class="person-leg left"></div>
        <div class="person-leg right"></div>
    </div>
</div>
<div class="person person2" style="background:transparent">
    <div class="person-head" style="background:#d4a080"></div>
    <div class="person-body" style="background:#e67e22"></div>
    <div class="person-legs">
        <div class="person-leg left" style="background:#1a252f"></div>
        <div class="person-leg right" style="background:#1a252f"></div>
    </div>
</div>
<div class="person person3" style="background:transparent">
    <div class="person-head" style="background:#e8b89a"></div>
    <div class="person-body" style="background:#27ae60"></div>
    <div class="person-legs">
        <div class="person-leg left" style="background:#1a252f"></div>
        <div class="person-leg right" style="background:#1a252f"></div>
    </div>
</div>

<!-- Traffic light kanan -->
<div style="position:fixed;right:2%;bottom:78px;z-index:10;display:flex;flex-direction:column;align-items:center">
    <div style="width:4px;height:80px;background:#444;margin:0 auto"></div>
    <div style="width:22px;background:#222;border-radius:4px;padding:4px;display:flex;flex-direction:column;gap:4px;position:absolute;top:0">
        <div id="tl-red"   style="width:14px;height:14px;border-radius:50%;background:#e74c3c;margin:0 auto;box-shadow:0 0 6px #e74c3c"></div>
        <div id="tl-yellow" style="width:14px;height:14px;border-radius:50%;background:#333;margin:0 auto"></div>
        <div id="tl-green"  style="width:14px;height:14px;border-radius:50%;background:#333;margin:0 auto"></div>
    </div>
</div>

<!-- Traffic light kiri -->
<div style="position:fixed;left:2%;bottom:78px;z-index:10;display:flex;flex-direction:column;align-items:center">
    <div style="width:4px;height:80px;background:#444;margin:0 auto"></div>
    <div style="width:22px;background:#222;border-radius:4px;padding:4px;display:flex;flex-direction:column;gap:4px;position:absolute;top:0">
        <div id="tl-red2"    style="width:14px;height:14px;border-radius:50%;background:#333;margin:0 auto"></div>
        <div id="tl-yellow2" style="width:14px;height:14px;border-radius:50%;background:#333;margin:0 auto"></div>
        <div id="tl-green2"  style="width:14px;height:14px;border-radius:50%;background:#27ae60;margin:0 auto;box-shadow:0 0 6px #27ae60"></div>
    </div>
</div>

<!-- Mekanik depan bengkel -->
<div class="mechanic">
    <div class="person-head" style="background:#f0c8a0;width:10px;height:10px;border-radius:50%;margin:0 auto"></div>
    <div class="person-body" style="background:#e74c3c;width:8px;height:14px;margin:0 auto;border-radius:2px"></div>
    <div class="person-legs" style="display:flex;justify-content:center;gap:2px">
        <div class="person-leg left" style="width:4px;height:10px;background:#2c3e50;border-radius:0 0 2px 2px"></div>
        <div class="person-leg right" style="width:4px;height:10px;background:#2c3e50;border-radius:0 0 2px 2px"></div>
    </div>
</div>

<!-- Login Card -->
<div class="login-card">
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
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="admin@chrismamotor.com" required autofocus>
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
    <div class="footer-text">Chrisma Motor Subah &copy; {{ date('Y') }}</div>
</div>

<script>
const motor = document.getElementById('motor');
const smokeWrap = document.getElementById('smoke-wrap');

function runMotorCycle() {
    motor.style.transition = 'none';
    motor.style.left = '-120px';
    motor.style.transform = 'scaleX(1)';
    smokeWrap.style.display = 'none';

    // Fase 1: motor datang dari kiri
    setTimeout(() => {
        motor.style.transition = 'left 4s linear';
        motor.style.left = 'calc(50% - 90px)';
    }, 200);

    // Fase 2: parkir, asap mati
    setTimeout(() => {
        motor.style.transition = 'left 1s ease-out';
        motor.style.left = 'calc(50% - 60px)';
        smokeWrap.style.display = 'block';
        smokeWrap.style.left = 'calc(50% - 120px)';
    }, 4500);

    // Fase 3: diam (servis)
    setTimeout(() => {
        smokeWrap.style.display = 'none';
    }, 8000);

    // Fase 4: motor pergi ke kanan
    setTimeout(() => {
        motor.style.transform = 'scaleX(-1)';
        motor.style.transition = 'left 4s linear';
        motor.style.left = '110%';
    }, 9500);

    // Ulang siklus
    setTimeout(runMotorCycle, 15000);
}

runMotorCycle();

// Traffic light
const lights = {
    red:    document.getElementById('tl-red'),
    yellow: document.getElementById('tl-yellow'),
    green:  document.getElementById('tl-green'),
    red2:    document.getElementById('tl-red2'),
    yellow2: document.getElementById('tl-yellow2'),
    green2:  document.getElementById('tl-green2'),
};

const off = '#333';
function setLight(phase) {
    lights.red.style.background    = off; lights.red.style.boxShadow    = 'none';
    lights.yellow.style.background = off; lights.yellow.style.boxShadow = 'none';
    lights.green.style.background  = off; lights.green.style.boxShadow  = 'none';
    lights.red2.style.background    = off; lights.red2.style.boxShadow    = 'none';
    lights.yellow2.style.background = off; lights.yellow2.style.boxShadow = 'none';
    lights.green2.style.background  = off; lights.green2.style.boxShadow  = 'none';

    if (phase === 'red') {
        lights.red.style.background = '#e74c3c'; lights.red.style.boxShadow = '0 0 6px #e74c3c';
        lights.green2.style.background = '#27ae60'; lights.green2.style.boxShadow = '0 0 6px #27ae60';
    } else if (phase === 'yellow') {
        lights.yellow.style.background = '#f39c12'; lights.yellow.style.boxShadow = '0 0 6px #f39c12';
        lights.yellow2.style.background = '#f39c12'; lights.yellow2.style.boxShadow = '0 0 6px #f39c12';
    } else if (phase === 'green') {
        lights.green.style.background = '#27ae60'; lights.green.style.boxShadow = '0 0 6px #27ae60';
        lights.red2.style.background = '#e74c3c'; lights.red2.style.boxShadow = '0 0 6px #e74c3c';
    }
}

function trafficCycle() {
    setLight('red');
    setTimeout(() => setLight('yellow'), 4000);
    setTimeout(() => setLight('green'),  5000);
    setTimeout(() => setLight('yellow'), 9000);
    setTimeout(() => { setLight('red'); trafficCycle(); }, 10000);
}
trafficCycle();
</script>
</body>
</html>