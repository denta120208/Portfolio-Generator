<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

Route::get('/', function () {
    return redirect()->route('portfolios.index');
});

Route::resource('portfolios', PortfolioController::class);
Route::post('portfolios/preview', [PortfolioController::class, 'preview'])->name('portfolios.preview');
Route::get('portfolios/{id}/preview', [PortfolioController::class, 'previewPortfolio'])->name('portfolios.preview.portfolio');
Route::get('portfolios/{id}/export', [PortfolioController::class, 'export'])->name('portfolios.export');
