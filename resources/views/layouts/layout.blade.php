<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CED</title>
  <meta content="" name="description">
  <meta content="" name="keywords">



  
  <!-- Favicons -->

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <!--  -->

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="https://fontawesome.com/icons/square-poll-horizontal?f=classic&s=regular" rel="stylesheet">
  
  
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
<base href="/public">
<body>

@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Centre d’Etudes Doctorales</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
      @csrf
    
    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
</div>
        <div class="search-bar1">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="table" class="form-select" >
        <option value="membres">Membres</option>
        <option value="affiliations">Affiliations</option>
        <!-- Add more options for other tables if needed -->
    <!-- </select>
      </form>
    </div>End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        {{-- <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon--> --}}

        <li class="nav-item dropdown">

          {{-- <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <img src="/assets/img/notification-icon.png" alt="Notification icon"  class=""> 
            <!-- <i class="bi bi-bell"></i> -->
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon --> --}}

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            {{-- <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-info-circle text-primary"></i>
              <div>
                <h4>Dicta reprehenderit</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>4 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>
            <li class="dropdown-footer">
              <a href="#">Show all notifications</a>
            </li> --}}

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          {{-- <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <!-- <i class="fa-regular fa-square-poll-horizontal"></i> -->
         <img src="/assets/img/result-Icon.png" alt="Result icon"  class=""> 
            <!-- <i class="bi bi-chat-left-text"></i> -->
            <span class="badge bg-success badge-number">3</span>
          </a><!-- End Messages Icon --> --}}

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            {{-- <li class="dropdown-header">
              You have 3 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-1.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Maria Hudson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>4 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-2.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>Anna Nelson</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>6 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="message-item">
              <a href="#">
                <img src="assets/img/messages-3.jpg" alt="" class="rounded-circle">
                <div>
                  <h4>David Muldon</h4>
                  <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                  <p>8 hrs. ago</p>
                </div>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li> --}}

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">
@if($user)
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{$user->name}}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{$user->name}}</h6>
              <span>{{$user->type}}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            @endif
            <li>
              <a class="dropdown-item d-flex align-items-center" href="/profile">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
    <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i>
        <span>Sign Out</span>
    </a>
</li>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>


          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
 <!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link collapsed" href="dashboard">
      <i class="bi bi-grid"></i>
        <span>Acceuil</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
    <a class="nav-link collapsed{{ Request::is('demandes*') ? 'active' : '' }} " href="{{ route('demandes.index') }}">
      <i class="ri-draft-line"></i><span>Cree Domandes</span>
      </a>
    </li>

    <li class="nav-item">
    <a class="nav-link collapsed {{ Request::is('doctorants*') ? 'active' : '' }}" href="{{ route('doctorants.index') }}">
      <i class="ri-account-circle-line"></i><span>Doctorants</span>
      </a>
    </li>

    <li class="nav-item">
    <a class="nav-link collapsed {{ Request::is('membres*', 'ajouter-membre') ? 'active' : '' }}" href="{{ route('membres.index') }}">
    <i class="ri-account-circle-fill"></i><span>Membres</span>
      </a>
    </li>

    <!-- <li class="nav-item">
    <a class="nav-link collapsed {{ Request::is('commissions*') ? 'active' : '' }}" href="{{ route('commissions.commissions') }}">
      <i class="ri-checkbox-multiple-line"></i><span>Commision des theses</span>
      </a>
    </li> -->

    <!-- <li class="nav-item">
    <a class="nav-link collapsed {{ Request::is('rapporteur/create*') ? 'active' : '' }}" href="{{ route('rapporteurs.create') }}">
      <i class="ri-contacts-fill"></i><span>Desination des Rapporteurs</span>
      </a>
    </li> -->

    <!-- <li class="nav-item">
    <a class="nav-link collapsed {{ Request::is('rapporteurs*') ? 'active' : '' }}" href="{{ route('rapporteurs.index') }}">

      <i class="ri-contacts-line"></i><span>Rapporteurs</span>
      </a>
    </li> -->

    <li class="nav-item">
      <a class="nav-link collapsed {{ Request::is('creesoutenance*') ? 'active' : '' }}" href="{{ route('soutenance.index') }}">
      <i class="bi bi-journal"></i><span>Cree avis de Soutenonce</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed {{ Request::is('avisdesoutenance*') ? 'active' : '' }}" href="{{ route('soutenance.liste') }}">
      <i class="bi bi-card-checklist"></i> <span>Liste des Soutenonces</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed {{ Request::is('Resultat*', 'resultats*','autre-information*') ? 'active' : '' }}" href="{{ route('resultats.index') }}">
        <i class="bi bi-card-checklist"></i> <span>Resultat</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed{{ Request::is('Diplome*', 'diplome*','final-page*') ? 'active' : '' }}" href="\Diplome">
      <i class="bi bi-file-earmark-check"></i><span>Diplome</span>
      </a>
    </li>
  </ul>
</aside><!-- End Sidebar-->




    @yield('content')


  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
    Copyright  &copy; <strong><span>Faculté des Sciences et Techniques</span></strong>. All Rights Reserved
    </div>
  </footer><!-- End Footer -->

  <!-- <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a> -->

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