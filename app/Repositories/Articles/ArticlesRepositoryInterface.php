<?php

namespace App\Repositories\Articles;

use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;
use Illuminate\Database\Eloquent\Collection;

interface ArticlesRepositoryInterface
{
    public function search(string $query = ''): Collection;
}