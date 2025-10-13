<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\LoginCode; // <— tambahkan

class DashboardController extends Controller
{
    // Halaman beranda (user biasa)
    public function index()
    {
        $candidates = Candidate::orderBy('order')->get();
        $myVote = auth()->check() ? auth()->user()->votes()->latest()->first() : null;
        $total  = Vote::count();

        return view('home', compact('candidates', 'myVote', 'total'));
    }

    // Halaman admin dashboard
    public function admin()
    {
        $totalUsers = User::count();
        $totalVotes = Vote::count();

        // Ringkasan election + positions
        $elections = Election::withCount(['positions'])
            ->with(['positions' => fn($q) => $q->withCount('votes')])
            ->orderByDesc('created_at')
            ->get();

        // Total positions (aman jika tidak ada data)
        $positionsTotal = $elections->sum(fn($e) => $e->positions_count ?? ($e->positions?->count() ?? 0));

        // Ringkasan kandidat
        $candidates = Candidate::withCount('votes')->orderBy('order')->get();

        // ====== METRIK KODE LOGIN ======
        $now = now();
        $codesTotal  = LoginCode::count();
        $codesActive = LoginCode::where('is_active', 1)->count();

        // Kode yang "tersedia untuk dipakai"
        $codesAvailable = LoginCode::query()
            ->where('is_active', 1)
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
            })
            ->where(function ($q) {
                // one-time: belum pernah dipakai
                $q->where(function ($qq) {
                    $qq->where('is_one_time', 1)->whereNull('used_at');
                })
                // multi-use: belum mencapai max_uses (atau max_uses null = tanpa batas)
                ->orWhere(function ($qq) {
                    $qq->where('is_one_time', 0)
                       ->where(function ($qqq) {
                           $qqq->whereNull('max_uses')
                               ->orWhereColumn('uses_count', '<', 'max_uses');
                       });
                });
            })
            ->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVotes',
            'elections',
            'positionsTotal',
            'candidates',
            'codesTotal',
            'codesActive',
            'codesAvailable'
        ));
    }
}
