<?php

use Illuminate\Support\Facades\Route;
use Level7up\AIAssistant\Http\Controllers\AIAssistantController;

Route::prefix('ai-assistant')->name('ai-assistant.')->group(function () {
    Route::get('/', [AIAssistantController::class, 'index'])->name('index');
    Route::post('/query', [AIAssistantController::class, 'query'])->name('query');
});
