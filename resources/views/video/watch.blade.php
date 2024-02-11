<!DOCTYPE html>
<html>
<head>
    <title>{{ $videoTitle }}</title>
</head>
<body>
<h2>{{ $videoTitle }}</h2>
    <iframe src="{{ $videoUrl }}" width="200px" height="200px" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

    <script src="{{$videoUrl}}"></script>
    <script>
        var iframe = document.querySelector('iframe');
        var player = new Vimeo.Player(iframe);

        player.on('play', function() {
            console.log('Played the video');
        });

        player.getVideoTitle().then(function(title) {
            console.log('title:', title);
        });
    </script>
</body>
</html>
