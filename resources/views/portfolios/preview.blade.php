<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Portfolio</title>
    <style>
        {!! $template->template_css !!}
    </style>
</head>
<body>
    {!! str_replace([
        '{{project_name}}',
        '{{project_title}}',
        '{{description}}',
        '{{project_image}}',
        '{{additional_images}}'
    ], [
        $portfolioData['project_name'] ?? 'Nama Proyek',
        $portfolioData['project_title'] ?? 'Judul Proyek',
        $portfolioData['description'] ?? 'Deskripsi proyek',
        $portfolioData['project_image'] ? asset('storage/' . $portfolioData['project_image']) : 'https://via.placeholder.com/800x400?text=No+Image',
        ''
    ], $template->template_html) !!}
</body>
</html>
