<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 100%;
            background-color: #fff;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .video-container {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>{{ $data['name'] }}</h2>
    <div class="video-container">
        <iframe src="{{ $data['player_embed_url'] }}" allowfullscreen></iframe>
    </div>
</div>

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
