<?php

namespace App\Console\Commands;

use App\Models\Article;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\ClientErrorResponseException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\FlareClient\Http\Exceptions\MissingParameter;

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
            try {
                $this->elasticsearch->index([
                    'index' => $article->getSearchIndex(),
                    'id' => $article->getKey(),
                    'body' => $article->toArray(),
                ]);
            } catch (ClientErrorResponseException|MissingParameter $e) {
                Log::debug( $e->getMessage());
            }

            // PHPUnit-style feedback
            $this->output->write('.');
        }

        $this->info("\nDone!");
    }
}
