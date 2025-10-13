@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.index') }}" class="hover:underline">Elections</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.positions.index',$election) }}" class="hover:underline">Positions — {{ $election->name }}</a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Tambah</span>
@endsection

@section('content')
<div class="max-w-3xl">

  {{-- Judul --}}
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Tambah Position — {{ $election->name }}</h1>
    <p class="text-sm text-slate-500">Buat jabatan/posisi yang akan diperebutkan pada election ini.</p>
  </div>

  {{-- Alert error global --}}
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
    <form method="POST" action="{{ route('admin.elections.positions.store',$election) }}" class="p-6 space-y-6" novalidate>
      @csrf

      {{-- Nama posisi --}}
      <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Posisi</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
               placeholder="Contoh: Ketua Umum, Sekretaris, Bendahara"
               class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                      focus:border-blue-600 focus:ring-blue-600">
        @error('name')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- (Opsional) Deskripsi singkat posisi --}}
      <div>
        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">
          Deskripsi <span class="text-slate-400">(opsional)</span>
        </label>
        <textarea id="description" name="description" rows="3"
                  placeholder="Tanggung jawab singkat, kriteria kandidat, dsb."
                  class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                         focus:border-blue-600 focus:ring-blue-600">{{ old('description') }}</textarea>
        @error('description')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        {{-- Urut (order) --}}
        <div>
          <label for="order" class="block text-sm font-medium text-slate-700 mb-1">
            Urut <span class="text-slate-400">(angka)</span>
          </label>
          <input
            type="number" inputmode="numeric" pattern="[0-9]*" min="0" step="1"
            id="order" name="order" value="{{ old('order') }}"
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

        {{-- Quota pemenang --}}
        <div>
          <label for="quota" class="block text-sm font-medium text-slate-700 mb-1">
            Quota (jumlah pemenang)
          </label>
          <input
            type="number" inputmode="numeric" pattern="[0-9]*" min="1" step="1"
            id="quota" name="quota" value="{{ old('quota', 1) }}"
            placeholder="Contoh: 1"
            class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                   focus:border-blue-600 focus:ring-blue-600"
            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
          >
          <p class="mt-1 text-xs text-slate-500">Biasanya 1 (satu pemenang). Ubah jika ada lebih dari satu pemenang.</p>
          @error('quota')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Tombol --}}
      <div class="pt-2 flex items-center gap-3">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow">
          Simpan
        </button>
        <a href="{{ route('admin.elections.positions.index',$election) }}"
           class="inline-flex items-center px-4 py-2 rounded-lg border hover:bg-slate-50">
          Kembali
        </a>
      </div>
    </form>
  </div>

  {{-- Tips --}}
  <div class="mt-6 text-xs text-slate-500">
    <p>Setelah menambahkan posisi, lanjutkan ke <strong>Kelola Calon</strong> untuk menambahkan kandidat beserta foto, visi, dan misi.</p>
  </div>

</div>
@endsection
