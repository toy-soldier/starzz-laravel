<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('GET returns the list of users', function () {
    $this->getJson('/api/users')
        ->assertOk()
        ->assertJsonCount(100);
});

it('GET returns a user without the password', function () {
    $this->getJson('/api/users/1')
        ->assertOk()
        ->assertJson([
            'userId' => 1,
            'username' => 'ebalducci29128',
            'email' => 'eliot.balducci@aol.com',
            'firstName' => 'Eliot',
            'lastName' => 'Balducci',
            'dateOfBirth' => '1993-08-24',
        ])
        ->assertJsonMissingPath('password');
});

it('GET returns HTTP 404 for a nonexistent id', function () {
    $this->getJson('/api/users/9999')
        ->assertNotFound()
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST creates a user and returns HTTP 201 without the password', function () {
    $this->postJson('/api/users', createUserPayload(['firstName' => 'Test']))
        ->assertCreated()
        ->assertJson(['username' => 'testuser', 'email' => 'email@test.com', 'firstName' => 'Test'])
        ->assertJsonMissingPath('password');

    $this->assertDatabaseHas('users', ['username' => 'testuser']);
});

it('POST saves the password reset sentinel as a hashed password', function () {
    $this->postJson('/api/users', createUserPayload())->assertCreated();

    $sentinel = config('starzz.password_reset_sentinel');
    $user = User::where('username', 'testuser')->firstOrFail();

    expect($sentinel)->not->toBeNull()
        ->and($user->password)->not->toBe($sentinel)
        ->and(Hash::check($sentinel, $user->password))->toBeTrue();
});

it('PUT updates a user and returns HTTP 200', function () {
    $this->putJson('/api/users/1', createUserPayload(['username' => 'renamed', 'firstName' => 'New']))
        ->assertOk()
        ->assertJson(['userId' => 1, 'username' => 'renamed', 'firstName' => 'New']);

    $this->assertDatabaseHas('users', ['user_id' => 1, 'username' => 'renamed', 'first_name' => 'New']);
});

it('PUT does not change the password', function () {
    $before = User::findOrFail(1)->password;

    $this->putJson('/api/users/1', createUserPayload())->assertOk();

    expect(User::findOrFail(1)->password)->toBe($before);
});

it('PUT returns HTTP 404 for a nonexistent id', function () {
    $this->putJson('/api/users/9999', createUserPayload())->assertNotFound();
});

it('PATCH changes the password and returns HTTP 204', function () {
    $user = User::create(['username' => 'pwuser', 'email' => 'pw@test.com', 'password' => 'oldPassword']);

    $this->patchJson("/api/users/{$user->user_id}/change-password", createChangePasswordPayload())
        ->assertNoContent();

    $user->refresh();

    expect(Hash::check('newPassword', $user->password))->toBeTrue()
        ->and(Hash::check('oldPassword', $user->password))->toBeFalse();
});

it('PATCH returns HTTP 400 and keeps the password when the existing password is wrong', function () {
    $user = User::create(['username' => 'pwuser', 'email' => 'pw@test.com', 'password' => 'oldPassword']);

    $this->patchJson("/api/users/{$user->user_id}/change-password", createChangePasswordPayload([
        'existingPassword' => 'wrongPassword',
    ]))
        ->assertStatus(400)
        ->assertJsonValidationErrors('existingPassword');

    expect(Hash::check('oldPassword', $user->refresh()->password))->toBeTrue();
});

it('PATCH replaces the sentinel password of a newly registered user', function () {
    $id = $this->postJson('/api/users', createUserPayload())->assertCreated()->json('userId');

    $this->patchJson("/api/users/{$id}/change-password", [
        'existingPassword' => config('starzz.password_reset_sentinel'),
        'newPassword' => 'myNewPassword',
    ])->assertNoContent();

    expect(Hash::check('myNewPassword', User::findOrFail($id)->password))->toBeTrue();
});

it('PATCH returns HTTP 404 for a nonexistent id', function () {
    $this->patchJson('/api/users/9999/change-password', createChangePasswordPayload())
        ->assertNotFound();
});
