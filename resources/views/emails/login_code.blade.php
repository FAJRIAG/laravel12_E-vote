<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Kode Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--
    Catatan desain:
    - Font besar & kontras baik agar nyaman dibaca semua usia
    - Spasi lapang, tombol besar mudah diklik (tap target)
    - Tidak pakai CSS eksternal agar aman di klien email
    - Warna brand bisa diganti via $brandColor
  -->
  <style>
    /* Mobile tuning */
    @media (max-width: 600px) {
      .container { width: 100% !important; padding: 20px !important; border-radius: 0 !important; }
      .code     { font-size: 22px !important; letter-spacing: 2px !important; }
      .btn      { display: block !important; width: 100% !important; text-align: center !important; }
    }
    /* Dark mode hint (tidak semua klien support, tapi harmless) */
    @media (prefers-color-scheme: dark) {
      body { background:#0b0f14 !important; }
      .card { background:#0f172a !important; color:#e5e7eb !important; }
      .muted { color:#9aa4b2 !important; }
      .divider { border-top-color:#263241 !important; }
      .codewrap { background:#0b1220 !important; border-color:#2a3a53 !important; }
    }
    a { color: inherit; }
    .shadow { box-shadow: 0 8px 24px rgba(2, 6, 23, 0.08), 0 2px 6px rgba(2, 6, 23, 0.06); }
  </style>
</head>
<body style="margin:0; padding:0; background:#f3f6fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, 'Helvetica Neue', sans-serif; color:#0f172a;">

  <!-- Preheader tersembunyi (muncul di preview Gmail/Outlook) -->
  <div style="display:none;visibility:hidden;opacity:0;height:0;width:0;overflow:hidden;">
    {{ $preheader ?? ('Kode login Anda untuk ' . ($brandName ?? config('app.name'))) }}
  </div>

  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td align="center" style="padding: 28px;">
        <table class="container card shadow" role="presentation" cellpadding="0" cellspacing="0" border="0" width="640" style="width:640px; max-width:640px; background:#ffffff; border-radius:16px; overflow:hidden;">
          <!-- Header -->
          <tr>
            <td style="padding: 20px 24px; background: {{ $brandColor ?? '#2563eb' }};">
              <table width="100%" role="presentation">
                <tr>
                  <td style="vertical-align:middle;">
                    @if(!empty($brandLogoUrl))
                      <img src="{{ $brandLogoUrl }}" alt="{{ $brandName ?? config('app.name') }}" style="height:34px; display:block; border:0; outline:none;">
                    @else
                      <span style="font-size:18px; font-weight:700; color:#ffffff; letter-spacing:0.3px;">
                        {{ $brandName ?? config('app.name') }}
                      </span>
                    @endif
                  </td>
                  <td align="right" style="vertical-align:middle;">
                    <span style="font-size:13px; color:#eaf2ff; opacity:0.95;">Verifikasi Masuk</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding: 28px 28px 8px;">
              <h1 style="margin:0 0 10px 0; font-size:22px; line-height:1.3; letter-spacing:0.2px;">Kode Login Anda</h1>
              <p class="muted" style="margin:0 0 18px 0; font-size:15px; line-height:1.7; color:#475569;">
                Gunakan kode di bawah untuk masuk ke sistem. @if(!empty($expiresInMinutes)) <br>
                <strong>Berlaku {{ $expiresInMinutes }} menit</strong> sejak email ini diterima. @endif
              </p>

              <!-- KODE -->
              <div class="codewrap" style="margin: 12px 0 22px; display:inline-block; padding:14px 18px; border:1px dashed #c2cedb; border-radius:12px; background:#f8fbff;">
                <code class="code" style="
                  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', monospace;
                  font-size:24px; font-weight:800; letter-spacing:4px; color:#0b1220; display:inline-block;">
                  {{ $code }}
                </code>
              </div>

              <!-- CTA -->
              <p style="margin:0 0 14px 0; font-size:15px; color:#334155;">Masuk melalui tautan berikut:</p>
              <p style="margin:0 0 24px 0;">
                <a class="btn" href="{{ $loginUrl }}" target="_blank"
                   style="display:inline-block; text-decoration:none; background:{{ $brandColor ?? '#2563eb' }}; color:#ffffff;
                          padding:14px 18px; border-radius:12px; font-weight:700; font-size:15px; letter-spacing:0.2px;">
                  Buka Halaman Login
                </a>
              </p>

              <!-- Fallback URL -->
              <div style="margin: 0 0 8px 0; font-size:12px; color:#64748b;">
                Jika tombol tidak berfungsi, salin & tempel URL ini ke browser Anda:
              </div>
              <div style="font-size:13px; line-height:1.6; color:#0f172a; word-break:break-all;">
                {{ $loginUrl }}
              </div>

              <hr class="divider" style="border:none; border-top:1px solid #e6edf5; margin:26px 0;">

              <p class="muted" style="margin:0; font-size:12.5px; color:#76869b;">
                Email ini dikirim otomatis oleh {{ $brandName ?? config('app.name') }}. Abaikan jika Anda tidak meminta kode.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding: 16px 24px 22px; background:#f6f9fe; text-align:center;">
              <div style="font-size:12.5px; color:#8fa1b6;">
                © {{ date('Y') }} {{ $brandName ?? config('app.name') }} &middot; Semua hak dilindungi.
              </div>
            </td>
          </tr>
        </table>

        <!-- Notice kecil di luar kartu -->
        <div class="muted" style="font-size:12px; color:#8fa1b6; margin-top:12px; text-align:center;">
          Anda menerima email ini karena ada permintaan login.
        </div>
      </td>
    </tr>
  </table>
</body>
</html>
