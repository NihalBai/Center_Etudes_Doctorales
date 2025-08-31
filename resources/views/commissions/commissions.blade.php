<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commissions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <base href="/public"> 
</head>
<body>

@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Bienvenue!</h5>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('commissions.index') }}">Commissions</a></li>
                <li class="breadcrumb-item active">Voir</li>
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
                    <h5 class="card-title">Liste de Commissions</h5>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('commissions.create') }}" class="btn btn-primary" style="margin-right: 10px;">Nouvelle Commission</a>
                        <a href="{{ route('commissions.index') }}" class="btn btn-primary" style="margin-right: 10px;">PVS</a>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Session de Commission</th>
                                <th>Avis Favorable (%)</th>
                                <th>PV Global</th>
                                <th>PVs Individuels</th>
                                <th>Notifier</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($sessions as $sessionData)
                    <tr  class="demande-row" data-id="{{ $sessionData['session']->id}}" >
                        <td>{{ $sessionData['session']->id }}</td>
                        <td>{{ \Carbon\Carbon::parse($sessionData['session']->date)->locale('fr')->isoFormat('D MMMM YYYY') }}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $sessionData['pourcentage'] }}%;" aria-valuenow="{{ $sessionData['pourcentage'] }}" aria-valuemin="0" aria-valuemax="100">{{ $sessionData['pourcentage'] }}%</div>
                            </div>
                        </td>
                        <td align="center">
    @php
        // Récupérer l'extension du fichier
        $extension = pathinfo($sessionData['session']->pv_global_signe, PATHINFO_EXTENSION);
    @endphp

    <a href="{{ asset('storage/' . $sessionData['session']->pv_global_signe) }}"
    class="btn btn-outline-primary my-2 my-sm-0" 
       download="PV_Global_Final {{ \Carbon\Carbon::parse($sessionData['session']->date)->locale('fr')->isoFormat('D MMMM YYYY') }}.{{ $extension }}" >
       <i class="bi bi-download"></i>
    </a>
</td>
                        <td align="center">
                            <a href="{{ route('commissions.individualPVs', ['idSession' => $sessionData['session']->id]) }}"  class="btn btn-outline-primary my-2 my-sm-0"  >Consulter</a>
                        </td>
           <td>
    @php
        $favorableDoctorants = $sessionData['doctorants']->flatten()->unique();
        $encadrantEmails = $favorableDoctorants->map(function ($doctorant) {
            return optional($doctorant->encadrant)->email;
        })->filter()->unique()->implode(',');

        $sessionDate = \Carbon\Carbon::parse($sessionData['session']->date)->locale('fr')->isoFormat('D MMMM YYYY');
        $subject = 'Désignation des rapporteurs pour la session du ' . $sessionDate;

        $doctorantsList = $favorableDoctorants->map(function ($doctorant) {
            return $doctorant->nom ." ". $doctorant->prenom. " : " . $doctorant->these->titreOriginal;
        })->implode("\n- ");

        $body = "Bonjour,\n\n" .
                "Veuillez trouver ci-dessous la liste des doctorants ayant reçu un avis favorable lors de la commission du " . $sessionDate . " :\n\n" .
                "- " . $doctorantsList . "\n\n" .
                "Nous vous prions de bien vouloir nous fournir une liste des rapporteurs pour chacun de ces doctorants.\n\n" .
                "Cordialement,\n" .
                "Le Directeur CED";
    @endphp

    @if ($encadrantEmails)
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $encadrantEmails }}&su={{ urlencode($subject) }}&body={{ urlencode($body) }}" id="emailLink{{ $loop->iteration }}" class="btn btn-primary" target="_blank" onclick="changeButtonColor({{ $loop->iteration }})" >Informer Encadrants</a>
    @else
        <span>Pas d'avis favorable</span>
    @endif
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

<style>
    .demande-row:hover {
        background-color: #f5f5f5; /* Change la couleur de fond au survol */
        cursor: pointer; /* Change le curseur en main pour indiquer la clicabilité */
    }
</style>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        var demandeRows = document.querySelectorAll(".demande-row");

        demandeRows.forEach(function(row) {
            row.addEventListener("click", function(event) {
                // Check if the click was on the download link
                if (!event.target.closest('.btn-primary')) {
                    var sessionId = this.getAttribute("data-id");
                    window.location.href = "{{ url('commissions/edit') }}/" + sessionId;
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function changeButtonColor(iteration) {
            var emailLink = document.getElementById('emailLink' + iteration);
            emailLink.style.backgroundColor = '#28a745'; // Change la couleur du bouton au vert (success color)
        }
    </script>

@endsection
</body>
</html>
