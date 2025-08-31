@extends('layouts.layoutLogin')

@section('content')
<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

        {{-- <div class="d-flex justify-content-center py-4">
          <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
            <span class="d-none d-lg-block">Center d'Etude Doctoral</span>
          </a>
        </div><!-- End Logo --> --}}
        <div class="d-flex justify-content-center py-4">
            <div  class="logo1 d-flex align-items-center w-auto">
                <img src="assets/img/logo.png" alt="">
                 <span class="d-none d-lg-block">Centres d’Etudes Doctorales</span>
            </div>
            </div><!-- End Logo -->

        <div class="card mb-3">

          <div class="card-body">

            <div class="pt-4 pb-2">
              <h5 class="card-title text-center pb-0 fs-4"></h5>
            </div>

            <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation" novalidate>
              @csrf

              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <div class="input-group has-validation">
                  
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-12">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required autocomplete="current-password">
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">Remember me</label>
                </div>
              </div>

              <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Login</button>
              </div>

              <div class="col-12">
                <p class="small mb-0"><a href="{{ route('register') }}">Oublie mot de passe</a></p>
              </div>
            </form>

          </div>
        </div>

        

      </div>
    </div>
  </div>
</section>
@endsection