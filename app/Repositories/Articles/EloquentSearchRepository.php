<?php

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\SearchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentSearchRepository implements SearchRepositoryInterface
{
    public function search(string $term): Collection
    {
        return Article::query()
            ->where(fn ($query) => (
            $query->where('body', 'LIKE', "%{$term}%")
                ->orWhere('title', 'LIKE', "%{$term}%")
            ))
            ->get()
        ;
    }
}