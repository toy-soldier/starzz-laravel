<?php

declare(strict_types=1);

it('GET returns the list of galaxies', function () {
    $this->getJson('/api/galaxies')
        ->assertOk()
        ->assertJsonCount(100);
});

it('GET returns a galaxy with its adder and verifier', function () {
    $this->getJson('/api/galaxies/1')
        ->assertOk()
        ->assertJson([
            'galaxyId' => 1,
            'galaxyName' => 'GAL-LPvfm',
            'galaxyType' => 'Barred Spiral',
            'distanceMly' => 5096,
            'addedBy' => ['userId' => 15],
            'verifiedBy' => ['userId' => 62],
        ]);
});

it('GET returns HTTP 404 for a nonexistent id', function () {
    $this->getJson('/api/galaxies/9999')
        ->assertNotFound()
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST creates a galaxy and returns HTTP 201', function () {
    $this->postJson('/api/galaxies', createGalaxyPayload(['galaxyName' => 'Brand New']))
        ->assertCreated()
        ->assertJson(['galaxyName' => 'Brand New', 'galaxyType' => 'Spiral']);

    $this->assertDatabaseHas('galaxies', [
        'galaxy_name' => 'Brand New',
        'added_by' => 1,
        'verified_by' => null,
    ]);
});

it('POST returns HTTP 400 when a referenced user does not exist', function (string $field, int $value) {
    $this->postJson('/api/galaxies', createGalaxyPayload([$field => $value]))
        ->assertStatus(400)
        ->assertJsonValidationErrors($field);
})->with([
    'adderId' => ['adderId', -1],
    'verifierId' => ['verifierId', 9999],
]);

it('PUT updates a galaxy and returns HTTP 200', function () {
    $galaxy = insertGalaxy('Before');

    $this->putJson("/api/galaxies/{$galaxy->galaxy_id}", createGalaxyPayload([
        'galaxyName' => 'After',
        'distanceMly' => 777,
    ]))
        ->assertOk()
        ->assertJson(['galaxyId' => $galaxy->galaxy_id, 'galaxyName' => 'After', 'distanceMly' => 777]);

    $this->assertDatabaseHas('galaxies', [
        'galaxy_id' => $galaxy->galaxy_id,
        'galaxy_name' => 'After',
        'distance_mly' => 777,
    ]);
});

it('PUT returns HTTP 404 for a nonexistent id', function () {
    $this->putJson('/api/galaxies/9999', createGalaxyPayload())->assertNotFound();
});

it('DELETE removes a galaxy, its constellations and their stars, and nothing else', function () {
    $galaxy = insertGalaxy();
    $c1 = insertConstellation($galaxy->galaxy_id, 'C1');
    $c2 = insertConstellation($galaxy->galaxy_id, 'C2');
    insertStar($c1->constellation_id, 'S1');
    insertStar($c2->constellation_id, 'S2');

    $otherGalaxy = insertGalaxy('Other Galaxy');
    $otherConstellation = insertConstellation($otherGalaxy->galaxy_id, 'Other C');
    $otherStar = insertStar($otherConstellation->constellation_id, 'Other S');

    $this->deleteJson("/api/galaxies/{$galaxy->galaxy_id}")->assertNoContent();

    $this->assertDatabaseMissing('galaxies', ['galaxy_id' => $galaxy->galaxy_id]);
    $this->assertDatabaseMissing('constellations', ['galaxy_id' => $galaxy->galaxy_id]);
    $this->assertDatabaseMissing('stars', ['constellation_id' => $c1->constellation_id]);
    $this->assertDatabaseMissing('stars', ['constellation_id' => $c2->constellation_id]);

    $this->assertDatabaseHas('galaxies', ['galaxy_id' => $otherGalaxy->galaxy_id]);
    $this->assertDatabaseHas('constellations', ['constellation_id' => $otherConstellation->constellation_id]);
    $this->assertDatabaseHas('stars', ['star_id' => $otherStar->star_id]);
});

it('DELETE returns HTTP 404 for a nonexistent id', function () {
    $this->deleteJson('/api/galaxies/9999')->assertNotFound();
});
