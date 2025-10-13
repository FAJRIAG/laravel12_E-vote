<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel — Sistem E-Voting</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

  {{-- Header --}}
  <header class="bg-white/90 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-blue-700">Admin Panel</a>
        <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">v1.0</span>
      </div>
      <nav class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="text-sm text-slate-700 hover:text-blue-600">Halaman User</a>
        <span class="text-sm hidden sm:inline">Hi, {{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button class="text-red-600 hover:underline text-sm">Logout</button>
        </form>
      </nav>
    </div>
  </header>

  {{-- Breadcrumb (opsional) --}}
  @hasSection('breadcrumb')
    <div class="bg-white border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 text-sm text-slate-500">
        @yield('breadcrumb')
      </div>
    </div>
  @endif

  {{-- Konten utama dengan padding lega --}}
  <main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </div>
  </main>

  {{-- Footer --}}
  <footer class="bg-white border-t mt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-slate-600">
      © 2025 Laravel. Dibuat Tim Developer.
    </div>
  </footer>

</body>
</html>
