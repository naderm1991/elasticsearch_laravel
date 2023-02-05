<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface SearchRepositoryInterface
{
    public function search(string $term): Collection;
}