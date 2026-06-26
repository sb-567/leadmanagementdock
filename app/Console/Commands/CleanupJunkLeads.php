<?php

namespace App\Console\Commands;

use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupJunkLeads extends Command
{
    protected $signature   = 'crm:cleanup-junk-leads';
    protected $description = '3 mahine purane junk leads delete karo';

    public function handle(): void
    {
        $leads = Lead::where('status', 'junk')
                     ->where('updated_at', '<', now()->subMonths(3))
                     ->get();

        if ($leads->isEmpty()) {
            $this->info('Koi junk lead nahi mila.');
            return;
        }

        $count = $leads->count();
        $leads->each->delete();

        Log::warning('Junk leads cleanup', [
            'deleted_count' => $count,
            'deleted_at'    => now(),
        ]);

        $this->info("{$count} junk leads delete kiye.");
    }
}