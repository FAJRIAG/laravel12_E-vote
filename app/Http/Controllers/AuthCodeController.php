<?php

namespace App\Http\Controllers;

use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthCodeController extends Controller
{
    public function showForm()
    {
        // Jika masih login, beri info di view agar user bisa logout dulu (opsional)
        return view('auth.login_code');
    }

        public function authenticate(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:32'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        // 1) Normalisasi input: buang whitespace, UPPERCASE
        //    Simpan versi dengan dash & tanpa dash (untuk pencarian fleksibel)
        $entered       = strtoupper(preg_replace('/\s+/', '', $data['code']));
        $enteredNoDash = str_replace('-', '', $entered);

        // 2) Cari kode: exact match ATAU match setelah '-' dihapus
        $code = \App\Models\LoginCode::query()
            ->where('code', $entered)
            ->orWhereRaw("REPLACE(code, '-', '') = ?", [$enteredNoDash])
            ->first();

        if (!$code || !$code->isValid()) {
            return back()
                ->withErrors(['code' => 'Kode tidak valid / sudah kadaluarsa / tidak aktif / kuota habis.'])
                ->withInput();
        }

        // 3) Resolve user (terikat / tidak terikat)
        if (!empty($code->user_id)) {
            $user = \App\Models\User::find($code->user_id);
            if (!$user) {
                return back()
                    ->withErrors(['code' => 'Kode terikat ke user yang tidak ditemukan.'])
                    ->withInput();
            }
        } else {
            // Buat/temukan akun "pemilih" berdasarkan kode (email dummy stabil)
            $dummyEmail = 'code_' . $enteredNoDash . '@example.local';

            $user = \App\Models\User::where('email', $dummyEmail)->first();
            if (!$user) {
                $displayName = ($data['name'] ?? null) ?: ('Pemilih ' . $entered);
                $user = \App\Models\User::create([
                    'name'     => $displayName,
                    'email'    => $dummyEmail,
                    'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                    'is_admin' => false,
                ]);
            }

            // Kaitkan ke code agar konsisten pada login berikutnya
            $code->user_id = $user->id;
        }

        // 4) Tandai pemakaian kode
        if (!empty($code->is_one_time) && $code->is_one_time && empty($code->used_at)) {
            $code->used_at = now();
        }
        $code->uses_count = (int) $code->uses_count + 1;

        // Hanya set last_used_at jika kolomnya ada
        if (\Illuminate\Support\Facades\Schema::hasColumn('login_codes', 'last_used_at')) {
            $code->last_used_at = now();
        }
        $code->save();

        // 5) Login & redirect
        \Illuminate\Support\Facades\Auth::login($user, false);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('ok', 'Login berhasil dengan kode.');
    }

}
