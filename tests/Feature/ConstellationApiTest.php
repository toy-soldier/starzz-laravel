<?php

declare(strict_types=1);

it('GET returns the list of constellations', function () {
    $this->getJson('/api/constellations')
        ->assertOk()
        ->assertJsonCount(100);
});

it('GET returns a constellation with its galaxy, adder and verifier', function () {
    $this->getJson('/api/constellations/1')
        ->assertOk()
        ->assertJson([
            'constellationId' => 1,
            'constellationName' => 'CON-YKkuk',
            'galaxy' => ['galaxyId' => 1],
            'addedBy' => ['userId' => 10],
            'verifiedBy' => ['userId' => 37],
        ]);
});

it('GET returns HTTP 404 for a nonexistent id', function () {
    $this->getJson('/api/constellations/9999')
        ->assertNotFound()
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST creates a constellation and returns HTTP 201', function () {
    $this->postJson('/api/constellations', createConstellationPayload(['constellationName' => 'Brand New']))
        ->assertCreated()
        ->assertJson(['constellationName' => 'Brand New', 'galaxy' => ['galaxyId' => 1]]);

    $this->assertDatabaseHas('constellations', [
        'constellation_name' => 'Brand New',
        'galaxy_id' => 1,
        'added_by' => 1,
        'verified_by' => null,
    ]);
});

it('POST returns HTTP 400 when a referenced id does not exist', function (string $field, int $value) {
    $this->postJson('/api/constellations', createConstellationPayload([$field => $value]))
        ->assertStatus(400)
        ->assertJsonValidationErrors($field);
})->with([
    'galaxyId' => ['galaxyId', 9999],
    'adderId' => ['adderId', -1],
    'verifierId' => ['verifierId', 9999],
]);

it('PUT updates a constellation and returns HTTP 200', function () {
    $constellation = insertConstellation(1, 'Before');
    $id = $constellation->constellation_id;

    $this->putJson("/api/constellations/{$id}", createConstellationPayload([
        'constellationName' => 'After',
        'galaxyId' => 2,
    ]))
        ->assertOk()
        ->assertJson(['constellationId' => $id, 'constellationName' => 'After', 'galaxy' => ['galaxyId' => 2]]);

    $this->assertDatabaseHas('constellations', [
        'constellation_id' => $id,
        'constellation_name' => 'After',
        'galaxy_id' => 2,
    ]);
});

it('PUT without a verifierId clears the verifier', function () {
    $constellation = insertConstellation(1);
    $constellation->update(['verified_by' => 2]);

    $this->putJson("/api/constellations/{$constellation->constellation_id}", createConstellationPayload())
        ->assertOk();

    $this->assertDatabaseHas('constellations', [
        'constellation_id' => $constellation->constellation_id,
        'verified_by' => null,
    ]);
});

it('PUT returns HTTP 404 for a nonexistent id', function () {
    $this->putJson('/api/constellations/9999', createConstellationPayload())
        ->assertNotFound();
});

it('DELETE removes a constellation and its stars, and nothing else', function () {
    $constellation = insertConstellation(1);
    insertStar($constellation->constellation_id, 'Star A');
    insertStar($constellation->constellation_id, 'Star B');

    $other = insertConstellation(1, 'Other');
    $otherStar = insertStar($other->constellation_id, 'Other Star');

    $this->deleteJson("/api/constellations/{$constellation->constellation_id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('constellations', ['constellation_id' => $constellation->constellation_id]);
    $this->assertDatabaseMissing('stars', ['constellation_id' => $constellation->constellation_id]);
    $this->assertDatabaseHas('constellations', ['constellation_id' => $other->constellation_id]);
    $this->assertDatabaseHas('stars', ['star_id' => $otherStar->star_id]);
});

it('DELETE returns HTTP 404 for a nonexistent id', function () {
    $this->deleteJson('/api/constellations/9999')->assertNotFound();
});
