<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $database = (string) ($_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE'));

        if (! str_ends_with($database, '_test')) {
            throw new RuntimeException(
                "Refusing to run tests: DB_DATABASE is '{$database}', but it must end in '_test'."
            );
        }

        parent::setUp();
    }
}
