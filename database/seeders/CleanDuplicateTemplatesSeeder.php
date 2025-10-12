<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioTemplate;

class CleanDuplicateTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua template yang ada
        PortfolioTemplate::truncate();
        
        // Insert template yang unik saja
        $templates = [
            [
                'name' => 'Modern Clean',
                'slug' => 'modern-clean',
                'description' => 'Template modern dengan desain bersih dan minimalis',
                'preview_image' => 'templates/modern-clean-preview.jpg',
                'template_html' => '
                <div class="portfolio-container">
                    <header class="portfolio-header">
                        <h1 class="project-name">{{project_name}}</h1>
                        <h2 class="project-title">{{project_title}}</h2>
                    </header>
                    
                    <main class="portfolio-content">
                        <!-- Personal Information Section -->
                        <div class="personal-info-section">
                            <div class="personal-header">
                                <h2>{{full_name}}</h2>
                                <div class="contact-info">
                                    <p><i class="fas fa-envelope"></i> {{email}}</p>
                                    <p><i class="fas fa-phone"></i> {{phone}}</p>
                                    <div class="social-links">
                                        <a href="{{linkedin}}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                        <a href="{{github}}" target="_blank" class="social-link"><i class="fab fa-github"></i> GitHub</a>
                                        <a href="{{website}}" target="_blank" class="social-link"><i class="fas fa-globe"></i> Website</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="about-section">
                                <h3>Tentang Saya</h3>
                                <p>{{about_me}}</p>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-card">
                                    <h4><i class="fas fa-graduation-cap"></i> Pendidikan</h4>
                                    <div class="info-content">{{education}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-briefcase"></i> Pengalaman</h4>
                                    <div class="info-content">{{experience}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-code"></i> Keterampilan</h4>
                                    <div class="info-content">{{skills}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-certificate"></i> Sertifikasi</h4>
                                    <div class="info-content">{{certifications}}</div>
                                    <div class="certificate-images">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="project-image-container">
                            <img src="{{project_image}}" alt="{{project_name}}" class="main-image">
                        </div>
                        
                        <div class="project-description">
                            <h3>Tentang Proyek</h3>
                            <p>{{description}}</p>
                        </div>
                        
                        <div class="additional-images">
                            {{additional_images}}
                        </div>
                    </main>
                </div>',
                'template_css' => '
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: "Inter", sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                }
                
                .portfolio-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    background: white;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
                    border-radius: 20px;
                    overflow: hidden;
                }
                
                .portfolio-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 60px 40px;
                    text-align: center;
                }
                
                .project-name {
                    font-size: 3rem;
                    font-weight: 700;
                    margin-bottom: 10px;
                }
                
                .project-title {
                    font-size: 1.5rem;
                    font-weight: 300;
                    opacity: 0.9;
                }
                
                .portfolio-content {
                    padding: 60px 40px;
                }
                
                .personal-info-section {
                    background: #f8f9fa;
                    padding: 40px;
                    border-radius: 15px;
                    margin-bottom: 40px;
                }
                
                .personal-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                
                .personal-header h2 {
                    font-size: 2.5rem;
                    color: #667eea;
                    margin-bottom: 15px;
                }
                
                .contact-info p {
                    margin: 8px 0;
                    color: #666;
                }
                
                .social-links {
                    margin-top: 20px;
                }
                
                .social-link {
                    display: inline-block;
                    margin: 0 10px;
                    padding: 8px 16px;
                    background: #667eea;
                    color: white;
                    text-decoration: none;
                    border-radius: 20px;
                    transition: all 0.3s ease;
                }
                
                .social-link:hover {
                    background: #764ba2;
                    transform: translateY(-2px);
                }
                
                .about-section {
                    margin-bottom: 30px;
                }
                
                .about-section h3 {
                    font-size: 1.8rem;
                    color: #333;
                    margin-bottom: 15px;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                }
                
                .info-card {
                    background: white;
                    padding: 25px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                }
                
                .info-card h4 {
                    font-size: 1.3rem;
                    color: #667eea;
                    margin-bottom: 15px;
                }
                
                .info-content {
                    color: #666;
                    line-height: 1.6;
                    white-space: pre-line;
                }
                
                .certificate-images {
                    margin-top: 15px;
                }
                
                .cert-item {
                    display: inline-block;
                    margin: 5px;
                }
                
                .project-image-container {
                    margin-bottom: 40px;
                    text-align: center;
                }
                
                .main-image {
                    max-width: 100%;
                    height: auto;
                    border-radius: 15px;
                    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                }
                
                .project-description h3 {
                    font-size: 2rem;
                    margin-bottom: 20px;
                    color: #333;
                }
                
                .project-description p {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #666;
                }
                
                .additional-images {
                    margin-top: 40px;
                }
                
                .image-item {
                    margin-bottom: 30px;
                    text-align: center;
                }
                
                .additional-image {
                    max-width: 100%;
                    height: auto;
                    border-radius: 10px;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                }
                
                .image-title {
                    margin-top: 15px;
                    font-size: 1.2rem;
                    color: #333;
                }
                
                .image-description {
                    margin-top: 10px;
                    color: #666;
                    font-style: italic;
                }
                ',
                'is_active' => true
            ],
            [
                'name' => 'Creative Portfolio',
                'slug' => 'creative-portfolio',
                'description' => 'Template kreatif dengan desain yang menarik dan colorful',
                'preview_image' => 'templates/creative-portfolio-preview.jpg',
                'template_html' => '
                <div class="creative-portfolio">
                    <div class="hero-section">
                        <div class="hero-content">
                            <h1 class="creative-title">{{project_name}}</h1>
                            <p class="creative-subtitle">{{project_title}}</p>
                            <div class="hero-image">
                                <img src="{{project_image}}" alt="{{project_name}}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="content-section">
                        <!-- Personal Information Section -->
                        <div class="personal-info-section">
                            <div class="personal-header">
                                <h2>{{full_name}}</h2>
                                <div class="contact-info">
                                    <p><i class="fas fa-envelope"></i> {{email}}</p>
                                    <p><i class="fas fa-phone"></i> {{phone}}</p>
                                    <div class="social-links">
                                        <a href="{{linkedin}}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                        <a href="{{github}}" target="_blank" class="social-link"><i class="fab fa-github"></i> GitHub</a>
                                        <a href="{{website}}" target="_blank" class="social-link"><i class="fas fa-globe"></i> Website</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="about-section">
                                <h3>Tentang Saya</h3>
                                <p>{{about_me}}</p>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-card">
                                    <h4><i class="fas fa-graduation-cap"></i> Pendidikan</h4>
                                    <div class="info-content">{{education}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-briefcase"></i> Pengalaman</h4>
                                    <div class="info-content">{{experience}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-code"></i> Keterampilan</h4>
                                    <div class="info-content">{{skills}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-certificate"></i> Sertifikasi</h4>
                                    <div class="info-content">{{certifications}}</div>
                                    <div class="certificate-images">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="description-box">
                            <h2>Proyek Detail</h2>
                            <p>{{description}}</p>
                        </div>
                        
                        <div class="gallery-section">
                            <h2>Galeri Proyek</h2>
                            <div class="image-gallery">
                                {{additional_images}}
                            </div>
                        </div>
                    </div>
                </div>',
                'template_css' => '
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: "Poppins", sans-serif;
                    background: #f8f9fa;
                }
                
                .creative-portfolio {
                    min-height: 100vh;
                }
                
                .hero-section {
                    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
                    padding: 80px 20px;
                    text-align: center;
                    color: white;
                }
                
                .hero-content {
                    max-width: 1200px;
                    margin: 0 auto;
                }
                
                .creative-title {
                    font-size: 4rem;
                    font-weight: 800;
                    margin-bottom: 20px;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                }
                
                .creative-subtitle {
                    font-size: 1.5rem;
                    margin-bottom: 40px;
                    opacity: 0.9;
                }
                
                .hero-image img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                }
                
                .content-section {
                    padding: 80px 20px;
                    max-width: 1200px;
                    margin: 0 auto;
                }
                
                .personal-info-section {
                    background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
                    padding: 40px;
                    border-radius: 20px;
                    margin-bottom: 40px;
                    color: white;
                }
                
                .personal-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                
                .personal-header h2 {
                    font-size: 2.5rem;
                    margin-bottom: 15px;
                    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
                }
                
                .contact-info p {
                    margin: 8px 0;
                    font-size: 1.1rem;
                }
                
                .social-links {
                    margin-top: 20px;
                }
                
                .social-link {
                    display: inline-block;
                    margin: 0 10px;
                    padding: 10px 20px;
                    background: rgba(255,255,255,0.2);
                    color: white;
                    text-decoration: none;
                    border-radius: 25px;
                    transition: all 0.3s ease;
                    backdrop-filter: blur(10px);
                }
                
                .social-link:hover {
                    background: rgba(255,255,255,0.3);
                    transform: translateY(-3px);
                }
                
                .about-section {
                    margin-bottom: 30px;
                }
                
                .about-section h3 {
                    font-size: 1.8rem;
                    margin-bottom: 15px;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                }
                
                .info-card {
                    background: rgba(255,255,255,0.1);
                    padding: 25px;
                    border-radius: 15px;
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255,255,255,0.2);
                }
                
                .info-card h4 {
                    font-size: 1.3rem;
                    margin-bottom: 15px;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
                }
                
                .info-content {
                    line-height: 1.6;
                    white-space: pre-line;
                }
                
                .certificate-images {
                    margin-top: 15px;
                }
                
                .cert-item {
                    display: inline-block;
                    margin: 5px;
                }
                
                .description-box {
                    background: white;
                    padding: 40px;
                    border-radius: 20px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                    margin-bottom: 40px;
                }
                
                .description-box h2 {
                    font-size: 2.5rem;
                    color: #ff6b6b;
                    margin-bottom: 20px;
                }
                
                .description-box p {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #666;
                }
                
                .gallery-section h2 {
                    font-size: 2.5rem;
                    color: #4ecdc4;
                    margin-bottom: 30px;
                    text-align: center;
                }
                
                .image-gallery {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 30px;
                }
                
                .image-item {
                    background: white;
                    padding: 20px;
                    border-radius: 15px;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                    text-align: center;
                }
                
                .additional-image {
                    max-width: 100%;
                    height: auto;
                    border-radius: 10px;
                    margin-bottom: 15px;
                }
                
                .image-title {
                    font-size: 1.2rem;
                    color: #333;
                    margin-bottom: 10px;
                }
                
                .image-description {
                    color: #666;
                    font-style: italic;
                }
                ',
                'is_active' => true
            ],
            [
                'name' => 'Professional Business',
                'slug' => 'professional-business',
                'description' => 'Template profesional untuk bisnis dengan desain formal',
                'preview_image' => 'templates/professional-business-preview.jpg',
                'template_html' => '
                <div class="business-portfolio">
                    <div class="header-section">
                        <div class="container">
                            <h1 class="business-title">{{project_name}}</h1>
                            <h2 class="business-subtitle">{{project_title}}</h2>
                        </div>
                    </div>
                    
                    <div class="main-content">
                        <div class="container">
                            <!-- Personal Information Section -->
                            <div class="personal-info-section">
                                <div class="personal-header">
                                    <h2>{{full_name}}</h2>
                                    <div class="contact-info">
                                        <p><i class="fas fa-envelope"></i> {{email}}</p>
                                        <p><i class="fas fa-phone"></i> {{phone}}</p>
                                        <div class="social-links">
                                            <a href="{{linkedin}}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                            <a href="{{github}}" target="_blank" class="social-link"><i class="fab fa-github"></i> GitHub</a>
                                            <a href="{{website}}" target="_blank" class="social-link"><i class="fas fa-globe"></i> Website</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="about-section">
                                    <h3>Tentang Saya</h3>
                                    <p>{{about_me}}</p>
                                </div>
                                
                                <div class="info-grid">
                                    <div class="info-card">
                                        <h4><i class="fas fa-graduation-cap"></i> Pendidikan</h4>
                                        <div class="info-content">{{education}}</div>
                                    </div>
                                    
                                    <div class="info-card">
                                        <h4><i class="fas fa-briefcase"></i> Pengalaman</h4>
                                        <div class="info-content">{{experience}}</div>
                                    </div>
                                    
                                    <div class="info-card">
                                        <h4><i class="fas fa-code"></i> Keterampilan</h4>
                                        <div class="info-content">{{skills}}</div>
                                    </div>
                                    
                                    <div class="info-card">
                                        <h4><i class="fas fa-certificate"></i> Sertifikasi</h4>
                                        <div class="info-content">{{certifications}}</div>
                                        <div class="certificate-images">{{certificate_images}}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="project-showcase">
                                <div class="main-image-wrapper">
                                    <img src="{{project_image}}" alt="{{project_name}}" class="main-project-image">
                                </div>
                                
                                <div class="project-info">
                                    <h3>Deskripsi Proyek</h3>
                                    <p>{{description}}</p>
                                </div>
                            </div>
                            
                            <div class="project-gallery">
                                <h3>Dokumentasi Proyek</h3>
                                <div class="gallery-grid">
                                    {{additional_images}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>',
                'template_css' => '
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: "Roboto", sans-serif;
                    background: #f5f5f5;
                    color: #333;
                }
                
                .business-portfolio {
                    min-height: 100vh;
                }
                
                .header-section {
                    background: #2c3e50;
                    color: white;
                    padding: 60px 0;
                }
                
                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 0 20px;
                }
                
                .business-title {
                    font-size: 3rem;
                    font-weight: 700;
                    margin-bottom: 10px;
                }
                
                .business-subtitle {
                    font-size: 1.5rem;
                    font-weight: 300;
                    opacity: 0.9;
                }
                
                .main-content {
                    padding: 80px 0;
                }
                
                .personal-info-section {
                    background: #f8f9fa;
                    padding: 40px;
                    border-radius: 10px;
                    margin-bottom: 40px;
                    border: 1px solid #e9ecef;
                }
                
                .personal-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                
                .personal-header h2 {
                    font-size: 2.5rem;
                    color: #2c3e50;
                    margin-bottom: 15px;
                    font-weight: 600;
                }
                
                .contact-info p {
                    margin: 8px 0;
                    color: #666;
                    font-size: 1.1rem;
                }
                
                .social-links {
                    margin-top: 20px;
                }
                
                .social-link {
                    display: inline-block;
                    margin: 0 10px;
                    padding: 10px 20px;
                    background: #3498db;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    transition: all 0.3s ease;
                    font-weight: 500;
                }
                
                .social-link:hover {
                    background: #2980b9;
                    transform: translateY(-2px);
                }
                
                .about-section {
                    margin-bottom: 30px;
                }
                
                .about-section h3 {
                    font-size: 1.8rem;
                    color: #2c3e50;
                    margin-bottom: 15px;
                    font-weight: 600;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                }
                
                .info-card {
                    background: white;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    border-left: 4px solid #3498db;
                }
                
                .info-card h4 {
                    font-size: 1.3rem;
                    color: #2c3e50;
                    margin-bottom: 15px;
                    font-weight: 600;
                }
                
                .info-content {
                    color: #666;
                    line-height: 1.6;
                    white-space: pre-line;
                }
                
                .certificate-images {
                    margin-top: 15px;
                }
                
                .cert-item {
                    display: inline-block;
                    margin: 5px;
                }
                
                .project-showcase {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 60px;
                    margin-bottom: 80px;
                    align-items: center;
                }
                
                .main-image-wrapper {
                    text-align: center;
                }
                
                .main-project-image {
                    max-width: 100%;
                    height: auto;
                    border-radius: 10px;
                    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                }
                
                .project-info h3 {
                    font-size: 2rem;
                    color: #2c3e50;
                    margin-bottom: 20px;
                }
                
                .project-info p {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #666;
                }
                
                .project-gallery h3 {
                    font-size: 2rem;
                    color: #2c3e50;
                    margin-bottom: 30px;
                    text-align: center;
                }
                
                .gallery-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 30px;
                }
                
                .image-item {
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    text-align: center;
                }
                
                .additional-image {
                    max-width: 100%;
                    height: auto;
                    border-radius: 8px;
                    margin-bottom: 15px;
                }
                
                .image-title {
                    font-size: 1.2rem;
                    color: #2c3e50;
                    margin-bottom: 10px;
                    font-weight: 600;
                }
                
                .image-description {
                    color: #666;
                    font-style: italic;
                }
                ',
                'is_active' => true
            ],
            [
                'name' => 'Minimalist',
                'slug' => 'minimalist',
                'description' => 'Template minimalis dengan fokus pada konten',
                'preview_image' => 'templates/minimalist-preview.jpg',
                'template_html' => '
                <div class="minimalist-portfolio">
                    <div class="content-wrapper">
                        <header class="portfolio-header">
                            <h1>{{project_name}}</h1>
                            <p class="subtitle">{{project_title}}</p>
                        </header>
                        
                        <!-- Personal Information Section -->
                        <div class="personal-info-section">
                            <div class="personal-header">
                                <h2>{{full_name}}</h2>
                                <div class="contact-info">
                                    <p><i class="fas fa-envelope"></i> {{email}}</p>
                                    <p><i class="fas fa-phone"></i> {{phone}}</p>
                                    <div class="social-links">
                                        <a href="{{linkedin}}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                        <a href="{{github}}" target="_blank" class="social-link"><i class="fab fa-github"></i> GitHub</a>
                                        <a href="{{website}}" target="_blank" class="social-link"><i class="fas fa-globe"></i> Website</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="about-section">
                                <h3>Tentang Saya</h3>
                                <p>{{about_me}}</p>
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-card">
                                    <h4><i class="fas fa-graduation-cap"></i> Pendidikan</h4>
                                    <div class="info-content">{{education}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-briefcase"></i> Pengalaman</h4>
                                    <div class="info-content">{{experience}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-code"></i> Keterampilan</h4>
                                    <div class="info-content">{{skills}}</div>
                                </div>
                                
                                <div class="info-card">
                                    <h4><i class="fas fa-certificate"></i> Sertifikasi</h4>
                                    <div class="info-content">{{certifications}}</div>
                                    <div class="certificate-images">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="project-image">
                            <img src="{{project_image}}" alt="{{project_name}}">
                        </div>
                        
                        <div class="project-description">
                            <p>{{description}}</p>
                        </div>
                        
                        <div class="additional-gallery">
                            {{additional_images}}
                        </div>
                    </div>
                </div>',
                'template_css' => '
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: "Helvetica Neue", Arial, sans-serif;
                    background: #ffffff;
                    color: #333;
                }
                
                .minimalist-portfolio {
                    min-height: 100vh;
                    padding: 40px 20px;
                }
                
                .content-wrapper {
                    max-width: 800px;
                    margin: 0 auto;
                }
                
                .portfolio-header {
                    text-align: center;
                    margin-bottom: 60px;
                    padding-bottom: 40px;
                    border-bottom: 1px solid #eee;
                }
                
                .portfolio-header h1 {
                    font-size: 3rem;
                    font-weight: 300;
                    color: #333;
                    margin-bottom: 10px;
                }
                
                .subtitle {
                    font-size: 1.2rem;
                    color: #666;
                    font-weight: 300;
                }
                
                .personal-info-section {
                    background: #fafafa;
                    padding: 40px 0;
                    margin: 40px 0;
                    border-top: 1px solid #eee;
                    border-bottom: 1px solid #eee;
                }
                
                .personal-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                
                .personal-header h2 {
                    font-size: 2rem;
                    color: #333;
                    margin-bottom: 15px;
                    font-weight: 300;
                }
                
                .contact-info p {
                    margin: 8px 0;
                    color: #666;
                    font-size: 1rem;
                }
                
                .social-links {
                    margin-top: 20px;
                }
                
                .social-link {
                    display: inline-block;
                    margin: 0 8px;
                    padding: 8px 16px;
                    color: #333;
                    text-decoration: none;
                    border: 1px solid #ddd;
                    transition: all 0.3s ease;
                    font-size: 0.9rem;
                }
                
                .social-link:hover {
                    background: #333;
                    color: white;
                }
                
                .about-section {
                    margin-bottom: 30px;
                    text-align: center;
                }
                
                .about-section h3 {
                    font-size: 1.5rem;
                    color: #333;
                    margin-bottom: 15px;
                    font-weight: 300;
                }
                
                .info-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                    margin-top: 30px;
                }
                
                .info-card {
                    background: white;
                    padding: 20px;
                    border: 1px solid #eee;
                }
                
                .info-card h4 {
                    font-size: 1.1rem;
                    color: #333;
                    margin-bottom: 15px;
                    font-weight: 400;
                }
                
                .info-content {
                    color: #666;
                    line-height: 1.6;
                    white-space: pre-line;
                    font-size: 0.9rem;
                }
                
                .certificate-images {
                    margin-top: 15px;
                }
                
                .cert-item {
                    display: inline-block;
                    margin: 5px;
                }
                
                .project-image {
                    margin-bottom: 60px;
                    text-align: center;
                }
                
                .project-image img {
                    max-width: 100%;
                    height: auto;
                }
                
                .project-description {
                    margin-bottom: 60px;
                    text-align: center;
                }
                
                .project-description p {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #666;
                    max-width: 600px;
                    margin: 0 auto;
                }
                
                .additional-gallery {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 40px;
                }
                
                .image-item {
                    text-align: center;
                }
                
                .additional-image {
                    max-width: 100%;
                    height: auto;
                    margin-bottom: 20px;
                }
                
                .image-title {
                    font-size: 1.1rem;
                    color: #333;
                    margin-bottom: 10px;
                    font-weight: 400;
                }
                
                .image-description {
                    color: #666;
                    font-size: 0.9rem;
                    font-style: italic;
                }
                ',
                'is_active' => true
            ]
        ];
        
        foreach ($templates as $template) {
            PortfolioTemplate::create($template);
        }
    }
}