<?php

declare(strict_types=1);

use App\Models\Constellation;
use App\Models\Galaxy;
use App\Models\Star;
use App\Models\User;

function createUserPayload(array $overrides = []): array
{
    return array_merge([
        'username' => 'testuser',
        'email' => 'email@test.com',
    ], $overrides);
}

function makeMinimalUser(int $id, string $name): User
{
    return (new User)->forceFill(['user_id' => $id, 'username' => $name]);
}

function makeUser(): User
{
    return (new User)->forceFill([
        'user_id' => 1,
        'username' => 'john',
        'email' => 'john@example.com',
        'password' => 'secret',
        'first_name' => 'John',
        'last_name' => 'Smith',
        'date_of_birth' => '2000-02-28',
    ]);
}

function createGalaxyPayload(array $overrides = []): array
{
    return array_merge([
        'galaxyName' => 'Test Galaxy',
        'galaxyType' => 'Spiral',
        'distanceMly' => 1234,
        'redshift' => 6,
        'massSolar' => 89700,
        'diameterLy' => 45000,
        'adderId' => 1,
    ], $overrides);
}

function makeMinimalGalaxy(int $id, string $name): Galaxy
{
    return (new Galaxy)->forceFill([
        'galaxy_id' => $id,
        'galaxy_name' => $name
    ]);
}

function makeGalaxy(): Galaxy
{
    $galaxy = (new Galaxy)->forceFill([
        'galaxy_id' => 5,
        'galaxy_name' => 'GAL-1',
        'galaxy_type' => 'Spiral',
        'distance_mly' => 100,
        'redshift' => 2,
        'mass_solar' => 300,
        'diameter_ly' => 400,
    ]);
    $galaxy->setRelation('addedBy', makeMinimalUser(10, 'adder'));
    $galaxy->setRelation('verifiedBy', makeMinimalUser(11, 'verifier'));
    return $galaxy;
}

function insertGalaxy(string $name = 'Test Galaxy'): Galaxy
{
    return Galaxy::create([
        'galaxy_name' => $name,
        'galaxy_type' => 'Spiral',
        'distance_mly' => 1,
        'redshift' => 1,
        'mass_solar' => 1,
        'diameter_ly' => 1,
        'added_by' => 1,
    ]);
}

function createConstellationPayload(array $overrides = []): array
{
    return array_merge([
        'constellationName' => 'Test Constellation',
        'galaxyId' => 1,
        'adderId' => 1,
    ], $overrides);
}

function makeMinimalConstellation(int $id, string $name): Constellation
{
    return (new Constellation)->forceFill([
        'constellation_id' => $id,
        'constellation_name' => $name,
    ]);
}

function makeConstellation(): Constellation
{
    $constellation = makeMinimalConstellation(1, 'C-882');
    $constellation->setRelation('galaxy', makeMinimalGalaxy(1, 'Andromeda'));
    $constellation->setRelation('addedBy', makeMinimalUser(10, 'adder'));
    $constellation->setRelation('verifiedBy', makeMinimalUser(11, 'verifier'));
    return $constellation;
}

function insertConstellation(int $galaxyId, string $name = 'Test Constellation'): Constellation
{
    return Constellation::create([
        'constellation_name' => $name,
        'galaxy_id' => $galaxyId,
        'added_by' => 1,
    ]);
}

function createStarPayload(array $overrides = []): array
{
    return array_merge([
        'starName' => 'Test Star',
        'starType' => 'red dwarf star',
        'constellationId' => 1,
        'rightAscension' => 24,
        'declination' => 20,
        'apparentMagnitude' => -5,
        'spectralType' => 'O',
        'adderId' => 1,
    ], $overrides);
}

function makeMinimalStar(int $id, string $name): Star
{
    return (new Star)->forceFill([
        'star_id' => $id,
        'star_name' => $name,
    ]);
}

function makeStar(): Star
{
    $star = (new Star)->forceFill([
        'star_id' => 7,
        'star_name' => 'S-1',
        'star_type' => 'red dwarf star',
        'right_ascension' => 10,
        'declination' => -20,
        'apparent_magnitude' => -12,
        'spectral_type' => 'O',
    ]);
    $star->setRelation('constellation', makeMinimalConstellation(1, 'Orion'));
    $star->setRelation('addedBy', makeMinimalUser(10, 'adder'));
    $star->setRelation('verifiedBy', makeMinimalUser(11, 'verifier'));
    return $star;
}

function insertStar(int $constellationId, string $name = 'Test Star'): Star
{
    return Star::create([
        'star_name' => $name,
        'star_type' => 'red dwarf star',
        'constellation_id' => $constellationId,
        'right_ascension' => 1,
        'declination' => 1,
        'apparent_magnitude' => 1,
        'spectral_type' => 'O',
        'added_by' => 1,
    ]);
}
