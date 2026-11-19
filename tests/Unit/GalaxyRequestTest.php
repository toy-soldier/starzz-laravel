<?php

declare(strict_types=1);

use App\Http\Requests\StoreGalaxyRequest;

it('accepts :dataset with 100 characters', function (string $field) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload([$field => str_repeat('a', 100)])
    )
        ->fails())->toBeFalse();
})->with(['galaxyName', 'galaxyType']);

it('rejects :dataset longer than 100 characters', function (string $field) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload([$field => str_repeat('a', 101)])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['galaxyName', 'galaxyType']);

it('requires :dataset', function (string $field) {
    $data = createGalaxyPayload();
    unset($data[$field]);

    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        $data
    )
        ->errors()->has($field))->toBeTrue();
})->with(['galaxyName', 'galaxyType', 'distanceMly', 'redshift', 'massSolar', 'diameterLy', 'adderId']);

it('rejects values > 32-bit int max for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload([$field => config('starzz.int_max') + 1])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['distanceMly', 'massSolar', 'diameterLy', 'redshift']);

it('accepts the 32-bit int max for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload([$field => config('starzz.int_max')])
    )
        ->fails())->toBeFalse();
})->with(['distanceMly', 'massSolar', 'diameterLy', 'redshift']);

it('rejects negative values for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload([$field => -1])
    )
        ->errors()->has($field))->toBeTrue();
})->with(['distanceMly', 'massSolar', 'diameterLy']);

it('accepts 0 values for :dataset', function (string $field) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload([$field => 0])
    )
        ->fails())->toBeFalse();
})->with(['distanceMly', 'massSolar', 'diameterLy']);

it('accepts the 32-bit int min for redshift', function () {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload(['redshift' => config('starzz.int_min')])
    )
        ->fails())->toBeFalse();
});

it('rejects values < 32-bit int min for redshift', function () {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload(['redshift' => config('starzz.int_min') - 1])
    )
        ->errors()->has('redshift'))->toBeTrue();
});

it('rejects non-int values for adder', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload(['adderId' => $value])
    )
        ->errors()->has('adderId'))->toBeTrue();
})->with(['abc', 1.5]);

it('accepts a valid payload without a verifier', function () {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload()
    )
        ->fails())->toBeFalse();
});

it('accepts a null verifier', function () {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload(['verifierId' => null])
    )
        ->fails())->toBeFalse();
});

it('rejects non-int values for verifier', function (mixed $value) {
    expect(validateWithoutDb(
        new StoreGalaxyRequest,
        createGalaxyPayload(['verifierId' => $value])
    )
        ->errors()->has('verifierId'))->toBeTrue();
})->with(['abc', 1.5]);
