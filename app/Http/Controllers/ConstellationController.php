<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ConstellationController extends Controller
{
    public function getConstellationList(): array
    {
        return ["message" => "Successfully called getConstellationList()"];
    }

    public function getConstellation(string $id): array
    {
        return ["message" => "Successfully called getConstellation({$id})"];
    }

    public function registerConstellation(): array
    {
        return ["message" => "Successfully called registerConstellation"];
    }

    public function updateConstellation(string $id): array
    {
        return ["message" => "Successfully called updateConstellation({$id})"];
    }

    public function deleteConstellation(string $id): array
    {
        return ["message" => "Successfully called deleteConstellation({$id})"];
    }
}
