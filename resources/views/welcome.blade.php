<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>BiteRush</title>
  <script>
    setTimeout(function () {
      window.location.href = "{{ url('/home') }}";
    }, 2500);
  </script>
  <style>
    body {
      margin: 0;
      background: #fff9f2;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    img {
      width: 400px;
      animation: bounceInRotate 1.8s ease-in-out forwards,
                 pulseGlow 1.8s ease-in-out 1.9s infinite;
    }

    @keyframes bounceInRotate {
      0% {
        opacity: 0;
        transform: scale(0.3) rotate(-120deg);
      }
      50% {
        opacity: 1;
        transform: scale(1.05) rotate(15deg);
      }
      70% {
        transform: scale(0.95) rotate(-5deg);
      }
      100% {
        transform: scale(1) rotate(0deg);
      }
    }

    @keyframes pulseGlow {
      0%, 100% {
        filter: drop-shadow(0 0 0px #ffa726);
      }
      50% {
        filter: drop-shadow(0 0 20px #ff6f00);
      }
    }
  </style>
</head>
<body>
  <img src="{{ asset('images/biterush.png') }}" alt="BiteRush Logo">
</body>
</html>
