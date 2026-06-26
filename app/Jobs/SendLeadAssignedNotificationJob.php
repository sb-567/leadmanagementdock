<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendLeadAssignedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public int $tries = 3;

    public function __construct(
        public Lead $lead,
        public User $assignedTo
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         Log::info('Lead assignment notification sent', [
            'lead_id'     => $this->lead->id,
            'lead_name'   => $this->lead->name,
            'assigned_to' => $this->assignedTo->name,
            'email'       => $this->assignedTo->email,
        ]);
    }

     public function failed(\Throwable $exception): void
    {
        Log::error('Lead assignment notification failed', [
            'lead_id' => $this->lead->id,
            'error'   => $exception->getMessage(),
        ]);
    }
}
