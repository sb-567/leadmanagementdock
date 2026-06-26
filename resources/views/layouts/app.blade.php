<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Management CRM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1e1e2d;
            position: fixed;
            top: 0; left: 0;
            padding: 20px 0;
            z-index: 100;
        }
        .sidebar .brand {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            padding: 0 20px 20px;
            border-bottom: 1px solid #2d2d3f;
        }
        .sidebar a {
            display: block;
            color: #a0a0b0;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 14px;
        }
        .sidebar a:hover { background: #2d2d3f; color: #fff; }
        .sidebar .nav-label {
            color: #555570;
            font-size: 11px;
            text-transform: uppercase;
            padding: 14px 20px 4px;
            letter-spacing: 1px;
        }
        .topbar {
            margin-left: 240px;
            background: #fff;
            padding: 12px 24px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .main-content {
            margin-left: 240px;
            padding: 24px;
        }
    </style>
</head>
<body>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="brand">Lead CRM</div>

    @role('admin')
    <div class="nav-label">Admin</div>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.users.index') }}">Users</a>
    <a href="{{ route('leads.index') }}">Leads</a>
    @endrole

    @role('teamleader')
    <div class="nav-label">Team Leader</div>
    <a href="{{ route('tl.dashboard') }}">Dashboard</a>
    <a href="{{ route('leads.index') }}">Leads</a>
    @endrole

    @role('telecaller')
    <div class="nav-label">Telecaller</div>
    <a href="{{ route('telecaller.dashboard') }}">Dashboard</a>
    <a href="{{ route('telecaller.leads.index') }}">My Leads</a>
    @endrole


    {{-- layouts/app.blade.php sidebar --}}
   

    @hasanyrole('admin|teamleader')
    <a href="{{ route('reports.index') }}">Reports</a>
    @endhasanyrole

    {{-- leads, follow-ups will be added here as we build --}}

    <div style="position:absolute; bottom:20px; width:100%;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none; border:none; color:#a0a0b0; padding: 10px 20px; cursor:pointer; font-size:14px;">
                Logout
            </button>
        </form>
    </div>
</div>

{{-- TOPBAR --}}
<div class="topbar">
    <span style="font-weight:500;">@yield('page_title', 'Dashboard')</span>
    <span style="font-size:14px; color:#666;">
        {{ auth()->user()->name }}
        <span class="badge bg-secondary ms-1">{{ auth()->user()->getRoleNames()->first() }}</span>
    </span>
</div>

{{-- MAIN CONTENT --}}
<div class="main-content">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>