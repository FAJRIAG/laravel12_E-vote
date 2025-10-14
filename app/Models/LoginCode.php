<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class LoginCode extends Model
{
    protected $table = 'login_codes'; // ubah jika nama tabel beda

    protected $fillable = [
        'code',
        'label',
        'user_id',
        'is_active',
        'is_one_time',
        'max_uses',
        // pakai yang ada di DB-mu, dua-duanya disiapkan agar kompatibel:
        'uses_count',   // jika kolom ini ada
        'used_count',   // atau kolom ini
        'used_at',      // untuk one-time
        'last_used_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_one_time'  => 'boolean',
        'expires_at'   => 'datetime',
        'used_at'      => 'datetime',
        'last_used_at' => 'datetime',
    ];

    // =========================
    // Relasi
    // =========================
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // =========================
    // Validasi & Kuota
    // =========================

    /**
     * TRUE jika:
     * - aktif
     * - belum expired (expires_at null ATAU now < expires_at)
     * - one-time: belum pernah dipakai (used_at null ATAU usesCount < 1)
     * - multi-use: usesCount < max_uses (null = unlimited)
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
    }

        if ($this->isExpired()) {
            return false;
        }

        if ($this->is_one_time) {
            // Jika ada kolom used_at, pakai itu dulu
            if (Schema::hasColumn($this->getTable(), 'used_at')) {
                return empty($this->used_at);
            }
            // fallback pakai counter
            return $this->getUsesCount() < 1;
        }

        // Multi-use
        $max = $this->getMaxUses();    // null = unlimited
        return $max === null ? true : ($this->getUsesCount() < $max);
    }

    /** Apakah sudah kadaluarsa? */
    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon
            ? now()->greaterThan($this->expires_at)
            : false;
    }

    /** Kuota maksimal; one-time dianggap 1; null = unlimited */
    public function getMaxUses(): ?int
    {
        if ($this->is_one_time) {
            return 1;
        }
        return $this->max_uses !== null ? (int) $this->max_uses : null;
    }

    /** Ambil jumlah pemakaian (kompatibel dengan uses_count ATAU used_count). */
    public function getUsesCount(): int
    {
        if (Schema::hasColumn($this->getTable(), 'uses_count')) {
            return (int) ($this->uses_count ?? 0);
        }
        if (Schema::hasColumn($this->getTable(), 'used_count')) {
            return (int) ($this->used_count ?? 0);
        }
        return 0;
    }

    /** Helper opsional untuk menaikkan counter pemakaian. */
    public function incrementUsage(): void
    {
        if (Schema::hasColumn($this->getTable(), 'uses_count')) {
            $this->uses_count = (int) ($this->uses_count ?? 0) + 1;
        } elseif (Schema::hasColumn($this->getTable(), 'used_count')) {
            $this->used_count = (int) ($this->used_count ?? 0) + 1;
        }
    }

    // Scope opsional
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
