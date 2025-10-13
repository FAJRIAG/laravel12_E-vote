@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.index') }}" class="hover:underline">Elections</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.positions.index',$position->election) }}" class="hover:underline">
    Positions — {{ $position->election->name }}
  </a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Edit</span>
@endsection

@section('content')
<div class="max-w-3xl">

  {{-- Judul --}}
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Edit Position</h1>
    <p class="text-sm text-slate-500">
      Perbarui data posisi untuk election <span class="font-medium">{{ $position->election->name }}</span>.
    </p>
  </div>

  {{-- Flash --}}
  @if(session('ok'))
    <div class="mb-5 p-4 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-800 text-sm">
      {{ session('ok') }}
    </div>
  @endif

  {{-- Error global --}}
  @if($errors->any())
    <div class="mb-5 p-4 rounded-lg border border-red-300 bg-red-50 text-red-700 text-sm">
      <div class="font-medium mb-1">Periksa kembali isian Anda:</div>
      <ul class="list-disc ps-5 space-y-0.5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Card form --}}
  <div class="bg-white rounded-xl shadow">
    <form method="POST" action="{{ route('admin.positions.update',$position) }}" class="p-6 space-y-6" novalidate>
      @csrf
      @method('PUT')

      {{-- Nama posisi --}}
      <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Posisi</label>
        <input type="text" id="name" name="name" value="{{ old('name', $position->name) }}" required
               placeholder="Contoh: Ketua Umum, Sekretaris, Bendahara"
               class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                      focus:border-blue-600 focus:ring-blue-600">
        @error('name')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- (Opsional) Deskripsi --}}
      <div>
        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
          Deskripsi <span class="text-slate-400">(opsional)</span>
        </label>
        <textarea id="description" name="description" rows="3"
                  placeholder="Tanggung jawab, kriteria, catatan khusus…"
                  class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                         focus:border-blue-600 focus:ring-blue-600">{{ old('description', $position->description ?? '') }}</textarea>
        @error('description')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        {{-- Urut --}}
        <div>
          <label for="order" class="block text-sm font-medium text-slate-700 mb-1">
            Urut <span class="text-slate-400">(angka)</span>
          </label>
          <input
            type="number" inputmode="numeric" pattern="[0-9]*" min="0" step="1"
            id="order" name="order" value="{{ old('order', $position->order) }}"
            placeholder="Contoh: 1"
            class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                   focus:border-blue-600 focus:ring-blue-600"
            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
          >
          <p class="mt-1 text-xs text-slate-500">Menentukan urutan tampil pada daftar posisi.</p>
          @error('order')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Quota --}}
        <div>
          <label for="quota" class="block text-sm font-medium text-slate-700 mb-1">
            Quota (jumlah pemenang)
          </label>
          <input
            type="number" inputmode="numeric" pattern="[0-9]*" min="1" step="1"
            id="quota" name="quota" value="{{ old('quota', $position->quota) }}"
            placeholder="Contoh: 1"
            class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                   focus:border-blue-600 focus:ring-blue-600"
            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
          >
          <p class="mt-1 text-xs text-slate-500">Biasanya 1 (satu pemenang). Sesuaikan jika lebih dari satu.</p>
          @error('quota')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Tombol --}}
      <div class="pt-2 flex flex-wrap items-center gap-3">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow">
          Update
        </button>

        <a href="{{ route('admin.elections.positions.index',$position->election) }}"
           class="inline-flex items-center px-4 py-2 rounded-lg border hover:bg-slate-50">
          Kembali
        </a>

        <a href="{{ route('admin.positions.candidates.index', $position) }}"
           class="inline-flex items-center px-4 py-2 rounded-lg border text-slate-800 hover:bg-slate-50">
          Kelola Calon
        </a>

        {{-- Hapus (opsional) --}}
        <form action="{{ route('admin.positions.destroy', $position) }}" method="post"
              onsubmit="return confirm('Hapus position ini? Kandidat di dalamnya juga akan terdampak. Lanjutkan?')"
              class="inline ms-auto">
          @csrf
          @method('DELETE')
          <button class="inline-flex items-center px-4 py-2 rounded-lg border text-red-600 hover:bg-red-50">
            Hapus
          </button>
        </form>
      </div>
    </form>
  </div>

  {{-- Info kecil --}}
  <div class="mt-6 text-xs text-slate-500">
    <p>Setelah mengubah posisi, Anda dapat menambahkan atau mengatur kandidat melalui tombol <strong>Kelola Calon</strong>.</p>
  </div>
</div>
@endsection
