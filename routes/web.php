<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RichTextController;

Route::get('/', function () {
    return redirect()->route('richtext.index');
});

Route::get('/richtext', [RichTextController::class, 'index'])->name('richtext.index');
Route::post('/richtext/store', [RichTextController::class, 'store'])->name('richtext.store');
Route::get('/richtext/edit/{id}', [RichTextController::class, 'edit'])->name('richtext.edit');
Route::put('/richtext/update/{id}', [RichTextController::class, 'update'])->name('richtext.update');
Route::delete('/richtext/delete/{id}', [RichTextController::class, 'destroy'])->name('richtext.delete');
Route::post('/richtext/toggle/{id}', [RichTextController::class, 'toggleStatus'])->name('richtext.toggle');