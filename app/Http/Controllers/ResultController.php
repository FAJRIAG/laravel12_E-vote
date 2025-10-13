<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // <-- WAJIB untuk DomPDF

class ResultController extends Controller
{
    // =========================
    // ==== ADMIN (Grafik) =====
    // =========================
    public function index(Election $election)
    {
        // Halaman admin: grafik realtime + dropdown posisi
        $positions = $election->positions()->orderBy('order')->get();
        $total     = Vote::where('election_id', $election->id)->count();

        return view('results.index', [
            'election'  => $election,
            'positions' => $positions,
            'total'     => $total,
            'isAdmin'   => true, // flag ke Blade untuk tampilkan kontrol admin
        ]);
    }

    // Data JSON untuk Chart.js (admin)
    public function json(Election $election, Request $request)
    {
        $positionId = $request->integer('position_id');

        $cands = Candidate::query()
            ->where('election_id', $election->id)
            ->when($positionId, fn($q) => $q->where('position_id', $positionId))
            ->withCount([
                'votes' => function ($q) use ($election, $positionId) {
                    $q->where('election_id', $election->id);
                    if ($positionId) {
                        $q->where('position_id', $positionId);
                    }
                }
            ])
            ->orderByDesc('votes_count')
            ->orderBy('order')
            ->get();

        return response()->json([
            'labels' => $cands->pluck('name'),
            'data'   => $cands->pluck('votes_count'),
        ]);
    }

    // =========================
    // ====== ADMIN PDF ========
    // =========================
    public function pdf(Election $election)
    {
        // Ambil posisi + kandidat + total suara per kandidat khusus election ini
        $positions = $election->positions()
            ->with(['candidates' => function ($q) use ($election) {
                $q->withCount([
                    'votes' => fn($v) => $v->where('election_id', $election->id)
                ])
                ->orderByDesc('votes_count')
                ->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        $grandTotal = Vote::where('election_id', $election->id)->count();

        // Render Blade ke PDF
        $pdf = Pdf::setOption('isRemoteEnabled', true)
            ->loadView('results.pdf', [
                'election'   => $election,
                'positions'  => $positions,
                'grandTotal' => $grandTotal,
            ]);

        // Nama file rapi
        $safeName = str($election->name)->slug('-');
        return $pdf->download("hasil-{$safeName}-{$election->id}.pdf");
        // Jika ingin tampil di browser:
        // return $pdf->stream("hasil-{$safeName}-{$election->id}.pdf");
    }

    // =========================
    // ====== USER RINGKAS =====
    // =========================
    public function userResults(Election $election)
    {
        // Halaman user: tanpa grafik realtime, hanya tabel ringkas
        $positions = $election->positions()
            ->with(['candidates' => function ($q) use ($election) {
                $q->withCount([
                    'votes' => fn($v) => $v->where('election_id', $election->id)
                ])
                ->orderByDesc('votes_count')
                ->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        $total = Vote::where('election_id', $election->id)->count();

        return view('results.user', [
            'election'  => $election,
            'positions' => $positions,
            'total'     => $total,
            'isAdmin'   => false,
        ]);
    }
}
