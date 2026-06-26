@extends('layouts.app')

@section('page_title', 'Import Leads')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Import Leads from Excel</h5>
    <a href="{{ route('leads.index') }}" class="btn btn-secondary btn-sm">Back</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('leads.import.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Select Excel File</label>
                        <input type="file" name="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".xlsx,.xls,.csv">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Import Leads</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sample format --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Excel Format</div>
            <div class="card-body">
                <p class="text-muted mb-2">Your Excel file must have these column headings in row 1:</p>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Column</th>
                            <th>Required</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>name</td><td>No</td></tr>
                        <tr><td>phone</td><td><span class="text-danger">Yes</span></td></tr>
                        <tr><td>email</td><td>No</td></tr>
                        <tr><td>product_interest</td><td>No</td></tr>
                        <tr><td>city</td><td>No</td></tr>
                        <tr><td>assigned_to</td><td>No</td></tr>
                        <tr><td>remarks</td><td>No</td></tr>
                    </tbody>
                </table>
                <small class="text-muted">
                    For <strong>assigned_to</strong> — write the telecaller's name exactly as in the system.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection