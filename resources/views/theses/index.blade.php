<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CED</title>
    <base href="/public">
</head>
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
                    <li class="breadcrumb-item active">Thèses</li>
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
                        <h5 class="card-title">Liste des thèses</h5>
                        <div class="d-flex justify-content-end">
    <a href="{{ route('theses.create') }}" class="btn btn-primary " style="margin-right: 20px;">Nouvelle Demande</a>
                       </div>

                        <!-- Table with stripped rows  -->
                        <table class="table datatable" cellspacing="2px">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titre Original</th>
                                    <th>Titre Final</th>
                                    <th>Avis-Directeur</th>
                                    <th>Formation</th>
                                    <th>Doctorant</th>
                                    <th>CINE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($theses as $these)
                                    <tr class="thesis-row" data-id="{{ $these->id }}">
                                        <td>{{ $these->id }}</td>
                                        <td>{{ $these->titreOriginal }}</td>
                                        <td>{{ $these->titreFinal }}</td>
                                        <td>{{ $these->acceptationDirecteur }}</td>
                                        <td>{{ $these->formation }}</td>
                                        <td>{{ $these->doctorant->nom }} {{ $these->doctorant->prenom }} </td> <!-- Affichage du CINE du doctorant -->
                                        <td>{{$these->doctorant->CINE}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->


<style>
    .thesis-row:hover {
        background-color: #f5f5f5; /* Change la couleur de fond au survol */
        cursor: pointer; /* Change le curseur en main pour indiquer la clicabilité */
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var thesisRows = document.querySelectorAll(".thesis-row");

        thesisRows.forEach(function(row) {
            row.addEventListener("click", function() {
                var thesisId = this.getAttribute("data-id");
                window.location.href = "{{ url('theses') }}/" + thesisId + "/edit";
            });
        });
    });
</script>

















@endsection
</body>
</html>