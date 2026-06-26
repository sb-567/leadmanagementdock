@extends('layouts.app')

@section('page_title', 'Add Lead')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Add New Lead</h5>
    <a href="{{ route('leads.index') }}" class="btn btn-secondary btn-sm">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('leads.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone"
                           class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Source</label>
                    <select name="source" class="form-select">
                        <option value="">-- Select Source --</option>
                        <option value="walk-in"   {{ old('source') == 'walk-in'   ? 'selected' : '' }}>Walk-in</option>
                        <option value="online"    {{ old('source') == 'online'    ? 'selected' : '' }}>Online</option>
                        <option value="referral"  {{ old('source') == 'referral'  ? 'selected' : '' }}>Referral</option>
                        <option value="excel"     {{ old('source') == 'excel'     ? 'selected' : '' }}>Excel Upload</option>
                        <option value="other"     {{ old('source') == 'other'     ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Product Interest</label>
                    <input type="text" name="product_interest"
                           class="form-control"
                           value="{{ old('product_interest') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city"
                           class="form-control"
                           value="{{ old('city') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Assign To</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">-- Unassigned --</option>
                        @foreach($telecallers as $tc)
                            <option value="{{ $tc->id }}"
                                {{ old('assigned_to') == $tc->id ? 'selected' : '' }}>
                                {{ $tc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Lead</button>
            <a href="{{ route('leads.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection