<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginCodeController extends Controller
{
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
            ->paginate(20);

        return view('admin.codes.index', compact('codes', 'q'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name']);
        return view('admin.codes.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $data = $request->validate([
            // Jika quantity == 1 boleh input manual "code" (opsional).
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

        // Hindari NULL untuk DB yang NOT NULL: paksa default 1 bila kosong
        // (Kalau di DB sudah nullable, ini tetap aman)
        $maxUsesInput = $data['max_uses'] ?? null;
        $maxUses = $isOneTime
            ? 1                       // one-time => batasi 1 secara praktis
            : ($maxUsesInput ?: 1);   // multi-use => default 1 jika kosong

        $toCreate = [];

        // Jika hanya 1 kode & user mengisi "code" manual, pakai itu.
        if ($qty === 1 && !empty($data['code'])) {
            $codeStr = $this->normalizeCode($data['code']);
            // Pastikan unik
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
            // Generate banyak kode
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

    public function update(Request $request, LoginCode $code)
    {
        // Update ringan: label, user_id, aktif, one-time, max_uses, expires_at
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

        // Sama seperti store: jangan biarkan null ke DB NOT NULL
        $maxUsesInput = $data['max_uses'] ?? null;
        $code->max_uses = $code->is_one_time
            ? 1
            : ($maxUsesInput ?: ($code->max_uses ?: 1));

        $code->expires_at  = $data['expires_at'] ?? $code->expires_at;
        $code->save();

        return back()->with('ok', 'Kode diperbarui.');
    }

    public function toggle(LoginCode $code)
    {
        $code->is_active = !$code->is_active;
        $code->save();

        return back()->with('ok', 'Status aktif kode diubah.');
    }

    public function destroy(LoginCode $code)
    {
        $code->delete();
        return back()->with('ok', 'Kode dihapus.');
    }

    // =========================
    // Helpers
    // =========================
    protected function normalizeCode(string $raw): string
    {
        // Rapikan: upper, hilangkan spasi, pastikan format XXX-XXXX-XXXX-ish
        $t = strtoupper(preg_replace('/[^A-Z0-9]/', '', $raw));
        // Kelompokkan jadi 4-4-4 (atau sesuai panjang)
        $parts = str_split($t, 4);
        return implode('-', $parts);
    }

    protected function generateUniqueCode(): string
    {
        do {
            // Format: XXXX-XXXX-XXXX (alnum uppercase)
            $c = strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (LoginCode::where('code', $c)->exists());

        return $c;
    }
}
