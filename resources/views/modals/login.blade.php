<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><b>Sign In</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      @if($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif

      <form action="{{ route('login.post') }}" method="POST">
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
            <input type="email" class="form-control" name="email" placeholder="Email" >
          </div>

          <div class="mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" >
          </div>
        </div>

        <div class="text-center mb-3" style="color: gray;">OR</div>

         <a href="{{ route('google.login') }}" class="btn btn-danger w-50 mb-3" style="margin-left: 25%;">
          <i class="bi bi-google"></i>Sign in with Google
        </a>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Login</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

        <p style="margin-top: 10px; text-align:center;">
          Get in to have something amazing!
          <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
        </p>
      </form>

    </div>
  </div>
</div>