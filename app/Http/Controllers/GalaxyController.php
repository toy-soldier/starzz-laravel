<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class GalaxyController extends Controller
{
    public function getGalaxyList(): array
    {
        return ["message" => "Successfully called getGalaxyList()"];
    }

    public function getGalaxy(string $id): array
    {
        return ["message" => "Successfully called getGalaxy({$id})"];
    }

    public function registerGalaxy(): array
    {
        return ["message" => "Successfully called registerGalaxy"];
    }

    public function updateGalaxy(string $id): array
    {
        return ["message" => "Successfully called updateGalaxy({$id})"];
    }

    public function deleteGalaxy(string $id): array
    {
        return ["message" => "Successfully called deleteGalaxy({$id})"];
    }
}
