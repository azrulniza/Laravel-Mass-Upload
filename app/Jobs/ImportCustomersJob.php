<?php

namespace App\Jobs;

use App\Imports\CustomersImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportCustomersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        // Import the file using the stored path
        Excel::import(new CustomersImport, Storage::path($this->filePath));

        // Optionally delete the file after processing
        Storage::delete($this->filePath);
    }
}
