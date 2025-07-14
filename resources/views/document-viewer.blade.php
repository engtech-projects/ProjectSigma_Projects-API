<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    @foreach ($publicFilePaths as $file)
        <iframe src="{{ $file }}" width="100%" height="600px" style="margin-bottom: 20px;"></iframe>
    @endforeach
</body>
</html>
