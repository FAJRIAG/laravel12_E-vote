<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index(Position $position)
    {
        // $this->authorize('manage', $position->election); // HAPUS baris ini

        $election   = $position->election;
        $candidates = $position->candidates()->orderBy('order')->paginate(20);

        return view('admin.candidates.index', compact('election','position','candidates'));
    }

    public function create(Position $position)
    {
        // $this->authorize('manage', $position->election); // HAPUS baris ini

        $election = $position->election;
        return view('admin.candidates.create', compact('election','position'));
    }

    public function store(Request $request, Position $position)
    {
        // $this->authorize('manage', $position->election); // HAPUS baris ini

        $data = $request->validate([
            'name'   => ['required','string','max:120'],
            'order'  => ['required','numeric','min:0'],
            'vision' => ['nullable','string'],
            'mission'=> ['nullable','string'],
            'photo'  => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $data['order']       = (int) $data['order'];
        $data['election_id'] = $position->election_id;
        $data['position_id'] = $position->id;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create($data);

        return redirect()->route('admin.positions.candidates.index', $position)
            ->with('ok','Calon ditambahkan');
    }

    public function edit(Candidate $candidate)
    {
        // $this->authorize('manage', $candidate->election); // HAPUS baris ini

        $election  = $candidate->election;
        $positions = $election->positions()->orderBy('order')->get();

        return view('admin.candidates.edit', compact('candidate','election','positions'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        // $this->authorize('manage', $candidate->election); // HAPUS baris ini

        $data = $request->validate([
            'name'        => ['required','string','max:120'],
            'order'       => ['required','numeric','min:0'],
            'vision'      => ['nullable','string'],
            'mission'     => ['nullable','string'],
            'position_id' => ['required','integer','exists:positions,id'],
            'photo'       => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $data['order'] = (int) $data['order'];

        if ($request->hasFile('photo')) {
            if ($candidate->photo_path && Storage::disk('public')->exists($candidate->photo_path)) {
                Storage::disk('public')->delete($candidate->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.positions.candidates.index', $candidate->position)
            ->with('ok','Calon diupdate');
    }

    public function destroy(Candidate $candidate)
    {
        // $this->authorize('manage', $candidate->election); // HAPUS baris ini

        if ($candidate->photo_path && Storage::disk('public')->exists($candidate->photo_path)) {
            Storage::disk('public')->delete($candidate->photo_path);
        }

        $position = $candidate->position;
        $candidate->delete();

        return redirect()->route('admin.positions.candidates.index', $position)
            ->with('ok','Calon dihapus');
    }
}
