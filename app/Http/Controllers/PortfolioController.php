<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioTemplate;
use App\Models\PortfolioImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Portfolio::with(['template', 'images'])
            ->whereNotNull('template_id');

        // Filter per user bila login
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $portfolios = $query->get();
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
            // Require Gmail specifically
            'email' => ['required','email','max:255', function ($attribute, $value, $fail) {
                if (!preg_match('/@gmail\.com$/i', $value)) {
                    $fail('Email harus alamat Gmail (contoh: user@gmail.com).');
                }
            }],
            'phone' => 'nullable|string|max:20',
            'linkedin' => 'nullable|url|max:255',
            'github' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'about_me' => 'required|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            'certificate_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'certificate_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
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
        
        $data['user_id'] = Auth::id();
        
        // Handle single certificate image upload (backwards compatible)
        if ($request->hasFile('certificate_image')) {
            $file = $request->file('certificate_image');
            $filename = time() . '_cert_' . $file->getClientOriginalName();
            $path = $file->storeAs('certificate-images', $filename, 'public');
            $data['certificate_image'] = $path;
        }

        // Handle multiple certificate images (preferred)
        if ($request->hasFile('certificate_images')) {
            $certPaths = [];
            foreach ($request->file('certificate_images') as $file) {
                if ($file) {
                    $filename = time() . '_cert_' . $file->getClientOriginalName();
                    $path = $file->storeAs('certificate-images', $filename, 'public');
                    $certPaths[] = $path;
                }
            }
            if (!empty($certPaths)) {
                $data['certificate_images'] = $certPaths;
            }
        }
        
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

        // Generate public URL
        $portfolio->public_url = $this->generatePublicUrl($portfolio);
        $portfolio->save();

        return redirect()->route('portfolios.index')->with('success', 'Portfolio berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
        if (Auth::check() && $portfolio->user_id && $portfolio->user_id !== Auth::id()) {
            abort(403);
        }
        return view('portfolios.show', compact('portfolio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
        if (Auth::check() && $portfolio->user_id && $portfolio->user_id !== Auth::id()) {
            abort(403);
        }
        $templates = PortfolioTemplate::where('is_active', true)->get();
        return view('portfolios.edit', compact('portfolio', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        if (Auth::check() && $portfolio->user_id && $portfolio->user_id !== Auth::id()) {
            abort(403);
        }
        
    $request->validate([
            'project_name' => 'required|string|max:255',
            'project_title' => 'required|string|max:255',
            'description' => 'required|string',
            'template_id' => 'required|exists:portfolio_templates,id',
            'project_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'certificate_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
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

        // Handle single certificate image upload separately (backwards compatible)
        if ($request->hasFile('certificate_image')) {
            if ($portfolio->certificate_image) {
                Storage::disk('public')->delete($portfolio->certificate_image);
            }

            $file = $request->file('certificate_image');
            $filename = time() . '_cert_' . $file->getClientOriginalName();
            $path = $file->storeAs('certificate-images', $filename, 'public');

            $portfolio->certificate_image = $path;
            $portfolio->save();
        }

        // Handle multiple certificate images: replace previous set if provided
        if ($request->hasFile('certificate_images')) {
            // Delete old certificate images if exist
            if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
                foreach ($portfolio->certificate_images as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $certPaths = [];
            foreach ($request->file('certificate_images') as $file) {
                if ($file) {
                    $filename = time() . '_cert_' . $file->getClientOriginalName();
                    $path = $file->storeAs('certificate-images', $filename, 'public');
                    $certPaths[] = $path;
                }
            }

            $portfolio->certificate_images = $certPaths;
            $portfolio->save();
        }

        return redirect()->route('portfolios.index')->with('success', 'Portfolio berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $portfolio = Portfolio::findOrFail($id);
        if (Auth::check() && $portfolio->user_id && $portfolio->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Delete all images
        foreach ($portfolio->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        // Delete certificate image if exists
        if ($portfolio->certificate_image) {
            Storage::disk('public')->delete($portfolio->certificate_image);
        }
        // Delete multiple certificate images if exist
        if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
            foreach ($portfolio->certificate_images as $path) {
                Storage::disk('public')->delete($path);
            }
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
            $projectImage = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $portfolioData['project_image'])));
        } else {
            $projectImage = 'https://via.placeholder.com/800x400?text=No+Image';
        }
        $html = str_replace('{{project_image}}', $projectImage, $html);
        
        // Handle additional images
        $additionalImages = '';
        if (isset($portfolioData['additional_images']) && is_array($portfolioData['additional_images'])) {
            foreach ($portfolioData['additional_images'] as $image) {
                $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $image)));
                $additionalImages .= '<div class="image-item">';
                $additionalImages .= '<img src="' . $imageData . '" alt="Project Image" class="additional-image">';
                $additionalImages .= '</div>';
            }
        }
        $html = str_replace('{{additional_images}}', $additionalImages, $html);
        // Personal information replacements for preview
        $html = str_replace('{{full_name}}', $portfolioData['full_name'] ?? '', $html);
        $html = str_replace('{{email}}', $portfolioData['email'] ?? '', $html);
        $html = str_replace('{{phone}}', $portfolioData['phone'] ?? '', $html);
        $html = str_replace('{{linkedin}}', $portfolioData['linkedin'] ?? '', $html);
        $html = str_replace('{{github}}', $portfolioData['github'] ?? '', $html);
        $html = str_replace('{{website}}', $portfolioData['website'] ?? '', $html);
        $html = str_replace('{{about_me}}', $portfolioData['about_me'] ?? '', $html);
        $html = str_replace('{{education}}', $portfolioData['education'] ?? '', $html);
        $html = str_replace('{{experience}}', $portfolioData['experience'] ?? '', $html);
        $html = str_replace('{{skills}}', $portfolioData['skills'] ?? '', $html);
        $html = str_replace('{{certifications}}', $portfolioData['certifications'] ?? '', $html);

        // Certificate images in preview (if provided as array of paths)
        $certImagesHtml = '';
        if (isset($portfolioData['certificate_images']) && is_array($portfolioData['certificate_images'])) {
            foreach ($portfolioData['certificate_images'] as $img) {
                $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $img)));
                $certImagesHtml .= '<div class="cert-item"><img src="' . $imageData . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
            }
        }
        $html = str_replace('{{certificate_images}}', $certImagesHtml, $html);
        
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
     * Export portfolio as PDF
     */
    public function exportPdf($id)
    {
        try {
            $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
            
            $html = $this->generatePortfolioHTML($portfolio);
            
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            $filename = Str::slug($portfolio->project_name) . '-portfolio.pdf';
            
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export portfolio as HTML with external images (for document readers)
     */
    public function exportHtmlExternal($id)
    {
        try {
            $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
            
            $html = $this->generatePortfolioHTMLForDocumentReader($portfolio);
            
            $filename = Str::slug($portfolio->project_name) . '-portfolio-document.html';
            
            return response($html)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat HTML: ' . $e->getMessage());
        }
    }

    /**
     * Export portfolio as ZIP containing HTML and images folder
     */
    public function exportZip($id)
    {
        try {
            $portfolio = Portfolio::with(['template', 'images'])->findOrFail($id);
            
            // Create temporary directory
            $tempDir = storage_path('app/temp/' . Str::random(10));
            if (!mkdir($tempDir, 0755, true)) {
                throw new \Exception('Gagal membuat direktori temporary');
            }
            
            // Generate HTML with relative image paths
            $html = $this->generatePortfolioHTMLRelative($portfolio);
            
            // Save HTML file
            if (file_put_contents($tempDir . '/index.html', $html) === false) {
                throw new \Exception('Gagal menyimpan file HTML');
            }
            
            // Create images directory
            $imagesDir = $tempDir . '/images';
            if (!mkdir($imagesDir, 0755, true)) {
                throw new \Exception('Gagal membuat direktori images');
            }
            
            // Copy images to images directory
            $imageCounter = 1;
            
            // Copy main image
            $mainImage = $portfolio->mainImage;
            if ($mainImage && file_exists(public_path('storage/' . $mainImage->image_path))) {
                $extension = pathinfo($mainImage->image_path, PATHINFO_EXTENSION);
                $newName = 'main_image.' . $extension;
                if (!copy(public_path('storage/' . $mainImage->image_path), $imagesDir . '/' . $newName)) {
                    throw new \Exception('Gagal menyalin gambar utama');
                }
            }
            
            // Copy additional images
            $additionalImages = $portfolio->images()->where('is_main', false)->get();
            foreach ($additionalImages as $image) {
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    $extension = pathinfo($image->image_path, PATHINFO_EXTENSION);
                    $newName = 'image_' . $imageCounter . '.' . $extension;
                    if (!copy(public_path('storage/' . $image->image_path), $imagesDir . '/' . $newName)) {
                        throw new \Exception('Gagal menyalin gambar tambahan');
                    }
                    $imageCounter++;
                }
            }
            
            // Copy certificate images
            if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
                foreach ($portfolio->certificate_images as $img) {
                    if (file_exists(public_path('storage/' . $img))) {
                        $extension = pathinfo($img, PATHINFO_EXTENSION);
                        $newName = 'certificate_' . $imageCounter . '.' . $extension;
                        if (!copy(public_path('storage/' . $img), $imagesDir . '/' . $newName)) {
                            throw new \Exception('Gagal menyalin gambar sertifikat');
                        }
                        $imageCounter++;
                    }
                }
            } elseif ($portfolio->certificate_image && file_exists(public_path('storage/' . $portfolio->certificate_image))) {
                $extension = pathinfo($portfolio->certificate_image, PATHINFO_EXTENSION);
                $newName = 'certificate.' . $extension;
                if (!copy(public_path('storage/' . $portfolio->certificate_image), $imagesDir . '/' . $newName)) {
                    throw new \Exception('Gagal menyalin gambar sertifikat');
                }
            }
            
            // Create ZIP file
            $zip = new \ZipArchive();
            $zipFilename = storage_path('app/temp/' . Str::slug($portfolio->project_name) . '-portfolio.zip');
            
            if ($zip->open($zipFilename, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Gagal membuat file ZIP');
            }
            
            // Add files to ZIP
            $this->addFolderToZip($tempDir, $zip, '');
            
            if ($zip->close() !== TRUE) {
                throw new \Exception('Gagal menutup file ZIP');
            }
            
            // Clean up temporary directory
            $this->deleteDirectory($tempDir);
            
            // Return ZIP file
            return response()->download($zipFilename, Str::slug($portfolio->project_name) . '-portfolio.zip')
                ->deleteFileAfterSend(true);
                
        } catch (\Exception $e) {
            // Clean up on error
            if (isset($tempDir) && is_dir($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            return redirect()->back()->with('error', 'Gagal membuat ZIP: ' . $e->getMessage());
        }
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
        $mainImagePath = '';
        if ($mainImage && file_exists(public_path('storage/' . $mainImage->image_path))) {
            $mainImagePath = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $mainImage->image_path)));
        }
        
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
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $image->image_path)));
                    $additionalImagesHTML .= '<div class="image-item">';
                    $additionalImagesHTML .= '<img src="' . $imageData . '" alt="' . ($image->title ?: 'Project Image') . '" class="additional-image">';
                    if ($image->title) {
                        $additionalImagesHTML .= '<h4 class="image-title">' . $image->title . '</h4>';
                    }
                    if ($image->description) {
                        $additionalImagesHTML .= '<p class="image-description">' . $image->description . '</p>';
                    }
                    $additionalImagesHTML .= '</div>';
                }
            }
            $html = str_replace('{{additional_images}}', $additionalImagesHTML, $html);
        } else {
            $html = str_replace('{{additional_images}}', '', $html);
        }

        // Personal information replacements for saved portfolio
        $html = str_replace('{{full_name}}', $portfolio->full_name ?? '', $html);
        $html = str_replace('{{email}}', $portfolio->email ?? '', $html);
        $html = str_replace('{{phone}}', $portfolio->phone ?? '', $html);
        $html = str_replace('{{linkedin}}', $portfolio->linkedin ?? '', $html);
        $html = str_replace('{{github}}', $portfolio->github ?? '', $html);
        $html = str_replace('{{website}}', $portfolio->website ?? '', $html);
        $html = str_replace('{{about_me}}', $portfolio->about_me ?? '', $html);
        $html = str_replace('{{education}}', $portfolio->education ?? '', $html);
        $html = str_replace('{{experience}}', $portfolio->experience ?? '', $html);
        $html = str_replace('{{skills}}', $portfolio->skills ?? '', $html);
        $html = str_replace('{{certifications}}', $portfolio->certifications ?? '', $html);

        // Certificate images for saved portfolio
        $certImagesHTML = '';
        if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
            foreach ($portfolio->certificate_images as $img) {
                if (file_exists(public_path('storage/' . $img))) {
                    $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $img)));
                    $certImagesHTML .= '<div class="cert-item"><img src="' . $imageData . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
                }
            }
        } elseif ($portfolio->certificate_image && file_exists(public_path('storage/' . $portfolio->certificate_image))) {
            // fallback to single legacy image
            $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $portfolio->certificate_image)));
            $certImagesHTML = '<div class="cert-item"><img src="' . $imageData . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
        }
        $html = str_replace('{{certificate_images}}', $certImagesHTML, $html);
        
        return '<!DOCTYPE html><html><head><style>' . $css . '</style></head><body>' . $html . '</body></html>';
    }

    /**
     * Generate HTML for portfolio optimized for document readers
     */
    private function generatePortfolioHTMLForDocumentReader($portfolio)
    {
        // Kembalikan ke template asli yang keren dengan base64 images
        $template = $portfolio->template;
        $html = $template->template_html;
        $css = $template->template_css;
        
        // Get main image as base64
        $mainImage = $portfolio->mainImage;
        $mainImagePath = '';
        if ($mainImage && file_exists(public_path('storage/' . $mainImage->image_path))) {
            $mainImagePath = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $mainImage->image_path)));
        }
        
        // Replace placeholders with actual data
        $html = str_replace('{{project_name}}', htmlspecialchars($portfolio->project_name), $html);
        $html = str_replace('{{project_title}}', htmlspecialchars($portfolio->project_title), $html);
        $html = str_replace('{{description}}', htmlspecialchars($portfolio->description), $html);
        $html = str_replace('{{project_image}}', $mainImagePath, $html);
        
        // Handle additional images as base64
        $additionalImages = $portfolio->images()->where('is_main', false)->get();
        if ($additionalImages->count() > 0) {
            $additionalImagesHTML = '';
            foreach ($additionalImages as $image) {
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $image->image_path)));
                    $additionalImagesHTML .= '<div class="image-item">';
                    $additionalImagesHTML .= '<img src="' . $imageData . '" alt="' . htmlspecialchars($image->title ?: 'Project Image') . '" class="additional-image">';
                    if ($image->title) {
                        $additionalImagesHTML .= '<h4 class="image-title">' . htmlspecialchars($image->title) . '</h4>';
                    }
                    if ($image->description) {
                        $additionalImagesHTML .= '<p class="image-description">' . htmlspecialchars($image->description) . '</p>';
                    }
                    $additionalImagesHTML .= '</div>';
                }
            }
            $html = str_replace('{{additional_images}}', $additionalImagesHTML, $html);
        } else {
            $html = str_replace('{{additional_images}}', '', $html);
        }

        // Personal information replacements
        $html = str_replace('{{full_name}}', htmlspecialchars($portfolio->full_name ?? ''), $html);
        $html = str_replace('{{email}}', htmlspecialchars($portfolio->email ?? ''), $html);
        $html = str_replace('{{phone}}', htmlspecialchars($portfolio->phone ?? ''), $html);
        $html = str_replace('{{linkedin}}', htmlspecialchars($portfolio->linkedin ?? ''), $html);
        $html = str_replace('{{github}}', htmlspecialchars($portfolio->github ?? ''), $html);
        $html = str_replace('{{website}}', htmlspecialchars($portfolio->website ?? ''), $html);
        $html = str_replace('{{about_me}}', htmlspecialchars($portfolio->about_me ?? ''), $html);
        $html = str_replace('{{education}}', htmlspecialchars($portfolio->education ?? ''), $html);
        $html = str_replace('{{experience}}', htmlspecialchars($portfolio->experience ?? ''), $html);
        $html = str_replace('{{skills}}', htmlspecialchars($portfolio->skills ?? ''), $html);
        $html = str_replace('{{certifications}}', htmlspecialchars($portfolio->certifications ?? ''), $html);

        // Certificate images as base64
        $certImagesHTML = '';
        if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
            foreach ($portfolio->certificate_images as $img) {
                if (file_exists(public_path('storage/' . $img))) {
                    $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $img)));
                    $certImagesHTML .= '<div class="cert-item"><img src="' . $imageData . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
                }
            }
        } elseif ($portfolio->certificate_image && file_exists(public_path('storage/' . $portfolio->certificate_image))) {
            $imageData = 'data:image/jpeg;base64,' . base64_encode(file_get_contents(public_path('storage/' . $portfolio->certificate_image)));
            $certImagesHTML = '<div class="cert-item"><img src="' . $imageData . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
        }
        $html = str_replace('{{certificate_images}}', $certImagesHTML, $html);
        
        // Buat HTML yang benar-benar hanya bisa dibuka di browser
        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>' . htmlspecialchars($portfolio->project_name) . ' - Portfolio</title>
    <style>
        ' . $css . '
        /* CSS yang hanya didukung browser modern */
        body {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        /* Paksa tampil di browser dengan CSS modern */
        @supports (display: grid) {
            .browser-only { display: block !important; }
        }
        
        @supports (backdrop-filter: blur(10px)) {
            .modern-browser { display: block !important; }
        }
        
        /* Hide content jika bukan browser */
        .document-reader-warning {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            text-align: center;
            padding: 50px 20px;
            font-family: Arial, sans-serif;
        }
        
        .browser-check {
            display: none;
        }
        
        /* CSS Grid dan Flexbox yang tidak didukung document reader */
        .portfolio-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
        }
        
        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        
        /* CSS Custom Properties yang tidak didukung document reader */
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        .btn {
            background: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        /* CSS yang akan rusak di document reader */
        .modern-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            transform: translateZ(0);
        }
    </style>
    <script>
        // Deteksi browser vs document reader
        function detectBrowser() {
            const isBrowser = (
                typeof window !== "undefined" &&
                typeof document !== "undefined" &&
                typeof navigator !== "undefined" &&
                typeof localStorage !== "undefined" &&
                typeof sessionStorage !== "undefined" &&
                typeof fetch !== "undefined" &&
                typeof Promise !== "undefined" &&
                window.CSS && window.CSS.supports &&
                window.CSS.supports("display", "grid") &&
                window.CSS.supports("backdrop-filter", "blur(10px)")
            );
            
            const isDocumentReader = (
                navigator.userAgent.indexOf("WPS") > -1 ||
                navigator.userAgent.indexOf("Office") > -1 ||
                navigator.userAgent.indexOf("Document") > -1 ||
                navigator.userAgent.indexOf("Word") > -1 ||
                navigator.userAgent.indexOf("Excel") > -1 ||
                navigator.userAgent.indexOf("PowerPoint") > -1 ||
                !window.CSS ||
                !window.CSS.supports ||
                !window.CSS.supports("display", "grid")
            );
            
            if (isDocumentReader || !isBrowser) {
                // Tampilkan warning dan sembunyikan konten
                document.querySelector(".document-reader-warning").style.display = "block";
                document.querySelector(".portfolio-container").style.display = "none";
                
                // Coba redirect ke browser jika mungkin
                setTimeout(() => {
                    alert("⚠️ PORTFOLIO INI HARUS DIBUKA DI BROWSER WEB!\n\n" +
                          "File ini dirancang khusus untuk browser seperti:\n" +
                          "• Google Chrome\n" +
                          "• Mozilla Firefox\n" +
                          "• Safari\n" +
                          "• Microsoft Edge\n\n" +
                          "Silakan buka file ini di browser untuk melihat portfolio dengan tampilan yang benar.");
                }, 1000);
            } else {
                // Browser detected, show content
                document.querySelector(".browser-check").style.display = "block";
            }
        }
        
        // Run detection when page loads
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", detectBrowser);
        } else {
            detectBrowser();
        }
        
        // Additional browser checks
        window.addEventListener("load", function() {
            // Test modern browser features
            if (!window.CSS.supports("display", "grid")) {
                document.querySelector(".document-reader-warning").style.display = "block";
                document.querySelector(".portfolio-container").style.display = "none";
            }
        });
    </script>
</head>
<body>
    <!-- Warning untuk document reader -->
    <div class="document-reader-warning">
        <div style="max-width: 500px; margin: 0 auto;">
            <h1 style="color: #dc3545; margin-bottom: 30px;">⚠️ Browser Required</h1>
            <div style="font-size: 18px; line-height: 1.6; color: #333;">
                <p><strong>Portfolio ini harus dibuka di browser web!</strong></p>
                <p>File ini dirancang khusus untuk browser modern seperti:</p>
                <ul style="text-align: left; margin: 20px 0;">
                    <li>🌐 Google Chrome</li>
                    <li>🦊 Mozilla Firefox</li>
                    <li>🧭 Safari</li>
                    <li>🔷 Microsoft Edge</li>
                </ul>
                <p style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                    <strong>Cara membuka:</strong><br>
                    1. Simpan file ini di HP<br>
                    2. Buka aplikasi Chrome/Firefox<br>
                    3. Ketik file:///path/to/file.html di address bar<br>
                    4. Atau buka file manager → pilih file → "Buka dengan Chrome"
                </p>
            </div>
        </div>
    </div>
    
    <!-- Browser check indicator -->
    <div class="browser-check" style="display: none;">
        <div style="position: fixed; top: 10px; right: 10px; background: #28a745; color: white; padding: 5px 10px; border-radius: 3px; font-size: 12px; z-index: 1000;">
            ✅ Browser Detected
        </div>
    </div>
    
    <!-- Portfolio content -->
    <div class="portfolio-container">
        ' . $html . '
    </div>
</body>
</html>';
    }

    /**
     * Create simple HTML that works well in document readers on mobile
     */
    private function createSimpleDocumentReaderHTML($portfolio)
    {
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($portfolio->project_name) . ' - Portfolio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
            color: #333;
        }
        .header h2 {
            font-size: 18px;
            margin: 0;
            color: #666;
            font-weight: normal;
        }
        .section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section h3 {
            font-size: 16px;
            margin: 0 0 10px 0;
            color: #333;
            background-color: #f5f5f5;
            padding: 8px;
            border-left: 4px solid #333;
        }
        .section p {
            margin: 5px 0;
            text-align: justify;
        }
        .contact-info {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin: 10px 0;
        }
        .contact-info p {
            margin: 3px 0;
        }
        .image-placeholder {
            background-color: #f0f0f0;
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            margin: 10px 0;
            color: #666;
        }
        .certificate-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 10px 0;
        }
        .certificate-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            min-width: 120px;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .skill-tag {
            background-color: #e9ecef;
            border: 1px solid #adb5bd;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        @media print {
            body { margin: 0; padding: 10px; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . htmlspecialchars($portfolio->full_name ?? $portfolio->project_name) . '</h1>
        <h2>' . htmlspecialchars($portfolio->project_title) . '</h2>
    </div>

    <div class="contact-info">
        <p><strong>Email:</strong> ' . htmlspecialchars($portfolio->email ?? '') . '</p>
        <p><strong>Telepon:</strong> ' . htmlspecialchars($portfolio->phone ?? '') . '</p>';
        
        if ($portfolio->linkedin) {
            $html .= '<p><strong>LinkedIn:</strong> ' . htmlspecialchars($portfolio->linkedin) . '</p>';
        }
        if ($portfolio->github) {
            $html .= '<p><strong>GitHub:</strong> ' . htmlspecialchars($portfolio->github) . '</p>';
        }
        if ($portfolio->website) {
            $html .= '<p><strong>Website:</strong> ' . htmlspecialchars($portfolio->website) . '</p>';
        }
        
        $html .= '</div>';

        // About Me Section
        if ($portfolio->about_me) {
            $html .= '<div class="section">
                <h3>Tentang Saya</h3>
                <p>' . nl2br(htmlspecialchars($portfolio->about_me)) . '</p>
            </div>';
        }

        // Education Section
        if ($portfolio->education) {
            $html .= '<div class="section">
                <h3>Pendidikan</h3>
                <p>' . nl2br(htmlspecialchars($portfolio->education)) . '</p>
            </div>';
        }

        // Experience Section
        if ($portfolio->experience) {
            $html .= '<div class="section">
                <h3>Pengalaman</h3>
                <p>' . nl2br(htmlspecialchars($portfolio->experience)) . '</p>
            </div>';
        }

        // Skills Section
        if ($portfolio->skills) {
            $skills = explode(',', $portfolio->skills);
            $html .= '<div class="section">
                <h3>Keterampilan</h3>
                <div class="skills-list">';
            foreach ($skills as $skill) {
                $skill = trim($skill);
                if ($skill) {
                    $html .= '<span class="skill-tag">' . htmlspecialchars($skill) . '</span>';
                }
            }
            $html .= '</div>
            </div>';
        }

        // Certifications Section
        if ($portfolio->certifications) {
            $html .= '<div class="section">
                <h3>Sertifikasi</h3>
                <p>' . nl2br(htmlspecialchars($portfolio->certifications)) . '</p>
            </div>';
        }

        // Project Description
        if ($portfolio->description) {
            $html .= '<div class="section">
                <h3>Tentang Proyek</h3>
                <p>' . nl2br(htmlspecialchars($portfolio->description)) . '</p>
            </div>';
        }

        // Images Section
        $images = $portfolio->images;
        if ($images->count() > 0) {
            $html .= '<div class="section">
                <h3>Galeri Proyek</h3>';
            
            foreach ($images as $image) {
                $html .= '<div class="image-placeholder">
                    <strong>' . htmlspecialchars($image->title ?: 'Gambar Proyek') . '</strong><br>';
                if ($image->description) {
                    $html .= htmlspecialchars($image->description) . '<br>';
                }
                $html .= '<em>[Gambar: ' . basename($image->image_path) . ']</em>
                </div>';
            }
            
            $html .= '</div>';
        }

        // Certificate Images
        if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
            $html .= '<div class="section">
                <h3>Dokumen Sertifikat</h3>
                <div class="certificate-grid">';
            foreach ($portfolio->certificate_images as $img) {
                $html .= '<div class="certificate-item">
                    <strong>Sertifikat</strong><br>
                    <em>[' . basename($img) . ']</em>
                </div>';
            }
            $html .= '</div>
            </div>';
        } elseif ($portfolio->certificate_image) {
            $html .= '<div class="section">
                <h3>Dokumen Sertifikat</h3>
                <div class="certificate-item">
                    <strong>Sertifikat</strong><br>
                    <em>[' . basename($portfolio->certificate_image) . ']</em>
                </div>
            </div>';
        }

        $html .= '</body>
</html>';

        return $html;
    }

    /**
     * Convert CSS to inline styles for better document reader compatibility
     */
    private function convertCSSToInline($css)
    {
        // Basic CSS to inline conversion
        $css = str_replace(['{', '}'], [' {', '} '], $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Add some basic responsive styles
        $additionalCSS = '
        @media print {
            body { margin: 0; padding: 20px; }
            .container { box-shadow: none; }
        }
        ';
        
        return $css . $additionalCSS;
    }

    /**
     * Generate HTML for portfolio with external images (absolute URLs)
     */
    private function generatePortfolioHTMLExternal($portfolio)
    {
        $template = $portfolio->template;
        $html = $template->template_html;
        $css = $template->template_css;
        
        // Get main image
        $mainImage = $portfolio->mainImage;
        $mainImagePath = '';
        if ($mainImage && file_exists(public_path('storage/' . $mainImage->image_path))) {
            $mainImagePath = url('storage/' . $mainImage->image_path);
        }
        
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
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    $imageUrl = url('storage/' . $image->image_path);
                    $additionalImagesHTML .= '<div class="image-item">';
                    $additionalImagesHTML .= '<img src="' . $imageUrl . '" alt="' . ($image->title ?: 'Project Image') . '" class="additional-image">';
                    if ($image->title) {
                        $additionalImagesHTML .= '<h4 class="image-title">' . $image->title . '</h4>';
                    }
                    if ($image->description) {
                        $additionalImagesHTML .= '<p class="image-description">' . $image->description . '</p>';
                    }
                    $additionalImagesHTML .= '</div>';
                }
            }
            $html = str_replace('{{additional_images}}', $additionalImagesHTML, $html);
        } else {
            $html = str_replace('{{additional_images}}', '', $html);
        }

        // Personal information replacements
        $html = str_replace('{{full_name}}', $portfolio->full_name ?? '', $html);
        $html = str_replace('{{email}}', $portfolio->email ?? '', $html);
        $html = str_replace('{{phone}}', $portfolio->phone ?? '', $html);
        $html = str_replace('{{linkedin}}', $portfolio->linkedin ?? '', $html);
        $html = str_replace('{{github}}', $portfolio->github ?? '', $html);
        $html = str_replace('{{website}}', $portfolio->website ?? '', $html);
        $html = str_replace('{{about_me}}', $portfolio->about_me ?? '', $html);
        $html = str_replace('{{education}}', $portfolio->education ?? '', $html);
        $html = str_replace('{{experience}}', $portfolio->experience ?? '', $html);
        $html = str_replace('{{skills}}', $portfolio->skills ?? '', $html);
        $html = str_replace('{{certifications}}', $portfolio->certifications ?? '', $html);

        // Certificate images with external URLs
        $certImagesHTML = '';
        if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
            foreach ($portfolio->certificate_images as $img) {
                if (file_exists(public_path('storage/' . $img))) {
                    $imageUrl = url('storage/' . $img);
                    $certImagesHTML .= '<div class="cert-item"><img src="' . $imageUrl . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
                }
            }
        } elseif ($portfolio->certificate_image && file_exists(public_path('storage/' . $portfolio->certificate_image))) {
            $imageUrl = url('storage/' . $portfolio->certificate_image);
            $certImagesHTML = '<div class="cert-item"><img src="' . $imageUrl . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
        }
        $html = str_replace('{{certificate_images}}', $certImagesHTML, $html);
        
        return '<!DOCTYPE html><html><head><style>' . $css . '</style></head><body>' . $html . '</body></html>';
    }

    /**
     * Generate HTML for portfolio with relative image paths (for ZIP)
     */
    private function generatePortfolioHTMLRelative($portfolio)
    {
        $template = $portfolio->template;
        $html = $template->template_html;
        $css = $template->template_css;
        
        // Get main image
        $mainImage = $portfolio->mainImage;
        $mainImagePath = '';
        if ($mainImage && file_exists(public_path('storage/' . $mainImage->image_path))) {
            $extension = pathinfo($mainImage->image_path, PATHINFO_EXTENSION);
            $mainImagePath = 'images/main_image.' . $extension;
        }
        
        // Replace placeholders with actual data
        $html = str_replace('{{project_name}}', $portfolio->project_name, $html);
        $html = str_replace('{{project_title}}', $portfolio->project_title, $html);
        $html = str_replace('{{description}}', $portfolio->description, $html);
        $html = str_replace('{{project_image}}', $mainImagePath, $html);
        
        // Handle additional images
        $additionalImages = $portfolio->images()->where('is_main', false)->get();
        if ($additionalImages->count() > 0) {
            $additionalImagesHTML = '';
            $imageCounter = 1;
            foreach ($additionalImages as $image) {
                if (file_exists(public_path('storage/' . $image->image_path))) {
                    $extension = pathinfo($image->image_path, PATHINFO_EXTENSION);
                    $imagePath = 'images/image_' . $imageCounter . '.' . $extension;
                    $additionalImagesHTML .= '<div class="image-item">';
                    $additionalImagesHTML .= '<img src="' . $imagePath . '" alt="' . ($image->title ?: 'Project Image') . '" class="additional-image">';
                    if ($image->title) {
                        $additionalImagesHTML .= '<h4 class="image-title">' . $image->title . '</h4>';
                    }
                    if ($image->description) {
                        $additionalImagesHTML .= '<p class="image-description">' . $image->description . '</p>';
                    }
                    $additionalImagesHTML .= '</div>';
                    $imageCounter++;
                }
            }
            $html = str_replace('{{additional_images}}', $additionalImagesHTML, $html);
        } else {
            $html = str_replace('{{additional_images}}', '', $html);
        }

        // Personal information replacements
        $html = str_replace('{{full_name}}', $portfolio->full_name ?? '', $html);
        $html = str_replace('{{email}}', $portfolio->email ?? '', $html);
        $html = str_replace('{{phone}}', $portfolio->phone ?? '', $html);
        $html = str_replace('{{linkedin}}', $portfolio->linkedin ?? '', $html);
        $html = str_replace('{{github}}', $portfolio->github ?? '', $html);
        $html = str_replace('{{website}}', $portfolio->website ?? '', $html);
        $html = str_replace('{{about_me}}', $portfolio->about_me ?? '', $html);
        $html = str_replace('{{education}}', $portfolio->education ?? '', $html);
        $html = str_replace('{{experience}}', $portfolio->experience ?? '', $html);
        $html = str_replace('{{skills}}', $portfolio->skills ?? '', $html);
        $html = str_replace('{{certifications}}', $portfolio->certifications ?? '', $html);

        // Certificate images with relative paths
        $certImagesHTML = '';
        $certCounter = 1;
        if ($portfolio->certificate_images && is_array($portfolio->certificate_images)) {
            foreach ($portfolio->certificate_images as $img) {
                if (file_exists(public_path('storage/' . $img))) {
                    $extension = pathinfo($img, PATHINFO_EXTENSION);
                    $imagePath = 'images/certificate_' . $certCounter . '.' . $extension;
                    $certImagesHTML .= '<div class="cert-item"><img src="' . $imagePath . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
                    $certCounter++;
                }
            }
        } elseif ($portfolio->certificate_image && file_exists(public_path('storage/' . $portfolio->certificate_image))) {
            $extension = pathinfo($portfolio->certificate_image, PATHINFO_EXTENSION);
            $imagePath = 'images/certificate.' . $extension;
            $certImagesHTML = '<div class="cert-item"><img src="' . $imagePath . '" alt="Sertifikat" style="max-width:150px; max-height:150px;"/></div>';
        }
        $html = str_replace('{{certificate_images}}', $certImagesHTML, $html);
        
        return '<!DOCTYPE html><html><head><style>' . $css . '</style></head><body>' . $html . '</body></html>';
    }

    /**
     * Add folder to ZIP recursively
     */
    private function addFolderToZip($dir, $zip, $zipPath)
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $filePath = $dir . '/' . $file;
                $zipFilePath = $zipPath . $file;
                
                if (is_dir($filePath)) {
                    $zip->addEmptyDir($zipFilePath);
                    $this->addFolderToZip($filePath, $zip, $zipFilePath . '/');
                } else {
                    $zip->addFile($filePath, $zipFilePath);
                }
            }
        }
    }

    /**
     * Delete directory recursively
     */
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    /**
     * Generate public URL for portfolio sharing
     */
    private function generatePublicUrl($portfolio)
    {
        // Generate unique URL based on portfolio name and ID
        $slug = Str::slug($portfolio->project_name);
        $uniqueId = substr(md5($portfolio->id . time()), 0, 8);
        return $slug . '-' . $uniqueId;
    }

    /**
     * Make portfolio public
     */
    public function makePublic($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->is_public = true;
        $portfolio->save();

        return redirect()->back()->with('success', 'Portfolio berhasil dibuat public!');
    }

    /**
     * Make portfolio private
     */
    public function makePrivate($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->is_public = false;
        $portfolio->save();

        return redirect()->back()->with('success', 'Portfolio berhasil dibuat private!');
    }

    /**
     * Show public portfolio
     */
    public function showPublic($publicUrl)
    {
        $portfolio = Portfolio::with(['template', 'images'])
            ->where('public_url', $publicUrl)
            ->where('is_public', true)
            ->firstOrFail();

        // Generate HTML for public view
        $html = $this->generatePortfolioHTMLForDocumentReader($portfolio);
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Copy public link
     */
    public function copyPublicLink($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        
        if (!$portfolio->is_public) {
            return redirect()->back()->with('error', 'Portfolio belum dibuat public!');
        }

        $publicUrl = url('/portfolio/' . $portfolio->public_url);
        
        return response()->json([
            'success' => true,
            'url' => $publicUrl,
            'message' => 'Link berhasil dicopy!'
        ]);
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