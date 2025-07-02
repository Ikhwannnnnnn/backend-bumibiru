@extends('auth.authApp')
@section('title')
BumiBiru | Buat Akun
@endsection
@section('content')
<section class="bg-light py-3 py-md-5 d-flex justify-content-center align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <div class="card border border-light-subtle rounded-3 shadow-sm">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="text-center mb-3">
              <a href="#!">
                <img src="{{ asset('img/logo-iwan.png') }}" alt="logo-iwan" width="128" height="128">
              </a>
            </div>
            <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Buat akun Anda</h2>
            @if(Session::has('error'))
                <div class="alert alert-danger" role="alert" style="text-align: center">{{ Session::get('error') }}</div>
            @endif
            @if(Session::has('success'))
                <div class="alert alert-success" role="alert" style="text-align: center">{{ Session::get('success') }}</div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row gy-2 overflow-hidden">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Nama" required>
                            <label for="username" class="form-label">Username</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                            <label for="email" class="form-label">Email</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                            <label for="password" class="form-label">Password</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Password Again" required>
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid my-3">
                            <button id="registerButton" class="btn btn-primary btn-lg" type="submit" disabled>Daftar</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <p class="m-0 text-secondary text-center">Sudah memiliki akun? <a href="{{ route('login') }}" class="link-primary text-decoration-none">Masuk</a></p>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  @media (max-width: 768px) {
    section {
      display: flex;
      justify-content: center;
      align-items: center;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input');
    const registerButton = document.getElementById('registerButton');

    function toggleRegisterButton() {
      let allFilled = true;
      inputs.forEach(input => {
        if (input.value.trim() === '') {
          allFilled = false;
        }
      });
      if (allFilled) {
        registerButton.removeAttribute('disabled');
      } else {
        registerButton.setAttribute('disabled', 'true');
      }
    }

    inputs.forEach(input => {
      input.addEventListener('input', toggleRegisterButton);
    });
  });
</script>
@endsection
