<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Hasil Voting — {{ $election->name }}</title>
  <style>
    @page { margin: 24px 28px; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
    h1 { font-size: 18px; margin: 0 0 6px; }
    h2 { font-size: 14px; margin: 14px 0 6px; }
    p { margin: 0 0 6px; }
    .meta { font-size: 11px; color: #555; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    th, td { border: 1px solid #666; padding: 6px; vertical-align: top; }
    th { background: #efefef; }
    .right { text-align: right; }
    .small { font-size: 11px; color: #444; }
  </style>
</head>
<body>
  <h1>Laporan Hasil Voting: {{ $election->name }}</h1>
  <p class="meta">
    Dicetak: {{ now()->timezone(config('app.timezone'))->format('d-m-Y H:i') }} |
    Masa Pemilihan : {{ optional($election->starts_at)->format('d/m/Y H:i') }} — {{ optional($election->ends_at)->format('d/m/Y H:i') }} |
    Total suara: <strong>{{ $grandTotal }}</strong>
  </p>

  @foreach($positions as $pos)
    <h2>{{ $pos->name }}</h2>
    <table>
      <thead>
        <tr>
          <th style="width:40px;">No</th>
          <th>Nama Calon</th>
          <th>Visi</th>
          <th>Misi (ringkas)</th>
          <th class="right" style="width:80px;">Suara</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pos->candidates as $i => $c)
          <tr>
            <td class="right">{{ $i+1 }}</td>
            <td>{{ $c->name }}</td>
            <td class="small">{{ \Illuminate\Support\Str::limit($c->vision, 180) }}</td>
            <td class="small">{{ \Illuminate\Support\Str::limit($c->mission, 180) }}</td>
            <td class="right">{{ $c->votes_count }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

  <p class="small">Dokumen ini dihasilkan otomatis oleh sistem E-Voting.</p>
</body>
</html>
