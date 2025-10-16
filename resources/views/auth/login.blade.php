@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4">
  <div class="w-full max-w-md">
    {{-- Heading --}}
    <div class="text-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Masuk ke Akun</h1>
      <p class="text-sm text-gray-500 mt-1">Pilih metode login yang kamu inginkan.</p>
    </div>

    {{-- Alerts --}}
    @if (session('ok'))
      <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded">
        {{ session('ok') }}
      </div>
    @endif
    @if (session('status'))
      <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded">
        {{ session('status') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 border border-red-300 rounded">
        <ul class="list-disc ps-5">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
      {{-- Tabs --}}
      <div class="flex rounded-lg p-1 bg-gray-100 mb-6" role="tablist" aria-label="Metode Login">
        <button id="tab-email" type="button"
                class="flex-1 text-sm font-medium px-4 py-2 rounded-md bg-white shadow"
                aria-selected="true" aria-controls="panel-email">
          Email & Password
        </button>
        <button id="tab-code" type="button"
                class="flex-1 text-sm font-medium px-4 py-2 rounded-md"
                aria-selected="false" aria-controls="panel-code">
          Login via Kode
        </button>
      </div>

      {{-- Panel: Email --}}
      <div id="panel-email">
        <form method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
          @csrf

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                   placeholder="nama@email.com">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Password</label>
            <div class="relative">
              <input type="password" id="password" name="password" required autocomplete="current-password"
                     class="w-full rounded-lg border-gray-300 pr-10 focus:border-blue-500 focus:ring-blue-500"
                     placeholder="••••••••">
              <button type="button" id="togglePass"
                      class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700 flex items-center"
                      aria-label="Tampilkan/sembunyikan password">
                {{-- icon open --}}
                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{-- icon closed --}}
                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                        a9.977 9.977 0 012.395-4.362M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18" />
                </svg>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
              <input type="checkbox" name="remember" class="rounded text-blue-600">
              Ingat saya
            </label>
          </div>

          <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                           bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Masuk
            </button>
          </div>
        </form>
      </div>

      {{-- Panel: Kode --}}
      <div id="panel-code" class="hidden">
        <form method="POST" action="{{ route('login.code') }}" class="space-y-4" novalidate>
          @csrf
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="code">Kode Login</label>
            <input type="text" id="code" name="code" required
                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 uppercase tracking-widest"
                   placeholder="CTH: ABCD-1234" oninput="this.value=this.value.toUpperCase()">
            <p class="mt-1 text-xs text-gray-500">
              Masukkan kode yang dibagikan admin (satu kali pakai / sesuai pengaturan).
            </p>
          </div>

          <div class="pt-2">
            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                           bg-blue-600 text-white font-medium hover:bg-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Masuk
            </button>
          </div>
        </form>

        {{-- Optional: Form minta kode via email, hanya jika route tersedia --}}
        @if (Route::has('login.code.request.send'))
          <div class="mt-6">
            <button type="button" id="toggleRequestBox"
                    class="w-full text-sm text-emerald-700 hover:text-emerald-800 underline">
              Belum punya kode? Minta kode dikirim ke email
            </button>

            <div id="requestBox" class="hidden mt-3 border border-emerald-200 rounded-lg p-4 bg-emerald-50">
              <form method="POST" action="{{ route('login.code.request.send') }}" class="space-y-3" novalidate>
                @csrf
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1" for="req_email">Email</label>
                  <input type="email" id="req_email" name="email" required
                         class="w-full rounded-lg border-gray-300 focus:border-emerald-600 focus:ring-emerald-600"
                         placeholder="alamat@gmail.com">
                  <p class="mt-1 text-xs text-gray-600">Kode akan dikirim ke email ini (cek Inbox/Spam).</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1" for="req_name">Nama (opsional)</label>
                  <input type="text" id="req_name" name="name"
                         class="w-full rounded-lg border-gray-300 focus:border-emerald-600 focus:ring-emerald-600"
                         placeholder="Nama Anda">
                </div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                               bg-emerald-600 text-white font-medium hover:bg-emerald-700 focus:outline-none
                               focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2">
                  Kirim Kode ke Email
                </button>
              </form>
            </div>
          </div>
        @endif
      </div>
      {{-- /Panel Kode --}}
    </div>
  </div>
</div>

<script>
  // Toggle password
  const btn = document.getElementById('togglePass');
  const pass = document.getElementById('password');
  const eyeOpen = document.getElementById('eyeOpen');
  const eyeClosed = document.getElementById('eyeClosed');
  btn?.addEventListener('click', () => {
    const show = pass.type === 'password';
    pass.type = show ? 'text' : 'password';
    eyeOpen?.classList.toggle('hidden', show);
    eyeClosed?.classList.toggle('hidden', !show);
  });

  // Tabs
  const tabEmail = document.getElementById('tab-email');
  const tabCode = document.getElementById('tab-code');
  const panelEmail = document.getElementById('panel-email');
  const panelCode = document.getElementById('panel-code');

  function activate(tab) {
    const isEmail = tab === 'email';
    tabEmail.classList.toggle('bg-white', isEmail);
    tabEmail.classList.toggle('shadow', isEmail);
    tabEmail.setAttribute('aria-selected', isEmail ? 'true' : 'false');

    tabCode.classList.toggle('bg-white', !isEmail);
    tabCode.classList.toggle('shadow', !isEmail);
    tabCode.setAttribute('aria-selected', !isEmail ? 'true' : 'false');

    panelEmail.classList.toggle('hidden', !isEmail);
    panelCode.classList.toggle('hidden', isEmail);
  }

  tabEmail?.addEventListener('click', () => activate('email'));
  tabCode?.addEventListener('click', () => activate('code'));
  activate('email'); // default

  // Toggle request box (jika ada)
  document.getElementById('toggleRequestBox')?.addEventListener('click', () => {
    document.getElementById('requestBox')?.classList.toggle('hidden');
  });
</script>
@endsection
