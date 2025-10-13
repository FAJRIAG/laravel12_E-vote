@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-semibold">Kode Login</h1>
  <a href="{{ route('admin.codes.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Buat Kode</a>
</div>

<form method="get" class="mb-3">
  <input class="border rounded p-2" name="q" value="{{ $q }}" placeholder="Cari kode/label...">
  <button class="px-3 py-2 bg-gray-900 text-white rounded">Cari</button>
</form>

<table class="w-full text-sm bg-white rounded shadow">
  <thead>
    <tr class="border-b">
      <th class="text-left p-2">Kode</th>
      <th class="text-left p-2">Label</th>
      <th class="text-left p-2">User</th>
      <th class="text-left p-2">Maks/Pakai</th>
      <th class="text-left p-2">Kadaluarsa</th>
      <th class="text-left p-2">Aktif</th>
      <th class="text-right p-2">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @foreach($codes as $c)
    <tr class="border-b">
      <td class="p-2 font-mono">{{ $c->code }}</td>
      <td class="p-2">{{ $c->label }}</td>
      <td class="p-2">{{ $c->user?->name ?? '—' }}</td>
      <td class="p-2">{{ $c->max_uses }} / {{ $c->used_count }}</td>
      <td class="p-2">{{ $c->expires_at?->format('d/m/Y H:i') ?? '—' }}</td>
      <td class="p-2">{{ $c->is_active ? 'Ya' : 'Tidak' }}</td>
      <td class="p-2 text-right">
        <form action="{{ route('admin.codes.toggle', $c) }}" method="post" class="inline">
          @csrf
          <button class="text-emerald-700">{{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
        </form>
        <form action="{{ route('admin.codes.destroy', $c) }}" method="post" class="inline"
              onsubmit="return confirm('Hapus kode ini?')">
          @csrf @method('DELETE')
          <button class="text-red-600 ms-2">Hapus</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>

<div class="mt-3">{{ $codes->links() }}</div>
@endsection
