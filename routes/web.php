<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\RichTextController;

Route::get('/richtext', [RichTextController::class, 'create']);
Route::post('/richtext', [RichTextController::class, 'store'])->name('richtext.store');
