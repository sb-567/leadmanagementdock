@extends('layouts.app')

@section('page_title', 'Reports')

@section('content')

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0">{{ $summary['total'] }}</h4>
                <small class="text-muted">Total Leads</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0 text-primary">{{ $summary['new'] }}</h4>
                <small class="text-muted">New</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0 text-info">{{ $summary['interested'] }}</h4>
                <small class="text-muted">Interested</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0 text-warning">{{ $summary['callback'] }}</h4>
                <small class="text-muted">Callback</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0 text-success">{{ $summary['converted'] }}</h4>
                <small class="text-muted">Converted</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0 text-danger">{{ $summary['junk'] }}</h4>
                <small class="text-muted">Junk</small>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control"
                           value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control"
                           value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Telecaller</label>
                    <select name="telecaller_id" class="form-select">
                        <option value="">All</option>
                        @foreach($allTelecallers as $tc)
                            <option value="{{ $tc->id }}"
                                {{ request('telecaller_id') == $tc->id ? 'selected' : '' }}>
                                {{ $tc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        @foreach(\App\Models\Lead::$statuses as $key => $val)
                            <option value="{{ $key }}"
                                {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $val['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Telecaller Performance --}}
<div class="card mb-4">
    <div class="card-header">Telecaller Performance</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Telecaller</th>
                    <th>Total</th>
                    <th>Interested</th>
                    <th>Converted</th>
                    <th>Callback</th>
                    <th>Junk</th>
                    <th>Conversion %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($telecallers as $tc)
                <tr>
                    <td>{{ $tc->name }}</td>
                    <td>{{ $tc->total_leads }}</td>
                    <td>{{ $tc->interested }}</td>
                    <td>{{ $tc->converted }}</td>
                    <td>{{ $tc->callback }}</td>
                    <td>{{ $tc->junk }}</td>
                    <td>
                        @if($tc->total_leads > 0)
                            {{ round(($tc->converted / $tc->total_leads) * 100, 1) }}%
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">No data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Leads List --}}
<div class="card">
    <div class="card-header">Leads</div>
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
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('leads.show', $lead->id) }}">
                            {{ $lead->name }}
                        </a>
                    </td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ $lead->source ?? '-' }}</td>
                    <td>{{ $lead->assignedTo->name ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $lead->statusColor() }}">
                            {{ $lead->statusLabel() }}
                        </span>
                    </td>
                    <td>{{ $lead->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">No leads found.</td>
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