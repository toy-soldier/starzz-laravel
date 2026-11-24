<?php

declare(strict_types=1);

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Testing\DatabaseTransactions;

require_once __DIR__ . '/Helpers.php';

pest()->extend(Tests\TestCase::class)
    ->beforeEach(function () {
        $this->withToken(userToken());
    })
    ->in('Unit');

pest()->extend(Tests\TestCase::class)
    ->use(DatabaseTransactions::class)
    ->beforeEach(function () {
        $this->withToken(userToken());
    })
    ->in('Feature');
/**
 * Validate data against a Form Request's rules, skipping `exists:` rules
 * because those need a database.
 */
function validateWithoutDb(FormRequest $request, array $data): Validator
{
    $rules = array_map(
        fn(array $fieldRules) => array_values(array_filter(
            $fieldRules,
            fn($rule) => ! (is_string($rule) && str_starts_with($rule, 'exists:'))
        )),
        $request->rules()
    );

    return validator($data, $rules);
}

/** Serialize a resource the way it would appear in a response body. */
function resourceToArray(mixed $resource): array
{
    return json_decode(json_encode($resource), true);
}
