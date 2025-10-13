@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
  <h1 class="text-2xl font-bold mb-4">Edit Election</h1>

  @if(session('ok'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-300 rounded">
      {{ session('ok') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded">
      <ul class="list-disc ps-5">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.elections.update',$election) }}">
    @csrf
    @method('PUT')

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Nama</label>
      <input type="text" name="name" value="{{ old('name',$election->name) }}"
             class="w-full border rounded p-2 mt-1" required>
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
      <textarea name="description" rows="3"
        class="w-full border rounded p-2 mt-1">{{ old('description',$election->description) }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Mulai</label>
        <input type="datetime-local" name="starts_at"
               value="{{ old('starts_at', optional($election->starts_at)->format('Y-m-d\TH:i')) }}"
               class="w-full border rounded p-2 mt-1">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Selesai</label>
        <input type="datetime-local" name="ends_at"
               value="{{ old('ends_at', optional($election->ends_at)->format('Y-m-d\TH:i')) }}"
               class="w-full border rounded p-2 mt-1">
      </div>
    </div>

    {{-- Aktif --}}
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Aktif</label>
      {{-- hidden agar saat uncheck tetap terkirim --}}
      <input type="hidden" name="is_active" value="0">
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1"
          {{ old('is_active', $election->is_active) ? 'checked' : '' }}
          class="h-4 w-4 text-blue-600 rounded">
        <span class="text-sm text-gray-700">Jadikan election aktif</span>
      </label>
      @error('is_active')
        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
      @enderror
    </div>

    <div class="flex gap-2">
      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
      <a href="{{ route('admin.elections.index') }}" class="px-4 py-2 border rounded">Kembali</a>
      <a href="{{ route('admin.elections.positions.index',$election) }}" class="px-4 py-2 border rounded ms-2">Kelola Positions</a>
    </div>
  </form>
</div>
@endsection
