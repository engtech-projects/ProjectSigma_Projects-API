<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->name }}'s documents</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .attachment { margin-bottom: 2rem; }
        iframe, img { max-width: 100%; height: auto; border: 1px solid #ccc; }
        a.download-link { display: inline-block; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <h1>Attachments for Project: {{ $project->name ?? 'Untitled' }}</h1>

    @foreach ($files as $file)
        <div class="attachment">
            <h3>{{ $file['name'] }}</h3>
            @if(Str::startsWith($file['mime_type'], 'image/'))
                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}">
                <a class="download-link" href="{{ $file['url'] }}" target="_blank" download>Download</a>
            @elseif($file['mime_type'] === 'application/pdf')
                <iframe src="{{ $file['url'] }}" width="100%" height="600px"></iframe>
                <a class="download-link" href="{{ $file['url'] }}" target="_blank" download>Download</a>
            @else
                <p>Unsupported preview for file type: {{ $file['mime_type'] }}</p>
                <a class="download-link" href="{{ $file['url'] }}" target="_blank" download>Download</a>
            @endif
        </div>
    @endforeach
</body>
</html>
