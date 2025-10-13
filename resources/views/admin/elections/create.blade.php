@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-2 text-slate-400">/</span>
  <a href="{{ route('admin.elections.index') }}" class="hover:underline">Elections</a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Tambah</span>
@endsection

@section('content')
<div class="max-w-3xl">
  {{-- Judul halaman --}}
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Tambah Election</h1>
    <p class="text-sm text-slate-500 mt-1">
      Isi detail pemilihan. Pastikan waktu sesuai zona <span class="font-medium">Asia/Jakarta</span>.
    </p>
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
    <form method="POST" action="{{ route('admin.elections.store') }}" class="p-6 space-y-6" novalidate>
      @csrf

      {{-- Nama --}}
      <div>
        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
               class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                      focus:border-blue-500 focus:ring-blue-500"
               placeholder="Contoh: Pemilihan Ketua">
        @error('name')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Deskripsi --}}
      <div>
        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
        <textarea id="description" name="description" rows="4"
                  class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                         focus:border-blue-500 focus:ring-blue-500"
                  placeholder="Deskripsi singkat tentang Calon">{{ old('description') }}</textarea>
        @error('description')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Waktu mulai & selesai --}}
      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label for="starts_at" class="block text-sm font-medium text-slate-700 mb-1">
            Mulai <span class="text-slate-400">(datetime-local)</span>
          </label>
          <input type="datetime-local" id="starts_at" name="starts_at"
                 value="{{ old('starts_at') }}"
                 class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                        focus:border-blue-500 focus:ring-blue-500">
          @error('starts_at')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-xs text-slate-500">Format: <code>dd/mm/yyyy hh:mm</code> sesuai perangkat.</p>
        </div>

        <div>
          <label for="ends_at" class="block text-sm font-medium text-slate-700 mb-1">
            Selesai <span class="text-slate-400">(datetime-local)</span>
          </label>
          <input type="datetime-local" id="ends_at" name="ends_at"
                 value="{{ old('ends_at') }}"
                 class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                        focus:border-blue-500 focus:ring-blue-500">
          @error('ends_at')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      {{-- Aktif (selalu mengirim nilai) --}}
      <div>
        <span class="block text-sm font-medium text-slate-700 mb-1">Status</span>
        <input type="hidden" name="is_active" value="0">
        <label class="inline-flex items-center gap-2 text-sm text-slate-800">
          <input type="checkbox" name="is_active" value="1"
                 class="rounded text-blue-600 border-slate-300"
                 {{ old('is_active', false) ? 'checked' : '' }}>
          Aktif
        </label>
        @error('is_active')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-slate-500">Jika aktif, pemilih dapat mengakses halaman voting pada periode yang ditentukan.</p>
      </div>

      {{-- Tombol aksi --}}
      <div class="pt-2 flex items-center gap-3">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow">
          Simpan
        </button>
        <a href="{{ route('admin.elections.index') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg border hover:bg-slate-50">
          Batal
        </a>
      </div>
    </form>
  </div>

  {{-- Tips lanjutan --}}
 {{--  <div class="mt-6 text-xs text-slate-500">
    <p>Setelah membuat election, tambahkan <strong>Positions</strong> dan <strong>Candidates</strong> dari menu “Positions”.</p>
  </div> --}}
</div>
@endsection
