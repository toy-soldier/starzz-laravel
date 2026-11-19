<?php

declare(strict_types=1);

it('GET returns HTTP 404 for a non-numeric id', function () {
    $this->get('/api/constellations/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('DELETE returns HTTP 404 for a non-numeric id', function () {
    $this->delete('/api/constellations/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('PUT returns HTTP 404 for a non-numeric id', function () {
    $this->put('/api/constellations/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST returns HTTP 400 with errors when the body fails validation', function () {
    $this->post('/api/constellations', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors(['constellationName', 'galaxyId', 'adderId']);
});

it('PUT returns HTTP 400 with errors when the body fails validation', function () {
    $this->put('/api/constellations/1', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors(['constellationName', 'galaxyId', 'adderId']);
});
