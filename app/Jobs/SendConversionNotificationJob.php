<?php

namespace App\Jobs;


use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendConversionNotificationJob implements ShouldQueue
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
        $teamLeaders = User::role('teamleader')->get();

        foreach ($teamLeaders as $tl) {
            Log::info('Conversion notification sent to TL', [
                'tl_name'   => $tl->name,
                'lead_name' => $this->lead->name,
                'lead_id'   => $this->lead->id,
            ]);

            // Mail::to($tl->email)
            //     ->send(new LeadConvertedMail($this->lead, $tl));
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Conversion notification failed', [
            'lead_id' => $this->lead->id,
            'error'   => $exception->getMessage(),
        ]);
    }
}
