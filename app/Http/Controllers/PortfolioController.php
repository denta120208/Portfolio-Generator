<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioTemplate;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Portfolio::with(['template', 'images'])->get();
        return view('portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $templates = PortfolioTemplate::where('is_active', true)->get();
        return view('portfolios.create', compact('templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'description' => 'required|string',
            'template_id' => 'required|exists:portfolio_templates,id',
            // Personal Information
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'about_me' => 'required|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            // Images
            'images.*.file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*.title' => 'nullable|string|max:255',
            'images.*.description' => 'nullable|string',
            'images.*.is_main' => 'nullable|boolean'
        ]);

        $data = $request->only([
            'project_name', 'project_title', 'description', 'template_id',
            'full_name', 'email', 'phone', 'linkedin', 'github', 'website',
            'about_me', 'education', 'experience', 'skills', 'certifications'
        ]);
        
        // Create portfolio
        $portfolio = Portfolio::create($data);

        // Handle images upload with metadata
        if ($request->has('images')) {
            $mainImageIndex = $request->input('main_image', 0);
            
            foreach ($request->images as $index => $imageData) {
                if (isset($imageData['file']) && $imageData['file']) {
                    $file = $imageData['file'];
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('portfolio-images', $filename, 'public');
                    
                    // Create thumbnail
                    $this->createThumbnail($file, $filename);
                    
                    PortfolioImage::create([
                        'portfolio_id' => $portfolio->id,
                        'image_path' => $path,
                        'title' => $imageData['title'] ?? null,
                        'description' => $imageData['description'] ?? null,
                        'is_main' => ($index == $mainImageIndex),
                        'sort_order' => $index
                    ]);
                }
            }
        }

        return redirect()->route('portfolios.index')->with('success', 'Portfolio berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
        return view('portfolios.show', compact('portfolio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
        $templates = PortfolioTemplate::where('is_active', true)->get();
        return view('portfolios.edit', compact('portfolio', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'description' => 'required|string',
            'template_id' => 'required|exists:portfolio_templates,id',
            'project_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['project_name', 'project_title', 'description', 'template_id']);
        
        // Handle main project image upload
        if ($request->hasFile('project_image')) {
            // Delete old main image
            $oldMainImage = $portfolio->mainImage;
            if ($oldMainImage) {
                Storage::disk('public')->delete($oldMainImage->image_path);
                $oldMainImage->delete();
            }
            
            $file = $request->file('project_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('portfolio-images', $filename, 'public');
            
            PortfolioImage::create([
                'portfolio_id' => $portfolio->id,
                'image_path' => $path,
                'title' => null,
                'description' => null,
                'is_main' => true,
                'sort_order' => 0
            ]);
        }

        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            // Delete old additional images
            $oldImages = $portfolio->images()->where('is_main', false)->get();
            foreach ($oldImages as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
            
            foreach ($request->file('additional_images') as $index => $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('portfolio-images', $filename, 'public');
                
                PortfolioImage::create([
                    'portfolio_id' => $portfolio->id,
                    'image_path' => $path,
                    'title' => null,
                    'description' => null,
                    'is_main' => false,
                    'sort_order' => $index + 1
                ]);
            }
        }

        $portfolio->update($data);

        return redirect()->route('portfolios.index')->with('success', 'Portfolio berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        
        // Delete all images
        foreach ($portfolio->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $portfolio->delete();

        return redirect()->route('portfolios.index')->with('success', 'Portfolio berhasil dihapus!');
    }

    /**
     * Preview portfolio with selected template
     */
    public function preview(Request $request)
    {
        $template = PortfolioTemplate::findOrFail($request->template_id);
        $portfolioData = $request->all();
        
        // Generate HTML for preview
        $html = $this->generatePreviewHTML($template, $portfolioData);
        
        return response($html)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Preview existing portfolio
     */
    public function previewPortfolio($id)
    {
        $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
        
        // Generate HTML for preview
        $html = $this->generatePortfolioHTML($portfolio);
        
        return response($html)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Generate HTML for portfolio preview
     */
    private function generatePreviewHTML($template, $portfolioData)
    {
        $html = $template->template_html;
        $css = $template->template_css;
        
        // Replace placeholders with preview data
        $html = str_replace('{{project_name}}', $portfolioData['project_name'] ?? 'Nama Proyek', $html);
        $html = str_replace('{{project_title}}', $portfolioData['project_title'] ?? 'Judul Proyek', $html);
        $html = str_replace('{{description}}', $portfolioData['description'] ?? 'Deskripsi proyek', $html);
        
        // Handle project image
        $projectImage = '';
        if (isset($portfolioData['project_image']) && $portfolioData['project_image']) {
            $projectImage = asset('storage/' . $portfolioData['project_image']);
        } else {
            $projectImage = 'https://via.placeholder.com/800x400?text=No+Image';
        }
        $html = str_replace('{{project_image}}', $projectImage, $html);
        
        // Handle additional images
        $additionalImages = '';
        if (isset($portfolioData['additional_images']) && is_array($portfolioData['additional_images'])) {
            foreach ($portfolioData['additional_images'] as $image) {
                $additionalImages .= '<div class="image-item">';
                $additionalImages .= '<img src="' . asset('storage/' . $image) . '" alt="Project Image" class="additional-image">';
                $additionalImages .= '</div>';
            }
        }
        $html = str_replace('{{additional_images}}', $additionalImages, $html);
        
        return '<!DOCTYPE html><html><head><style>' . $css . '</style></head><body>' . $html . '</body></html>';
    }

    /**
     * Export portfolio as HTML
     */
    public function export($id)
    {
        $portfolio = Portfolio::with('template')->findOrFail($id);
        
        $html = $this->generatePortfolioHTML($portfolio);
        
        $filename = Str::slug($portfolio->project_name) . '-portfolio.html';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate HTML for portfolio
     */
    private function generatePortfolioHTML($portfolio)
    {
        $template = $portfolio->template;
        $html = $template->template_html;
        $css = $template->template_css;
        
        // Get main image
        $mainImage = $portfolio->mainImage;
        $mainImagePath = $mainImage ? asset('storage/' . $mainImage->image_path) : '';
        
        // Replace placeholders with actual data
        $html = str_replace('{{project_name}}', $portfolio->project_name, $html);
        $html = str_replace('{{project_title}}', $portfolio->project_title, $html);
        $html = str_replace('{{description}}', $portfolio->description, $html);
        $html = str_replace('{{project_image}}', $mainImagePath, $html);
        
        // Handle additional images
        $additionalImages = $portfolio->images()->where('is_main', false)->get();
        if ($additionalImages->count() > 0) {
            $additionalImagesHTML = '';
            foreach ($additionalImages as $image) {
                $additionalImagesHTML .= '<div class="image-item">';
                $additionalImagesHTML .= '<img src="' . asset('storage/' . $image->image_path) . '" alt="' . ($image->title ?: 'Project Image') . '" class="additional-image">';
                if ($image->title) {
                    $additionalImagesHTML .= '<h4 class="image-title">' . $image->title . '</h4>';
                }
                if ($image->description) {
                    $additionalImagesHTML .= '<p class="image-description">' . $image->description . '</p>';
                }
                $additionalImagesHTML .= '</div>';
            }
            $html = str_replace('{{additional_images}}', $additionalImagesHTML, $html);
        } else {
            $html = str_replace('{{additional_images}}', '', $html);
        }
        
        return '<!DOCTYPE html><html><head><style>' . $css . '</style></head><body>' . $html . '</body></html>';
    }

    /**
     * Create thumbnail for image
     */
    private function createThumbnail($file, $filename)
    {
        // For now, we'll skip thumbnail creation
        // In a real implementation, you would use a library like Intervention Image
        // or create thumbnails using GD or Imagick
    }

}
