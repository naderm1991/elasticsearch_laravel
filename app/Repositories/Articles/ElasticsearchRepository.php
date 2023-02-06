<?php

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\SearchRepositoryInterface;
use Elasticsearch\Client;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
class ElasticsearchRepository implements SearchRepositoryInterface
{
    private Client $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $term = ''): Collection
    {
        $items = $this->searchOnElasticsearch($term);

        return $this->buildCollection((array)$items);
    }

    private function searchOnElasticsearch(string $query = ''): callable|array
    {
        $model = new Article;
        return $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'body', 'tags'],
                        'query' => $query,
                    ],
                ],
            ],
        ]);
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Article::findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            })
        ;
    }
}