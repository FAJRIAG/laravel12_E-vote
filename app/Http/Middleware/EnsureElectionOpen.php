<?php

namespace App\Http\Middleware;

use App\Models\Election;
use Closure;
use Illuminate\Http\Request;

class EnsureElectionOpen
{
    public function handle(Request $request, Closure $next)
    {
        $election = $request->route('election');
        if (!$election instanceof Election || !$election->isOpen()) {
            abort(403, 'Pemilihan belum dibuka atau sudah ditutup.');
        }
        return $next($request);
    }
}
