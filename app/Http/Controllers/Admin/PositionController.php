<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Election $election)
    {
        $positions = $election->positions()->orderBy('order')->paginate(20);
        return view('admin.positions.index', compact('election','positions'));
    }

    public function create(Election $election)
    {
        return view('admin.positions.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $data = $request->validate([
            'name'  => ['required','string','max:120'],
            'order' => ['required','numeric','min:0'],
            'quota' => ['required','integer','min:1'],
        ], [
            'order.numeric' => 'Urut harus berupa angka.',
            'quota.integer' => 'Quota harus bilangan bulat.',
        ]);

        // Pastikan tersimpan integer meski input "01"
        $data['order'] = (int) $data['order'];
        $data['election_id'] = $election->id;

        Position::create($data);

        return redirect()
            ->route('admin.elections.positions.index', $election)
            ->with('ok', 'Position berhasil ditambahkan');
    }

    public function edit(Position $position)
    {
        $election = $position->election;
        return view('admin.positions.edit', compact('election','position'));
    }

    public function update(Request $request, Position $position)
    {
        $data = $request->validate([
            'name'  => ['required','string','max:120'],
            'order' => ['required','numeric','min:0'],
            'quota' => ['required','integer','min:1'],
        ]);

        $data['order'] = (int) $data['order'];
        $position->update($data);

        return redirect()
            ->route('admin.elections.positions.index', $position->election)
            ->with('ok', 'Position berhasil diupdate');
    }

    public function destroy(Position $position)
    {
        $election = $position->election;
        $position->delete();

        return redirect()
            ->route('admin.elections.positions.index', $election)
            ->with('ok', 'Position berhasil dihapus');
    }
}
