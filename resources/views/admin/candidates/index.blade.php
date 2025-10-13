@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.index') }}" class="hover:underline">Elections</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.positions.index',$election) }}" class="hover:underline">
    Positions — {{ $election->name }}
  </a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Candidates — {{ $position->name }}</span>
@endsection

@section('content')
<div class="space-y-6 max-w-6xl">

  {{-- Header + CTA --}}
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Candidates — {{ $position->name }}</h1>
      <p class="text-sm text-slate-500">
        Tambahkan dan kelola kandidat untuk posisi ini. Sertakan foto, visi, dan misi agar pemilih mengenal kandidat.
      </p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.elections.positions.index',$election) }}"
         class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm">Kembali ke Positions</a>
      <a href="{{ route('admin.positions.candidates.create',$position) }}"
         class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow">
        + Tambah Calon
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
        <label for="q" class="sr-only">Cari kandidat</label>
        <div class="relative">
          <input id="q" name="q" value="{{ request('q') }}" placeholder="Cari nama kandidat…"
                 class="w-full ps-10 pe-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                        focus:border-blue-500 focus:ring-blue-500">
          <span class="absolute inset-y-0 left-0 flex items-center ps-3 text-slate-400">🔎</span>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button class="px-4 py-2 rounded-lg border hover:bg-slate-50">Cari</button>
        <a href="{{ route('admin.positions.candidates.index',$position) }}"
           class="px-4 py-2 rounded-lg border hover:bg-slate-50">Reset</a>
      </div>
    </form>

    {{-- Tabel / Grid responsif --}}
    <div class="hidden md:block">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="text-left px-5 py-3 w-24">Urut</th>
            <th class="text-left px-5 py-3 w-28">Foto</th>
            <th class="text-left px-5 py-3">Nama</th>
            <th class="text-left px-5 py-3">Visi</th>
            <th class="text-left px-5 py-3">Misi</th>
            <th class="text-right px-5 py-3 w-[260px]">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @forelse($candidates as $c)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3 font-medium">{{ $c->order }}</td>
              <td class="px-5 py-3">
                @if($c->photo_path)
                  <img src="{{ asset('storage/'.$c->photo_path) }}" alt="Foto {{ $c->name }}"
                       class="h-14 w-14 object-cover rounded-lg border">
                @else
                  <div class="h-14 w-14 rounded-lg bg-slate-100 border flex items-center justify-center text-slate-400">—</div>
                @endif
              </td>
              <td class="px-5 py-3">
                <div class="font-medium text-slate-800">{{ $c->name }}</div>
                @if(!empty($c->nickname))
                  <div class="text-xs text-slate-500">({{ $c->nickname }})</div>
                @endif
              </td>
              <td class="px-5 py-3">
                <div class="text-slate-700">
                  {{ \Illuminate\Support\Str::limit($c->vision, 120) ?: '—' }}
                </div>
              </td>
              <td class="px-5 py-3">
                <div class="text-slate-700">
                  {{ \Illuminate\Support\Str::limit($c->mission, 120) ?: '—' }}
                </div>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end gap-2 flex-wrap">
                  <a href="{{ route('admin.candidates.edit',$c) }}"
                     class="px-3 py-1.5 rounded border text-blue-700 hover:bg-blue-50">Edit</a>
                  <form action="{{ route('admin.candidates.destroy',$c) }}" method="post"
                        onsubmit="return confirm('Hapus calon ini? Tindakan tidak dapat dibatalkan.')"
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
              <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                Belum ada kandidat. Klik <span class="font-medium">+ Tambah Calon</span> untuk menambahkan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Kartu untuk mobile --}}
    <div class="md:hidden p-4 grid grid-cols-1 gap-3">
      @forelse($candidates as $c)
        <div class="rounded-xl border p-4 flex gap-3">
          @if($c->photo_path)
            <img src="{{ asset('storage/'.$c->photo_path) }}" alt="Foto {{ $c->name }}"
                 class="h-16 w-16 object-cover rounded-lg border">
          @else
            <div class="h-16 w-16 rounded-lg bg-slate-100 border flex items-center justify-center text-slate-400">—</div>
          @endif
          <div class="flex-1">
            <div class="flex items-center justify-between">
              <div class="font-semibold text-slate-800">{{ $c->name }}</div>
              <span class="text-xs text-slate-500">#{{ $c->order }}</span>
            </div>
            @if(!empty($c->nickname))
              <div class="text-xs text-slate-500">({{ $c->nickname }})</div>
            @endif
            <div class="mt-2 text-xs">
              <div><span class="font-medium">Visi:</span> {{ \Illuminate\Support\Str::limit($c->vision, 90) ?: '—' }}</div>
              <div class="mt-1"><span class="font-medium">Misi:</span> {{ \Illuminate\Support\Str::limit($c->mission, 90) ?: '—' }}</div>
            </div>
            <div class="mt-3 flex items-center gap-2">
              <a href="{{ route('admin.candidates.edit',$c) }}"
                 class="px-3 py-1.5 rounded border text-blue-700 hover:bg-blue-50 text-sm">Edit</a>
              <form action="{{ route('admin.candidates.destroy',$c) }}" method="post"
                    onsubmit="return confirm('Hapus calon ini?')">
                @csrf
                @method('DELETE')
                <button class="px-3 py-1.5 rounded border text-red-600 hover:bg-red-50 text-sm">Hapus</button>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center text-slate-500">Belum ada kandidat.</div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if(method_exists($candidates, 'links'))
      <div class="px-5 py-4 border-t">
        {{ $candidates->withQueryString()->links() }}
      </div>
    @endif
  </div>

  {{-- Footer aksi cepat --}}
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.elections.positions.index',$election) }}" class="text-sm text-slate-600 hover:underline">
      ← Kembali ke Positions
    </a>
{{--     <a href="{{ route('admin.positions.candidates.create',$position) }}"
       class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow">
      + Tambah Calon
    </a> --}}
  </div>
</div>
@endsection
