<?php

use App\Jobs\DataJob;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;

afterEach(function () {
    Cache::flush();
});

test('index route inital load success', function () {
    Queue::fake();

    $this->get(route('book.index'))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(15, 'data');

    $this->assertDatabaseCount('books', 32);

    Queue::assertPushed(DataJob::class);
});

test('index route initial load reads from db', function () {
    Queue::fake();
    Cache::put('data_loaded', true);

    Book::factory()->create();

    $this->get(route('book.index'))
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(1, 'data');

    Queue::assertNotPushed(DataJob::class);
});

test('search route search by title', function () {
    Cache::put('data_loaded', true);

    $book = Book::factory()->create(['title' => 'test-title']);

    $this->get(route('book.index') . "?param=test")
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                $book->toArray()
            ]
        ]);
});

test('search route search by description', function () {
    Cache::put('data_loaded', true);

    $book = Book::factory()->create(['description' => 'description-test']);

    $this->get(route('book.index') . "?param=description")
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                $book->toArray()
            ]
        ]);
});

test('search route search by author name', function () {
    Cache::put('data_loaded', true);

    $book = Book::factory()->create(['description' => 'description-test']);
    $author = Author::factory()->create(['name' => 'John doe']);
    $book->authors()->attach([$author->id]);

    $this->get(route('book.index') . "?param=jo")
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                $book->toArray()
            ]
        ]);
});

test('search route match all', function () {
    Cache::put('data_loaded', true);

    $book1 = Book::factory()->create(['description' => 'description-test']);
    $book2 = Book::factory()->create(['title' => 'test-title']);
    $book3 = Book::factory()->create();

    $author = Author::factory()->create(['name' => 'tester']);
    $book3->authors()->attach([$author->id]);

    $this->get(route('book.index') . "?param=test")
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                $book1->toArray(),
                $book2->toArray(),
                $book3->toArray(),
            ]
        ]);
});
