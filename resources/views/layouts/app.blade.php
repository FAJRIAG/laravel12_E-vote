<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem E-Voting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

  {{-- Navbar --}}
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
      <a href="{{ route('home') }}" class="font-bold text-lg text-blue-700">Sistem E-Voting</a>

      <nav class="flex items-center gap-3">
        @auth
            {{-- Tampilkan tombol Admin Panel bila user adalah admin --}}
            @if(auth()->user()->is_admin ?? false)
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center px-3 py-1.5 text-sm rounded bg-gray-900 text-white hover:bg-black">
                    Admin Panel
                </a>
{{--                 <a href="{{ route('admin.elections.index') }}"
                   class="text-sm text-gray-700 hover:text-blue-600">
                    Elections
                </a> --}}
            @endif

            <span class="text-sm">Hi, {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-red-600 hover:underline">Logout</button>
            </form>
        @else
            {{-- <a href="{{ route('login.show') }}" class="text-blue-600 hover:underline">Login</a> --}}
            {{-- <a href="{{ route('register.show') }}" class="text-blue-600 hover:underline">Register</a> --}}
        @endauth
      </nav>
    </div>
  </header>

  {{-- Konten --}}
  <main class="flex-1 py-6 px-4">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="bg-white border-t mt-6 py-4 text-center text-sm text-gray-600">
    © 2025 Laravel. Dibuat oleh Tim Developer.
  </footer>

</body>
</html>
