<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CED</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <base href="/public">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
   <!-- ======= Sidebar ======= -->
    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
   
  <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
        <h2>Bonjour!</h2>

@if($user->sex === 'female')
    <h5>Madame {{ $user->name }}</h5>
@else
    <h5>Monsieur {{ $user->name }}</h5>
@endif


        </div>


    </div>
   
  </ul>
</aside><!-- End Sidebar-->
   
   <!-- End Sidebar-->





   <!-- ======= Header ======= -->
   <header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a class="logo d-flex align-items-center">
    <img src="assets/img/logo.png" alt="">
    <span class="d-none d-lg-block">Centre d’Etudes Doctorales</span>
  </a>
 
</div><!-- End Logo -->
<i class="bi bi-list toggle-sidebar-btn"></i>
<!-- End Search Bar -->
    



    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
 
        <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
  <div id="profile-letter" class="profile-letter"></div>
  <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name }}</span>
</a><!-- End Profile Image Icon -->


          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{$user->name}}</h6>
              <span>{{$user->tele}}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
  <a class="dropdown-item d-flex align-items-center" href="{{ route('rapporteurs.profil') }}">
    <i class="bi bi-person"></i>
    <span>My Profile</span>
  </a>
</li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('rapporteurs.compte') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
      </ul>
    </nav><!-- End Icons Navigation -->
  </header><!-- End Header -->

  <!-- ======= Main Content ======= -->

<main id="main" class="main">



  @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
  @endif

  <section class="section profile">
    <div class="row">
      

      <div class="col-xl-19">

        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Général</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Modifier Profil</button>
              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Changer Mot de Passe</button>
              </li>

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade show active profile-overview" id="profile-overview">
                
                <h5 class="card-title"> </h5>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Nom et Prénom</div>
                  <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email</div>
                  <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Téléphone</div>
                  <div class="col-lg-9 col-md-8">{{ $user->tele }}</div>
                </div>

              </div>

              <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form method="POST" action="{{ route('profile.update', ['user' => $user]) }}" enctype="multipart/form-data">
                  @csrf
                 

                  <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="email" type="text" class="form-control" value="{{ $user->email }}">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Téléphone</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="tele" type="text" class="form-control" value="{{ $user->tele }}">
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
                <!-- End Change Password Form -->

              </div>

            </div><!-- End Tab Content -->

          </div><!-- End Card Body -->

        </div><!-- End Card -->

      </div><!-- End Col -->

    </div><!-- End Row -->

  </section><!-- End Profile Section -->

</main><!-- End Main -->
<style>
  .profile-letter {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #4154f1; /* Vous pouvez changer la couleur selon vos besoins */
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: bold;
}
</style>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var userName = "{{ $user->name }}"; // Utilisez la variable contenant le nom de l'utilisateur
    var firstLetter = userName.charAt(0).toUpperCase();
    document.getElementById("profile-letter").textContent = firstLetter;
  });
</script>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; <strong><span>Faculté des Sciences et Techniques 2024</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
