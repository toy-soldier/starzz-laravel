<?php

declare(strict_types=1);

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private const ALGORITHM = 'HS256';

    public function __construct(
        private readonly string $secret,
        private readonly int $ttlSeconds,
    ) {}

    public function generateToken(int $id, string $username, string $role): string
    {
        $now = time();

        return JWT::encode([
            'sub' => (string) $id,
            'username' => $username,
            'role' => $role,
            'iat' => $now,
            'exp' => $now + $this->ttlSeconds,
        ], $this->secret, self::ALGORITHM);
    }

    /** Throws if the token is malformed, tampered with or expired. */
    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->secret, self::ALGORITHM));
    }
}
