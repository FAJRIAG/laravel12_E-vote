@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
  <h1 class="text-2xl font-semibold mb-2">Selamat datang di Sistem E-Voting</h1>
  <p class="text-sm text-gray-500 mb-6">Silakan pilih pemilihan yang tersedia di bawah.</p>

  @php
    // fallback jika controller tidak kirim data
    $activeElections = $activeElections
      ?? \App\Models\Election::where('is_active', true)
          ->orderByDesc('starts_at')
          ->with(['positions.candidates']) // eager load candidates
          ->get();
  @endphp

  @if($activeElections->isEmpty())
    <div class="p-4 bg-white rounded shadow">
      <p class="text-gray-600">Belum ada election aktif saat ini.</p>
      @auth
        @if(auth()->user()->is_admin)
          <p class="mt-2">
            <a href="{{ route('admin.elections.index') }}" class="text-blue-600">Buat election baru di Admin</a>
          </p>
        @endif
      @endauth
    </div>
  @else
    <div class="grid md:grid-cols-2 gap-6">
      @foreach($activeElections as $e)
        <div class="p-5 bg-white rounded-xl shadow">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold">{{ $e->name }}</h2>
            <span class="text-xs px-2 py-1 rounded {{ $e->isOpen() ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
              {{ $e->isOpen() ? 'DIBUKA' : 'TERTUTUP' }}
            </span>
          </div>
{{--           <p class="text-sm text-gray-500 mt-1">
            {{ optional($e->starts_at)->format('d/m/Y H:i') }} — {{ optional($e->ends_at)->format('d/m/Y H:i') }}
          </p> --}}
          <p class="mt-2 text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($e->description, 140) }}</p>

          {{-- Kandidat preview --}}
          @if($e->positions->isNotEmpty())
            <div class="mt-4 grid grid-cols-2 gap-3">
              @foreach($e->positions as $pos)
                @foreach($pos->candidates->take(2) as $c) {{-- tampilkan 2 calon saja per posisi --}}
                  <div class="flex items-center gap-3">
                    @if($c->photo_path)
                      <img src="{{ asset('storage/'.$c->photo_path) }}" class="h-12 w-12 rounded-full object-cover border">
                    @else
                      <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">?</div>
                    @endif
                    <div>
                      <p class="text-sm font-medium">{{ $c->name }}</p>
                      <p class="text-xs text-gray-500">{{ $pos->name }}</p>
                    </div>
                  </div>
                @endforeach
              @endforeach
            </div>
          @endif

          <div class="mt-4 flex items-center gap-2">
            @auth
              @if(auth()->user()->is_admin)
                <a href="{{ route('admin.elections.results.index', $e) }}" class="px-3 py-2 border rounded">Lihat Hasil</a>
              @endif
            @endauth
            @if($e->isOpen())
              <a href="{{ route('vote.index', $e) }}" class="px-3 py-2 bg-blue-600 text-white rounded">Mulai Voting</a>
            @else
              <a href="{{ route('vote.select.election', $e) }}" class="px-3 py-2 bg-gray-900 text-white rounded">Detail</a>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
