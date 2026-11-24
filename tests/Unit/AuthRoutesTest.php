<?php

declare(strict_types=1);

it('POST login returns HTTP 400 with errors when the body fails validation', function () {
    $this->post('/api/login', [])
        ->assertStatus(400)
        ->assertJsonValidationErrors(['username', 'password']);
});
