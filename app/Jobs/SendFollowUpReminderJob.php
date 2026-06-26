<?php

namespace App\Jobs;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use Illuminate\Support\Facades\Log;

class SendFollowUpReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public Lead $lead
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $telecaller = $this->lead->assignedTo;

        if (!$telecaller) {
            return;
        }

        Log::info('Follow up reminder sent', [
            'lead_id'     => $this->lead->id,
            'lead_name'   => $this->lead->name,
            'telecaller'  => $telecaller->name,
            'remind_at'   => now(),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Follow up reminder failed', [
            'lead_id' => $this->lead->id,
            'error'   => $exception->getMessage(),
        ]);
    }
}
