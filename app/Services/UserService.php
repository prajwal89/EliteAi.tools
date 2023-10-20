<?php

namespace App\Services;

use App\Interfaces\EntityServiceCrudInterface;
use App\Models\User;

class UserService implements EntityServiceCrudInterface
{
    public function __construct(public User $model)
    {
    }

    public function store(array $attributes): ?User
    {
        return User::create($attributes);
        // return User::create([
        //     'name' => $attributes['name'],
        //     'email' => $attributes['email'],
        //     'avatar_url' => $attributes['avatar_url'],
        //     'provider_type' => $attributes['provider_type'],
        //     'provider_id' => $attributes['provider_id'],
        //     'password' => $attributes['password'],
        // ]);
    }

    public function update(int $id, array $attributes): int
    {
        return User::where('id', $id)->update($attributes);
    }

    public function destroy(int $id): int
    {
        return User::destroy($id);
    }
}
