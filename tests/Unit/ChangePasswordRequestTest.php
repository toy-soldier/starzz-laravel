<?php

declare(strict_types=1);

use App\Http\Requests\ChangePasswordRequest;

it('accepts a valid payload', function () {
    expect(validateWithoutDb(
        new ChangePasswordRequest,
        createChangePasswordPayload()
    )
        ->fails())->toBeFalse();
});

it('requires :dataset', function (string $field) {
    $data = createChangePasswordPayload();
    unset($data[$field]);

    expect(validateWithoutDb(
        new ChangePasswordRequest,
        $data
    )
        ->errors()->has($field))->toBeTrue();
})->with(['existingPassword', 'newPassword']);
