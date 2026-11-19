<?php

declare(strict_types=1);

it('GET returns HTTP 404 for a non-numeric id', function () {
    $this->get('/api/users/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('DELETE is not allowed on a user', function () {
    $this->delete('/api/users/1')->assertStatus(405);
});

it('PUT returns HTTP 404 for a non-numeric id', function () {
    $this->put('/api/users/xyz')
        ->assertStatus(404)
        ->assertExactJson(['message' => 'Not found.']);
});

it('POST returns HTTP 400 with errors when the body fails validation', function () {
    $this->post('/api/users', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors(['username', 'email']);
});

it('PUT returns HTTP 400 with errors when the body fails validation', function () {
    $this->put('/api/users/1', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors(['username', 'email']);
});
