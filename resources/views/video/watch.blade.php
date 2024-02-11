<!DOCTYPE html>
<html>
<head>
    <title>{{ $videoTitle }}</title>
</head>
<body>
<h2>{{ $videoTitle }}</h2>
<video controls>
    <source src="{{ $videoUrl }}" type="video/mp4">
    Your browser does not support the video tag.
</video>
</body>
</html>
