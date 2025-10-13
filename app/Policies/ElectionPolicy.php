<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Election;

class ElectionPolicy
{
    public function manage(User $user, ?Election $model = null): bool
    {
        return (bool) $user->is_admin;
    }
}
