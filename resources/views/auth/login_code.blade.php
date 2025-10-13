@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
  <div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-xl font-semibold mb-1">Login dengan Kode</h1>
    <p class="text-sm text-gray-500 mb-4">Masukkan kode yang diberikan admin.</p>

    @if(session('ok'))
      <div class="mb-3 p-3 text-sm bg-green-50 border border-green-200 text-green-800 rounded">
        {{ session('ok') }}
      </div>
    @endif
    @if($errors->any())
      <div class="mb-3 p-3 text-sm bg-red-50 border border-red-200 text-red-800 rounded">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="post" action="{{ route('login.code') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium mb-1">Kode Login</label>
        <input type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: ABCD-1234-EFGH"
               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
               required>
      </div>

      {{-- Opsional: jika code tidak diikat ke user, kita minta nama untuk dibuatkan akun --}}
      <div>
        <label class="block text-sm font-medium mb-1">Nama (opsional)</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda"
               class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak perlu.</p>
      </div>

      <button class="w-full px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
        Masuk
      </button>
    </form>

    <div class="mt-4 text-center">
      <a href="{{ route('login.show') }}" class="text-sm text-gray-600 hover:underline">Login dengan Email/Password</a>
    </div>
  </div>
</div>
@endsection
