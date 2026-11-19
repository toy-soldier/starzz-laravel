<?php

declare(strict_types=1);

use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserSummaryResource;

it('displays a correct user summary', function () {
    expect(resourceToArray(new UserSummaryResource(makeUser())))
        ->toBe([
            'userId' => 1,
            'username' => 'john',
        ]);
});

it('maps a complete user record', function () {
    $result = resourceToArray(new UserDetailResource(makeUser()));

    expect($result)
        ->not->toHaveKey('password')
        ->and($result)->toBe([
            'userId' => 1,
            'username' => 'john',
            'email' => 'john@example.com',
            'firstName' => 'John',
            'lastName' => 'Smith',
            'dateOfBirth' => '2000-02-28',
        ]);
});
