<?php

declare(strict_types=1);

use App\Services\JwtService;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

function makeJwtService(
    string $secret = 'unit-test-secret-key-0123456789abcdef',
    int $ttl = 3600
): JwtService {
    return new JwtService($secret, $ttl);
}

it('generates a token with the correct claims', function () {
    $jwt = makeJwtService();

    $claims = $jwt->decode($jwt->generateToken(1, 'john', 'USER'));

    expect($claims['sub'])->toBe('1')
        ->and($claims['username'])->toBe('john')
        ->and($claims['role'])->toBe('USER')
        ->and($claims['exp'] - $claims['iat'])->toBe(3600);
});

it('generates a token with the ADMIN role', function () {
    $jwt = makeJwtService();

    expect($jwt->decode($jwt->generateToken(1, 'admin1', 'ADMIN'))['role'])->toBe('ADMIN');
});

it('throws when decoding an expired token', function () {
    $jwt = makeJwtService(ttl: -10);

    expect(fn() => $jwt->decode($jwt->generateToken(1, 'john', 'USER')))
        ->toThrow(ExpiredException::class);
});

it('throws when decoding a token signed with a different secret', function () {
    $token = makeJwtService()->generateToken(1, 'john', 'USER');

    expect(fn() => makeJwtService('a-completely-different-secret-key-0123')->decode($token))
        ->toThrow(SignatureInvalidException::class);
});

it('throws when decoding a malformed token', function () {
    expect(fn() => makeJwtService()->decode('abcde'))
        ->toThrow(UnexpectedValueException::class);
});
