<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Book;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataService
{
    public string $url;

    function __construct()
    {
        $this->url = env('EXTERNAL_API', 'https://gutendex.com/books/');
    }

    // handle initial page load
    public function loadData(int $page): void
    {
        try {
            $res = Http::get($this->url, ['page' => $page]);
            $uri = $res->effectiveUri();

            if ($res->ok()) {
                $data = $res->collect('results');
                if ($data->isEmpty()) {
                    $logData = sprintf('Fetched empty data, page:  %s', $uri);

                    Log::error($logData);
                    throw new Exception('No data on server');
                }
                self::storeData($data);
            } else {
                $logData = sprintf('API request failed with status: %d on page: %s', $res->status(), $uri);

                Log::error($logData);
                throw new Exception('Server error');
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch data: ' . $e->getMessage());
            throw $e;
        }
    }

    private static function storeData(Collection $collection): void
    {
        foreach ($collection as $item) {
            $book = Book::create([
                'title' => $item['title'],
                'description' => $item['summaries'][0]
            ]);

            $ids = [];

            foreach ($item['authors'] as $key => $value) {
                $ids[$key] = Author::create([
                    'name' => $value['name'],
                    'birth_year' => $value['birth_year'],
                    'death_year' => $value['death_year'],
                ])->id;
            }

            $book->authors()->attach($ids);
        }
    }
}
