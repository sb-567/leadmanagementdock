<?php


namespace App\Http\Controllers;

use App\Imports\LeadsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExcelImportController extends Controller
{
    // app/Http/Controllers/ExcelImportController.php

public function index()
{
    if (!auth()->user()->hasAnyRole(['admin', 'teamleader'])) {
        abort(403, 'Access denied.');
    }

    return view('leads.excel_import');
}

public function store(Request $request)
{
    if (!auth()->user()->hasAnyRole(['admin', 'teamleader'])) {
        abort(403, 'Access denied.');
    }

    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:2048',
    ]);

    Excel::import(
        new LeadsImport(auth()->id()),
        $request->file('file')
    );

    return redirect()->route('leads.index')
                     ->with('success', 'Leads imported successfully.');
}
}