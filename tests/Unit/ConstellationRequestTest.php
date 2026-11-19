<?php

declare(strict_types=1);

use App\Http\Requests\StoreConstellationRequest;

it('accepts constellationName with 100 characters', function () {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload(['constellationName' => str_repeat('a', 100)])
    )
        ->fails())->toBeFalse();
});

it('rejects constellationName longer than 100 characters', function () {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload(['constellationName' => str_repeat('a', 101)])
    )
        ->errors()->has('constellationName'))->toBeTrue();
});

it('requires :dataset', function (string $field) {
    $data = createConstellationPayload();
    unset($data[$field]);

    expect(validateWithoutDb(
        new StoreConstellationRequest,
        $data
    )
        ->errors()->has($field))->toBeTrue();
})->with(['constellationName', 'galaxyId', 'adderId']);

it('rejects non-int values for galaxyId', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload(['galaxyId' => $value])
    )
        ->errors()->has('galaxyId'))->toBeTrue();
})->with(['abc', 1.5]);

it('rejects non-int values for adder', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload(['adderId' => $value])
    )
        ->errors()->has('adderId'))->toBeTrue();
})->with(['abc', 1.5]);

it('accepts a valid payload without a verifier', function () {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload()
    )
        ->fails())->toBeFalse();
});

it('accepts a null verifier', function () {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload(['verifierId' => null])
    )
        ->fails())->toBeFalse();
});

it('rejects non-int values for verifier', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreConstellationRequest,
        createConstellationPayload(['verifierId' => $value])
    )
        ->errors()->has('verifierId'))->toBeTrue();
})->with(['abc', 1.5]);
