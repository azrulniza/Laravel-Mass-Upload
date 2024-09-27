<?php

namespace App\Imports;

use App\Models\Customer;
use App\Notifications\ExcelImportCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Concerns\WithEvents;

class CustomersImport implements ToModel, WithBatchInserts, WithChunkReading, ShouldQueue, WithEvents
{
    public function model(array $row)
    {
        // Handle non-numeric or empty amount values
        // $amount = is_numeric($row[2]) ? $row[2] : 0.00; // Set default to 0.00 if not valid

        // return new Customer([
        //     'mobile_number' => $row[0],  // Assuming mobile_number is in the first column
        //     'text1'         => $row[1],  // Assuming text1 is in the second column
        //     'amount'        => $amount,  // Assuming amount is in the third column
        //     'text2'         => $row[3],  // Assuming text2 is in the fourth column
        // ]);

        return DB::transaction(function () use ($row) {
            return new Customer([
                'mobile_number' => $row[0],
                'text1' => $row[1],
                'amount' => is_numeric($row[2]) ? $row[2] : 0.00,
                'text2' => $row[3],
            ]);
        });
    }

    public function batchSize(): int
    {
        return 5000; // Process in batches of 1000 records
    }

    public function chunkSize(): int
    {
        return 5000; // Read 1000 rows at a time
    }

    // Register an event for AfterImport to set a session message
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                // Store notification directly in the notifications table
                DB::table('notifications')->insert([
                    'id' => DB::raw('UUID()'),
                    'type' => 'App\Notifications\ExcelImportCompleted', // Specify the notification type
                    'notifiable_type' => 'App\Models\User', // or your dummy model
                    'notifiable_id' => 1, // Change to a valid user ID or a dummy ID
                    'data' => json_encode(['message' => 'Your Excel data import has been successfully completed.']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            },
        ];
    }
}
