@extends('layouts.app')

@section('title', 'Daftar Portfolio')

@section('content')
<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="text-center">
            <h2 class="mb-0">Portfolio Generator</h2>
            <p class="text-muted mb-0">Buat portfolio profesional dengan mudah</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-briefcase me-2"></i>Daftar Portfolio
                </h3>
                <a href="{{ route('portfolios.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Portfolio Baru
                </a>
            </div>
            <div class="card-body">
                @if($portfolios->count() > 0)
                    <div class="row">
                        @foreach($portfolios as $portfolio)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card template-card h-100">
                                    @if($portfolio->mainImage)
                                        <img src="{{ asset('storage/' . $portfolio->mainImage->image_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $portfolio->project_name }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $portfolio->project_name }}</h5>
                                        <p class="card-text text-muted">{{ $portfolio->project_title }}</p>
                                        <p class="card-text small">{{ Str::limit($portfolio->description ?? 'No description', 100) }}</p>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-palette me-1"></i>{{ $portfolio->template ? $portfolio->template->name : 'No Template' }}
                                                </small>
                                                <span class="badge bg-{{ ($portfolio->status ?? 'draft') == 'published' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($portfolio->status ?? 'draft') }}
                                                </span>
                                            </div>
                                            
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('portfolios.show', $portfolio->id) }}" 
                                                   class="btn btn-outline-primary btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('portfolios.edit', $portfolio->id) }}" 
                                                   class="btn btn-outline-success btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('portfolios.export', $portfolio->id) }}" 
                                                   class="btn btn-outline-info btn-sm" title="Download HTML">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('portfolios.preview.portfolio', $portfolio->id) }}" 
                                                   class="btn btn-outline-warning btn-sm" title="Preview" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('portfolios.destroy', $portfolio->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus portfolio ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum ada portfolio</h4>
                        <p class="text-muted">Mulai buat portfolio pertama Anda dengan memilih template yang menarik!</p>
                        <a href="{{ route('portfolios.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Buat Portfolio Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection