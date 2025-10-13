<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $elections = Election::when($q, fn($qq)=>$qq->where('name','like',"%$q%"))
            ->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('admin.elections.index', compact('elections','q'));
    }

    public function create(){ return view('admin.elections.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>['required','string','max:160'],
            'description'=>['nullable','string'],
            'starts_at'=>['nullable','date'],
            'ends_at'=>['nullable','date','after_or_equal:starts_at'],
            'is_active'=>['boolean']
        ]);
        Election::create($data);
        return redirect()->route('admin.elections.index')->with('ok','Election dibuat');
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $data = $request->validate([
            'name'=>['required','string','max:160'],
            'description'=>['nullable','string'],
            'starts_at'=>['nullable','date'],
            'ends_at'=>['nullable','date','after_or_equal:starts_at'],
            'is_active'=>['required','boolean']
        ]);
        $election->update($data);
        return back()->with('ok','Election diupdate');
    }

    public function destroy(Election $election)
    {
        $election->delete();
        return back()->with('ok','Election dihapus');
    }
}
