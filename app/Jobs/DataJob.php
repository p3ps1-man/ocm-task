<?php

namespace App\Jobs;

use App\Services\DataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DataJob implements ShouldQueue
{
    use Queueable;

    private DataService $service;

    /**
     * Create a new job instance.
     */
    public function __construct(DataService $service) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // start loading on second page because first hit is going to load first page
        for ($i = 2; $i <= 10; $i++) {
            $this->service->loadData($i);
        }
    }
}
