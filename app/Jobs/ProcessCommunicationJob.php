<?php

namespace App\Jobs;

use App\Models\Communication;
use App\Services\MessagingService;
use App\Mail\GenericMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCommunicationJob implements ShouldQueue
{
    use Queueable;

    protected $communication;
    protected $recipients;

    /**
     * Create a new job instance.
     */
    public function __construct(Communication $communication, array $recipients)
    {
        $this->communication = $communication;
        $this->recipients = $recipients;
    }

    /**
     * Execute the job.
     */
    public function handle(MessagingService $messagingService): void
    {
        try {
            if ($this->communication->type === 'SMS') {
                $response = $messagingService->sendSms($this->recipients, $this->communication->message);
                if ($response['status'] === 'success') {
                    $this->communication->update(['status' => 'Sent']);
                } else {
                    $this->communication->update(['status' => 'Failed']);
                    Log::error("Communication Job SMS Failed: " . $response['message']);
                }
            } elseif ($this->communication->type === 'Email') {
                foreach ($this->recipients as $email) {
                    Mail::to($email)->send(new GenericMailable($this->communication->subject, $this->communication->message));
                }
                $this->communication->update(['status' => 'Sent']);
            } elseif ($this->communication->type === 'WhatsApp') {
                $response = $messagingService->sendWhatsApp($this->recipients, $this->communication->message);
                if ($response['status'] === 'success') {
                    $this->communication->update(['status' => 'Sent']);
                } else {
                    $this->communication->update(['status' => 'Failed']);
                }
            }
        } catch (\Exception $e) {
            $this->communication->update(['status' => 'Failed']);
            Log::error("ProcessCommunicationJob Exception: " . $e->getMessage());
            throw $e;
        }
    }
}
