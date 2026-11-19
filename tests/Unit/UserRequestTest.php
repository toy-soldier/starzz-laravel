<?php

declare(strict_types=1);

use App\Http\Requests\StoreUserRequest;

it('accepts :dataset with 100 characters', function (string $field) {
    expect(validateWithoutDb(
        new StoreUserRequest,
        createUserPayload([$field => str_repeat('a', 100)])
    )
        ->fails())->toBeFalse();
})->with(['username', 'firstName', 'lastName', 'dateOfBirth',]);

it('rejects :dataset longer than 100 characters', function (string $field) {
    expect(validateWithoutDb(
        new StoreUserRequest,
        createUserPayload([$field => str_repeat('a', 101)])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['username', 'firstName', 'lastName', 'dateOfBirth',]);

it('requires :dataset', function (string $field) {
    $data = createUserPayload();
    unset($data[$field]);

    expect(validateWithoutDb(
        new StoreUserRequest,
        $data
    )
        ->errors()->has($field))->toBeTrue();
})->with(['username', 'email',]);

it('accepts email with 100 characters', function () {
    expect(validateWithoutDb(
        new StoreUserRequest,
        createUserPayload(['email' => str_repeat('a', 94) . '@a.com'])
    )
        ->fails())->toBeFalse();
});

it('rejects email with more than 100 characters', function () {
    expect(validateWithoutDb(
        new StoreUserRequest,
        createUserPayload(['email' => str_repeat('a', 95) . '@a.com'])
    )
        ->errors()->has('email'))->toBeTrue();
});

it('rejects invalid email', function () {
    expect(validateWithoutDb(
        new StoreUserRequest,
        createUserPayload(['email' => str_repeat('a', 6)])
    )
        ->errors()->has('email'))->toBeTrue();
});
