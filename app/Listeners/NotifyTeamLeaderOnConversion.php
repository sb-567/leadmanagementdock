<?php

namespace App\Listeners;

use App\Events\LeadStatusChanged;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyTeamLeaderOnConversion
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
        // sirf tab fire ho jab status converted ho
        if ($event->newStatus !== 'converted') {
            return;
        }

        // sabhi teamleaders ko notify karo
        $teamLeaders = User::role('teamleader')->get();

        foreach ($teamLeaders as $tl) {
            Log::info('Team leader notified of conversion', [
                'tl_name'   => $tl->name,
                'lead_name' => $event->lead->name,
                'converted_by' => $event->changedBy->name,
            ]);

            // jab mail setup ho tab yahan Mail::to($tl)->send(...) lagana
        }
    }
}
