<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
Route::prefix('translations')->group(function () {
    Route::get('/', [TranslationController::class, 'index']);
    Route::post('/', [TranslationController::class, 'store']);
    Route::get('/search', [TranslationController::class, 'search']);
    Route::get('/export', [TranslationController::class, 'export']);
    Route::get('/{id}', [TranslationController::class, 'show']);
    Route::put('/{id}', [TranslationController::class, 'update']);
    Route::delete('/{id}', [TranslationController::class, 'destroy']);

});

