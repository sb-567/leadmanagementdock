<?php

namespace App\Console\Commands;

use App\Jobs\SendFollowUpReminderJob;
use App\Models\Lead;
use Illuminate\Console\Command;

class SendDailyFollowUpReminders extends Command
{
    // artisan se manually bhi chala sakte ho
    protected $signature   = 'crm:send-followup-reminders';
    protected $description = 'Aaj ke followup reminders telecallers ko bhejo';

    public function handle(): void
    {
        // aaj jinko followup karna hai woh leads lo
        $leads = Lead::with('assignedTo')
            ->whereDate('next_followup_date', today())
            ->whereNotIn('status', ['converted', 'junk'])
            ->whereNotNull('assigned_to')
            ->get();

        if ($leads->isEmpty()) {
            $this->info('Aaj koi followup nahi hai.');
            return;
        }

        foreach ($leads as $lead) {
            // har lead ke liye job dispatch karo
            SendFollowUpReminderJob::dispatch($lead);
            $this->info("Reminder bheja: {$lead->name} → {$lead->assignedTo->name}");
        }

        $this->info("Total {$leads->count()} reminders bheje.");
    }
}