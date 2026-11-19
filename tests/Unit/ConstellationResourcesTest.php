<?php

declare(strict_types=1);

use App\Http\Resources\ConstellationDetailResource;
use App\Http\Resources\ConstellationSummaryResource;

it('displays a correct constellation summary', function () {
    expect(resourceToArray(new ConstellationSummaryResource(makeConstellation())))
        ->toBe([
            'constellationId' => 1,
            'constellationName' => 'C-882',
        ]);
});

it('maps a complete constellation record', function () {
    expect(resourceToArray(new ConstellationDetailResource(makeConstellation())))
        ->toBe([
            'constellationId' => 1,
            'constellationName' => 'C-882',
            'galaxy' => ['galaxyId' => 1, 'galaxyName' => 'Andromeda'],
            'addedBy' => ['userId' => 10, 'username' => 'adder'],
            'verifiedBy' => ['userId' => 11, 'username' => 'verifier'],
        ]);
});

it('returns null for a missing verifier', function () {
    $constellation = makeConstellation();
    $constellation->setRelation('verifiedBy', null);

    expect(resourceToArray(new ConstellationDetailResource($constellation)))
        ->toHaveKey('verifiedBy', null);
});
