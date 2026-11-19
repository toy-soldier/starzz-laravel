<?php

declare(strict_types=1);

it('GET returns HTTP 404 for a non-numeric id', function () {
    $this->get('/api/stars/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('DELETE returns HTTP 404 for a non-numeric id', function () {
    $this->delete('/api/stars/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('PUT returns HTTP 404 for a non-numeric id', function () {
    $this->put('/api/stars/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST returns HTTP 400 with errors when the body fails validation', function () {
    $this->post('/api/stars', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors([
            'starName',
            'starType',
            'constellationId',
            'rightAscension',
            'declination',
            'apparentMagnitude',
            'spectralType',
            'adderId',
        ]);
});

it('PUT returns HTTP 400 with errors when the body fails validation', function () {
    $this->put('/api/stars/1', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors([
            'starName',
            'starType',
            'constellationId',
            'rightAscension',
            'declination',
            'apparentMagnitude',
            'spectralType',
            'adderId',
        ]);
});
