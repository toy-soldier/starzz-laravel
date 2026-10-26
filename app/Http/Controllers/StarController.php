<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class StarController extends Controller
{
    public function getStarList(): array
    {
        return ["message" => "Successfully called getStarList()"];
    }

    public function getStar(string $id): array
    {
        return ["message" => "Successfully called getStar({$id})"];
    }

    public function registerStar(): array
    {
        return ["message" => "Successfully called registerStar"];
    }

    public function updateStar(string $id): array
    {
        return ["message" => "Successfully called updateStar({$id})"];
    }

    public function deleteStar(string $id): array
    {
        return ["message" => "Successfully called deleteStar({$id})"];
    }
}
