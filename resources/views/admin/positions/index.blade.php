@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.index') }}" class="hover:underline">Elections</a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Positions — {{ $election->name }}</span>
@endsection

@section('content')
<div class="space-y-6 max-w-6xl">

  {{-- Header + CTA --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Positions — {{ $election->name }}</h1>
      <p class="text-sm text-slate-500">
        Kelola daftar posisi/jabatan untuk election ini. Tambahkan kandidat pada setiap posisi.
      </p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.elections.results.index', $election) }}"
         class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm">Lihat Hasil</a>
      {{-- <a href="{{ route('admin.elections.edit', $election) }}"
         class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm">Edit Election</a> --}}
      <a href="{{ route('admin.elections.positions.create', $election) }}"
         class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow">
        + Tambah Position
      </a>
    </div>
  </div>

  {{-- Flash / Error --}}
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

  {{-- Filter / Pencarian --}}
  <div class="bg-white rounded-xl shadow">
    <form method="get" class="p-4 sm:p-5 border-b flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div class="flex-1">
        <label for="q" class="sr-only">Cari posisi</label>
        <div class="relative">
          <input id="q" name="q" value="{{ $q ?? request('q') }}" placeholder="Cari nama posisi…"
                 class="w-full ps-10 pe-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                        focus:border-blue-500 focus:ring-blue-500">
          <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400">🔎</span>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button class="px-4 py-2 rounded-lg border hover:bg-slate-50">Cari</button>
        <a href="{{ route('admin.elections.positions.index', $election) }}"
           class="px-4 py-2 rounded-lg border hover:bg-slate-50">Reset</a>
      </div>
    </form>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="text-left px-5 py-3 w-24">Urut</th>
            <th class="text-left px-5 py-3">Nama Posisi</th>
            <th class="text-left px-5 py-3 w-28">Quota</th>
            <th class="text-left px-5 py-3 w-28">Kandidat</th>
            <th class="text-right px-5 py-3 w-[320px]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
        @forelse($positions as $pos)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-medium">{{ $pos->order }}</td>
            <td class="px-5 py-3">
              <div class="font-medium text-slate-800">{{ $pos->name }}</div>
              @if(!empty($pos->description))
                <div class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($pos->description, 90) }}</div>
              @endif
            </td>
            <td class="px-5 py-3">{{ $pos->quota }}</td>
            <td class="px-5 py-3">
              {{-- Jika controller pakai withCount('candidates') --}}
              {{ $pos->candidates_count ?? $pos->candidates()->count() }}
            </td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-end gap-2 flex-wrap">
                <a href="{{ route('admin.positions.candidates.index', $pos) }}"
                   class="px-3 py-1.5 rounded border text-slate-800 hover:bg-slate-50">Kelola Calon</a>

                <a href="{{ route('admin.positions.edit', $pos) }}"
                   class="px-3 py-1.5 rounded border text-blue-700 hover:bg-blue-50">Edit</a>

                <form action="{{ route('admin.positions.destroy', $pos) }}" method="post"
                      onsubmit="return confirm('Hapus position ini? Kandidat di dalamnya juga akan terdampak. Lanjutkan?')"
                      class="inline">
                  @csrf
                  @method('DELETE')
                  <button class="px-3 py-1.5 rounded border text-red-600 hover:bg-red-50">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-8 text-center text-slate-500">
              Belum ada position. Klik <span class="font-medium">+ Tambah Position</span> untuk membuat baru.
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if(method_exists($positions, 'links'))
      <div class="px-5 py-4 border-t">
        {{ $positions->withQueryString()->links() }}
      </div>
    @endif
  </div>

  {{-- Footer aksi cepat --}}
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.elections.index') }}" class="text-sm text-slate-600 hover:underline">
      ← Kembali ke daftar Elections
    </a>
{{--     <a href="{{ route('admin.elections.positions.create', $election) }}"
       class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow">
      + Tambah Position
    </a> --}}
  </div>
</div>
@endsection
