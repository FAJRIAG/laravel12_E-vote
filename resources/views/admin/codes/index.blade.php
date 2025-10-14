@extends('layouts.admin')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a>
  <span class="mx-1 text-slate-400">/</span>
  <span class="text-slate-500">Kode Login</span>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
  <div class="px-5 py-4 border-b flex flex-col md:flex-row md:items-center gap-3 md:gap-2 md:justify-between">
    <h1 class="text-lg font-semibold">Kode Login</h1>

    <div class="flex items-center gap-2">
      <form method="get" class="hidden md:flex items-center gap-2">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari kode / label..."
               class="rounded border-slate-300 text-sm">
        <button class="px-2.5 py-1.5 rounded bg-slate-800 text-white text-sm">Cari</button>
      </form>

      <a href="{{ route('admin.codes.export.csv', request()->only('q')) }}"
         class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
        Download CSV
      </a>

      <a href="{{ route('admin.codes.export.pdf', request()->only('q')) }}"
         class="px-3 py-2 rounded border text-sm hover:bg-gray-50">
        Download PDF
      </a>

      <a href="{{ route('admin.codes.create') }}"
         class="px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
        Buat Kode
      </a>
    </div>
  </div>

  @if(session('ok'))
    <div class="px-5 pt-4">
      <div class="p-3 text-sm text-green-700 bg-green-100 border border-green-300 rounded">
        {{ session('ok') }}
      </div>
    </div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-600">
        <tr>
          <th class="text-left px-5 py-3">Kode</th>
          <th class="text-left px-5 py-3">Label</th>
          <th class="text-left px-5 py-3">User</th>
          <th class="text-left px-5 py-3">Status</th>
          <th class="text-left px-5 py-3">Once</th>
          <th class="text-left px-5 py-3">Max</th>
          <th class="text-left px-5 py-3">Used</th>
          <th class="text-left px-5 py-3">Expired</th>
          <th class="text-left px-5 py-3">Last Used</th>
          <th class="text-right px-5 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($codes as $c)
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-3 font-mono">{{ $c->code }}</td>
            <td class="px-5 py-3">{{ $c->label ?? '—' }}</td>
            <td class="px-5 py-3">{{ optional($c->user)->name ?? '—' }}</td>
            <td class="px-5 py-3">
              <span class="text-xs px-2 py-1 rounded {{ $c->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $c->is_active ? 'Aktif' : 'Nonaktif' }}
              </span>
            </td>
            <td class="px-5 py-3">{{ $c->is_one_time ? 'Ya' : 'Tidak' }}</td>
            <td class="px-5 py-3">{{ $c->max_uses ?? '—' }}</td>
            <td class="px-5 py-3">{{ $c->used_count ?? 0 }}</td>
            <td class="px-5 py-3">{{ optional($c->expires_at)->format('d/m/Y H:i') ?? '—' }}</td>
            <td class="px-5 py-3">{{ optional($c->last_used_at)->format('d/m/Y H:i') ?? '—' }}</td>
            <td class="px-5 py-3 text-right">
              {{-- Toggle aktif --}}
              <form method="post" action="{{ route('admin.codes.toggle', $c) }}" class="inline">
                @csrf
                <button class="text-sm text-emerald-700 hover:underline" type="submit">
                  {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </form>

              {{-- Hapus --}}
              <form method="post" action="{{ route('admin.codes.destroy', $c) }}" class="inline ms-3"
                    onsubmit="return confirm('Hapus kode {{ $c->code }} ?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-600 hover:underline" type="submit">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="px-5 py-6 text-center text-slate-500">
              Belum ada kode login.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="px-5 py-4">
    {{ $codes->links() }}
  </div>
</div>
@endsection
