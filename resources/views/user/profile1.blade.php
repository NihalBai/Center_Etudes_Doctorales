<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
<base href="/public">

@extends($layoutFile)
@section('content')


<main id="main" class="main">

<div class="pagetitle">
  <h1>Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Utilisateur</li>
      <li class="breadcrumb-item ">Profile</li>
    </ol>
  </nav>
</div><!-- End Page Title -->
@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">
      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

          <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" class="rounded-circle">
          <h2>{{$user->name}}</h2>
          <h3>{{$user->type}}</h3>
          
        </div>
      </div>

    </div>

    <div class="col-xl-8">

      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">

            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">General</button>
            </li>

            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Modifier Profile</button>
            </li>


            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Changer Mot de Passe</button>
            </li>

          </ul>
          <div class="tab-content pt-2">

            <div class="tab-pane fade show active profile-overview" id="profile-overview">
              
              <h5 class="card-title">Profile Details</h5>

              <div class="row">
                <div class="col-lg-3 col-md-4 label "> Nom et Prenom </div>
                <div class="col-lg-9 col-md-8">{{$user->name}}</div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Email</div>
                <div class="col-lg-9 col-md-8">{{$user->email}}</div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Telephone</div>
                <div class="col-lg-9 col-md-8">{{$user->tele}}</div>
              </div>

            </div>

            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

              <!-- Profile Edit Form -->
              <form method="POST" action="{{ route('profile.update', ['user' => $user]) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row mb-3">
        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
        <div class="col-md-8 col-lg-9">
        <img id="previewImage" src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://via.placeholder.com/150' }}" alt="Profile" style="max-width: 150px;">
            <div class="pt-2">
                <label for="profileImage" class="btn btn-primary btn-sm" title="Upload new profile image">
                    <i class="bi bi-upload"></i> Télécharger une nouvelle image de profil
                    <input type="file" id="profileImage" name="profile_photo" style="display: none;" onchange="previewFile()">
                </label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label>
        <div class="col-md-8 col-lg-9">
            <input name="email" type="text" class="form-control" value="{{$user->email}}">
        </div>
    </div>
    <div class="row mb-3">
        <label for="company" class="col-md-4 col-lg-3 col-form-label">Telephone</label>
        <div class="col-md-8 col-lg-9">
            <input name="tele" type="text" class="form-control" value="{{$user->tele}}">
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form><!-- End Profile Edit Form -->

<script>
    function previewFile() {
        var preview = document.getElementById('previewImage');
        var file = document.getElementById('profileImage').files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
</script>
<!-- End Profile Edit Form -->

            </div>

            <div class="tab-pane fade pt-3" id="profile-change-password">
                 <!-- Change Password Form -->
                 <form method="POST" action="{{ route('password.update') }}">
    @csrf

    <div class="row mb-3">
        <label for="current_password" class="col-md-4 col-lg-3 col-form-label">{{ __('Current Password') }}</label>
        <div class="col-md-8 col-lg-9">
            <input id="current_password" class="form-control" type="password" name="current_password" required autocomplete="current-password">
        </div>
    </div>

    <div class="row mb-3">
        <label for="password" class="col-md-4 col-lg-3 col-form-label">{{ __('New Password') }}</label>
        <div class="col-md-8 col-lg-9">
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        </div>
    </div>

    <div class="row mb-3">
        <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">{{ __('Confirm Password') }}</label>
        <div class="col-md-8 col-lg-9">
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
    </div>
</form>



                </div>
          </div><!-- End Bordered Tabs -->

        </div>
      </div>

    </div>
  </div>
</section>



</main><!-- End #main -->


@endsection