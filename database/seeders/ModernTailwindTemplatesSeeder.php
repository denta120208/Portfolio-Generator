<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioTemplate;

class ModernTailwindTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua template yang ada
        PortfolioTemplate::truncate();
        
        // Insert template modern dengan Tailwind CSS
        $templates = [
            [
                'name' => 'Modern Glassmorphism',
                'slug' => 'modern-glassmorphism',
                'description' => 'Template modern dengan efek glassmorphism dan gradient yang menawan',
                'preview_image' => 'templates/modern-glassmorphism-preview.jpg',
                'template_html' => '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>{{project_name}} - Portfolio</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
                    <style>
                        .glass {
                            background: rgba(255, 255, 255, 0.1);
                            backdrop-filter: blur(10px);
                            border: 1px solid rgba(255, 255, 255, 0.2);
                        }
                        .gradient-bg {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        }
                        .gradient-text {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            -webkit-background-clip: text;
                            -webkit-text-fill-color: transparent;
                            background-clip: text;
                        }
                    </style>
                </head>
                <body class="gradient-bg min-h-screen">
                    <div class="container mx-auto px-4 py-8">
                        <!-- Header Section -->
                        <div class="glass rounded-3xl p-8 mb-8 text-center text-white">
                            <h1 class="text-5xl font-bold mb-4">{{project_name}}</h1>
                            <p class="text-xl opacity-90">{{project_title}}</p>
                        </div>
                        
                        <!-- Personal Information Section -->
                        <div class="glass rounded-3xl p-8 mb-8 text-white">
                            <div class="text-center mb-8">
                                <h2 class="text-4xl font-bold mb-4">{{full_name}}</h2>
                                <div class="space-y-2 mb-6">
                                    <p class="text-lg"><i class="fas fa-envelope mr-2"></i>{{email}}</p>
                                    <p class="text-lg"><i class="fas fa-phone mr-2"></i>{{phone}}</p>
                                </div>
                                <div class="flex justify-center space-x-4">
                                    <a href="{{linkedin}}" target="_blank" class="glass px-6 py-3 rounded-full hover:bg-white hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105">
                                        <i class="fab fa-linkedin mr-2"></i>LinkedIn
                                    </a>
                                    <a href="{{github}}" target="_blank" class="glass px-6 py-3 rounded-full hover:bg-white hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105">
                                        <i class="fab fa-github mr-2"></i>GitHub
                                    </a>
                                    <a href="{{website}}" target="_blank" class="glass px-6 py-3 rounded-full hover:bg-white hover:bg-opacity-20 transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-globe mr-2"></i>Website
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold mb-4">Tentang Saya</h3>
                                <p class="text-lg leading-relaxed">{{about_me}}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="glass rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-graduation-cap mr-2"></i>Pendidikan</h4>
                                    <div class="whitespace-pre-line">{{education}}</div>
                                </div>
                                
                                <div class="glass rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-briefcase mr-2"></i>Pengalaman</h4>
                                    <div class="whitespace-pre-line">{{experience}}</div>
                                </div>
                                
                                <div class="glass rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-code mr-2"></i>Keterampilan</h4>
                                    <div class="whitespace-pre-line">{{skills}}</div>
                                </div>
                                
                                <div class="glass rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-certificate mr-2"></i>Sertifikasi</h4>
                                    <div class="whitespace-pre-line">{{certifications}}</div>
                                    <div class="mt-4">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project Section -->
                        <div class="glass rounded-3xl p-8 mb-8 text-white">
                            <div class="text-center mb-8">
                                <img src="{{project_image}}" alt="{{project_name}}" class="w-full max-w-2xl mx-auto rounded-2xl shadow-2xl">
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-3xl font-bold mb-6">Tentang Proyek</h3>
                                <p class="text-lg leading-relaxed max-w-4xl mx-auto">{{description}}</p>
                            </div>
                        </div>
                        
                        <!-- Additional Images -->
                        <div class="glass rounded-3xl p-8 text-white">
                            <h3 class="text-3xl font-bold mb-8 text-center">Galeri Proyek</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {{additional_images}}
                            </div>
                        </div>
                    </div>
                </body>
                </html>',
                'template_css' => '',
                'is_active' => true
            ],
            [
                'name' => 'Dark Neon',
                'slug' => 'dark-neon',
                'description' => 'Template dark mode dengan efek neon yang futuristik',
                'preview_image' => 'templates/dark-neon-preview.jpg',
                'template_html' => '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>{{project_name}} - Portfolio</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
                    <style>
                        .neon-glow {
                            box-shadow: 0 0 20px #00ff88, 0 0 40px #00ff88, 0 0 60px #00ff88;
                        }
                        .neon-text {
                            text-shadow: 0 0 10px #00ff88, 0 0 20px #00ff88, 0 0 30px #00ff88;
                        }
                        .neon-border {
                            border: 2px solid #00ff88;
                            box-shadow: 0 0 10px #00ff88;
                        }
                        .dark-bg {
                            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
                        }
                    </style>
                </head>
                <body class="dark-bg min-h-screen text-white">
                    <div class="container mx-auto px-4 py-8">
                        <!-- Header Section -->
                        <div class="neon-border rounded-3xl p-8 mb-8 text-center">
                            <h1 class="text-5xl font-bold mb-4 neon-text">{{project_name}}</h1>
                            <p class="text-xl text-gray-300">{{project_title}}</p>
                        </div>
                        
                        <!-- Personal Information Section -->
                        <div class="neon-border rounded-3xl p-8 mb-8">
                            <div class="text-center mb-8">
                                <h2 class="text-4xl font-bold mb-4 neon-text">{{full_name}}</h2>
                                <div class="space-y-2 mb-6">
                                    <p class="text-lg"><i class="fas fa-envelope mr-2 text-green-400"></i>{{email}}</p>
                                    <p class="text-lg"><i class="fas fa-phone mr-2 text-green-400"></i>{{phone}}</p>
                                </div>
                                <div class="flex justify-center space-x-4">
                                    <a href="{{linkedin}}" target="_blank" class="neon-border px-6 py-3 rounded-full hover:neon-glow transition-all duration-300">
                                        <i class="fab fa-linkedin mr-2"></i>LinkedIn
                                    </a>
                                    <a href="{{github}}" target="_blank" class="neon-border px-6 py-3 rounded-full hover:neon-glow transition-all duration-300">
                                        <i class="fab fa-github mr-2"></i>GitHub
                                    </a>
                                    <a href="{{website}}" target="_blank" class="neon-border px-6 py-3 rounded-full hover:neon-glow transition-all duration-300">
                                        <i class="fas fa-globe mr-2"></i>Website
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold mb-4 neon-text">Tentang Saya</h3>
                                <p class="text-lg leading-relaxed text-gray-300">{{about_me}}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="neon-border rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4 text-green-400"><i class="fas fa-graduation-cap mr-2"></i>Pendidikan</h4>
                                    <div class="whitespace-pre-line text-gray-300">{{education}}</div>
                                </div>
                                
                                <div class="neon-border rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4 text-green-400"><i class="fas fa-briefcase mr-2"></i>Pengalaman</h4>
                                    <div class="whitespace-pre-line text-gray-300">{{experience}}</div>
                                </div>
                                
                                <div class="neon-border rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4 text-green-400"><i class="fas fa-code mr-2"></i>Keterampilan</h4>
                                    <div class="whitespace-pre-line text-gray-300">{{skills}}</div>
                                </div>
                                
                                <div class="neon-border rounded-2xl p-6">
                                    <h4 class="text-xl font-bold mb-4 text-green-400"><i class="fas fa-certificate mr-2"></i>Sertifikasi</h4>
                                    <div class="whitespace-pre-line text-gray-300">{{certifications}}</div>
                                    <div class="mt-4">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project Section -->
                        <div class="neon-border rounded-3xl p-8 mb-8">
                            <div class="text-center mb-8">
                                <img src="{{project_image}}" alt="{{project_name}}" class="w-full max-w-2xl mx-auto rounded-2xl neon-glow">
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-3xl font-bold mb-6 neon-text">Tentang Proyek</h3>
                                <p class="text-lg leading-relaxed max-w-4xl mx-auto text-gray-300">{{description}}</p>
                            </div>
                        </div>
                        
                        <!-- Additional Images -->
                        <div class="neon-border rounded-3xl p-8">
                            <h3 class="text-3xl font-bold mb-8 text-center neon-text">Galeri Proyek</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {{additional_images}}
                            </div>
                        </div>
                    </div>
                </body>
                </html>',
                'template_css' => '',
                'is_active' => true
            ],
            [
                'name' => 'Gradient Paradise',
                'slug' => 'gradient-paradise',
                'description' => 'Template dengan gradient warna-warni yang memukau',
                'preview_image' => 'templates/gradient-paradise-preview.jpg',
                'template_html' => '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>{{project_name}} - Portfolio</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
                    <style>
                        .gradient-1 {
                            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        }
                        .gradient-2 {
                            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                        }
                        .gradient-3 {
                            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                        }
                        .gradient-4 {
                            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
                        }
                        .gradient-5 {
                            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
                        }
                        .gradient-6 {
                            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
                        }
                        .floating {
                            animation: floating 3s ease-in-out infinite;
                        }
                        @keyframes floating {
                            0%, 100% { transform: translateY(0px); }
                            50% { transform: translateY(-10px); }
                        }
                    </style>
                </head>
                <body class="gradient-6 min-h-screen">
                    <div class="container mx-auto px-4 py-8">
                        <!-- Header Section -->
                        <div class="gradient-1 rounded-3xl p-8 mb-8 text-center text-white floating">
                            <h1 class="text-5xl font-bold mb-4">{{project_name}}</h1>
                            <p class="text-xl opacity-90">{{project_title}}</p>
                        </div>
                        
                        <!-- Personal Information Section -->
                        <div class="gradient-2 rounded-3xl p-8 mb-8 text-white">
                            <div class="text-center mb-8">
                                <h2 class="text-4xl font-bold mb-4">{{full_name}}</h2>
                                <div class="space-y-2 mb-6">
                                    <p class="text-lg"><i class="fas fa-envelope mr-2"></i>{{email}}</p>
                                    <p class="text-lg"><i class="fas fa-phone mr-2"></i>{{phone}}</p>
                                </div>
                                <div class="flex justify-center space-x-4">
                                    <a href="{{linkedin}}" target="_blank" class="gradient-3 px-6 py-3 rounded-full hover:scale-105 transition-all duration-300">
                                        <i class="fab fa-linkedin mr-2"></i>LinkedIn
                                    </a>
                                    <a href="{{github}}" target="_blank" class="gradient-4 px-6 py-3 rounded-full hover:scale-105 transition-all duration-300">
                                        <i class="fab fa-github mr-2"></i>GitHub
                                    </a>
                                    <a href="{{website}}" target="_blank" class="gradient-5 px-6 py-3 rounded-full hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-globe mr-2"></i>Website
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-8">
                                <h3 class="text-2xl font-bold mb-4">Tentang Saya</h3>
                                <p class="text-lg leading-relaxed">{{about_me}}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="gradient-3 rounded-2xl p-6 text-white">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-graduation-cap mr-2"></i>Pendidikan</h4>
                                    <div class="whitespace-pre-line">{{education}}</div>
                                </div>
                                
                                <div class="gradient-4 rounded-2xl p-6 text-white">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-briefcase mr-2"></i>Pengalaman</h4>
                                    <div class="whitespace-pre-line">{{experience}}</div>
                                </div>
                                
                                <div class="gradient-5 rounded-2xl p-6 text-white">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-code mr-2"></i>Keterampilan</h4>
                                    <div class="whitespace-pre-line">{{skills}}</div>
                                </div>
                                
                                <div class="gradient-1 rounded-2xl p-6 text-white">
                                    <h4 class="text-xl font-bold mb-4"><i class="fas fa-certificate mr-2"></i>Sertifikasi</h4>
                                    <div class="whitespace-pre-line">{{certifications}}</div>
                                    <div class="mt-4">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project Section -->
                        <div class="gradient-3 rounded-3xl p-8 mb-8 text-white">
                            <div class="text-center mb-8">
                                <img src="{{project_image}}" alt="{{project_name}}" class="w-full max-w-2xl mx-auto rounded-2xl shadow-2xl floating">
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-3xl font-bold mb-6">Tentang Proyek</h3>
                                <p class="text-lg leading-relaxed max-w-4xl mx-auto">{{description}}</p>
                            </div>
                        </div>
                        
                        <!-- Additional Images -->
                        <div class="gradient-4 rounded-3xl p-8 text-white">
                            <h3 class="text-3xl font-bold mb-8 text-center">Galeri Proyek</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {{additional_images}}
                            </div>
                        </div>
                    </div>
                </body>
                </html>',
                'template_css' => '',
                'is_active' => true
            ],
            [
                'name' => 'Minimalist Elegant',
                'slug' => 'minimalist-elegant',
                'description' => 'Template minimalis elegan dengan tipografi yang indah',
                'preview_image' => 'templates/minimalist-elegant-preview.jpg',
                'template_html' => '
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>{{project_name}} - Portfolio</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
                    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                    <style>
                        body {
                            font-family: "Inter", sans-serif;
                        }
                        .elegant-shadow {
                            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                        }
                        .hover-lift:hover {
                            transform: translateY(-5px);
                        }
                    </style>
                </head>
                <body class="bg-gray-50 min-h-screen">
                    <div class="max-w-6xl mx-auto px-4 py-12">
                        <!-- Header Section -->
                        <div class="bg-white rounded-3xl p-12 mb-12 text-center elegant-shadow">
                            <h1 class="text-6xl font-light mb-4 text-gray-800">{{project_name}}</h1>
                            <p class="text-xl text-gray-600 font-light">{{project_title}}</p>
                        </div>
                        
                        <!-- Personal Information Section -->
                        <div class="bg-white rounded-3xl p-12 mb-12 elegant-shadow">
                            <div class="text-center mb-12">
                                <h2 class="text-4xl font-light mb-6 text-gray-800">{{full_name}}</h2>
                                <div class="space-y-3 mb-8">
                                    <p class="text-lg text-gray-600"><i class="fas fa-envelope mr-3 text-gray-400"></i>{{email}}</p>
                                    <p class="text-lg text-gray-600"><i class="fas fa-phone mr-3 text-gray-400"></i>{{phone}}</p>
                                </div>
                                <div class="flex justify-center space-x-6">
                                    <a href="{{linkedin}}" target="_blank" class="bg-gray-800 text-white px-8 py-3 rounded-full hover:bg-gray-700 transition-all duration-300 hover-lift">
                                        <i class="fab fa-linkedin mr-2"></i>LinkedIn
                                    </a>
                                    <a href="{{github}}" target="_blank" class="bg-gray-800 text-white px-8 py-3 rounded-full hover:bg-gray-700 transition-all duration-300 hover-lift">
                                        <i class="fab fa-github mr-2"></i>GitHub
                                    </a>
                                    <a href="{{website}}" target="_blank" class="bg-gray-800 text-white px-8 py-3 rounded-full hover:bg-gray-700 transition-all duration-300 hover-lift">
                                        <i class="fas fa-globe mr-2"></i>Website
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-12">
                                <h3 class="text-2xl font-light mb-6 text-gray-800">Tentang Saya</h3>
                                <p class="text-lg leading-relaxed text-gray-600">{{about_me}}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all duration-300">
                                    <h4 class="text-xl font-medium mb-4 text-gray-800"><i class="fas fa-graduation-cap mr-3 text-gray-500"></i>Pendidikan</h4>
                                    <div class="whitespace-pre-line text-gray-600">{{education}}</div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all duration-300">
                                    <h4 class="text-xl font-medium mb-4 text-gray-800"><i class="fas fa-briefcase mr-3 text-gray-500"></i>Pengalaman</h4>
                                    <div class="whitespace-pre-line text-gray-600">{{experience}}</div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all duration-300">
                                    <h4 class="text-xl font-medium mb-4 text-gray-800"><i class="fas fa-code mr-3 text-gray-500"></i>Keterampilan</h4>
                                    <div class="whitespace-pre-line text-gray-600">{{skills}}</div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-2xl p-8 hover-lift transition-all duration-300">
                                    <h4 class="text-xl font-medium mb-4 text-gray-800"><i class="fas fa-certificate mr-3 text-gray-500"></i>Sertifikasi</h4>
                                    <div class="whitespace-pre-line text-gray-600">{{certifications}}</div>
                                    <div class="mt-4">{{certificate_images}}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Project Section -->
                        <div class="bg-white rounded-3xl p-12 mb-12 elegant-shadow">
                            <div class="text-center mb-12">
                                <img src="{{project_image}}" alt="{{project_name}}" class="w-full max-w-3xl mx-auto rounded-2xl elegant-shadow hover-lift transition-all duration-300">
                            </div>
                            
                            <div class="text-center">
                                <h3 class="text-3xl font-light mb-8 text-gray-800">Tentang Proyek</h3>
                                <p class="text-lg leading-relaxed max-w-4xl mx-auto text-gray-600">{{description}}</p>
                            </div>
                        </div>
                        
                        <!-- Additional Images -->
                        <div class="bg-white rounded-3xl p-12 elegant-shadow">
                            <h3 class="text-3xl font-light mb-12 text-center text-gray-800">Galeri Proyek</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {{additional_images}}
                            </div>
                        </div>
                    </div>
                </body>
                </html>',
                'template_css' => '',
                'is_active' => true
            ]
        ];
        
        foreach ($templates as $template) {
            PortfolioTemplate::create($template);
        }
    }
}