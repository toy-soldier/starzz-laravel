<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use DomainException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

class AuthenticateJwt
{
    public function __construct(private readonly JwtService $jwt) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (blank($token)) {
            return $this->unauthorized();
        }

        try {
            $claims = $this->jwt->decode($token);
        } catch (UnexpectedValueException | DomainException | InvalidArgumentException) {
            return $this->unauthorized();
        }

        $request->attributes->set('jwt_claims', $claims);

        return $next($request);
    }

    private function unauthorized(): Response
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
