<?php

declare(strict_types=1);

use App\Http\Requests\StoreStarRequest;

it('accepts :dataset with 100 characters', function (string $field) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload([$field => str_repeat('a', 100)])
    )
        ->fails())->toBeFalse();
})->with(['starName', 'starType']);

it('rejects :dataset longer than 100 characters', function (string $field) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload([$field => str_repeat('a', 101)])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['starName', 'starType']);

it('rejects non-int values for constellationId', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload(['constellationId' => $value])
    )
        ->errors()->has('constellationId'))->toBeTrue();
})->with(['abc', 1.5]);

it('requires :dataset', function (string $field) {
    $data = createStarPayload();
    unset($data[$field]);

    expect(validateWithoutDb(
        new StoreStarRequest,
        $data
    )
        ->errors()->has($field))->toBeTrue();
})->with(['starName', 'starType', 'constellationId', 'rightAscension', 'declination', 'apparentMagnitude', 'spectralType', 'adderId',]);

it('rejects values > 32-bit int max for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload([$field => config('starzz.int_max') + 1])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['rightAscension', 'declination', 'apparentMagnitude',]);

it('accepts the 32-bit int max for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload([$field => config('starzz.int_max')])
    )
        ->fails())->toBeFalse();
})->with(['rightAscension', 'declination', 'apparentMagnitude',]);

it('accepts the 32-bit int min for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload([$field => config('starzz.int_min')])
    )
        ->fails())->toBeFalse();
})->with(['rightAscension', 'declination', 'apparentMagnitude',]);

it('rejects values < 32-bit int min for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload([$field => config('starzz.int_min') - 1])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['rightAscension', 'declination', 'apparentMagnitude',]);

it('accepts spectralType with 5 characters', function () {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload(['spectralType' => str_repeat('a', 5)])
    )
        ->fails())->toBeFalse();
});

it('rejects spectralType longer than 5 characters', function () {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload(['spectralType' => str_repeat('a', 6)])
    )
        ->errors()->has('spectralType'))->toBeTrue();
});

it('rejects non-int values for adder', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload(['adderId' => $value])
    )
        ->errors()->has('adderId'))->toBeTrue();
})->with(['abc', 1.5]);

it('accepts a valid payload without a verifier', function () {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload()
    )
        ->fails())->toBeFalse();
});

it('accepts a null verifier', function () {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload(['verifierId' => null])
    )
        ->fails())->toBeFalse();
});

it('rejects non-int values for verifier', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreStarRequest,
        createStarPayload(['verifierId' => $value])
    )
        ->errors()->has('verifierId'))->toBeTrue();
})->with(['abc', 1.5]);
