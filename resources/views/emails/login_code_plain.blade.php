{{ $brandName ?? config('app.name') }} — Kode Login

Kode: {{ $code }}
Login: {{ $loginUrl }}
@if(!empty($expiresInMinutes))
Berlaku: {{ $expiresInMinutes }} menit
@endif

Jika tombol/tautan tidak berfungsi, salin URL login di atas ke browser.
Jika Anda tidak meminta kode ini, abaikan email ini.
