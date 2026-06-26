<?php

namespace App\Listeners;

use App\Events\LeadAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyTelecallerOnAssignment
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
     * @param  \App\Events\LeadAssigned  $event
     * @return void
     */
    public function handle(LeadAssigned $event)
    {
          Log::info('Lead assigned to telecaller', [
                'lead_id'       => $event->lead->id,
                'lead_name'     => $event->lead->name,
                'assigned_to'   => $event->assignedTo->name,
                'assigned_at'   => now(),
            ]);
    }
}
