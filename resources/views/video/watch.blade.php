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
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .video-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 16:9 aspect ratio */
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
    <div>
        <label for="speed">Playback Speed:</label>
        <select id="speed">
            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected>Normal</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="2">2x</option>
        </select>
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
    function changeSpeed(speed) {
        player.setPlaybackRate(speed).then(function () {
            console.log('Playback speed changed to ' + speed);
        }).catch(function (error) {
            console.error('Error changing playback speed:', error);
        });
    }
    document.getElementById('speed').addEventListener('change', function() {
        var speed = parseFloat(this.value);
        changeSpeed(speed);
    });
</script>
</body>
</html>
