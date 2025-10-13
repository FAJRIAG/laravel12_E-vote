@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Register</h1>
<form method="post" action="{{ route('register') }}" class="bg-white p-4 rounded shadow max-w-md">
  @csrf
  <label class="block mb-2 text-sm">Nama</label>
  <input name="name" class="w-full border rounded p-2 mb-3" value="{{ old('name') }}" required>
  <label class="block mb-2 text-sm">Email</label>
  <input name="email" type="email" class="w-full border rounded p-2 mb-3" value="{{ old('email') }}" required>
  <label class="block mb-2 text-sm">Password</label>
  <input name="password" type="password" class="w-full border rounded p-2 mb-3" required>
  <label class="block mb-2 text-sm">Konfirmasi Password</label>
  <input name="password_confirmation" type="password" class="w-full border rounded p-2 mb-3" required>
  <button class="px-3 py-2 bg-blue-600 text-white rounded">Daftar</button>
</form>
@endsection
