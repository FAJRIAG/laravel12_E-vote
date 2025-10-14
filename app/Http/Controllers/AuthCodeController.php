<?php

namespace App\Http\Controllers;

use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthCodeController extends Controller
{
    public function showForm()
    {
        return view('auth.login_code');
    }

    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:32'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        // 1) Normalisasi input
        $entered       = strtoupper(preg_replace('/\s+/', '', $data['code']));
        $enteredNoDash = str_replace('-', '', $entered);

        // 2) Cari kode (exact atau tanpa '-')
        $code = LoginCode::query()
            ->where('code', $entered)
            ->orWhereRaw("REPLACE(code, '-', '') = ?", [$enteredNoDash])
            ->first();

        // Pastikan model punya method isValid() (sudah kamu tambahkan di app/Models/LoginCode.php)
        if (!$code || !$code->isValid()) {
            return back()
                ->withErrors(['code' => 'Kode tidak valid / sudah kadaluarsa / tidak aktif / kuota habis.'])
                ->withInput();
        }

        // 3) Resolve user: terikat atau buat user dummy
        if (!empty($code->user_id)) {
            $user = User::find($code->user_id);
            if (!$user) {
                return back()
                    ->withErrors(['code' => 'Kode terikat ke user yang tidak ditemukan.'])
                    ->withInput();
            }
        } else {
            $dummyEmail = 'code_' . $enteredNoDash . '@example.local';
            $user = User::where('email', $dummyEmail)->first();

            if (!$user) {
                $displayName = ($data['name'] ?? null) ?: ('Pemilih ' . $entered);
                $user = User::create([
                    'name'     => $displayName,
                    'email'    => $dummyEmail,
                    'password' => bcrypt(Str::random(16)),
                    'is_admin' => false,
                ]);
            }

            // Kaitkan code ke user agar konsisten
            $code->user_id = $user->id;
        }

        // 4) Tandai pemakaian code
        if (!empty($code->is_one_time) && $code->is_one_time && empty($code->used_at)) {
            // jika pakai pola one-time + kolom used_at tersedia
            $code->used_at = now();
        }

        // Tingkatkan counter kalau kolomnya ada (uses_count atau used_count)
        if (Schema::hasColumn('login_codes', 'uses_count')) {
            $code->uses_count = (int) $code->uses_count + 1;
        } elseif (Schema::hasColumn('login_codes', 'used_count')) {
            $code->used_count = (int) $code->used_count + 1;
        }

        // Last used (jika ada)
        if (Schema::hasColumn('login_codes', 'last_used_at')) {
            $code->last_used_at = now();
        }

        $code->save();

        // 5) Login & redirect
        Auth::login($user, false);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('ok', 'Login berhasil dengan kode.');
    }
}
