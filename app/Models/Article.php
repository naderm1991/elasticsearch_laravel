<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @method static findMany(array $ids)
 */
class Article extends Model
{
    use HasFactory;
    use Searchable;

    protected $casts = [
        'tags' => 'json',
    ];

    public function getSearchIndex(): string
    {
        return "article";
    }

    public function getSearchType(): string
    {
        return "article";
    }

    public function toSearchArray(): array
    {
        return $this->toElasticsearchDocumentArray();
    }

    public function toElasticsearchDocumentArray(): array
    {
        return [
            'title','body','tags'
        ];
    }
}
