@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6">
  <h1 class="text-xl font-semibold mb-4">Buat Kode Login</h1>

  @if($errors->any())
    <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded">
      <ul class="list-disc ps-5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.codes.store') }}" class="space-y-4">
    @csrf

    {{-- Code (opsional) --}}
    <div>
      <label class="block text-sm font-medium mb-1">Code (opsional)</label>
      <div class="flex gap-2">
        <input id="code" name="code" value="{{ old('code') }}"
               class="flex-1 rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
               placeholder="Biarkan kosong untuk auto-generate">
        <button type="button" id="btnGen"
                class="px-3 py-2 rounded bg-gray-800 text-white text-sm">Generate</button>
      </div>
      <p class="text-xs text-gray-500 mt-1">Jika “Jumlah Kode” &gt; 1, input single code ini diabaikan.</p>
    </div>

    {{-- Label --}}
    <div>
      <label class="block text-sm font-medium mb-1">Label (opsional)</label>
      <input name="label" value="{{ old('label') }}"
             class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
             placeholder="Contoh: Gelombang 1">
    </div>

    {{-- Status & One-time --}}
    <div class="flex items-center gap-6">
      <label class="inline-flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="rounded">
        <span>Aktif</span>
      </label>

      <label class="inline-flex items-center gap-2">
        <input type="hidden" name="is_one_time" value="0">
        <input type="checkbox" name="is_one_time" value="1" @checked(old('is_one_time')) class="rounded">
        <span>Hanya sekali pakai (abaikan Maks. Pakai)</span>
      </label>
    </div>

    {{-- Maks. Pakai --}}
    <div>
      <label class="block text-sm font-medium mb-1">Maks. Pakai</label>
      <input type="number" name="max_uses" min="1" value="{{ old('max_uses') }}"
             class="w-40 rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
             placeholder="Kosongkan = tak terbatas">
    </div>

    {{-- Jumlah Kode --}}
    <div>
      <label class="block text-sm font-medium mb-1">Jumlah Kode</label>
      <div class="flex items-center gap-3">
        <input type="number" name="quantity" id="quantity" min="1" max="10000"
               value="{{ old('quantity', 1) }}"
               class="w-40 rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        <span id="qtyInfo" class="text-xs text-gray-500">
          Diabaikan jika kamu mengisi daftar email (otomatis 1 kode per email).
        </span>
      </div>
    </div>

    {{-- Daftar Email (opsional, satu per baris) --}}
    <div>
      <label class="block text-sm font-medium mb-1">Daftar Email (opsional)</label>
      <textarea name="emails" id="emails" rows="6"
                class="w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                placeholder="satu@email.com
kedua@email.com
ketiga@email.com">{{ old('emails') }}</textarea>
      <p class="text-xs text-gray-500 mt-1">Jika diisi, sistem membuat <b>1 kode per email</b>.</p>
    </div>

    {{-- Kirim Email Otomatis --}}
    <div class="flex items-center gap-6">
      <label class="inline-flex items-center gap-2">
        <input type="hidden" name="send_email_now" value="0">
        <input type="checkbox" name="send_email_now" value="1" @checked(old('send_email_now', true)) class="rounded">
        <span>Kirim email otomatis ke daftar email di atas</span>
      </label>
    </div>

    {{-- Kedaluwarsa --}}
    <div>
      <label class="block text-sm font-medium mb-1">Kedaluwarsa (opsional)</label>
      <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
             class="w-60 rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500">
    </div>

    <div class="flex items-center gap-2 pt-2">
      <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
      <a href="{{ route('admin.codes.index') }}" class="px-4 py-2 rounded border">Kembali</a>
    </div>
  </form>
</div>

<script>
  // --- Generator kode manual ---
  function genCode() {
    const rand = (n)=> Math.random().toString(36).toUpperCase().slice(2, 2+n).replace(/[^A-Z0-9]/g,'');
    return `${rand(4)}-${Math.floor(1000+Math.random()*9000)}-${rand(4)}`;
  }
  document.getElementById('btnGen')?.addEventListener('click', ()=>{
    const el = document.getElementById('code');
    if (el) el.value = genCode();
  });

  // --- Helper: validasi email sederhana untuk feedback UI ---
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/i;

  const emailsTA = document.getElementById('emails');
  const qtyInput = document.getElementById('quantity');
  const qtyInfo  = document.getElementById('qtyInfo');
  const form     = document.querySelector('form[action="{{ route('admin.codes.store') }}"]');

  function updateQuantityState() {
    const linesRaw = (emailsTA?.value || '').split(/\r?\n/);
    const emails = linesRaw
      .map(s => s.trim().toLowerCase())
      .filter(Boolean);

    // unik + valid (untuk feedback)
    const unique = emails.filter((v, i, arr) => arr.indexOf(v) === i);
    const valid  = unique.filter(e => emailRegex.test(e));

    if (valid.length > 0) {
      // Ada daftar email valid -> quantity diabaikan dan dinonaktifkan
      qtyInput?.setAttribute('disabled', 'disabled');
      if (qtyInfo) {
        qtyInfo.innerText = `Jumlah Kode mengikuti daftar email: ${valid.length} kode (otomatis 1 per email).`;
      }
      // (opsional) set nilai qty agar user paham visualnya, walau field disabled tidak ikut submit
      qtyInput.value = String(valid.length);
    } else {
      // Tidak ada email valid -> quantity berlaku, default min 1
      qtyInput?.removeAttribute('disabled');
      if (!qtyInput.value || Number(qtyInput.value) < 1) qtyInput.value = '1';
      if (qtyInfo) {
        qtyInfo.innerText = 'Diabaikan jika kamu mengisi daftar email (otomatis 1 kode per email).';
      }
    }
  }

  emailsTA?.addEventListener('input', updateQuantityState);

  // Jaga-jaga: enforce min=1 saat submit kalau emails kosong/invalid semua
  form?.addEventListener('submit', () => {
    const hasEmails = (emailsTA?.value || '').trim().length > 0;
    if (!hasEmails) {
      if (!qtyInput.value || Number(qtyInput.value) < 1) qtyInput.value = '1';
    }
  });

  // Init saat load
  updateQuantityState();
</script>
@endsection
