<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Candidate;

class CandidatePolicy
{
    public function update(User $user, Candidate $candidate): bool
    {
        // Admin boleh jika election masih belum ditutup
        return $user->is_admin && $candidate->election?->isOpen();
    }
    public function delete(User $user, Candidate $candidate): bool
    {
        return $user->is_admin && $candidate->election?->isOpen();
    }
    public function create(User $user): bool { return (bool) $user->is_admin; }
}
