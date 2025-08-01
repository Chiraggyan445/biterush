<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
</head>
<body>
  <div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><b>Sign Up</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('registration.post') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="modal-body">
          @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <div class="mb-3">
            <input type="text" class="form-control" name="name" placeholder="Name" >
          </div>

          <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" >
          </div>

          <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" >
          </div>

          <div class="mb-3">
            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" >
          </div>
        </div>

         <div class="text-center mb-3" style="color: gray;">OR</div>

         <a href="{{ route('google.login') }}" class="btn btn-danger w-50 mb-3" style="margin-left: 25%;">
          <i class="bi bi-google" style="margin-right: 20px;"></i> Sign in with Google
        </a>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Register</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

        <p style="margin-top: 10px; text-align:center;">
          Already have an account?
          <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
        </p>
      </form>

    </div>
  </div>
</div>

<script>
  function openLoginModal() {
  const modal = new bootstrap.Modal(document.getElementById('loginModal'));
  modal.show();
}

</script>

</body>
</html>