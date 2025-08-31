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
    <div class="card-body">
        <h5 class="card-title">Bienvenue!</h5>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('doctorants.index') }}">Doctorants</a></li>
                <li class="breadcrumb-item active">Demandes</li>
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
                    <h5 class="card-title">Liste de demandes</h5>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('theses.create') }}" class="btn btn-primary" style="margin-right: 3px;">Nouvelle Demande</a>
                        <a href="{{ route('theses.index') }}" class="btn btn-primary" style="margin-right: 10px;">Thèse</a>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr align="center">
                                <th>#</th>
                                <th><b>Doctorant</b></th>
                                <th>Formation</th>
                                <th data-type="date" data-format="YYYY-DD-MM">Date de demande</th>
                                <th>Etat</th>           
                                <th>Thèse</th>                            
                                <th>Dernière Session</th>
                                <th>PV Individuel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $demande)
                            <tr class="demande-row {{ $demande->etat === 'Acceptée' ? 'accepted-demande' : '' }}" data-id="{{ $demande->id }}" align="center" >
                                <td>{{ $demande->id }}</td>
                                <td>{{ $demande->nom }} {{ $demande->prenom }}</td>
                                <td>{{ $demande->formation }}{{ $demande->num }}</td>
                                <td >{{ $demande->date }}</td>
                                <td>{{ $demande->etat }}</td>                               
                                <td >
                                    <a href="{{ route('theses.show', ['id' => $demande->id_these]) }}"><strong>Voir</strong></a>
                                </td>               
                                <td>{{ \Carbon\Carbon::parse($demande->session)->locale('fr')->isoFormat('D MMMM YYYY') }}</td>
                                <td>
                                    <a href="{{ route('demandes.download', $demande->id) }}" class="btn btn-primary"><i class="bi bi-download">  </i></a>
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
            row.addEventListener("click", function() {
                var demandeId = this.getAttribute("data-id");
                window.location.href = "{{ url('demandes') }}/" + demandeId + "/edit";
            });
        });
    });
</script>
@endsection
</body>
</html>