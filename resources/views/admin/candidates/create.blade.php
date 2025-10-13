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
  <a href="{{ route('admin.positions.candidates.index',$position) }}" class="hover:underline">
    Candidates — {{ $position->name }}
  </a>
  <span class="mx-2 text-slate-400">/</span>
  <span class="text-slate-500">Tambah</span>
@endsection

@section('content')
<div class="max-w-4xl space-y-6">

  {{-- Heading --}}
  <div class="flex items-start justify-between gap-3">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Tambah Calon</h1>
      <p class="text-sm text-slate-500">
        Buat kandidat untuk posisi <span class="font-medium">{{ $position->name }}</span>.
      </p>
    </div>
    <a href="{{ route('admin.positions.candidates.index',$position) }}"
       class="px-3 py-2 rounded-lg border hover:bg-slate-50 text-sm shrink-0">
      Kembali
    </a>
  </div>

  {{-- Error --}}
  @if($errors->any())
    <div class="p-4 rounded-lg border border-red-300 bg-red-50 text-red-700 text-sm">
      <div class="font-medium mb-1">Periksa kembali isian Anda:</div>
      <ul class="list-disc ps-5 space-y-0.5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form --}}
  <div class="bg-white rounded-xl shadow">
    <form
      method="POST"
      action="{{ route('admin.positions.candidates.store',$position) }}"
      enctype="multipart/form-data"
      class="p-6 space-y-6"
      novalidate
    >
      @csrf

      <div class="grid lg:grid-cols-3 gap-6">
        {{-- Kolom kiri (teks) --}}
        <div class="lg:col-span-2 space-y-5">

          {{-- Nama --}}
          <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" id="name" name="name" required
                   value="{{ old('name') }}"
                   placeholder="Contoh: Raka Pratama"
                   class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                          focus:border-blue-600 focus:ring-blue-600">
            @error('name')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Nama panggilan (opsional) --}}
          <div>
            <label for="nickname" class="block text-sm font-medium text-slate-700 mb-1">
              Nama Panggilan <span class="text-slate-400">(opsional)</span>
            </label>
            <input type="text" id="nickname" name="nickname"
                   value="{{ old('nickname') }}"
                   placeholder="Contoh: Raka"
                   class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                          focus:border-blue-600 focus:ring-blue-600">
            @error('nickname')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Urut --}}
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label for="order" class="block text-sm font-medium text-slate-700 mb-1">Urut (angka)</label>
              <input type="number" inputmode="numeric" pattern="[0-9]*" min="0" step="1"
                     id="order" name="order"
                     value="{{ old('order') }}"
                     placeholder="Contoh: 1"
                     class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                            focus:border-blue-600 focus:ring-blue-600"
                     oninput="this.value=this.value.replace(/[^0-9]/g,'')">
              @error('order')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- (Opsional) kuota spesifik kandidat jika kamu pakai; kalau tidak, hapus blok ini --}}
            {{-- <div>
              <label for="quota" class="block text-sm font-medium text-slate-700 mb-1">Kuota (opsional)</label>
              <input type="number" id="quota" name="quota" value="{{ old('quota') }}"
                     class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                            focus:border-blue-600 focus:ring-blue-600">
            </div> --}}
          </div>

          {{-- Visi --}}
          <div>
            <label for="vision" class="block text-sm font-medium text-slate-700 mb-1">Visi</label>
            <textarea id="vision" name="vision" rows="3"
                      placeholder="Tulis visi kandidat secara ringkas"
                      class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                             focus:border-blue-600 focus:ring-blue-600">{{ old('vision') }}</textarea>
            @error('vision')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          {{-- Misi --}}
          <div>
            <label for="mission" class="block text-sm font-medium text-slate-700 mb-1">Misi</label>
            <textarea id="mission" name="mission" rows="5"
                      placeholder="Gunakan poin-poin singkat untuk memudahkan pemilih membaca"
                      class="w-full px-3 py-2 rounded-lg border border-slate-300 bg-slate-50 shadow-sm
                             focus:border-blue-600 focus:ring-blue-600">{{ old('mission') }}</textarea>
            @error('mission')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-slate-500">Tips: Anda bisa menuliskan misi baris-per-baris.</p>
          </div>

        </div>

        {{-- Kolom kanan (foto) --}}
        <div class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Foto Kandidat</label>

            {{-- Placeholder preview --}}
            <div class="mb-3">
              <div id="preview"
                   class="w-full max-w-[220px] aspect-[4/5] rounded-lg bg-slate-100 border
                          flex items-center justify-center text-slate-400">
                Preview foto
              </div>
            </div>

            {{-- Input file --}}
            <input type="file" id="photo" name="photo" accept="image/*"
                   class="block w-full text-sm file:me-3 file:px-3 file:py-2 file:rounded-lg
                          file:border file:bg-slate-50 file:hover:bg-slate-100
                          file:border-slate-300 file:text-slate-700 cursor-pointer" />

            @error('photo')
              <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror

            <p class="mt-2 text-xs text-slate-500">
              Disarankan rasio 4:5 (potret), ukuran &lt; 1MB. Format: JPG/PNG/WebP.
            </p>
          </div>
        </div>
      </div>

      {{-- Tombol --}}
      <div class="pt-2 flex flex-wrap items-center gap-3">
        <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-medium
                       hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow">
          Simpan
        </button>

        <a href="{{ route('admin.positions.candidates.index',$position) }}"
           class="inline-flex items-center px-4 py-2 rounded-lg border hover:bg-slate-50">
          Batal
        </a>
      </div>
    </form>
  </div>

  <p class="text-xs text-slate-500">
    Setelah tersimpan, Anda bisa mengubah urutan atau memperbarui foto dari halaman edit kandidat.
  </p>
</div>

{{-- Preview foto client-side --}}
<script>
  const input = document.getElementById('photo');
  const preview = document.getElementById('preview');

  if (input && preview) {
    input.addEventListener('change', () => {
      const file = input.files?.[0];
      if (!file) {
        preview.innerHTML = 'Preview foto';
        preview.style.backgroundImage = '';
        return;
      }
      const url = URL.createObjectURL(file);
      preview.style.backgroundImage = `url('${url}')`;
      preview.style.backgroundSize = 'cover';
      preview.style.backgroundPosition = 'center';
      preview.innerHTML = '';
    });
  }
</script>
@endsection
