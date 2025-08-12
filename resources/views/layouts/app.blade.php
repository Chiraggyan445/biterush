<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'BiteRush')</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
  <style>
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 70px;
      background: linear-gradient(to right, #ff7043, #ffacbc);
      display: flex;
      justify-content: space-around;
      align-items: center;
      border-top-left-radius: 25px;
      border-top-right-radius: 25px;
      box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.2);
      z-index: 9999;
    }
    .bottom-nav a {
      color: #fff;
      text-decoration: none;
      font-size: 0.85rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .bottom-nav a:hover {
      color: black;
    } 
    .bottom-nav i {
      font-size: 1.3rem;
      margin-bottom: 3px;
    }
    .cart-indicator {
      position: relative;
    }
    .cart-badge {
      position: absolute;
      top: -6px;
      right: -10px;
      background: #d32f2f;
      color: white;
      font-size: 0.7rem;
      padding: 2px 6px;
      border-radius: 50%;
    }
  </style>

  {{-- Bottom Navigation --}}
  <div class="bottom-nav">
    <a href="{{ url('/home') }}">
      <i class="bi bi-house-door-fill"></i>
      Home
    </a>

    <a href="{{ route('all-meals') }}">
      <i class="bi bi-egg-fried"></i>
      Meals
    </a>

    <a href="{{ route('cart.show') }}" class="cart-indicator position-relative">
      <i class="bi bi-cart3"></i>
      Cart
      <span class="cart-badge">
        {{ count(session('cart', [])) }}
      </span>
    </a>

    <a href="{{ route('search') }}">
      <i class="bi bi-geo-alt"></i>
      City
    </a>

    <a href="{{ route('admin.login') }}">
      <i class="bi bi-person-lock"></i>
      Admin
    </a>

    @if(Auth::check() && Auth::user()->role === 'customer')
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i>
        Logout
      </a>
    @endif
  </div>

  {{-- Hidden logout form --}}
  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
  </form>

  {{-- Page Content --}}
  @yield('content')

  {{-- JS --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  {{-- SweetAlert on login required --}}
  @if(session('login_required'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
          icon: 'warning',
          title: 'Login Required',
          text: "{{ session('login_required') }}",
          confirmButtonText: 'Login Now'
        }).then((result) => {
          if (result.isConfirmed) {
            const modal = new bootstrap.Modal(document.getElementById('loginModal'));
            modal.show();
          }
        });
      });
    </script>
  @endif

  {{-- Optional button animation for add to cart or place order --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const forms = document.querySelectorAll('.order-form');
      forms.forEach(form => {
        form.addEventListener('submit', function (e) {
          const button = form.querySelector('.order-btn');
          const btnText = button.querySelector('.btn-text');

          button.disabled = true;
          button.classList.remove('btn-outline-primary');
          button.classList.add('btn-warning');
          btnText.textContent = 'Adding...';

          setTimeout(() => {
            btnText.textContent = 'Added!';
            button.classList.remove('btn-warning');
            button.classList.add('btn-success');
          }, 500);
        });
      });
    });
  </script>

  @stack('scripts')

</body>
</html>
