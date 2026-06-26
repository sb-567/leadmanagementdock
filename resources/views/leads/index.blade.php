@extends('layouts.app')

@section('page_title', 'Leads')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">All Leads</h5>
   
     @role('admin|teamleader')
    <div>
        <a href="{{ route('leads.import') }}" class="btn btn-success btn-sm me-2">
            Import Excel
        </a>
        <a href="{{ route('leads.create') }}" class="btn btn-primary btn-sm">
            Add Lead
        </a>
    </div>
    @endrole
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Source</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Last Follow Up</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ $lead->source ?? '-' }}</td>
                    <td>{{ $lead->assignedTo->name ?? '-' }}</td>
                    <td>
                      <span class="badge bg-{{ \App\Models\Lead::$statuses[$lead->status]['color'] }}">
                        {{ \App\Models\Lead::$statuses[$lead->status]['label'] }}
                    </span>
                    </td>
                    <td>
                        {{ $lead->latestFollowUp ? $lead->latestFollowUp->created_at->diffForHumans() : '-' }}
                    </td>
                    <td>
                        <a href="{{ route('leads.show', $lead->id) }}"
                           class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('leads.edit', $lead->id) }}"
                           class="btn btn-sm btn-warning">Edit</a>
                        @role('admin')
                        <form action="{{ route('leads.destroy', $lead->id) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this lead?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $leads->links('pagination::bootstrap-5') }}
</div>
@endsection