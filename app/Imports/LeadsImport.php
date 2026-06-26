<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class LeadsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private int $createdBy;

    public function __construct(int $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function model(array $row): Lead|null
    {
        if (empty($row['phone'])) {
            return null;
        }

        $telecaller = null;
        if (!empty($row['assigned_to'])) {
            $telecaller = User::role('telecaller')
                ->where('name', 'like', '%' . $row['assigned_to'] . '%')
                ->first();
        }

        return new Lead([
            'name'             => $row['name']             ?? 'Unknown',
            'phone'            => $row['phone'],
            'email'            => $row['email']            ?? null,
            'source'           => 'excel',
            'product_interest' => $row['product_interest'] ?? null,
            'city'             => $row['city']             ?? null,
            'remarks'          => $row['remarks']          ?? null,
            'status'           => 'new',
            'assigned_to'      => $telecaller?->id,
            'created_by'       => $this->createdBy,
        ]);
    }
}