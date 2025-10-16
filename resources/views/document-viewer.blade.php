<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }}'s Documents</title>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border: #dee2e6;
            --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
            padding: 0;
            margin: 0;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .page-title { margin: 2rem 0 1rem; font-size: 1.8rem; color: var(--dark); }
        .page-subtitle { font-size: 1rem; color: var(--gray); margin-bottom: 2rem; }
        .documents-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }
        .document-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .document-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }
        .document-preview {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border);
            overflow: hidden;
            padding: 10px;
        }
        .document-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .document-preview iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        .document-preview .file-icon {
            font-size: 4rem;
            color: var(--gray);
        }
        .document-info { padding: 1rem; flex: 1; display: flex; flex-direction: column; }
        .document-name { font-weight: 600; font-size: 1rem; margin-bottom: 6px; }
        .document-meta { color: var(--gray); font-size: 0.85rem; margin-bottom: 1rem; }
        .download-btn {
            padding: 8px 14px;
            background-color: var(--primary);
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
            transition: var(--transition);
            margin-top: auto;
        }
        .download-btn:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .documents-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Attachments for Project: {{ $project->name ?? 'Untitled' }}</h1>
        <div class="page-subtitle">Project Code: {{ $project->code }}</div>

        <div class="documents-container">
            @forelse ($files as $file)
                <div class="document-card">
                    <div class="document-preview">
                        @if(Str::startsWith($file['mime_type'], 'image/'))
                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}">
                        @elseif($file['mime_type'] === 'application/pdf')
                            <iframe src="{{ $file['url'] }}"></iframe>
                        @else
                            <div class="file-icon">📄</div>
                        @endif
                    </div>
                    <div class="document-info">
                        <div class="document-name">{{ $file['name'] }}</div>
                        <div class="document-meta">{{ $file['mime_type'] }}</div>

                        <a class="download-btn" href="{{ route('attachments.download', ['path' => $file['path']]) }}">
                            @if($file['mime_type'] === 'application/pdf')
                                Download PDF
                            @else
                                Download
                            @endif
                        </a>
                    </div>
                </div>
            @empty
                <div class="document-card" style="text-align:center; padding:2rem;">
                    <div class="file-icon" style="font-size:3rem; margin-bottom:1rem;">📂</div>
                    <p>No attachments found for this project.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
