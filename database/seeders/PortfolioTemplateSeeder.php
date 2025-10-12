<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioTemplate;

class PortfolioTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                }
                
                .additional-image {
                    width: 100%;
                    height: 250px;
                    object-fit: cover;
                    border-radius: 10px;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                }
                ',
                'template_config' => [
                    'supports_additional_images' => true,
                    'max_additional_images' => 6,
                    'color_scheme' => 'gradient'
                ],
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
                    background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
                    background-size: 400% 400%;
                    animation: gradientShift 15s ease infinite;
                    padding: 80px 20px;
                    text-align: center;
                    color: white;
                }
                
                @keyframes gradientShift {
                    0% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                    100% { background-position: 0% 50%; }
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
                    height: 400px;
                    object-fit: cover;
                    border-radius: 20px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                }
                
                .content-section {
                    padding: 80px 20px;
                    max-width: 1200px;
                    margin: 0 auto;
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
                    color: #333;
                    margin-bottom: 20px;
                }
                
                .description-box p {
                    font-size: 1.2rem;
                    line-height: 1.8;
                    color: #666;
                }
                
                .gallery-section h2 {
                    font-size: 2.5rem;
                    color: #333;
                    margin-bottom: 30px;
                    text-align: center;
                }
                
                .image-gallery {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                    gap: 20px;
                }
                
                .additional-image {
                    width: 100%;
                    height: 300px;
                    object-fit: cover;
                    border-radius: 15px;
                    transition: transform 0.3s ease;
                }
                
                .additional-image:hover {
                    transform: scale(1.05);
                }
                ',
                'template_config' => [
                    'supports_additional_images' => true,
                    'max_additional_images' => 8,
                    'color_scheme' => 'colorful'
                ],
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
                    line-height: 1.6;
                    color: #333;
                }
                
                .business-portfolio {
                    background: #f8f9fa;
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
                    font-size: 3.5rem;
                    font-weight: 700;
                    margin-bottom: 10px;
                }
                
                .business-subtitle {
                    font-size: 1.3rem;
                    font-weight: 300;
                    opacity: 0.9;
                }
                
                .main-content {
                    padding: 80px 0;
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
                    height: 400px;
                    object-fit: cover;
                    border-radius: 10px;
                    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                }
                
                .project-info h3 {
                    font-size: 2rem;
                    margin-bottom: 20px;
                    color: #2c3e50;
                }
                
                .project-info p {
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #666;
                }
                
                .project-gallery h3 {
                    font-size: 2rem;
                    margin-bottom: 30px;
                    color: #2c3e50;
                    text-align: center;
                }
                
                .gallery-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                }
                
                .additional-image {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 8px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    transition: transform 0.3s ease;
                }
                
                .additional-image:hover {
                    transform: translateY(-5px);
                }
                ',
                'template_config' => [
                    'supports_additional_images' => true,
                    'max_additional_images' => 6,
                    'color_scheme' => 'professional'
                ],
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
                    margin-bottom: 10px;
                    color: #000;
                }
                
                .subtitle {
                    font-size: 1.2rem;
                    color: #666;
                    font-weight: 300;
                }
                
                .project-image {
                    margin-bottom: 60px;
                    text-align: center;
                }
                
                .project-image img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 4px;
                }
                
                .project-description {
                    margin-bottom: 60px;
                    font-size: 1.1rem;
                    line-height: 1.8;
                    color: #555;
                    text-align: justify;
                }
                
                .additional-gallery {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                }
                
                .additional-image {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 4px;
                    transition: opacity 0.3s ease;
                }
                
                .additional-image:hover {
                    opacity: 0.8;
                }
                ',
                'template_config' => [
                    'supports_additional_images' => true,
                    'max_additional_images' => 4,
                    'color_scheme' => 'minimal'
                ],
                'is_active' => true
            ]
        ];

        foreach ($templates as $template) {
            PortfolioTemplate::create($template);
        }
    }
}
