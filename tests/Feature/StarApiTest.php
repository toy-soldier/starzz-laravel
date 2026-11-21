<?php

declare(strict_types=1);

it('GET returns the list of stars', function () {
    $this->getJson('/api/stars')
        ->assertOk()
        ->assertJsonCount(100);
});

it('GET returns a star with its constellation, adder and verifier', function () {
    $this->getJson('/api/stars/1')
        ->assertOk()
        ->assertJson([
            'starId' => 1,
            'starName' => 'S-UL520',
            'starType' => 'red dwarf star',
            'constellation' => ['constellationId' => 38],
            'rightAscension' => 10,
            'declination' => 78,
            'apparentMagnitude' => -12,
            'spectralType' => 'O',
            'addedBy' => ['userId' => 1],
            'verifiedBy' => ['userId' => 89],
        ]);
});

it('GET returns HTTP 404 for a nonexistent id', function () {
    $this->getJson('/api/stars/9999')
        ->assertNotFound()
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST creates a star and returns HTTP 201', function () {
    $this->postJson('/api/stars', createStarPayload(['starName' => 'Brand New']))
        ->assertCreated()
        ->assertJson(['starName' => 'Brand New', 'constellation' => ['constellationId' => 1]]);

    $this->assertDatabaseHas('stars', [
        'star_name' => 'Brand New',
        'constellation_id' => 1,
        'added_by' => 1,
        'verified_by' => null,
    ]);
});

it('POST returns HTTP 400 when a referenced id does not exist', function (string $field, int $value) {
    $this->postJson('/api/stars', createStarPayload([$field => $value]))
        ->assertStatus(400)
        ->assertJsonValidationErrors($field);
})->with([
    'constellationId' => ['constellationId', 9999],
    'adderId' => ['adderId', -1],
    'verifierId' => ['verifierId', 9999],
]);

it('PUT updates a star and returns HTTP 200', function () {
    $star = insertStar(1, 'Before');

    $this->putJson("/api/stars/{$star->star_id}", createStarPayload([
        'starName' => 'After',
        'constellationId' => 2,
    ]))
        ->assertOk()
        ->assertJson(['starId' => $star->star_id, 'starName' => 'After', 'constellation' => ['constellationId' => 2]]);

    $this->assertDatabaseHas('stars', [
        'star_id' => $star->star_id,
        'star_name' => 'After',
        'constellation_id' => 2,
    ]);
});

it('PUT returns HTTP 404 for a nonexistent id', function () {
    $this->putJson('/api/stars/9999', createStarPayload())->assertNotFound();
});

it('DELETE removes a star', function () {
    $star = insertStar(1);

    $this->deleteJson("/api/stars/{$star->star_id}")->assertNoContent();

    $this->assertDatabaseMissing('stars', ['star_id' => $star->star_id]);
});

it('DELETE returns HTTP 404 for a nonexistent id', function () {
    $this->deleteJson('/api/stars/9999')->assertNotFound();
});
