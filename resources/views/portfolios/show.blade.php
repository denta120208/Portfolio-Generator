@extends('layouts.app')

@section('title', $portfolio->project_name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="fas fa-eye me-2"></i>{{ $portfolio->project_name }}
                </h3>
                <div>
                    <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="btn btn-success me-2">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    
                    <!-- Download Dropdown -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-2"></i>Download
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('portfolios.export', $portfolio->id) }}">
                                    <i class="fas fa-file-code me-2"></i>HTML (Base64)
                                    <small class="d-block text-muted">Untuk browser</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('portfolios.export.external', $portfolio->id) }}">
                                    <i class="fas fa-file-code me-2"></i>HTML (Browser Only)
                                    <small class="d-block text-muted">Hanya bisa dibuka di Chrome/Firefox</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('portfolios.export.pdf', $portfolio->id) }}">
                                    <i class="fas fa-file-pdf me-2"></i>PDF
                                    <small class="d-block text-muted">Universal format</small>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('portfolios.export.zip', $portfolio->id) }}">
                                    <i class="fas fa-file-archive me-2"></i>ZIP Package
                                    <small class="d-block text-muted">HTML + Images</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4>{{ $portfolio->project_title }}</h4>
                        <p class="text-muted mb-4">{{ $portfolio->description }}</p>
                        
                        @if($portfolio->images->count() > 0)
                            <div class="mb-4">
                                <h5>Galeri Proyek</h5>
                                <div class="row">
                                    @foreach($portfolio->images as $image)
                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     class="card-img-top" 
                                                     alt="{{ $image->title ?: 'Project Image' }}"
                                                     style="height: 250px; object-fit: cover;">
                                                <div class="card-body">
                                                    @if($image->is_main)
                                                        <span class="badge bg-primary mb-2">Gambar Utama</span>
                                                    @endif
                                                    @if($image->title)
                                                        <h6 class="card-title">{{ $image->title }}</h6>
                                                    @endif
                                                    @if($image->description)
                                                        <p class="card-text small">{{ $image->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Portfolio</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Template:</strong><br>
                                    <span class="badge bg-primary">{{ $portfolio->template->name }}</span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Status:</strong><br>
                                    <span class="badge bg-{{ $portfolio->status == 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($portfolio->status) }}
                                    </span>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Dibuat:</strong><br>
                                    {{ $portfolio->created_at->format('d M Y H:i') }}
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Diperbarui:</strong><br>
                                    {{ $portfolio->updated_at->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Aksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="btn btn-success">
                                        <i class="fas fa-edit me-2"></i>Edit Portfolio
                                    </a>
                                    
                                    <!-- Download Dropdown -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info dropdown-toggle w-100" 
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download me-2"></i>Download
                                        </button>
                                        <ul class="dropdown-menu w-100">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('portfolios.export', $portfolio->id) }}">
                                                    <i class="fas fa-file-code me-2"></i>HTML (Base64)
                                                    <small class="d-block text-muted">Untuk browser</small>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('portfolios.export.external', $portfolio->id) }}">
                                                    <i class="fas fa-file-code me-2"></i>HTML (Simple)
                                                    <small class="d-block text-muted">Untuk document reader HP</small>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('portfolios.export.pdf', $portfolio->id) }}">
                                                    <i class="fas fa-file-pdf me-2"></i>PDF
                                                    <small class="d-block text-muted">Universal format</small>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('portfolios.export.zip', $portfolio->id) }}">
                                                    <i class="fas fa-file-archive me-2"></i>ZIP Package
                                                    <small class="d-block text-muted">HTML + Images</small>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <a href="{{ route('portfolios.preview.portfolio', $portfolio->id) }}" class="btn btn-warning" target="_blank">
                                        <i class="fas fa-eye me-2"></i>Preview Template
                                    </a>
                                    <form action="{{ route('portfolios.destroy', $portfolio->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus portfolio ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="fas fa-trash me-2"></i>Hapus Portfolio
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
