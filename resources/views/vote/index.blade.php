@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
  <div class="bg-white border rounded-xl shadow-sm p-6">
    <div class="flex items-start justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $election->name }}</h1>
      </div>
      <span class="px-2.5 py-1 rounded-full text-xs
        {{ $election->isOpen() ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-700' }}">
        {{ $election->isOpen() ? 'TERBUKA' : 'TERTUTUP' }}
      </span>
    </div>

    {{-- Sudah vote --}}
    @if($hasAnyVote)
      <div class="mt-5 p-4 rounded-lg border border-blue-300 bg-blue-50 text-blue-800">
        <div class="font-semibold">Anda sudah memberikan suara.</div>
        @if($myVote)
          <div class="text-sm mt-1">
            Pilihan Anda: <b>{{ $myVote->candidate->name }}</b>
            ({{ $myVote->candidate->position->name }})
          </div>
        @endif
      </div>
    @else
      {{-- Form voting --}}
      <form method="POST" action="{{ route('vote.store',$election) }}" class="mt-5 space-y-5">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
          @foreach($allCandidates as $c)
            <label class="block p-4 border rounded-xl hover:bg-gray-50 cursor-pointer">
              <div class="flex items-start gap-3">
                <input type="radio" name="candidate_id" value="{{ $c->id }}" class="mt-1 h-4 w-4 text-blue-600">
                <div class="w-full">
                  <div class="flex items-center justify-between">
                    <p class="font-semibold text-gray-800">{{ $c->name }}</p>
                    <span class="text-[11px] px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                      {{ $c->position->name }}
                    </span>
                  </div>

                  @if($c->photo_path)
                    <img src="{{ asset('storage/'.$c->photo_path) }}"
                         alt="{{ $c->name }}"
                         class="h-24 mt-2 rounded object-cover cursor-zoom-in candidate-photo"
                         data-full="{{ asset('storage/'.$c->photo_path) }}">
                  @endif

                  @if($c->vision)
                    <p class="mt-2 text-xs text-gray-700"><b>Visi:</b> {{ \Illuminate\Support\Str::limit($c->vision, 140) }}</p>
                  @endif
                  @if($c->mission)
                    <p class="mt-1 text-xs text-gray-700"><b>Misi:</b> {{ \Illuminate\Support\Str::limit($c->mission, 160) }}</p>
                  @endif
                </div>
              </div>
            </label>
          @endforeach
        </div>

        <div class="pt-2">
          <button type="submit" class="w-full md:w-auto px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
            Kirim Pilihan
          </button>
        </div>
      </form>
    @endif
  </div>
</div>

{{-- Modal Zoom Foto --}}
<div id="photoModal" class="fixed inset-0 hidden bg-black/70 flex items-center justify-center z-50">
  <div class="relative">
    <button id="closeModal" class="absolute -top-4 -right-4 bg-white rounded-full shadow p-2 hover:bg-gray-200">
      ✕
    </button>
    <img id="modalImage" src="" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-lg">
  </div>
</div>

{{-- Script Zoom --}}
<script>
  const modal = document.getElementById('photoModal');
  const modalImg = document.getElementById('modalImage');
  const closeBtn = document.getElementById('closeModal');

  document.querySelectorAll('.candidate-photo').forEach(img => {
    img.addEventListener('click', () => {
      modalImg.src = img.dataset.full;
      modal.classList.remove('hidden');
    });
  });

  closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.classList.add('hidden');
  });
</script>
@endsection
