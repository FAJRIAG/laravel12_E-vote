@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Elections</span>
@endsection

@section('content')
<div class="space-y-6">

  {{-- Notifikasi --}}
  @if(session('ok'))
    <div class="p-4 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-800 text-sm">
      {{ session('ok') }}
    </div>
  @endif
  @if($errors->any())
    <div class="p-4 rounded-lg border border-red-300 bg-red-50 text-red-700 text-sm">
      <div class="font-medium mb-1">Terjadi kesalahan:</div>
      <ul class="list-disc ps-5 space-y-0.5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Header & actions --}}
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Elections</h1>
      <p class="text-sm text-slate-500">Kelola daftar pemilihan: buat, edit, atur periode, dan tindak lanjut.</p>
    </div>
    <a href="{{ route('admin.elections.create') }}"
       class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow">
      + Tambah Election
    </a>
  </div>

  {{-- Filter / Pencarian --}}
  <div class="bg-white rounded-xl shadow">
    <form method="get" class="p-4 sm:p-5 border-b flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex-1">
        <label for="q" class="sr-only">Cari election</label>
        <div class="relative">
          <input id="q" name="q" value="{{ $q ?? request('q') }}" placeholder="Cari nama/desk..."
                 class="w-full ps-10 pe-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                        focus:border-blue-500 focus:ring-blue-500">
          <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400">🔎</span>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button class="px-4 py-2 rounded-lg border hover:bg-slate-50">Cari</button>
        <a href="{{ route('admin.elections.index') }}" class="px-4 py-2 rounded-lg border hover:bg-slate-50">Reset</a>
      </div>
    </form>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="text-left px-5 py-3">Nama</th>
            <th class="text-left px-5 py-3">Periode</th>
            <th class="text-left px-5 py-3">Status</th>
            <th class="text-left px-5 py-3">Positions</th>
            <th class="text-right px-5 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
        @forelse($elections as $e)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3">
              <div class="font-medium text-slate-800">{{ $e->name }}</div>
              @if($e->description)
                <div class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($e->description, 90) }}</div>
              @endif
            </td>
            <td class="px-5 py-3 text-slate-700">
              {{ optional($e->starts_at)->format('d/m/Y H:i') }} — {{ optional($e->ends_at)->format('d/m/Y H:i') }}
            </td>
            <td class="px-5 py-3">
              <div class="flex items-center gap-2">
                <span class="text-xs px-2 py-1 rounded {{ $e->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                  {{ $e->is_active ? 'Aktif' : 'Tidak' }}
                </span>
                @if(method_exists($e,'isOpen') && $e->isOpen())
                  <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">Dibuka</span>
                @else
                  <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">Tertutup</span>
                @endif
              </div>
            </td>
            <td class="px-5 py-3">
              {{-- jika withCount positions dikirim dari controller --}}
              {{ $e->positions_count ?? ($e->positions->count() ?? 0) }}
            </td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-end gap-3">
                <a class="text-blue-600 hover:underline" href="{{ route('admin.elections.edit',$e) }}">Edit</a>
                <a class="text-emerald-700 hover:underline" href="{{ route('admin.elections.positions.index',$e) }}">Positions</a>
                <a class="text-slate-900 hover:underline" href="{{ route('admin.elections.results.index',$e) }}">Hasil</a>
                <a class="text-emerald-700 hover:underline" href="{{ route('admin.elections.results.pdf',$e) }}">PDF</a>
                <form class="inline" method="post" action="{{ route('admin.elections.destroy',$e) }}"
                      onsubmit="return confirm('Hapus election ini? Data terkait (positions/candidates/votes) juga akan ikut terhapus jika Anda menyiapkannya cascade. Lanjutkan?')">
                  @csrf
                  @method('DELETE')
                  <button class="text-red-600 hover:underline">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-8 text-center text-slate-500">
              Belum ada data. Klik <span class="font-medium">Tambah Election</span> untuk membuat baru.
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($elections, 'links'))
      <div class="px-5 py-4 border-t">
        {{ $elections->withQueryString()->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
