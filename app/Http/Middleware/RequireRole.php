<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $claims = $request->attributes->get('jwt_claims', []);

        if (($claims['role'] ?? null) !== $role) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
