@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
@endsection

@section('content')
  {{-- Kartu ringkas metrik umum --}}
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <div class="bg-white rounded-xl shadow p-5">
      <div class="text-sm text-slate-500">Total Pengguna</div>
      <div class="mt-1 text-2xl font-semibold">{{ $totalUsers ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
      <div class="text-sm text-slate-500">Total Suara</div>
      <div class="mt-1 text-2xl font-semibold">{{ $totalVotes ?? 0 }}</div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
      <div class="text-sm text-slate-500">Election Aktif</div>
      <div class="mt-1 text-2xl font-semibold">
        {{ ($elections ?? collect())->where('is_active', true)->count() }}
      </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
      <div class="text-sm text-slate-500">Positions</div>
      <div class="mt-1 text-2xl font-semibold">{{ $positionsTotal ?? 0 }}</div>
    </div>
  </div>

  {{-- Panel Kode Login --}}
  <div class="bg-white rounded-xl shadow p-5 mb-8">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-semibold text-gray-800">Kode Login</h2>
        <p class="text-sm text-gray-500">Kelola kode sekali pakai / multi-use untuk pemilih.</p>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.codes.index') }}"
           class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
          Lihat Semua
        </a>
        <a href="{{ route('admin.codes.create') }}"
           class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
          Buat Kode
        </a>
      </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4">
      <div class="rounded-lg border p-4">
        <div class="text-sm text-gray-500">Total Kode</div>
        <div class="mt-1 text-2xl font-semibold text-gray-900">{{ $codesTotal ?? 0 }}</div>
      </div>
      <div class="rounded-lg border p-4">
        <div class="text-sm text-gray-500">Aktif</div>
        <div class="mt-1 text-2xl font-semibold text-emerald-700">{{ $codesActive ?? 0 }}</div>
      </div>
      <div class="rounded-lg border p-4">
        <div class="text-sm text-gray-500">Tersedia (bisa dipakai)</div>
        <div class="mt-1 text-2xl font-semibold text-blue-700">{{ $codesAvailable ?? 0 }}</div>
      </div>
    </div>
  </div>

  {{-- Tabel elections --}}
  <div class="bg-white rounded-xl shadow overflow-hidden mb-8">
    <div class="px-5 py-4 border-b flex items-center justify-between">
      <h2 class="text-lg font-semibold">Daftar Elections</h2>
      <a href="{{ route('admin.elections.create') }}" class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
        Tambah Election
      </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="text-left px-5 py-3">Nama</th>
            <th class="text-left px-5 py-3">Masa Pemilihan</th>
            <th class="text-left px-5 py-3">Aktif</th>
            <th class="text-left px-5 py-3">Positions</th>
            <th class="text-right px-5 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse(($elections ?? collect()) as $e)
            @php
              $voteTotal = $e->positions->sum('votes_count') ?? 0;
            @endphp
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3 font-medium">{{ $e->name }}</td>
              <td class="px-5 py-3 text-slate-600">
                {{ optional($e->starts_at)->format('d/m/Y H:i') }} — {{ optional($e->ends_at)->format('d/m/Y H:i') }}
              </td>
              <td class="px-5 py-3">
                <span class="text-xs px-2 py-1 rounded {{ $e->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                  {{ $e->is_active ? 'Ya' : 'Tidak' }}
                </span>
              </td>
              <td class="px-5 py-3">{{ $e->positions_count ?? $e->positions->count() }}</td>
              <td class="px-5 py-3 text-right">
                <a class="text-blue-600 hover:underline" href="{{ route('admin.elections.edit',$e) }}">Edit</a>
                <a class="ms-3 text-emerald-700 hover:underline" href="{{ route('admin.elections.positions.index',$e) }}">Positions</a>
                <a class="ms-3 text-slate-800 hover:underline" href="{{ route('admin.elections.results.index',$e) }}">Hasil</a>
                <a class="ms-3 text-emerald-700 hover:underline" href="{{ route('admin.elections.results.pdf',$e) }}">PDF</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-6 text-center text-slate-500">
                Belum ada data elections.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Tabel ringkas kandidat (opsional) --}}
  @isset($candidates)
  <div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-5 py-4 border-b">
      <h2 class="text-lg font-semibold">Ringkasan Kandidat</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="text-left px-5 py-3">Urut</th>
            <th class="text-left px-5 py-3">Nama</th>
            <th class="text-left px-5 py-3">Posisi</th>
            <th class="text-left px-5 py-3">Visi</th>
            <th class="text-right px-5 py-3">Suara</th>
          </tr>
        </thead>
        <tbody class="divide-y">
        @forelse($candidates as $c)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 w-16">{{ $c->order }}</td>
            <td class="px-5 py-3">{{ $c->name }}</td>
            <td class="px-5 py-3 text-slate-600">{{ optional($c->position)->name }}</td>
            <td class="px-5 py-3 text-slate-600">{{ \Illuminate\Support\Str::limit($c->vision, 80) }}</td>
            <td class="px-5 py-3 text-right font-medium">{{ $c->votes_count ?? 0 }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-6 text-center text-slate-500">Belum ada kandidat.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @endisset
@endsection
