<?php

namespace App\Console\Commands;

use App\Models\Article;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all articles to Elasticsearch';
    private Client $elasticsearch;


    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }


    /**
     * Execute the  console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Indexing all articles. This might take a while...');


        foreach (Article::cursor() as $article)
        {
            //App\Models\Article::elasticsearchIndex()
            try {
                $this->elasticsearch->index([
                    'index' => $article->getSearchIndex(),
                    'type' => $article->getSearchType(),
                    'id' => $article->getKey(),
                    'body' => $article->toSearchArray(),
                ]);
            } catch (ClientResponseException|MissingParameterException|ServerResponseException $e) {
                Log::debug( $e->getMessage());
            }

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("\nDone!");
    }
}
