{{-- resources/views/errors/503.blade.php --}}
@php
  $appName     = config('app.name', 'Aplikasi');
  $supportEmail= config('mail.from.address', 'support@example.com');
  $retryAfter  = (int) request()->header('Retry-After', 30); // default 30s
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full antialiased">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>{{ $appName }} — Sedang Pemeliharaan</title>

  {{-- Tailwind CDN: aman untuk halaman statis maintenance --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // warna kustom halus
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ink:   { DEFAULT: '#0f172a' },
            mist:  { DEFAULT: '#f8fafc' },
            accent:{ DEFAULT: '#6366f1' },
            aqua:  { DEFAULT: '#22d3ee' },
          }
        }
      }
    }
  </script>

  <style>
    :root { color-scheme: light dark; }
    .glass {
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      background: rgba(255,255,255,.75);
    }
    @media (prefers-color-scheme: dark) {
      .glass { background: rgba(17,24,39,.55); }
    }
  </style>
</head>
<body class="h-full bg-gradient-to-br from-mist to-white dark:from-ink dark:to-slate-900 text-slate-800 dark:text-slate-100">

  {{-- dekorasi halus --}}
  <div class="pointer-events-none fixed inset-0 overflow-hidden">
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-aqua/20 blur-3xl"></div>
    <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-accent/20 blur-3xl"></div>
  </div>

  <main class="relative min-h-full flex items-center justify-center px-4 py-12">
    <section class="glass w-full max-w-2xl rounded-2xl shadow-xl ring-1 ring-black/5 dark:ring-white/10">
      {{-- header --}}
      <div class="flex items-center gap-4 px-6 py-5 border-b border-slate-200/60 dark:border-white/10">
        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-aqua to-accent text-ink grid place-items-center font-extrabold shadow-md">EV</div>
        <div>
          <h1 class="text-xl font-semibold leading-tight">{{ $appName }}</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">Sedang pemeliharaan • Kode status <span class="font-medium">503</span></p>
        </div>
      </div>

      {{-- body --}}
      <div class="px-6 pt-6 pb-2 space-y-6">
        <div class="space-y-2">
          <h2 class="text-2xl font-bold tracking-tight">🚧 Kami sedang memperbarui layanan</h2>
          <p class="text-slate-600 dark:text-slate-300">
            Demi kecepatan & keamanan yang lebih baik, beberapa menit lagi kami akan kembali online.
            @if($retryAfter > 0)
              Perkiraan coba lagi dalam <span class="font-semibold">{{ $retryAfter }} detik</span>.
            @endif
          </p>
        </div>

        {{-- countdown + progress --}}
        <div class="rounded-xl border border-slate-200/70 dark:border-white/10 p-4 bg-white/50 dark:bg-white/5">
          <div class="flex items-end gap-4 justify-between">
            <div>
              <p class="text-sm text-slate-500 dark:text-slate-400">Coba otomatis</p>
              <div class="mt-1 text-4xl font-bold tabular-nums leading-none" id="countLabel">{{ $retryAfter }}</div>
            </div>
            <div class="flex gap-2">
              <button id="btnRetry" class="inline-flex items-center gap-2 rounded-xl bg-ink/90 hover:bg-ink text-white px-4 py-2 transition active:scale-[.99]">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5V1L7 6l5 5V7a5 5 0 1 1-5 5H5a7 7 0 1 0 7-7Z"/></svg>
                Muat Ulang
              </button>
              <a href="/" class="inline-flex items-center gap-2 rounded-xl border border-slate-300/70 dark:border-white/15 bg-white/70 dark:bg-white/5 hover:bg-white/90 dark:hover:bg-white/10 px-4 py-2 transition">
                <svg class="h-5 w-5 opacity-80" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                Beranda
              </a>
            </div>
          </div>

          <div class="mt-4 h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
            <div id="bar" class="h-full w-0 bg-gradient-to-r from-aqua to-accent transition-all duration-300 ease-linear"></div>
          </div>

          <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
            Butuh bantuan? <a class="underline underline-offset-4" href="mailto:{{ $supportEmail }}?subject=Pertanyaan%20Maintenance%20{{ urlencode($appName) }}">Hubungi dukungan</a>.
          </p>
        </div>
      </div>

      {{-- footer --}}
      <div class="px-6 py-4 border-t border-slate-200/60 dark:border-white/10 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
        <span>© {{ date('Y') }} {{ $appName }}</span>
        <span class="font-mono">Ref: {{ substr(hash('xxh3', now()->timestamp . request()->ip()), 0, 8) }}</span>
      </div>
    </section>
  </main>

  <script>
    (function () {
      const total = {{ $retryAfter }};
      let t = total > 0 ? total : 30;
      const label = document.getElementById('countLabel');
      const bar   = document.getElementById('bar');
      const btn   = document.getElementById('btnRetry');

      function fmt(sec){
        if (sec < 60) return sec + 's';
        const m = Math.floor(sec/60), s = sec%60;
        return m + 'm ' + s + 's';
      }

      function tick(){
        label.textContent = fmt(t);
        const pct = Math.max(0, Math.min(100, Math.round(((total - t) / (total || 30)) * 100)));
        bar.style.width = pct + '%';
        if (t <= 0) { location.reload(); return; }
        t--; setTimeout(tick, 1000);
      }
      tick();

      btn?.addEventListener('click', () => location.reload());
    })();
  </script>
</body>
</html>
