{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('page_title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h5>Welcome, {{ auth()->user()->name }}</h5>
        <p class="text-muted">You are logged in as Admin.</p>
    </div>
</div>
@endsection