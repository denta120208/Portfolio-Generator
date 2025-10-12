@extends('layouts.app')

@section('title', 'Edit Portfolio - ' . $portfolio->project_name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Portfolio
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="project_name" class="form-label">Nama Proyek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="project_name" name="project_name" 
                                   value="{{ old('project_name', $portfolio->project_name) }}" required>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="project_title" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="project_title" name="project_title" 
                                   value="{{ old('project_title', $portfolio->project_title) }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Proyek <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $portfolio->description) }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="template_id" class="form-label">Template <span class="text-danger">*</span></label>
                        <select class="form-select" id="template_id" name="template_id" required>
                            <option value="">Pilih Template</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" 
                                        {{ old('template_id', $portfolio->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="project_image" class="form-label">Gambar Utama</label>
                            <input type="file" class="form-control" id="project_image" name="project_image" 
                                   accept="image/*" onchange="previewImage(this, 'mainPreview')">
                            @if($portfolio->mainImage)
                                <div class="mt-2">
                                    <p class="text-muted small">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $portfolio->mainImage->image_path) }}" 
                                         class="img-thumbnail w-100" 
                                         style="max-width: 200px; max-height: 200px;">
                                </div>
                            @endif
                            <div class="mt-2">
                                <img id="mainPreview" src="" alt="Preview" class="img-thumbnail d-none w-100" 
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 mb-3">
                            <label for="additional_images" class="form-label">Gambar Tambahan</label>
                            <input type="file" class="form-control" id="additional_images" name="additional_images[]" 
                                   accept="image/*" multiple onchange="previewImages(this, 'additionalPreview')">
                            <small class="text-muted">Anda dapat memilih beberapa gambar sekaligus</small>
                            
                            @if($portfolio->images->where('is_main', false)->count() > 0)
                                <div class="mt-2">
                                    <p class="text-muted small">Gambar saat ini:</p>
                                    <div class="row">
                                        @foreach($portfolio->images->where('is_main', false) as $image)
                                            <div class="col-6 col-md-4 mb-2">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     class="img-thumbnail w-100" 
                                                     style="max-width: 100px; max-height: 100px;">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-2" id="additionalPreview"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" {{ old('status', $portfolio->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $portfolio->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('portfolios.show', $portfolio->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Image preview functions
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('d-none');
    }
}

function previewImages(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    
    Array.from(input.files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail me-2 mb-2';
            img.style.maxWidth = '100px';
            img.style.maxHeight = '100px';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection
