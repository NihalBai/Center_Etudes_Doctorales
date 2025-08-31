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
   <aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    @foreach($data as $item)
    <li class="nav-item">
      <p class="nav-link " >
        <span><i class="bi bi-journal-bookmark"></i> {{ $item['these']['titreOriginal'] }}</span>
      </p>
    </li>
    @endforeach
  </ul>
</aside><!-- End Sidebar-->





  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="#" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Centre d’Etudes Doctorales</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
<!-- End Search Bar -->
    



    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
  <div id="profile-letter" class="profile-letter"></div>
  <span class="d-none d-md-block dropdown-toggle ps-2">{{ $rapporteur->name }}</span>
</a><!-- End Profile Image Icon -->


          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{$rapporteur->name}}</h6>
              <span>{{$rapporteur->tele}}</span>
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
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
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

  <div class="card">
            <div class="card-body" >
              <h5 class="card-title">Bienvenue! </h5>
              <h6>Merci pour votre participation</h6>
            </div>
          </div>
   


          <section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Thèses Assignées</h5>
          <div class="accordion accordion-flush" id="accordionFlushExample">
            @foreach ($data as $item)
              <div class="accordion-item">
                <h2 class="accordion-header" id="flush-heading{{ $loop->index }}">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $loop->index }}" aria-expanded="false" aria-controls="flush-collapse{{ $loop->index }}">
                    <i class="bi bi-journal-bookmark"></i>&nbsp; &nbsp;Thèse: {{ $item['these']['titreOriginal'] }}
                  </button>
                </h2>
                <div id="flush-collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{ $loop->index }}" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <section class="section">
                      <div class="card mb-3 shadow-sm rounded">
                        <div class="card-body">
                          <h5 class="card-title">Informations <i class="bi bi-info-circle"></i></h5>
                          <p class="card-text"><i class="bi bi-person"></i> Doctorant: {{ $item['these']['nom'] }} {{ $item['these']['prenom'] }}</p>
                          <p><i class="bi bi-calendar3"></i> Date de Naissance : {{ \Carbon\Carbon::parse($item['these']['date_de_naissance'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                          <p class="card-text"><i class="bi bi-building"></i> Structure de Recherche: {{ $item['these']['structure_recherche'] }}</p>
                          <p><i class="bi bi-building"></i> Formation Doctorale : {{ $item['these']['formation'] }}</p>

                          <p class="card-text"><i class="bi bi-calendar"></i> Date de Première Inscription: {{ \Carbon\Carbon::parse($item['these']['date_premiere_inscription'])->format('Y') }}-{{ \Carbon\Carbon::parse($item['these']['date_premiere_inscription'])->addYear()->format('Y') }}</p>
                          <p><i class="bi bi-credit-card"></i> CINE : {{ $item['these']['cine'] }}</p>
                          <p><i class="bi bi-envelope-fill"></i> Email du Doctorant : {{ $item['these']['email'] }}</p>

                          <p><i class="bi bi-person-circle"></i> Encadrant : {{ $item['these']['encadrant_nom'] }} {{ $item['these']['encadrant_prenom'] }}</p>
                          <p><i class="bi bi-envelope-fill"></i> Email de l'encadrant : {{ $item['these']['encadrant_email'] }}</p>
                          <p class="card-text"><i class="bi bi-file-earmark-text"></i> Nombre de Publications (Article): {{ $item['these']['nombre_publications_article'] }}</p>
                          <p class="card-text"><i class="bi bi-file-earmark-text"></i> Nombre de Publications (Conférence): {{ $item['these']['nombre_publications_conference'] }}</p>
                          <p class="card-text"><i class="bi bi-megaphone"></i> Nombre de Communications: {{ $item['these']['nombre_communications'] }}</p>
                          <p><i class="bi bi-calendar3"></i> Session de : {{ \Carbon\Carbon::parse($item['these']['session_date'])->locale('fr')->isoFormat('D MMMM YYYY') }}</p>
                          <h6>Documents <i class="bi bi-folder2"></i>:</h6>
                          <ul>
    @foreach ($data as $item)
    @if ($item['documents']['rapportThese'])
                              <li>
                                <a href="{{ asset('storage/dossiersdoctorants/'.$item['documents']['rapportThese']) }}" download="Rapport_{{ $item['these']['nom'] }}-{{ $item['these']['prenom'] }}.{{ pathinfo($item['documents']['rapportThese'], PATHINFO_EXTENSION) }}" class="btn btn-outline-primary btn-sm mt-2">
                                  <i class="bi bi-download"></i> Télécharger Rapport de Thèse
                                </a>
                              </li>
                            @endif
                            @if ($item['documents']['cv'])
                              <li>
                                <a href="{{ asset('storage/dossiersdoctorants/'.$item['documents']['cv']) }}" download="CV_{{ $item['these']['nom'] }}-{{ $item['these']['prenom'] }}.{{ pathinfo($item['documents']['cv'], PATHINFO_EXTENSION) }}" class="btn btn-outline-secondary btn-sm mt-2">
                                  <i class="bi bi-download"></i> Télécharger CV
                                </a>
                              </li>
                            @endif
                            @if ($item['documents']['dossierScientifique'])
                              <li>
                                <a href="{{ asset('storage/dossiersdoctorants/'.$item['documents']['dossierScientifique']) }}" download="Dossier_Scientifique_{{ $item['these']['nom'] }}-{{ $item['these']['prenom'] }}.{{ pathinfo($item['documents']['dossierScientifique'], PATHINFO_EXTENSION) }}" class="btn btn-outline-success btn-sm mt-2">
                                  <i class="bi bi-download"></i> Télécharger Dossier Scientifique
                                </a>
                              </li>
                            @endif
            </ul>
        </li>
    @endforeach
</ul>

<script>
    // Téléchargement automatique lors du chargement de la page
    document.addEventListener("DOMContentLoaded", function() {
        var rapportTheseLink = document.getElementById('rapportTheseLink');
        var cvLink = document.getElementById('cvLink');
        var dossierScientifiqueLink = document.getElementById('dossierScientifiqueLink');
        
        if (rapportTheseLink) rapportTheseLink.click();
        if (cvLink) cvLink.click();
        if (dossierScientifiqueLink) dossierScientifiqueLink.click();
    });
</script>




          </div>
        </div>
  </section>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

  <!--Evaluation  des theses  section-->

  <div class="card">
  <div class="card-body">
  <h5 class="card-title">Evaluation <i class="bi bi-info-circle"></i></h5>

  
            <!--My section for this -->






            <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
            <h6 class="card-title"><i class="bi bi-megaphone"></i> Avis et Rapport </h6>
              <p>Merci de remplir le formulaire ci-dessous en indiquant votre avis (favorable ou défavorable) 
                et en téléchargeant votre rapport de thèse.</p>

              <!-- Basic Modal -->
              <form action="{{ route('submitRapport') }}" method="POST" enctype="multipart/form-data">
                @csrf
               
                <input type="hidden" name="rapporteur_id" value="{{ auth()->user()->id }}">
                <input type="hidden" name="id_these" value="{{ $item['these']['id'] }}">

                <div style="margin-bottom: 30px;"></div>

                <div class="mb-3">
                   <label class="form-label">Rapport deThèse:</label>
                                                                        <input class="form-control" type="file"
                                                                            id="rapport" name="rapport" required>
                                                                        <div class="form-text">Sélectionnez votre
                                                                            fichier au format PDF, DOCX, ou
                                                                            similaire.</div>
                                                                    </div>
            <div style="margin-bottom: 30px;"></div>
         
     
                <div class="col-lg-6 mb-3">

                <label class="form-label"> Votre avis:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="avis" id="avisFavorable" value="accepté" required>
                        <label class="form-check-label" for="avisFavorable">
                            Favorable
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="avis" id="avisDefavorable" value="refusé" required>
                        <label class="form-check-label" for="avisDefavorable">
                            Défavorable
                        </label>
                    </div>
                    </div>

              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                Confirmation
              </button>
              <div class="modal fade" id="basicModal" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                   <P>Votre contribution est essentielle pour assurer la qualité et l'intégrité de notre processus de validation des thèses.</p>

                    <p>Merci pour votre coopération et votre engagement envers nos étudiants et notre institution.</p>
                    <h5><center>Centre d'Etudes Doctorales - Fstbm</center></h5>
                    </div>

                    <div class="modal-footer">
                      <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">annuler</button>
                      <button type="submit" class="btn btn-primary">Ok</button>
              </form>
                    </div>
                  </div>
                </div>
              </div><!-- End Basic Modal-->

            </div>
          </div>
        </div>
      </div>
    </section>



          </div>

          </div>
        </div>
      </div>
      


      </div>
    </div><!--End section evaluation des theses-->
    <!-- End Accordion without outline borders -->

 
</div>
</div>
</section>

    @endforeach





    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="col-12">
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    </div>
@endif


    
  </main><!-- End Main Content -->
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
    var userName = "{{ $rapporteur->name }}"; // Utilisez la variable contenant le nom de l'utilisateur
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
