<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\TodoController;
use App\Models\Todo;
use Illuminate\Support\Facades\Route;

Route::get('/todos', [TodoController::class, 'index']);

// Route untuk membuat todo baru
Route::post('/todos', [TodoController::class, 'store']);

// Route untuk mendapatkan satu todo berdasarkan ID
Route::get('/todos/{todo}', [TodoController::class, 'show']);

// Route untuk mengupdate todo berdasarkan ID
Route::put('/todos/{todo}', [TodoController::class, 'update']);
Route::patch('/todos/{todo}', [TodoController::class, 'update']); // Bisa juga pakai PATCH

// Route untuk menghapus todo berdasarkan ID
Route::delete('/todos/{todo}', [TodoController::class, 'destroy']);

Route::get('/todos/export/excel', [TodoController::class, 'exportToExcel']);

Route::get('/chart', [ChartController::class, 'getChartData']);
