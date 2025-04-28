<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [BookController::class, 'index'])->name('book.index');
Route::get('/books/search', [BookController::class, 'search'])->name('book.search');
