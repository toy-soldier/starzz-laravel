<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\JwtService;

function createLoginUser(string $username = 'loginuser', string $password = 'password'): User
{
    return User::create([
        'username' => $username,
        'email' => $username . '@test.com',
        'password' => $password,
    ]);
}

it('POST login returns a token for valid credentials', function () {
    $user = createLoginUser();

    $token = $this->postJson('/api/login', ['username' => 'loginuser', 'password' => 'password'])
        ->assertOk()
        ->assertJsonStructure(['token'])
        ->json('token');

    $claims = app(JwtService::class)->decode($token);

    expect($claims['sub'])->toBe((string) $user->user_id)
        ->and($claims['username'])->toBe('loginuser')
        ->and($claims['role'])->toBe('USER');
});

it('POST login issues the ADMIN role to a user in the admin list', function () {
    config(['starzz.admins' => ['loginadmin']]);
    createLoginUser('loginadmin');

    $token = $this->postJson('/api/login', ['username' => 'loginadmin', 'password' => 'password'])
        ->assertOk()
        ->json('token');

    expect(app(JwtService::class)->decode($token)['role'])->toBe('ADMIN');
});

it('POST login returns HTTP 401 for a wrong password', function () {
    createLoginUser();

    $this->postJson('/api/login', ['username' => 'loginuser', 'password' => 'wrongPassword'])
        ->assertUnauthorized()
        ->assertExactJson(['message' => 'Invalid credentials.']);
});

it('POST login returns HTTP 401 for an unknown user, with the same message', function () {
    $this->postJson('/api/login', ['username' => 'nobody', 'password' => 'password'])
        ->assertUnauthorized()
        ->assertExactJson(['message' => 'Invalid credentials.']);
});

it('POST login returns HTTP 403 with the user id when the password is the sentinel', function () {
    $user = createLoginUser('sentineluser', config('starzz.password_reset_sentinel'));

    $this->postJson('/api/login', [
        'username' => 'sentineluser',
        'password' => config('starzz.password_reset_sentinel'),
    ])
        ->assertForbidden()
        ->assertJson(['userId' => $user->user_id]);
});

it('a token from login grants access to a protected endpoint', function () {
    createLoginUser();

    $token = $this->postJson('/api/login', ['username' => 'loginuser', 'password' => 'password'])
        ->json('token');

    $this->withToken($token)->getJson('/api/users')->assertOk();
});

it('returns HTTP 401 on protected endpoints without a token', function (string $method, string $uri) {
    $this->withHeader('Authorization', '')
        ->json($method, $uri)
        ->assertUnauthorized();
})->with([
    'GET users' => ['GET', '/api/users'],
    'GET user' => ['GET', '/api/users/1'],
    'POST users' => ['POST', '/api/users'],
    'PUT user' => ['PUT', '/api/users/1'],
    'POST galaxies' => ['POST', '/api/galaxies'],
    'PUT galaxy' => ['PUT', '/api/galaxies/1'],
    'DELETE galaxy' => ['DELETE', '/api/galaxies/1'],
    'POST constellations' => ['POST', '/api/constellations'],
    'PUT constellation' => ['PUT', '/api/constellations/1'],
    'DELETE constellation' => ['DELETE', '/api/constellations/1'],
    'POST stars' => ['POST', '/api/stars'],
    'PUT star' => ['PUT', '/api/stars/1'],
    'DELETE star' => ['DELETE', '/api/stars/1'],
]);

it('returns HTTP 200 on public endpoints without a token', function (string $uri) {
    $this->withHeader('Authorization', '')
        ->getJson($uri)
        ->assertOk();
})->with([
    'galaxies' => '/api/galaxies',
    'galaxy' => '/api/galaxies/1',
    'constellations' => '/api/constellations',
    'constellation' => '/api/constellations/1',
    'stars' => '/api/stars',
    'star' => '/api/stars/1',
]);

it('PATCH change-password is public', function () {
    $this->withHeader('Authorization', '')
        ->patchJson('/api/users/1/change-password', [])
        ->assertStatus(400);
});

it('returns HTTP 401 for a garbage token', function () {
    $this->withToken('abcde')->getJson('/api/users')->assertUnauthorized();
});

it('returns HTTP 401 for a token signed with a different secret', function () {
    $token = (new JwtService('a-completely-different-secret-key-0123', 3600))
        ->generateToken(1, 'intruder', 'ADMIN');

    $this->withToken($token)->getJson('/api/users')->assertUnauthorized();
});

it('returns HTTP 401 for an expired token', function () {
    $token = (new JwtService(config('starzz.jwt_secret'), -10))
        ->generateToken(1, 'expired', 'USER');

    $this->withToken($token)->getJson('/api/users')->assertUnauthorized();
});

it('POST users returns HTTP 403 for a USER token', function () {
    $this->withToken(userToken())
        ->postJson('/api/users', createUserPayload())
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['username' => 'testuser']);
});

it('POST users returns HTTP 201 for an ADMIN token', function () {
    $this->withToken(adminToken())
        ->postJson('/api/users', createUserPayload())
        ->assertCreated();
});
