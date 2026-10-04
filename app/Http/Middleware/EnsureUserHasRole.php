<?php

// ====================================================================
// app/Http/Middleware/EnsureUserHasRole.php
// Dibuat dengan: php artisan make:middleware EnsureUserHasRole
// REQUIREMENT 6: custom middleware untuk cek role.
// Dipakai di routes/web.php: ->middleware('role:admin')
//                            ->middleware('role:admin,editor')
// ====================================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Silakan login dulu.');
        }

        if (! in_array($user->role, $roles, true)) {
            // REQUIREMENT 8: route protection -> role yang tidak sesuai
            // ditolak dengan 403 Forbidden, bukan dibiarkan lolos.
            abort(403, 'Kamu tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
