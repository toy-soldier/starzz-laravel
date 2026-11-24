<?php

declare(strict_types=1);

use App\Http\Requests\LoginRequest;

it('accepts a valid payload', function () {
    expect(validateWithoutDb(new LoginRequest, createLoginPayload())->fails())->toBeFalse();
});

it('requires :dataset', function (string $field) {
    $data = createLoginPayload();
    unset($data[$field]);

    expect(validateWithoutDb(new LoginRequest, $data)->errors()->has($field))->toBeTrue();
})->with(['username', 'password']);
