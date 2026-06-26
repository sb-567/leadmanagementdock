<?php

// app/Http/Controllers/ReportController.php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'teamleader'])) {
            abort(403);
        }

        $query = Lead::query();

        // filter by date range
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date   . ' 23:59:59',
            ]);
        }

        // filter by telecaller
        if ($request->telecaller_id) {
            $query->where('assigned_to', $request->telecaller_id);
        }

        // filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // summary counts
        $summary = [
            'total'          => (clone $query)->count(),
            'new'            => (clone $query)->where('status', 'new')->count(),
            'interested'     => (clone $query)->where('status', 'interested')->count(),
            'callback'       => (clone $query)->where('status', 'callback')->count(),
            'converted'      => (clone $query)->where('status', 'converted')->count(),
            'not_interested' => (clone $query)->where('status', 'not_interested')->count(),
            'junk'           => (clone $query)->where('status', 'junk')->count(),
        ];

        // telecaller performance
        $telecallers = User::role('telecaller')
            ->withCount([
                'leads as total_leads',
                'leads as converted' => fn($q) => $q->where('status', 'converted'),
                'leads as interested' => fn($q) => $q->where('status', 'interested'),
                'leads as callback'  => fn($q) => $q->where('status', 'callback'),
                'leads as junk'      => fn($q) => $q->where('status', 'junk'),
            ])
            ->get();

        // leads list with filters applied
        $leads = $query->with('assignedTo')
                       ->latest()
                       ->paginate(15);

        $allTelecallers = User::role('telecaller')->get();

        return view('reports.index', compact(
            'summary',
            'telecallers',
            'leads',
            'allTelecallers'
        ));
    }
}