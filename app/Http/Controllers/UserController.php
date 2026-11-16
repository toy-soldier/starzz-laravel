<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserSummaryResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function getUserList(): AnonymousResourceCollection
    {
        return UserSummaryResource::collection(
            User::all()
        );
    }

    public function getUser(int $id): UserDetailResource
    {
        return new UserDetailResource(User::findOrFail($id));
    }

    public function registerUser(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => config('starzz.password_reset_sentinel'),
            'first_name' => $data['firstName'] ?? null,
            'last_name' => $data['lastName'] ?? null,
            'date_of_birth' => $data['dateOfBirth'] ?? null,
        ]);

        return $this->getUser($user->user_id)
            ->response()
            ->setStatusCode(201);
    }

    public function updateUser(StoreUserRequest $request, int $id): UserDetailResource
    {
        $data = $request->validated();

        $user = User::findOrFail($id);

        $user->update([
            'username' => $data['username'],
            'email' => $data['email'],
            'first_name' => $data['firstName'] ?? null,
            'last_name' => $data['lastName'] ?? null,
            'date_of_birth' => $data['dateOfBirth'] ?? null,
        ]);

        return $this->getUser($id);
    }
}
