<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem E-Voting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Favicon (logo.png di folder public) --}}
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <meta name="theme-color" content="#0ea5e9">

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

  {{-- Navbar --}}
  <header class="bg-white shadow sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex h-14 items-center justify-between">
        {{-- Brand --}}
        <a href="{{ route('home') }}" class="font-bold text-lg text-blue-700 hover:text-blue-800">
          Sistem E-Voting
        </a>

        {{-- Desktop nav (md+) --}}
        <nav class="hidden md:flex items-center gap-4">
          @auth
            @if(auth()->user()->is_admin ?? false)
              <a href="{{ route('admin.dashboard') }}"
                 class="inline-flex items-center px-3 py-1.5 text-sm rounded border border-gray-200 bg-gray-900 text-white hover:bg-black">
                Admin Panel
              </a>
            @endif

            {{-- User dropdown (native, tanpa JS) --}}
            <details class="relative group">
              <summary class="list-none cursor-pointer select-none inline-flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600">
                <div class="h-8 w-8 rounded-full bg-blue-600/10 flex items-center justify-center text-blue-700 font-semibold">
                  {{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}
                </div>
                <span class="hidden sm:inline">Hi, {{ auth()->user()->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-70 group-open:rotate-180 transition"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
              </summary>
              <div class="absolute right-0 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow-lg py-1">
                @if(auth()->user()->is_admin ?? false)
                  <a href="{{ route('admin.dashboard') }}"
                     class="block px-3 py-2 text-sm hover:bg-gray-50">Admin Panel</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit"
                          class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                    Logout
                  </button>
                </form>
              </div>
            </details>
          @else
            {{-- (opsional) tombol login/daftar bisa ditaruh di sini --}}
            {{-- <a href="{{ route('login.show') }}" class="text-sm text-blue-600 hover:underline">Login</a> --}}
            {{-- <a href="{{ route('register.show') }}" class="text-sm text-blue-600 hover:underline">Register</a> --}}
          @endauth
        </nav>

        {{-- Mobile: hamburger (hanya saat login) --}}
        @auth
        <button id="navToggle"
                class="md:hidden inline-flex items-center justify-center h-9 w-9 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-controls="mobileMenu" aria-expanded="false" aria-label="Buka menu navigasi">
          <svg id="iconMenu" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
        @endauth
      </div>
    </div>

    {{-- Mobile menu (hanya saat login) --}}
    @auth
    <div id="mobileMenu" class="md:hidden hidden border-t border-gray-200 bg-white">
      <div class="max-w-7xl mx-auto px-4 py-3 space-y-2">
        @if(auth()->user()->is_admin ?? false)
          <a href="{{ route('admin.dashboard') }}"
             class="block w-full px-3 py-2 rounded-lg text-sm border border-gray-200 bg-gray-900 text-white hover:bg-black">
            Admin Panel
          </a>
        @endif

        <div class="flex items-center gap-3 px-1 py-2">
          <div class="h-9 w-9 rounded-full bg-blue-600/10 flex items-center justify-center text-blue-700 font-semibold">
            {{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}
          </div>
          <div class="text-sm">
            <div class="font-medium text-gray-800">Hi, {{ auth()->user()->name }}</div>
            <div class="text-gray-500">Akun aktif</div>
          </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="pt-1">
          @csrf
          <button type="submit"
                  class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50 border border-transparent hover:border-red-200">
            Logout
          </button>
        </form>
      </div>
    </div>
    @endauth
  </header>

  {{-- Konten --}}
  <main class="flex-1 py-6 px-4">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="bg-white border-t mt-6 py-4 text-center text-sm text-gray-600">
    © 2025 Laravel. Dibuat oleh Tim Developer.
  </footer>

  {{-- Script kecil untuk toggle hamburger --}}
  <script>
    const navToggle = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const iconMenu = document.getElementById('iconMenu');
    const iconClose = document.getElementById('iconClose');

    if (navToggle && mobileMenu && iconMenu && iconClose) {
      navToggle.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden', isOpen);
        iconMenu.classList.toggle('hidden', !isOpen === true);
        iconClose.classList.toggle('hidden', !isOpen === false);
        navToggle.setAttribute('aria-expanded', (!isOpen).toString());
      });
    }
  </script>
</body>
</html>
