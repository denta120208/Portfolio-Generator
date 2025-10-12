@extends('layouts.app')

@section('title', 'Portfolio Generator - Buat Portfolio Profesional')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-palette fa-5x text-primary mb-3"></i>
                <h1 class="display-4 fw-bold text-white">Portfolio Generator</h1>
                <p class="lead text-white-50">Buat portfolio profesional dengan mudah menggunakan template yang menarik</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body p-5">
                            <h3 class="mb-4">Fitur Utama</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-palette fa-2x text-primary me-3"></i>
                                        <div>
                                            <h5>4 Template Menarik</h5>
                                            <p class="text-muted mb-0">Modern Clean, Creative, Professional, Minimalist</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-images fa-2x text-success me-3"></i>
                                        <div>
                                            <h5>Upload Gambar</h5>
                                            <p class="text-muted mb-0">Gambar utama dan tambahan</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-download fa-2x text-info me-3"></i>
                                        <div>
                                            <h5>Export HTML</h5>
                                            <p class="text-muted mb-0">Download portfolio siap pakai</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-mobile-alt fa-2x text-warning me-3"></i>
                                        <div>
                                            <h5>Responsive Design</h5>
                                            <p class="text-muted mb-0">Tampil sempurna di semua device</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-plus me-2"></i>Buat Portfolio Baru
                                </a>
                                <a href="{{ route('portfolios.index') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-list me-2"></i>Lihat Portfolio
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>
@endsection