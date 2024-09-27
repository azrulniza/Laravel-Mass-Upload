<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelImportController;

Route::get('/', function () {
    return view('import');
});

Route::post('/import-excel', [ExcelImportController::class, 'import']);
