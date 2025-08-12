<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BiteRush</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      background: #000;
      font-family: 'Segoe UI', sans-serif;
    }

    video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      z-index: 1;
    }

    .overlay-content {
      position: relative;
      z-index: 2;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 1rem;
      box-sizing: border-box;
    }

    .logo {
      font-size: clamp(2rem, 10vw, 6rem);
      font-weight: 900;
      color: #FFC107;
      text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6);
      line-height: 1.1;
    }
  </style>
</head>
<body>

  <video id="splashVideo" autoplay muted playsinline>
    <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
    Your browser does not support the video tag.
  </video>


  <script>
    document.getElementById('splashVideo').addEventListener('ended', function () {
      window.location.href = "{{ url('/home') }}";
    });

    setTimeout(() => {
      window.location.href = "{{ url('/home') }}";
    }, 10000);
  </script>

</body>
</html>
