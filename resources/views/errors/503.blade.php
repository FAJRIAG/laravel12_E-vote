{{-- resources/views/errors/503.blade.php --}}
@php
  $appName     = config('app.name', 'Aplikasi');
  $supportEmail= config('mail.from.address', 'support@example.com');
  $retryAfter  = (int) request()->header('Retry-After', 30); // default 30s
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $appName }} — Sedang Pemeliharaan</title>
  <meta name="robots" content="noindex">
  <style>
    :root{
      --bg: #f7f7f8; --fg:#0f172a; --muted:#5b6472; --card:#ffffff; --line:#e6e7eb;
      --brand:#3b82f6; --brand-2:#0ea5e9; --shadow: 0 10px 30px rgba(15,23,42,.06);
    }
    @media (prefers-color-scheme: dark){
      :root{
        --bg:#0b1220; --fg:#e5e7eb; --muted:#9aa4b2; --card:#0f172a; --line:#1e293b;
        --brand:#60a5fa; --brand-2:#38bdf8; --shadow: 0 14px 40px rgba(0,0,0,.35);
      }
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
      color:var(--fg); background:
        radial-gradient(48rem 28rem at 10% -10%, rgba(59,130,246,.10), transparent),
        radial-gradient(38rem 24rem at 110% 10%, rgba(14,165,233,.10), transparent),
        var(--bg);
      display:flex; align-items:center; justify-content:center; padding:24px;
    }
    .wrap{width:100%; max-width:760px; background:var(--card); border:1px solid var(--line); border-radius:18px; box-shadow:var(--shadow); overflow:hidden}
    header, footer{padding:18px 22px; display:flex; align-items:center; justify-content:space-between; gap:12px; border-bottom:1px solid var(--line)}
    footer{border-top:1px solid var(--line); border-bottom:none}
    .brand{display:flex; align-items:center; gap:12px}
    .logo{width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg, var(--brand), var(--brand-2)); display:grid; place-items:center; color:#fff; font-weight:800; letter-spacing:.5px}
    main{padding:28px 22px}
    h1{margin:0 0 6px; font-size:26px; line-height:1.2}
    p{margin:8px 0; color:var(--muted); line-height:1.65}
    .panel{margin-top:18px; border:1px solid var(--line); border-radius:14px; padding:16px}
    .row{display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap}
    .count{font-size:40px; font-weight:700; letter-spacing:.5px}
    .sub{font-size:12px; color:var(--muted)}
    .actions{display:flex; gap:10px; flex-wrap:wrap}
    .btn{
      appearance:none; border:1px solid var(--line); background:#fff0; color:var(--fg);
      padding:10px 14px; border-radius:12px; cursor:pointer; transition:.18s ease;
    }
    .btn:hover{transform:translateY(-1px); background:rgba(0,0,0,.03)}
    .btn.primary{
      background:linear-gradient(135deg, var(--brand), var(--brand-2)); color:#fff; border:none; font-weight:600;
    }
    .bar{height:10px; width:100%; background:rgba(0,0,0,.06); border:1px solid var(--line); border-radius:999px; overflow:hidden; margin-top:14px}
    .bar>i{display:block; height:100%; width:0%; background:linear-gradient(90deg, var(--brand), var(--brand-2)); transition:width .35s ease}
    .muted{color:var(--muted)}
    .mono{font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace}
    .foot-note{font-size:12px}
  </style>
</head>
<body>
  <div class="wrap" role="alert" aria-live="polite" aria-atomic="true">
    <header>
      <div class="brand">
        <div class="logo" aria-hidden="true">EV</div>
        <div>
          <div style="font-weight:700">{{ $appName }}</div>
          <div class="sub">Sedang pemeliharaan • Kode status 503</div>
        </div>
      </div>
      <div class="sub">Terima kasih atas kesabaran Anda</div>
    </header>

    <main>
      <h1>Perbaikan kecil, manfaat besar</h1>
      <p>
        Kami sedang melakukan pemeliharaan agar layanan tetap cepat dan andal. Silakan coba lagi dalam
        @if($retryAfter > 0)
          <strong>{{ $retryAfter }} detik</strong>.
        @else
          beberapa saat.
        @endif
      </p>

      <div class="panel" aria-describedby="tips">
        <div class="row">
          <div>
            <div class="sub">Coba otomatis</div>
            <div id="count" class="count">—</div>
          </div>
          <div class="actions">
            <button id="btn-refresh" class="btn primary">Muat ulang</button>
            <a href="/" class="btn" aria-label="Ke beranda">Beranda</a>
            <a class="btn" href="mailto:{{ $supportEmail }}?subject=Pertanyaan%20Maintenance%20{{ urlencode($appName) }}">Dukungan</a>
          </div>
        </div>
        <div class="bar" aria-label="Progres menunggu">
          <i id="bar"></i>
        </div>
        <div class="sub" style="margin-top:10px">
          Halaman akan memuat ulang otomatis saat waktu habis.
        </div>
      </div>
    </main>

    <footer>
      <span class="foot-note">© {{ date('Y') }} {{ $appName }}</span>
      <span class="foot-note mono">Ref: {{ substr(hash('xxh3', now()->timestamp . request()->ip()), 0, 8) }}</span>
    </footer>
  </div>

  <script>
    (function(){
      const total = {{ max($retryAfter, 10) }}; // minimal 10s agar progres terasa
      let t = total;
      const label = document.getElementById('count');
      const bar   = document.getElementById('bar');
      const btn   = document.getElementById('btn-refresh');

      function fmt(sec){
        if (sec < 60) return sec + 's';
        const m = Math.floor(sec/60), s = sec % 60;
        return m + 'm ' + s + 's';
      }
      function tick(){
        label.textContent = fmt(t);
        const pct = Math.round(((total - t) / total) * 100);
        bar.style.width = Math.min(100, Math.max(0, pct)) + '%';
        if (t <= 0) { location.reload(); return; }
        t--; setTimeout(tick, 1000);
      }
      tick();

      btn?.addEventListener('click', () => location.reload());
    })();
  </script>
</body>
</html>
