<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'Daftar Kode Login' }}</title>
  <style>
    @page { margin: 24px 28px; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color:#111; }
    h1 { font-size: 16px; margin: 0 0 8px; }
    p.meta { font-size: 11px; color:#555; margin: 0 0 8px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #777; padding: 6px; vertical-align: top; }
    th { background: #efefef; }
    .mono { font-family: DejaVu Sans Mono, Consolas, monospace; }
  </style>
</head>
<body>
  <h1>{{ $title ?? 'Daftar Kode Login' }}</h1>
  <p class="meta">
    Dicetak: {{ now()->format('d/m/Y H:i') }}
    @if(!empty($onlyCodeAndExpiry))
    <table>
        <thead>
        <tr>
            <th style="text-align:left;">Code</th>
            <th style="text-align:left;">Masa Aktif Sampai</th>
        </tr>
        </thead>
        <tbody>
        @foreach($codes as $c)
            <tr>
            <td>{{ $c->code }}</td>
            <td>{{ $c->expires_at ? $c->expires_at->format('Y-m-d H:i') : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
    {{-- layout lamamu --}}
    @endif

    | Jumlah: {{ count($codes) }}
  </p>

  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Label</th>
        <th>User</th>
        <th>Aktif</th>
        <th>Once</th>
        <th>Max</th>
        <th>Used</th>
        <th>Expired</th>
        <th>Last Used</th>
        <th>Creator</th>
      </tr>
    </thead>
    <tbody>
      @foreach($codes as $c)
        <tr>
          <td class="mono">{{ $c->code }}</td>
          <td>{{ $c->label ?? '—' }}</td>
          <td>{{ optional($c->user)->name ?? '—' }}</td>
          <td>{{ $c->is_active ? 'Ya' : 'Tidak' }}</td>
          <td>{{ $c->is_one_time ? 'Ya' : 'Tidak' }}</td>
          <td>{{ $c->max_uses ?? '—' }}</td>
          <td>{{ $c->used_count ?? 0 }}</td>
          <td>{{ optional($c->expires_at)->format('d/m/Y H:i') ?? '—' }}</td>
          <td>{{ optional($c->last_used_at)->format('d/m/Y H:i') ?? '—' }}</td>
          <td>{{ optional($c->creator)->name ?? '—' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
