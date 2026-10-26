<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function getUserList(): array
    {
        return ["message" => "Successfully called getUserList()"];
    }

    public function getUser(string $id): array
    {
        return ["message" => "Successfully called getUser({$id})"];
    }

    public function registerUser(): array
    {
        return ["message" => "Successfully called registerUser"];
    }

    public function updateUser(string $id): array
    {
        return ["message" => "Successfully called updateUser({$id})"];
    }
}
