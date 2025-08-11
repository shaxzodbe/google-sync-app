<?php

use App\Http\Controllers\FetchController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::resource('items', ItemController::class);
Route::post('/items/generate', [ItemController::class, 'generate'])->name('items.generate');
Route::post('/items/clear', [ItemController::class, 'clear'])->name('items.clear');
Route::get('/fetch/{count?}', [FetchController::class, 'fetch'])->name('fetch.items');

Route::get('/', function() {
    return redirect()->route('items.index');
});
