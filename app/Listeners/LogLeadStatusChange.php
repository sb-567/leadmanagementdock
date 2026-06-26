<?php

namespace App\Listeners;

use App\Events\LeadStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogLeadStatusChange
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LeadStatusChanged  $event
     * @return void
     */
    public function handle(LeadStatusChanged $event)
    {
         Log::info('Lead status changed', [
            'lead_id'    => $event->lead->id,
            'lead_name'  => $event->lead->name,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'changed_by' => $event->changedBy->name,
            'changed_at' => now(),
        ]);
    }
}
