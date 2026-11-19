<?php

declare(strict_types=1);

use App\Http\Resources\GalaxyDetailResource;
use App\Http\Resources\GalaxySummaryResource;

it('displays a correct galaxy summary', function () {
    expect(resourceToArray(new GalaxySummaryResource(makeGalaxy())))
        ->toBe([
            'galaxyId' => 5,
            'galaxyName' => 'GAL-1',
        ]);
});

it('maps a complete galaxy record', function () {
    expect(resourceToArray(new GalaxyDetailResource(makeGalaxy())))
        ->toBe([
            'galaxyId' => 5,
            'galaxyName' => 'GAL-1',
            'galaxyType' => 'Spiral',
            'distanceMly' => 100,
            'redshift' => 2,
            'massSolar' => 300,
            'diameterLy' => 400,
            'addedBy' => ['userId' => 10, 'username' => 'adder'],
            'verifiedBy' => ['userId' => 11, 'username' => 'verifier'],
        ]);
});

it('returns null for a missing verifier', function () {
    $galaxy = makeGalaxy();
    $galaxy->setRelation('verifiedBy', null);

    expect(resourceToArray(new GalaxyDetailResource($galaxy)))
        ->toHaveKey('verifiedBy', null);
});
