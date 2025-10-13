<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    /**
     * Redirect helper ketika memilih election.
     */
    public function selectElection(Election $election)
    {
        return redirect()->route('vote.index', $election);
    }

    /**
     * Halaman vote.
     * - $hasAnyVote: apakah user sudah pernah vote di election mana pun (GLOBAL).
     * - $myVote: vote user di election ini (untuk ditampilkan jika sudah ada).
     * - $allCandidates: daftar kandidat gabungan semua posisi (untuk single-choice).
     * - $positions: opsional, kalau mau tetap tampilkan grouping posisi di UI.
     */
    public function index(Election $election)
    {
        $user = auth()->user();

        // GLOBAL: user sudah pernah vote di election mana pun
        $hasAnyVote = Vote::where('user_id', $user->id)->exists();

        // Vote user pada election ini (untuk info di UI)
        $myVote = Vote::where('election_id', $election->id)
            ->where('user_id', $user->id)
            ->with('candidate.position')
            ->first();

        // Kandidat gabungan (untuk single-choice)
        $allCandidates = $election->candidates()
            ->with('position')
            ->orderBy('position_id')
            ->orderBy('order')
            ->get();

        // Opsional: tetap kirim positions kalau UI butuh
        $positions = $election->positions()
            ->with(['candidates' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();

        return view('vote.index', [
            'election'      => $election,
            'positions'     => $positions,
            'allCandidates' => $allCandidates,
            'myVote'        => $myVote,
            'hasAnyVote'    => $hasAnyVote,
        ]);
    }

    /**
     * Simpan suara.
     * Aturan saat ini: 1 user = 1 suara GLOBAL.
     * Jika ingin "1 user = 1 suara PER ELECTION", ubah pengecekan $already sesuai komentar.
     */
    public function store(Request $request, Election $election)
    {
        $userId = auth()->id();

        // ====== PEMBATAS GLOBAL (aktif sekarang) ======
        $already = Vote::where('user_id', $userId)->exists();

        // ====== PEMBATAS PER ELECTION (alternatif) ======
        // $already = Vote::where('election_id', $election->id)->where('user_id', $userId)->exists();

        if ($already) {
            return back()
                ->withErrors(['vote' => 'Anda sudah memberikan suara. Setiap pengguna hanya boleh 1 suara.'])
                ->withInput();
        }

        // Normalisasi input: utamakan single-choice `candidate_id`
        $candidateId = null;

        if ($request->filled('candidate_id')) {
            $request->validate([
                'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
            ]);
            $candidateId = (int) $request->input('candidate_id');
        } elseif (is_array($request->input('votes'))) {
            // Fallback untuk form lama (multi-position): ambil kandidat pertama yang ada
            $votes = $request->input('votes', []);
            foreach ($votes as $row) {
                if (!empty($row['candidate_id'])) {
                    $candidateId = (int) $row['candidate_id'];
                    break;
                }
            }
            if (!$candidateId) {
                return back()->withErrors(['vote' => 'Silakan pilih satu kandidat.'])->withInput();
            }
            $request->merge(['candidate_id' => $candidateId]);
            $request->validate([
                'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
            ]);
        } else {
            return back()->withErrors(['vote' => 'Silakan pilih satu kandidat.'])->withInput();
        }

        // Ambil kandidat + validasi kandidat milik election ini
        $candidate = Candidate::with('position')->findOrFail($candidateId);
        if ($candidate->election_id !== $election->id) {
            return back()->withErrors(['vote' => 'Kandidat tidak termasuk dalam pemilihan ini.'])->withInput();
        }

        // Simpan vote (ambil position_id dari kandidat)
        DB::transaction(function () use ($election, $candidate, $userId) {
            Vote::create([
                'election_id'  => $election->id,
                'position_id'  => $candidate->position_id,
                'candidate_id' => $candidate->id,
                'user_id'      => $userId,
            ]);
        });

        // === Redirect sesuai peran ===
        if (auth()->user()->is_admin) {
            // Admin boleh lihat hasil
            return redirect()
                ->route('admin.elections.results.index', $election)
                ->with('ok', 'Terima kasih, suara Anda tercatat!');
        }

        // User biasa balik ke beranda (hasil dibatasi untuk admin)
        return redirect()
            ->route('home')
            ->with('ok', 'Terima kasih, suara Anda tercatat!');
    }
}
