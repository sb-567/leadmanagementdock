<?php

namespace App\Repositories\Interfaces;

use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): Lead;
    public function create(array $data): Lead;
    public function update(Lead $lead, array $data): bool;
    public function delete(Lead $lead): bool;
}