{{-- resources/views/errors/503.blade.php --}}
@php
  $appName = config('app.name', 'Aplikasi');
  $supportEmail = config('mail.from.address', 'support@example.com');
  $retryAfter = request()->header('Retry-After'); // di-set saat artisan down --retry=60
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $appName }} — Sedang Pemeliharaan</title>
  <style>
    :root{
      --bg:#0f172a; --card:#0b1220; --text:#e5e7eb; --muted:#9ca3af; --accent:#22d3ee; --border:#1f2937;
      --brand: linear-gradient(135deg, #22d3ee, #a78bfa);
    }
    @media (prefers-color-scheme: light) {
      :root { --bg:#f1f5f9; --card:#ffffff; --text:#111827; --muted:#6b7280; --accent:#06b6d4; --border:#e5e7eb; }
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif;
      background: radial-gradient(1200px 800px at 20% -10%, rgba(34,211,238,.12), transparent),
                  radial-gradient(1000px 600px at 110% 10%, rgba(167,139,250,.10), transparent),
                  var(--bg);
      color:var(--text); display:flex; align-items:center; justify-content:center; padding:24px;
    }
    .card{
      width:100%; max-width:760px; background:var(--card); border:1px solid var(--border);
      border-radius:20px; box-shadow: 0 10px 40px rgba(0,0,0,.25);
      overflow:hidden; position:relative;
    }
    .ribbon{
      position:absolute; inset:0; pointer-events:none; opacity:.12;
      background: conic-gradient(from 180deg at 50% 50%, rgba(34,211,238,.9), rgba(167,139,250,.9), rgba(34,211,238,.9));
      filter: blur(80px);
    }
    header{padding:22px 24px; border-bottom:1px solid var(--border); display:flex; gap:12px; align-items:center}
    .logo{
      width:40px; height:40px; border-radius:12px; background:var(--brand); display:grid; place-items:center; color:#0b1220; font-weight:800;
      box-shadow: 0 6px 18px rgba(34,211,238,.25);
    }
    .brand{font-size:18px; font-weight:700; letter-spacing:.2px}
    main{padding:30px 24px 8px}
    h1{margin:0 0 6px; font-size:28px; line-height:1.2}
    p{margin:8px 0; color:var(--muted); line-height:1.65}
    .grid{display:grid; grid-template-columns: 1fr; gap:22px; margin-top:18px}
    @media (min-width: 860px){ .grid{ grid-template-columns: 1.15fr .85fr; } }
    .panel{
      border:1px dashed var(--border); border-radius:16px; padding:20px; background: rgba(255,255,255,.02);
    }
    .cta{display:flex; gap:12px; flex-wrap:wrap; margin-top:16px}
    .btn{
      appearance:none; border:1px solid var(--border); padding:10px 14px; border-radius:12px; cursor:pointer;
      color:var(--text); background:transparent; transition:.2s transform, .2s background;
    }
    .btn:hover{transform:translateY(-1px); background: rgba(255,255,255,.03)}
    .btn.primary{
      background: var(--brand); color:#0b1220; border:none; font-weight:700; box-shadow: 0 10px 22px rgba(34,211,238,.25);
    }
    .meta{display:flex; gap:14px; flex-wrap:wrap; margin-top:10px; color:var(--muted); font-size:13px}
    .badge{padding:6px 10px; border:1px solid var(--border); border-radius:999px; background: rgba(255,255,255,.03)}
    .progress{height:10px; background:rgba(255,255,255,.06); border-radius:999px; overflow:hidden; margin-top:14px; border:1px solid var(--border)}
    .bar{height:100%; width:0%; background: var(--brand)}
    footer{padding:16px 24px 22px; display:flex; justify-content:space-between; align-items:center; gap:10px; color:var(--muted); border-top:1px solid var(--border)}
    .mono{font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;}
    .illu{
      width:100%; aspect-ratio: 16/10; border-radius:14px; border:1px solid var(--border);
      background:
        radial-gradient(120px 120px at 20% 30%, rgba(34,211,238,.22), transparent),
        radial-gradient(140px 140px at 80% 70%, rgba(167,139,250,.20), transparent),
        linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
      display:grid; place-items:center; color:var(--muted);
    }
    .illu svg{opacity:.9}
    .sr-only{position:absolute;left:-10000px;top:auto;width:1px;height:1px;overflow:hidden}
  </style>
</head>
<body>
  <div class="card" role="alert" aria-live="polite" aria-atomic="true">
    <div class="ribbon" aria-hidden="true"></div>
    <header>
      <div class="logo" aria-hidden="true">EV</div>
      <div>
        <div class="brand">{{ $appName }}</div>
        <div class="mono" style="font-size:12px; color:var(--muted)">Status: Pemeliharaan (503)</div>
      </div>
    </header>

    <main>
      <div class="grid">
        <section>
          <h1>🚧 Sedang Update — kembali sebentar lagi</h1>
          <p>
            Terima kasih atas kesabaranmu. Tim kami sedang melakukan pemeliharaan agar layanan tetap cepat dan aman.
            Silakan coba beberapa saat lagi. @if($retryAfter) Perkiraan siap dalam <strong>{{ $retryAfter }}</strong> detik. @endif
          </p>

          <div class="panel" aria-describedby="tips">
            <div id="tips" class="sr-only">Tindakan yang bisa dilakukan pengguna</div>
            <div class="cta">
              <button class="btn primary" id="btn-retry">Muat Ulang</button>
              <button class="btn" id="btn-status">Cek Status</button>
              <a class="btn" href="mailto:{{ $supportEmail }}?subject=Pertanyaan%20Maintenance%20{{ urlencode($appName) }}">Hubungi Dukungan</a>
            </div>
            <div class="progress" aria-label="Progres menunggu">
              <div class="bar" id="bar"></div>
            </div>
            <div class="meta">
              <span class="badge">Kode: 503</span>
              <span class="badge">Aman untuk dicoba ulang</span>
              <span class="badge">Otomatis memuat ulang</span>
            </div>
          </div>
        </section>

        <aside>
          <div class="illu" aria-hidden="true">
            {{-- Ilustrasi SVG kecil --}}
            <svg width="200" height="120" viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="20" y="20" width="160" height="80" rx="12" stroke="currentColor" stroke-width="2"/>
              <circle cx="60" cy="60" r="16" stroke="currentColor" stroke-width="2"/>
              <rect x="90" y="46" width="70" height="8" rx="4" fill="currentColor" />
              <rect x="90" y="62" width="50" height="8" rx="4" fill="currentColor" />
              <g>
                <rect x="28" y="92" width="24" height="8" rx="4" fill="currentColor" opacity=".7"/>
                <rect x="58" y="92" width="24" height="8" rx="4" fill="currentColor" opacity=".5"/>
                <rect x="88" y="92" width="24" height="8" rx="4" fill="currentColor" opacity=".3"/>
              </g>
            </svg>
          </div>
        </aside>
      </div>
    </main>

    <footer>
      <span>© {{ date('Y') }} {{ $appName }}</span>
      <span class="mono">Ref: {{ substr(hash('xxh3', now()->timestamp . request()->ip()), 0, 8) }}</span>
    </footer>
  </div>

  <script>
    (function(){
      const btnRetry = document.getElementById('btn-retry');
      const btnStatus = document.getElementById('btn-status');
      const bar = document.getElementById('bar');
      const retryHeader = {{ (int)($retryAfter ?? 0) }};
      let total = retryHeader > 0 ? retryHeader : 30; // default 30 detik
      let t = 0;

      // Auto-reload saat selesai
      const tick = () => {
        t++;
        const pct = Math.min(100, Math.round((t/total)*100));
        bar.style.width = pct + '%';
        if (t >= total) { location.reload(); return; }
        window._timer = setTimeout(tick, 1000);
      };
      tick();

      btnRetry?.addEventListener('click', () => location.reload());
      btnStatus?.addEventListener('click', () => {
        // Coba HEAD ke root untuk cek sudah up/belum
        fetch(window.location.href, { method: 'HEAD', cache:'no-store' })
          .then(()=> location.reload())
          .catch(()=> alert('Masih dalam pemeliharaan. Coba sebentar lagi ya.'));
      });
    })();
  </script>
</body>
</html>
