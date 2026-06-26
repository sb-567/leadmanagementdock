<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class LeadRepository implements LeadRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return Lead::with(['assignedTo', 'latestFollowUp'])
            ->when(
                isset($filters['assigned_to']),
                fn($q) => $q->where('assigned_to', $filters['assigned_to'])
            )
            ->when(
                isset($filters['status']),
                fn($q) => $q->where('status', $filters['status'])
            )
            ->when(
                isset($filters['from_date']),
                fn($q) => $q->whereDate('created_at', '>=', $filters['from_date'])
            )
            ->when(
                isset($filters['to_date']),
                fn($q) => $q->whereDate('created_at', '<=', $filters['to_date'])
            )
            ->latest()
            ->paginate(15);
    }

    public function findById(int $id): Lead
    {
        return Lead::with(['assignedTo', 'followUps.user'])->findOrFail($id);
    }

    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function update(Lead $lead, array $data): bool
    {
        return $lead->update($data);
    }

    public function delete(Lead $lead): bool
    {
        return $lead->delete();
    }
}