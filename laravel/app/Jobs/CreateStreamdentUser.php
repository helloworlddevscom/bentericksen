<?php

namespace App\Jobs;

use Bentericksen\StreamdentServices\StreamdentAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Facades\StreamdentService;

class CreateStreamdentUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 20;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $streamdentService = new StreamdentAPIService();
        $response = $streamdentService->createUser([
            'user_id' => $this->id
        ]);

        // If the job failed, attempt again in 10 seconds
        if (!isset($response['success']) || $response['success'] != 'true') {
            return $this->release(10);
        }
    }
}
