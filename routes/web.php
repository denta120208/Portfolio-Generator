<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('portfolios.index');
});

// Portfolio routes (protected)
Route::middleware('auth')->group(function () {
    Route::resource('portfolios', PortfolioController::class);
    Route::post('portfolios/preview', [PortfolioController::class, 'preview'])->name('portfolios.preview');
    Route::get('portfolios/{id}/preview', [PortfolioController::class, 'previewPortfolio'])->name('portfolios.preview.portfolio');
    Route::get('portfolios/{id}/export', [PortfolioController::class, 'export'])->name('portfolios.export');
    Route::get('portfolios/{id}/export-pdf', [PortfolioController::class, 'exportPdf'])->name('portfolios.export.pdf');
    Route::get('portfolios/{id}/export-external', [PortfolioController::class, 'exportHtmlExternal'])->name('portfolios.export.external');
    Route::get('portfolios/{id}/export-zip', [PortfolioController::class, 'exportZip'])->name('portfolios.export.zip');

    // Public sharing management
    Route::post('portfolios/{id}/make-public', [PortfolioController::class, 'makePublic'])->name('portfolios.make.public');
    Route::post('portfolios/{id}/make-private', [PortfolioController::class, 'makePrivate'])->name('portfolios.make.private');
    Route::get('portfolios/{id}/copy-link', [PortfolioController::class, 'copyPublicLink'])->name('portfolios.copy.link');
});

// Public sharing routes (no auth)
Route::get('portfolio/{publicUrl}', [PortfolioController::class, 'showPublic'])->name('portfolio.public');
