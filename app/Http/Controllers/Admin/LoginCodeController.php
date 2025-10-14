<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
// (opsional PDF)
use Barryvdh\DomPDF\Facade\Pdf;

class LoginCodeController extends Controller
{
    /** List kode login + pencarian sederhana (?q=...) */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $codes = LoginCode::with(['user', 'creator'])
            ->when($q, function ($x) use ($q) {
                $x->where(function ($w) use ($q) {
                    $w->where('code', 'like', "%{$q}%")
                      ->orWhere('label', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.codes.index', compact('codes', 'q'));
    }

    /** Form create */
    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name']);
        return view('admin.codes.create', compact('users'));
    }

    /** Simpan kode (bisa bulk via quantity) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code'        => ['nullable', 'string', 'max:32'],
            'label'       => ['nullable', 'string', 'max:120'],
            'user_id'     => ['nullable', 'integer', 'exists:users,id'],
            'is_active'   => ['nullable'],
            'is_one_time' => ['nullable'],
            'max_uses'    => ['nullable', 'integer', 'min:1'],
            'quantity'    => ['required', 'integer', 'min:1', 'max:500'],
            'expires_at'  => ['nullable', 'date'],
        ]);

        $qty        = (int) $data['quantity'];
        $label      = $data['label'] ?? null;
        $userId     = $data['user_id'] ?? null;
        $expiresAt  = $data['expires_at'] ?? null;

        $isActive   = $request->boolean('is_active', true);
        $isOneTime  = $request->boolean('is_one_time', false);

        $maxUsesInput = $data['max_uses'] ?? null;
        $maxUses = $isOneTime ? 1 : ($maxUsesInput ?: 1);

        $toCreate = [];

        if ($qty === 1 && !empty($data['code'])) {
            $codeStr = $this->normalizeCode($data['code']);
            if (LoginCode::where('code', $codeStr)->exists()) {
                return back()->withErrors(['code' => 'Kode sudah dipakai, gunakan kode lain.'])->withInput();
            }

            $toCreate[] = [
                'code'        => $codeStr,
                'label'       => $label,
                'user_id'     => $userId,
                'is_active'   => $isActive,
                'is_one_time' => $isOneTime,
                'max_uses'    => $maxUses,
                'expires_at'  => $expiresAt,
                'created_by'  => auth()->id(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        } else {
            for ($i = 0; $i < $qty; $i++) {
                $codeStr = $this->generateUniqueCode();

                $toCreate[] = [
                    'code'        => $codeStr,
                    'label'       => $label,
                    'user_id'     => $userId,
                    'is_active'   => $isActive,
                    'is_one_time' => $isOneTime,
                    'max_uses'    => $maxUses,
                    'expires_at'  => $expiresAt,
                    'created_by'  => auth()->id(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        LoginCode::insert($toCreate);

        return redirect()
            ->route('admin.codes.index')
            ->with('ok', "Berhasil membuat {$qty} kode.");
    }

    /** Update ringan */
    public function update(Request $request, LoginCode $code)
    {
        $data = $request->validate([
            'label'       => ['nullable', 'string', 'max:120'],
            'user_id'     => ['nullable', 'integer', 'exists:users,id'],
            'is_active'   => ['nullable'],
            'is_one_time' => ['nullable'],
            'max_uses'    => ['nullable', 'integer', 'min:1'],
            'expires_at'  => ['nullable', 'date'],
        ]);

        $code->label       = $data['label'] ?? $code->label;
        $code->user_id     = $data['user_id'] ?? $code->user_id;
        $code->is_active   = $request->boolean('is_active', $code->is_active);
        $code->is_one_time = $request->boolean('is_one_time', $code->is_one_time);

        $maxUsesInput = $data['max_uses'] ?? null;
        $code->max_uses = $code->is_one_time ? 1 : ($maxUsesInput ?: ($code->max_uses ?: 1));

        $code->expires_at  = $data['expires_at'] ?? $code->expires_at;
        $code->save();

        return back()->with('ok', 'Kode diperbarui.');
    }

    /** Toggle aktif/nonaktif */
    public function toggle(LoginCode $code)
    {
        $code->is_active = !$code->is_active;
        $code->save();

        return back()->with('ok', 'Status aktif kode diubah.');
    }

    /** Hapus kode */
    public function destroy(LoginCode $code)
    {
        $code->delete();
        return back()->with('ok', 'Kode dihapus.');
    }

    // =======================
    // EXPORT (HANYA 2 KOLOM)
    // =======================

    /** Export CSV: hanya Code & Masa Aktif (expires_at) */
    public function exportCsv(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $codes = LoginCode::query()
            ->select(['code', 'expires_at'])
            ->when($q, function ($x) use ($q) {
                $x->where(function ($s) use ($q) {
                    $s->where('code','like',"%{$q}%")
                      ->orWhere('label','like',"%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        $filename = 'login-codes-' . now()->format('Ymd-His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($codes) {
            $out = fopen('php://output', 'w');
            // BOM untuk Excel (UTF-8)
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header 2 kolom
            fputcsv($out, ['Code', 'Masa Aktif Sampai']);

            foreach ($codes as $c) {
                $exp = $c->expires_at ? $c->expires_at->format('Y-m-d H:i') : '-';
                fputcsv($out, [$c->code, $exp]);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export PDF: hanya Code & Masa Aktif (kamu kondisikan di Blade)
     * Syarat: view 'admin.codes.pdf' meng-handle $onlyCodeAndExpiry = true
     */
    public function exportPdf(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $codes = LoginCode::query()
            ->select(['code', 'expires_at'])
            ->when($q, function ($x) use ($q) {
                $x->where(function ($s) use ($q) {
                    $s->where('code','like',"%{$q}%")
                      ->orWhere('label','like',"%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('admin.codes.pdf', [
            'codes'              => $codes,
            'q'                  => $q,
            'title'              => 'Daftar Kode Login',
            'onlyCodeAndExpiry'  => true, // <-- pakai flag ini di Blade
        ])->setPaper('a4', 'portrait');

        $filename = 'login-codes-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->download($filename);
    }

    // =========================
    // Helpers
    // =========================

    /** Rapikan manual code: uppercase + grup 4-4-4 */
    protected function normalizeCode(string $raw): string
    {
        $t = strtoupper(preg_replace('/[^A-Z0-9]/', '', $raw));
        $parts = str_split($t, 4);
        return implode('-', $parts);
    }

    /** Generate kode unik: XXXX-XXXX-XXXX */
    protected function generateUniqueCode(): string
    {
        do {
            $c = strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (LoginCode::where('code', $c)->exists());

        return $c;
    }
}
