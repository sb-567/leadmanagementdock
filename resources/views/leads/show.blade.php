@extends('layouts.app')

@section('page_title', 'Lead Detail')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Lead Detail — {{ $lead->name }}</h5>
    <div>
        <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ route('leads.index') }}" class="btn btn-secondary btn-sm ms-2">Back</a>
    </div>
</div>

<div class="row">

    {{-- Lead Info --}}
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header fw-500">Lead Information</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Name</td>
                        <td>{{ $lead->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $lead->phone }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $lead->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Source</td>
                        <td>{{ $lead->source ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Product Interest</td>
                        <td>{{ $lead->product_interest ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">City</td>
                        <td>{{ $lead->city ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Assigned To</td>
                        <td>{{ $lead->assignedTo->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $lead->statusColor() }}">
                                {{ $lead->statusLabel() }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Remarks</td>
                        <td>{{ $lead->remarks ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created By</td>
                        <td>{{ $lead->createdBy->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created At</td>
                        <td>{{ $lead->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Follow Up Section --}}
    <div class="col-md-7">

        {{-- Add Follow Up Form --}}
        <div class="card mb-4">
            <div class="card-header fw-500">Add Follow Up</div>
            <div class="card-body">
                <form method="POST" action="{{ route('followups.store') }}">
                    @csrf
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Call Status</label>
                            <select name="call_status" class="form-select">
                                <option value="called">Called</option>
                                <option value="not_answered">Not Answered</option>
                                <option value="busy">Busy</option>
                                <option value="switched_off">Switched Off</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lead Status</label>
                            <select name="status" class="form-select">
                                <option value="new"            {{ $lead->status == 'new'            ? 'selected' : '' }}>New</option>
                                <option value="interested"     {{ $lead->status == 'interested'     ? 'selected' : '' }}>Interested</option>
                                <option value="not_interested" {{ $lead->status == 'not_interested' ? 'selected' : '' }}>Not Interested</option>
                                <option value="callback"       {{ $lead->status == 'callback'       ? 'selected' : '' }}>Callback</option>
                                <option value="converted"      {{ $lead->status == 'converted'      ? 'selected' : '' }}>Converted</option>
                                <option value="junk"           {{ $lead->status == 'junk'           ? 'selected' : '' }}>Junk</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Next Follow Up Date</label>
                            <input type="date" name="next_followup_date" class="form-control"
                                   min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">Save Follow Up</button>
                </form>
            </div>
        </div>

        {{-- Follow Up History --}}
        <div class="card">
            <div class="card-header fw-500">Follow Up History</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Call Status</th>
                            <th>Lead Status</th>
                            <th>Remarks</th>
                            <th>Next Date</th>
                            <th>By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lead->followUps as $followUp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $followUp->call_status)) }}</td>
                            <td>
                                 <span class="badge bg-{{ \App\Models\Lead::$statuses[$lead->status]['color'] }}">
                                    {{ \App\Models\Lead::$statuses[$lead->status]['label'] }}
                                </span>
                            </td>
                            <td>{{ $followUp->remarks ?? '-' }}</td>
                            <td>{{ $followUp->next_followup_date?->format('d M Y') ?? '-' }}</td>
                            <td>{{ $followUp->user->name ?? '-' }}</td>
                            <td>{{ $followUp->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No follow ups yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection