@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Hasil: {{ $election->name }}</h1>

<div class="grid md:grid-cols-3 gap-4 mb-6">
  <div class="md:col-span-2 p-4 bg-white rounded shadow">
    <div class="flex items-center gap-2 mb-2">
      <label class="text-sm">Pilih Posisi:</label>
      <select id="position" class="border rounded p-1">
        <option value="">Semua (gabungan)</option>
        @foreach($positions as $p)
          <option value="{{ $p->id }}">{{ $p->name }}</option>
        @endforeach
      </select>
    </div>
    <canvas id="chart" height="120"></canvas>
    <div id="resultNotice" class="hidden text-sm text-red-600 mt-2">Gagal memuat data grafik.</div>
  </div>

  <div class="p-4 bg-white rounded shadow">
    <a href="{{ route('admin.elections.results.pdf',$election) }}" class="px-3 py-2 rounded bg-emerald-600 text-white">Unduh PDF</a>
    <p class="text-sm text-gray-500 mt-3">Grafik auto-refresh setiap 5 detik.</p>
    <p class="text-sm text-gray-500">Total suara: {{ $total }}</p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart, timer;

async function fetchData(){
  const pos = document.getElementById('position').value;

  // ✅ gunakan route admin JSON
  const baseUrl = @json(route('admin.elections.results.json', $election));
  const url = new URL(baseUrl, window.location.origin);
  if (pos) url.searchParams.set('position_id', pos);

  try {
    const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const payload = await res.json();

    const labels = payload.labels ?? [];
    const data = payload.data ?? [];

    const ctx = document.getElementById('chart').getContext('2d');

    if (!labels.length) {
      if (chart) { chart.destroy(); chart = null; }
      const c = ctx.canvas;
      const g = c.getContext('2d');
      g.clearRect(0, 0, c.width, c.height);
      g.font = '14px sans-serif';
      g.fillText('Belum ada data untuk ditampilkan.', 12, 24);
      return;
    }

    if (!chart) {
      chart = new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets: [{ label:'Suara', data }] },
        options: { responsive: true, animation: false, scales: { y: { beginAtZero:true, ticks:{ precision:0 } } } }
      });
    } else {
      chart.data.labels = labels;
      chart.data.datasets[0].data = data;
      chart.update();
    }

    document.getElementById('resultNotice')?.classList.add('hidden');
  } catch (err) {
    console.error(err);
    const box = document.getElementById('resultNotice');
    if (box) {
      box.textContent = 'Gagal memuat data grafik. Coba refresh halaman.';
      box.classList.remove('hidden');
    }
  }
}

document.getElementById('position').addEventListener('change', fetchData);
fetchData();
timer = setInterval(fetchData, 5000);
</script>
@endsection
