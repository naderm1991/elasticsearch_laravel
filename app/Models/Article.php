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

    public function toElasticsearchDocumentArray(): array
    {
        return [];
    }

    public function getSearchIndex()
    {
        return $this->id;
    }

    public function getSearchType(): string
    {
        return "";
    }

    public function toSearchArray(): array
    {
        return $this->toElasticsearchDocumentArray();
    }
}
