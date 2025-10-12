@extends('layouts.app')

@section('title', 'Buat Portfolio Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">
                    <i class="fas fa-plus me-2"></i>Buat Portfolio Baru
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('portfolios.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Step 1: Template Selection -->
                    <div class="step" id="step1">
                        <h4 class="mb-4">
                            <i class="fas fa-palette me-2"></i>Pilih Template
                        </h4>
                        <div class="row">
                            @foreach($templates as $template)
                                <div class="col-md-6 col-lg-3 mb-4">
                                    <div class="card template-card h-100" data-template-id="{{ $template->id }}" style="cursor: pointer;">
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            @if($template->preview_image && file_exists(public_path('storage/' . $template->preview_image)))
                                                <img src="{{ asset('storage/' . $template->preview_image) }}" 
                                                     alt="{{ $template->name }}" 
                                                     class="img-fluid" 
                                                     style="max-height: 200px; object-fit: cover;">
                                            @else
                                                <div class="template-preview-placeholder">
                                                    @if($template->slug == 'modern-clean')
                                                        <div class="preview-modern-clean">
                                                            <div class="preview-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 60px; border-radius: 10px 10px 0 0;"></div>
                                                            <div class="preview-content" style="padding: 20px;">
                                                                <div class="preview-title" style="height: 20px; background: #667eea; border-radius: 5px; margin-bottom: 10px;"></div>
                                                                <div class="preview-image" style="height: 80px; background: #f0f0f0; border-radius: 5px; margin-bottom: 10px;"></div>
                                                                <div class="preview-text" style="height: 15px; background: #ddd; border-radius: 3px; margin-bottom: 5px;"></div>
                                                                <div class="preview-text" style="height: 15px; background: #ddd; border-radius: 3px; width: 70%;"></div>
                                                            </div>
                                                        </div>
                                                    @elseif($template->slug == 'creative-portfolio')
                                                        <div class="preview-creative">
                                                            <div class="preview-hero" style="background: linear-gradient(45deg, #ff6b6b, #4ecdc4); height: 100px; border-radius: 10px; position: relative;">
                                                                <div style="position: absolute; top: 20px; left: 20px; width: 60px; height: 15px; background: white; border-radius: 3px;"></div>
                                                                <div style="position: absolute; bottom: 20px; right: 20px; width: 40px; height: 40px; background: rgba(255,255,255,0.3); border-radius: 50%;"></div>
                                                            </div>
                                                            <div class="preview-content" style="padding: 15px;">
                                                                <div style="height: 12px; background: #ff6b6b; border-radius: 3px; margin-bottom: 8px;"></div>
                                                                <div style="height: 8px; background: #ddd; border-radius: 2px; margin-bottom: 5px;"></div>
                                                                <div style="height: 8px; background: #ddd; border-radius: 2px; width: 80%;"></div>
                                                            </div>
                                                        </div>
                                                    @elseif($template->slug == 'professional-business')
                                                        <div class="preview-professional">
                                                            <div class="preview-header" style="background: #2c3e50; height: 70px; border-radius: 10px 10px 0 0; display: flex; align-items: center; padding: 0 20px;">
                                                                <div style="width: 40px; height: 40px; background: #3498db; border-radius: 5px; margin-right: 15px;"></div>
                                                                <div>
                                                                    <div style="width: 80px; height: 12px; background: white; border-radius: 3px; margin-bottom: 5px;"></div>
                                                                    <div style="width: 60px; height: 8px; background: #bdc3c7; border-radius: 2px;"></div>
                                                                </div>
                                                            </div>
                                                            <div class="preview-content" style="padding: 20px;">
                                                                <div style="height: 15px; background: #2c3e50; border-radius: 3px; margin-bottom: 10px;"></div>
                                                                <div style="height: 10px; background: #ddd; border-radius: 2px; margin-bottom: 5px;"></div>
                                                                <div style="height: 10px; background: #ddd; border-radius: 2px; width: 70%;"></div>
                                                            </div>
                                                        </div>
                                                    @elseif($template->slug == 'minimalist')
                                                        <div class="preview-minimalist">
                                                            <div class="preview-header" style="text-align: center; padding: 20px; border-bottom: 1px solid #eee;">
                                                                <div style="width: 100px; height: 20px; background: #333; border-radius: 3px; margin: 0 auto 10px;"></div>
                                                                <div style="width: 60px; height: 12px; background: #666; border-radius: 2px; margin: 0 auto;"></div>
                                                            </div>
                                                            <div class="preview-content" style="padding: 20px;">
                                                                <div style="height: 60px; background: #f5f5f5; border-radius: 2px; margin-bottom: 15px;"></div>
                                                                <div style="height: 8px; background: #ddd; border-radius: 2px; margin-bottom: 5px;"></div>
                                                                <div style="height: 8px; background: #ddd; border-radius: 2px; width: 80%;"></div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <i class="fas fa-palette fa-3x text-primary"></i>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title">{{ $template->name }}</h5>
                                            <p class="card-text small text-muted">{{ $template->description }}</p>
                                            <div class="template-selection-indicator d-none">
                                                <i class="fas fa-check-circle text-success fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="template_id" id="selectedTemplateId" required>
                    </div>

                    <!-- Step 2: Project Information -->
                    <div class="step d-none" id="step2">
                        <h4 class="mb-4">
                            <i class="fas fa-info-circle me-2"></i>Informasi Proyek
                        </h4>
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="project_name" class="form-label">Nama Proyek <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_name" name="project_name" 
                                       placeholder="Masukkan nama proyek" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="project_title" class="form-label">Judul Proyek <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="project_title" name="project_title" 
                                       placeholder="Masukkan judul proyek" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Proyek <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="5" 
                                      placeholder="Jelaskan detail proyek Anda" required></textarea>
                        </div>
                    </div>

                    <!-- Step 3: Personal Information -->
                    <div class="step d-none" id="step3">
                        <h4 class="mb-4">
                            <i class="fas fa-user me-2"></i>Informasi Personal
                        </h4>
                        
                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   placeholder="Masukkan nama lengkap Anda" required>
                        </div>

                        <!-- Kontak -->
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="contoh@email.com" required>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor HP</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="+62 812-3456-7890">
                            </div>
                        </div>

                        <!-- Media Sosial Profesional -->
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                       placeholder="https://linkedin.com/in/username">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="github" class="form-label">GitHub</label>
                                <input type="url" class="form-control" id="github" name="github" 
                                       placeholder="https://github.com/username">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="website" class="form-label">Website/Portfolio</label>
                                <input type="url" class="form-control" id="website" name="website" 
                                       placeholder="https://yourwebsite.com">
                            </div>
                        </div>

                        <!-- Profil Singkat -->
                        <div class="mb-3">
                            <label for="about_me" class="form-label">Profil Singkat / Tentang Saya <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="about_me" name="about_me" rows="4" 
                                      placeholder="Ceritakan tentang diri Anda, latar belakang, minat profesional, dan tujuan karier..." required></textarea>
                        </div>

                        <!-- CV Singkat -->
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="education" class="form-label">Riwayat Pendidikan</label>
                                <textarea class="form-control" id="education" name="education" rows="3" 
                                          placeholder="Contoh:&#10;S1 Teknik Informatika - Universitas ABC (2020-2024)&#10;SMA XYZ (2017-2020)"></textarea>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="experience" class="form-label">Pengalaman Kerja/Magang</label>
                                <textarea class="form-control" id="experience" name="experience" rows="3" 
                                          placeholder="Contoh:&#10;Software Developer - PT ABC (2023-2024)&#10;Intern - PT XYZ (2022)"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="skills" class="form-label">Keterampilan (Skills)</label>
                                <textarea class="form-control" id="skills" name="skills" rows="3" 
                                          placeholder="Contoh:&#10;• Programming: PHP, JavaScript, Python&#10;• Framework: Laravel, React, Vue.js&#10;• Tools: Git, Docker, AWS"></textarea>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="certifications" class="form-label">Sertifikasi (Opsional)</label>
                                <textarea class="form-control" id="certifications" name="certifications" rows="3" 
                                          placeholder="Contoh:&#10;• AWS Certified Developer&#10;• Google Analytics Certified&#10;• Microsoft Azure Fundamentals"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Images Upload -->
                    <div class="step d-none" id="step4">
                        <h4 class="mb-4">
                            <i class="fas fa-images me-2"></i>Upload Gambar
                        </h4>
                        <div id="images-container">
                            <div class="image-upload-item mb-4 p-3 border rounded">
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-3">
                                        <label class="form-label">Gambar</label>
                                        <input type="file" class="form-control image-file" name="images[0][file]" 
                                               accept="image/*" onchange="previewImageWithMetadata(this, 0)">
                                        <div class="mt-2">
                                            <img id="preview-0" src="" alt="Preview" class="img-thumbnail d-none w-100" 
                                                 style="max-width: 150px; max-height: 150px;">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label class="form-label">Judul Gambar</label>
                                        <input type="text" class="form-control" name="images[0][title]" 
                                               placeholder="Judul gambar (opsional)">
                                    </div>
                                    <div class="col-12 col-md-4 mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="images[0][description]" rows="3" 
                                                  placeholder="Deskripsi gambar (opsional)"></textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="main_image" value="0" id="main-0">
                                            <label class="form-check-label" for="main-0">
                                                Jadikan sebagai gambar utama
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-primary" onclick="addImageUpload()">
                                <i class="fas fa-plus me-2"></i>Tambah Gambar Lain
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                            <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                        </button>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-secondary" id="nextBtn" onclick="changeStep(1)" disabled>
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <button type="submit" class="btn btn-success d-none" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Simpan Portfolio
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentStep = 1;
const totalSteps = 4;

function changeStep(direction) {
    const currentStepElement = document.getElementById(`step${currentStep}`);
    const nextStep = currentStep + direction;
    
    if (nextStep < 1 || nextStep > totalSteps) return;
    
    // Hide current step
    currentStepElement.classList.add('d-none');
    
    // Show next step
    currentStep = nextStep;
    const nextStepElement = document.getElementById(`step${currentStep}`);
    nextStepElement.classList.remove('d-none');
    
    // Update navigation buttons
    updateNavigationButtons();
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
    
        if (currentStep === totalSteps) {
            nextBtn.classList.add('d-none');
            submitBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        }
        
        // Reset next button state based on step
        if (currentStep === 1) {
            const templateId = document.getElementById('selectedTemplateId').value;
            if (templateId) {
                nextBtn.disabled = false;
                nextBtn.classList.remove('btn-secondary');
                nextBtn.classList.add('btn-primary');
            } else {
                nextBtn.disabled = true;
                nextBtn.classList.add('btn-secondary');
                nextBtn.classList.remove('btn-primary');
            }
        } else {
            nextBtn.disabled = false;
            nextBtn.classList.remove('btn-secondary');
            nextBtn.classList.add('btn-primary');
        }
}

// Template selection
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.template-card').forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            document.querySelectorAll('.template-card').forEach(c => {
                c.classList.remove('selected');
                const indicator = c.querySelector('.template-selection-indicator');
                if (indicator) {
                    indicator.classList.add('d-none');
                }
            });
            
            // Add selected class to clicked card
            this.classList.add('selected');
            const indicator = this.querySelector('.template-selection-indicator');
            if (indicator) {
                indicator.classList.remove('d-none');
            }
            
            // Set template ID
            const templateId = this.dataset.templateId;
            const templateInput = document.getElementById('selectedTemplateId');
            if (templateInput) {
                templateInput.value = templateId;
            }
            
            // Enable next button
            const nextBtn = document.getElementById('nextBtn');
            if (nextBtn) {
                nextBtn.disabled = false;
                nextBtn.classList.remove('btn-secondary');
                nextBtn.classList.add('btn-primary');
            }
        });
    });
});

let imageIndex = 1;

// Image preview functions
function previewImageWithMetadata(input, index) {
    const preview = document.getElementById(`preview-${index}`);
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

function addImageUpload() {
    const container = document.getElementById('images-container');
    const newItem = document.createElement('div');
    newItem.className = 'image-upload-item mb-4 p-3 border rounded';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control image-file" name="images[${imageIndex}][file]" 
                       accept="image/*" onchange="previewImageWithMetadata(this, ${imageIndex})">
                <div class="mt-2">
                    <img id="preview-${imageIndex}" src="" alt="Preview" class="img-thumbnail d-none w-100" 
                         style="max-width: 150px; max-height: 150px;">
                </div>
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Judul Gambar</label>
                <input type="text" class="form-control" name="images[${imageIndex}][title]" 
                       placeholder="Judul gambar (opsional)">
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="images[${imageIndex}][description]" rows="3" 
                          placeholder="Deskripsi gambar (opsional)"></textarea>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="main_image" value="${imageIndex}" id="main-${imageIndex}">
                    <label class="form-check-label" for="main-${imageIndex}">
                        Jadikan sebagai gambar utama
                    </label>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeImageUpload(this)">
                    <i class="fas fa-trash me-1"></i>Hapus
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    imageIndex++;
}

function removeImageUpload(button) {
    button.closest('.image-upload-item').remove();
}

// Form validation
document.getElementById('portfolioForm').addEventListener('submit', function(e) {
    if (!document.getElementById('selectedTemplateId').value) {
        e.preventDefault();
        alert('Silakan pilih template terlebih dahulu!');
        changeStep(1);
        return;
    }
    
    // Validate required personal information fields
    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const aboutMe = document.getElementById('about_me').value.trim();
    
    if (!fullName || !email || !aboutMe) {
        e.preventDefault();
        alert('Silakan lengkapi informasi personal yang wajib diisi (Nama Lengkap, Email, dan Profil Singkat)!');
        changeStep(3);
        return;
    }
});
</script>
@endsection