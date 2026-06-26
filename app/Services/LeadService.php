<?php

namespace App\Services;

use App\Models\Lead;
use App\Events\LeadAssigned;
use App\Events\LeadStatusChanged;
use App\Models\User;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Jobs\SendLeadAssignedNotificationJob;
use App\Jobs\SendConversionNotificationJob;

class LeadService
{
    public function __construct(
        protected LeadRepositoryInterface $leadRepository
    ) {}

    public function getAllLeads(array $filters = []): LengthAwarePaginator
    {
        // telecaller sees only their own leads
        if (auth()->user()->hasRole('telecaller')) {
            $filters['assigned_to'] = auth()->id();
        }

        return $this->leadRepository->getAll($filters);
    }

    public function findLead(int $id): Lead
    {
        return $this->leadRepository->findById($id);
    }

    public function createLead(array $data): Lead
    {
        $data['created_by'] = auth()->id();
        $data['status']     = 'new';

        $lead = $this->leadRepository->create($data);

        // fire LeadAssigned event if assigned
        if (!empty($data['assigned_to'])) {
            $assignedTo = User::find($data['assigned_to']);
            event(new LeadAssigned($lead, $assignedTo));

             SendLeadAssignedNotificationJob::dispatch($lead, $assignedTo);
        }

        return $lead;
    }

    public function updateLead(Lead $lead, array $data): bool
    {
       $oldStatus = $lead->status;

        $updated = $this->leadRepository->update($lead, $data);

        // fire LeadStatusChanged event if status changed
        if (isset($data['status']) && $data['status'] !== $oldStatus) {
            event(new LeadStatusChanged(
                $lead->fresh(),
                $oldStatus,
                $data['status'],
                auth()->user()
            ));


               if ($data['status'] === 'converted') {
                    SendConversionNotificationJob::dispatch($lead->fresh());
                }
                
        }

        // fire LeadAssigned event if assigned_to changed
        if (isset($data['assigned_to']) && $data['assigned_to'] !== $lead->assigned_to) {
            $assignedTo = User::find($data['assigned_to']);
            event(new LeadAssigned($lead->fresh(), $assignedTo));
        }

        return $updated;
    }

    public function deleteLead(Lead $lead): bool
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Only admin can delete leads.');
        }

        return $this->leadRepository->delete($lead);
    }
}