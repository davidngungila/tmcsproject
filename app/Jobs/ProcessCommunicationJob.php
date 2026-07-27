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
            $type = strtolower($this->communication->type);

            if ($type === 'sms') {
                $response = $messagingService->sendSms($this->recipients, $this->communication->message);
                if ($response['status'] === 'success') {
                    $this->communication->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                        'error_message' => null,
                    ]);
                } else {
                    $this->communication->update([
                        'status' => 'failed',
                        'error_message' => $response['message'] ?? 'Failed to send SMS.',
                    ]);
                    Log::error("Communication Job SMS Failed: " . $response['message']);
                }
            } elseif ($type === 'email') {
                foreach ($this->recipients as $email) {
                    Mail::to($email)->send(new GenericMailable($this->communication->subject, $this->communication->message));
                }
                $this->communication->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'error_message' => null,
                ]);
            } elseif ($type === 'whatsapp') {
                $response = $messagingService->sendWhatsApp($this->recipients, $this->communication->message);
                if ($response['status'] === 'success') {
                    $this->communication->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                        'error_message' => null,
                    ]);
                } else {
                    $this->communication->update([
                        'status' => 'failed',
                        'error_message' => $response['message'] ?? 'Failed to send WhatsApp message.',
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->communication->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("ProcessCommunicationJob Exception: " . $e->getMessage());
            throw $e;
        }
    }
}
