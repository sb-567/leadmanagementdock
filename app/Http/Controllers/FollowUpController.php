<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;
use App\Jobs\SendFollowUpReminderJob;


class FollowUpController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lead_id'            => 'required|exists:leads,id',
            'call_status'        => 'required',
            'status'             => 'required',
            'next_followup_date' => 'nullable|date',
            'remarks'            => 'nullable|string',
        ]);

        // Save follow up
        FollowUp::create([
            'lead_id'            => $request->lead_id,
            'user_id'            => auth()->id(),
            'call_status'        => $request->call_status,
            'remarks'            => $request->remarks,
            'next_followup_date' => $request->next_followup_date,
            'attempt'            => FollowUp::where('lead_id', $request->lead_id)->count() + 1,
        ]);

        // Update lead status
        Lead::find($request->lead_id)->update([
            'status' => $request->status
        ]);

            if ($request->next_followup_date) {
                $lead = Lead::find($request->lead_id);

                SendFollowUpReminderJob::dispatch($lead)
                    ->delay(now()->parse($request->next_followup_date)->startOfDay());
            }
            

        return redirect()->route('leads.show', $request->lead_id)
                         ->with('success', 'Follow up saved.');
    }
}
