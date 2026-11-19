<?php

declare(strict_types=1);

use App\Http\Resources\StarDetailResource;
use App\Http\Resources\StarSummaryResource;

it('displays a correct star summary', function () {
    expect(resourceToArray(new StarSummaryResource(makeStar())))
        ->toBe([
            'starId' => 7,
            'starName' => 'S-1',
        ]);
});

it('maps a complete star record', function () {
    expect(resourceToArray(new StarDetailResource(makeStar())))
        ->toBe([
            'starId' => 7,
            'starName' => 'S-1',
            'starType' => 'red dwarf star',
            'constellation' => ['constellationId' => 1, 'constellationName' => 'Orion'],
            'rightAscension' => 10,
            'declination' => -20,
            'apparentMagnitude' => -12,
            'spectralType' => 'O',
            'addedBy' => ['userId' => 10, 'username' => 'adder'],
            'verifiedBy' => ['userId' => 11, 'username' => 'verifier'],
        ]);
});

it('returns null for a missing verifier', function () {
    $star = makeStar();
    $star->setRelation('verifiedBy', null);

    expect(resourceToArray(new StarDetailResource($star)))
        ->toHaveKey('verifiedBy', null);
});
