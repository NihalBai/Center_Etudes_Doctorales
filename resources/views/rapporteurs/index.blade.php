<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CED</title>
</head>
<base href="/public"> 
<body>

@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
            <div class="card-body" >
              <h5 class="card-title">Bienvenue!</h5>

              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('demandes.index')}}">Demandes</a></li>
                  <li class="breadcrumb-item active">Rapporteurs</li>
                  
                </ol>
              </nav>
            </div>
          </div>
          <!-- End Page Title -->
   
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Liste de rapporteurs</h5>
              <div class="d-flex justify-content-end">
              <a href="{{ route('rapporteurs.ajouterDocuments') }}" class="btn btn-primary"  style="margin-right: 12px;" >Joindre Document</a>
</div>
              
              

             
              <!-- Table with stripped rows  -->
              <table class="table datatable" >
      <thead>
    <tr>
      <th>#</th>
      <th>Civilité</th>
      <th>Rapporteur</th>
      <th>Email</th>
      <th>Téléphone</th>
      <th>Contacter</th>
    </tr>
  </thead>
  <tbody>
  @foreach($rapporteurs as $rapporteur)
    <tr>
      <td>{{ $rapporteur->id }}</td>
      <td>
        @if($rapporteur->sex == 'male')
          Monsieur
         @else
          Madame
        @endif
       </td>
       <td>{{ $rapporteur->name }}</td>
       <td>
    <a href="https://mail.google.com/mail/?view=cm&to={{ $rapporteur->email }}">{{ $rapporteur->email }}</a>
</td>

       <td>{{ $rapporteur->tele }}</td>
       <td align="center">
    @php
        $subject = urlencode("Invitation à Participer en tant que Rapporteur de Thèse");
        $login = urlencode($rapporteur->email); // Encodage du login
        $password = urlencode($rapporteur->password); // Encodage du mot de passe
        $loginLink = urlencode('http://127.0.0.1:8000/login'); // Encodage du lien de connexion
        
        // Déterminer le texte de salutation en fonction du genre
        $salutation = $rapporteur->sex == 'female' ? "Madame" : "Monsieur";

        $body = urlencode("Bonjour " . $salutation . " " . $rapporteur->name . ",\n\n" .
            "Nous avons le plaisir de vous informer que vous avez été sélectionné(e) en tant que rapporteur de thèse par le Centre d'Etudes Doctorales - FSTBM.\n\n" .
            "Votre contribution en tant que rapporteur est cruciale pour l'évaluation et l'avancement de la recherche académique. Nous apprécions votre engagement envers l'excellence académique et nous vous remercions de votre participation.\n\n" .
            "Pour commencer, veuillez trouver ci-dessous vos identifiants de connexion pour accéder à votre compte :\n\n" .
            "Login : " . $rapporteur->email . "\n" .
            "Mot de passe : " . $rapporteur->pass . "\n\n" .
            "Veuillez utiliser ces identifiants pour accéder à votre compte sur notre plateforme en suivant ce lien : " . route('login') . ". Une fois connecté(e), vous pourrez remplir votre avis sur la thèse assignée ainsi que soumettre votre rapport de thèse.\n" .
            "Vous trouverez également toutes les informations relatives à la thèse dans votre compte, pour votre référence.\n\n" .
            "Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter à [ced.fstbm@usms.ma].\n\n" .
            "Nous vous remercions de votre collaboration et de votre contribution à l'avancement de la recherche scientifique .\n\n" .
            "Cordialement,\n" .
            "\n" .
            "Centre d'Etudes Doctorales - FSTBM");
    @endphp

    <a href="https://mail.google.com/mail/?view=cm&to={{ $rapporteur->email }}&su={{ $subject }}&body={{ $body }}" class="btn btn-outline-primary my-2 my-sm-0"><i class="bi bi-envelope-fill"></i></a>
</td>

    </tr>
    @endforeach

  </tbody>
</table>
</div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

@endsection
</body>
</html>