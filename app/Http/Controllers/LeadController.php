<?php

// app/Http/Controllers/LeadController.php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\FollowUp; 
use App\Services\LeadService;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;

class LeadController extends Controller
{

    // LeadController.php
    public function __construct(
        protected LeadService $leadService
    )
    {

    
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasAnyRole(['admin', 'teamleader', 'telecaller'])) {
                abort(403);
            }
            return $next($request);
        });

        
    }

    public function index()
    {   

      
        // $leads = Lead::with(['assignedTo', 'latestFollowUp'])
        //     ->when(auth()->user()->hasRole('telecaller'), function ($q) {
        //         $q->where('assigned_to', auth()->id());
        //     })
        //     ->latest()
        //     ->paginate(15);

            

        // return view('leads.index', compact('leads'));

        $leads = $this->leadService->getAllLeads(
            request()->only(['status', 'from_date', 'to_date'])
        );

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $telecallers = User::role('telecaller')->where('is_active', true)->get();
        return view('leads.create', compact('telecallers'));
    }

    public function store(StoreLeadRequest $request)
    {   


        // Lead::create(array_merge(
        //     $request->validated(),
        //     ['created_by' => auth()->id()]
        // ));

        

        // return redirect()->route('leads.index')
        //                  ->with('success', 'Lead created successfully.');



        $this->leadService->createLead($request->validated());

        return redirect()->route('leads.index')
                         ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {   

        // print_r($lead->statusLabel());
        // die;

        $lead = $this->leadService->findLead($lead->id);
        return view('leads.show', compact('lead'));

        // $lead->load(['assignedTo', 'followUps.user']);
        // return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $telecallers = User::role('telecaller')->where('is_active', true)->get();

        $statuses = Lead::$statuses;

     

        return view('leads.edit', compact('lead', 'telecallers', 'statuses'));

        
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {   
        
        // $lead->update($request->validated());

        // return redirect()->route('leads.index')
        //                  ->with('success', 'Lead updated successfully.');


        $this->leadService->updateLead($lead, $request->validated());

        return redirect()->route('leads.index')
                         ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        // $lead->delete();

        // return redirect()->route('leads.index')
        //                  ->with('success', 'Lead deleted.');

                         $this->leadService->deleteLead($lead);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead deleted.');
    }
}