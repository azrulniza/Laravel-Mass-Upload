<?php

namespace App\Http\Controllers;

use App\Imports\CustomersImport;
use App\Jobs\ImportCustomersJob;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        // Store the uploaded file temporarily
        $filePath = $request->file('excel_file')->store('uploads');

        // Queue the import job with the file path
        ImportCustomersJob::dispatch($filePath);

        return redirect()->back()->with('message', 'Your Excel data is being processed.');
    }
}
