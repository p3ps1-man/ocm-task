<?php

use App\Services\DataService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->service = new DataService();
});

test('service fails to fetch data with error code', function () {
    Http::fake([
        $this->service->url . '?page=1' => Http::response([], Response::HTTP_INTERNAL_SERVER_ERROR),
    ]);

    $logData = sprintf('API request failed with status: %d on page: %s', Response::HTTP_INTERNAL_SERVER_ERROR, $this->service->url . '?page=1');

    Log::shouldReceive('error')
        ->once()
        ->with($logData);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Server error');

    $this->service->loadData(1);
});

test('service fetches empty data set', function () {
    Http::fake([
        $this->service->url . '?page=1' => Http::response([], 200),
    ]);

    $logData = sprintf('Fetched empty data, page:  %s', $this->service->url . '?page=1');

    Log::shouldReceive('error')
        ->once()
        ->with($logData);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('No data on server');

    $this->service->loadData(1);
});

test('data is fetched and stored sucessfully', function () {
    $this->service->loadData(1);

    $this->assertDatabaseCount('books', 32);
});
