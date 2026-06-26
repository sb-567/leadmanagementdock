<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWeeklyReport extends Command
{
    protected $signature   = 'crm:send-weekly-report';
    protected $description = 'Admin ko weekly performance report bhejo';

    public function handle(): void
    {
        $from = now()->subWeek()->startOfWeek();
        $to   = now()->subWeek()->endOfWeek();

        // is hafte ke stats
        $stats = [
            'total'     => Lead::whereBetween('created_at', [$from, $to])->count(),
            'converted' => Lead::whereBetween('created_at', [$from, $to])
                               ->where('status', 'converted')->count(),
            'junk'      => Lead::whereBetween('created_at', [$from, $to])
                               ->where('status', 'junk')->count(),
        ];

        // telecaller performance
        $telecallers = User::role('telecaller')
            ->withCount([
                'leads as total'     => fn($q) => $q->whereBetween('created_at', [$from, $to]),
                'leads as converted' => fn($q) => $q->whereBetween('created_at', [$from, $to])
                                                     ->where('status', 'converted'),
            ])
            ->get();

        // abhi log kar rahe hain
        // baad mein Mail::to(admin)->send(new WeeklyReportMail($stats, $telecallers))
        Log::info('Weekly report generated', [
            'week'        => $from->format('d M') . ' - ' . $to->format('d M Y'),
            'total_leads' => $stats['total'],
            'converted'   => $stats['converted'],
            'junk'        => $stats['junk'],
        ]);

        $this->info('Weekly report bheja gaya.');
    }
}