<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface EntityServiceCrudInterface
{
    public function store(array $attributes): ?Model;

    public function update(int $id, array $attributes): int;

    public function destroy(int $id): int;
}
