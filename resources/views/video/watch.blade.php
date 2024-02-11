<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['name'] }}</title>
</head>
<body>
<h2>{{ $data['name'] }}</h2>
    <iframe src="{{ $data['player_embed_url'] }}" width="{{$data['width']}}" height="{{$data['height']}}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

    <script src="{{$data['player_embed_url']}}"></script>
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
