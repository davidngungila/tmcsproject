<?php

namespace App\Jobs;

use App\Services\MessagingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    protected $phone;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(MessagingService $messagingService): void
    {
        try {
            $result = $messagingService->sendSms($this->phone, $this->message);
            
            if ($result['status'] === 'error') {
                Log::error("Async SMS failed: " . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error("Exception in SendSmsJob: " . $e->getMessage());
            throw $e; // Retry if failed
        }
    }
}
