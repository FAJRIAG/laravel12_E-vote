<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginCode extends Model
{
    protected $table = 'login_codes';

    protected $fillable = [
        'code',
        'label',
        'user_id',
        'is_active',
        'is_one_time',
        'max_uses',
        'uses_count',
        'used_at',
        'last_used_at',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_one_time'  => 'boolean',
        'max_uses'     => 'integer',
        'uses_count'   => 'integer',
        'used_at'      => 'datetime',
        'last_used_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    // ============ Relasi ============
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============ Logika ============
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        // One-time: cukup cek pernah dipakai atau belum
        if ($this->is_one_time) {
            return empty($this->used_at);
        }

        // Multi-use: hormati max_uses bila diset
        if (!is_null($this->max_uses) && $this->max_uses > 0) {
            if ((int) $this->uses_count >= (int) $this->max_uses) {
                return false;
            }
        }

        return true;
    }

    public function markUsed(): void
    {
        // One-time: set used_at saat pertama dipakai
        if ($this->is_one_time && empty($this->used_at)) {
            $this->used_at = now();
        }

        // Tambah hit penggunaan
        $this->uses_count = (int) $this->uses_count + 1;

        // Catat pemakaian terakhir
        $this->last_used_at = now();

        $this->save();
    }
}
