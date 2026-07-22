<?php

namespace App\Console\Commands;

use App\Jobs\ProcessCommunicationJob;
use App\Models\Communication;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

#[Signature('app:process-scheduled-communications')]
#[Description('Processes scheduled communications that are due to be sent')]
class ProcessScheduledCommunications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Looking for scheduled communications to process...');

        $scheduledCommunications = Communication::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        $count = $scheduledCommunications->count();

        if ($count === 0) {
            $this->info('No scheduled communications to process.');
            return 0;
        }

        $this->info("Found {$count} scheduled communication(s) to process.");

        foreach ($scheduledCommunications as $communication) {
            try {
                $recipients = json_decode($communication->recipients, true) ?? [];
                
                if (!empty($recipients)) {
                    $this->info("Processing communication #{$communication->id} to " . count($recipients) . " recipient(s)...");
                    
                    ProcessCommunicationJob::dispatch($communication, $recipients);
                    
                    $communication->update([
                        'status' => 'pending',
                        'sent_at' => now(),
                    ]);
                } else {
                    $this->warn("Communication #{$communication->id} has no recipients, skipping.");
                }
            } catch (\Exception $e) {
                $this->error("Error processing communication #{$communication->id}: " . $e->getMessage());
                Log::error("Error processing scheduled communication #{$communication->id}", [
                    'exception' => $e,
                ]);
                $communication->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Scheduled communications processed successfully!");
        return 0;
    }
}
