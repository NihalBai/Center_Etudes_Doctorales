<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CED</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-download:hover {
            background-color: #28a745;
            border-color: #218838;
        }
        .text-refused {
            color: red;
        }
        /* Custom style for form alignment */
        .form-group-flex {
            display: flex;
            align-items: center;
        }
        .form-group-flex > * {
            margin-right: 10px; /* Adjust the margin as needed */
        }
    </style>
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
                <li class="breadcrumb-item"><a href="{{ route('rapporteurs.index') }}">Rapporteurs</a></li>
                <li class="breadcrumb-item active">Demandes</li>
            </ol>
        </nav>
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
    </div>
</div>
<!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Liste de Reponses</h5>

                    <!-- Dropdown for selecting a thesis title -->
                    <form class="form-inline mb-3" method="GET" action="{{ route('rapporteurs.avis.search') }}">
                        <select class="form-control mr-sm-2" style="width: 500px;" name="search">
                            <option value="">Sélectionnez un titre de thèse</option>
                            @foreach($theses as $these)
                                <option value="{{ $these->titreOriginal }}">{{ $these->titreOriginal }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
                    </form>
                    <div style="margin-bottom: 50px;"></div>
                    <!-- Table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Rapporteur</th>
                                <th scope="col">Avis</th>
                                <th scope="col">Rapport</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $result)
                            <tr>
                                <td>{{ $result['rapporteur'] }}</td>
                                <td class="{{ $result['avis'] == 'refusé' ? 'text-refused' : '' }}">{{ $result['avis'] }}</td>
                                <td><a href="{{ $result['lien_rapport'] }}" class="btn btn-outline-primary my-2 my-sm-0" download>Télécharger</a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Aucune avis pour le moment!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-bottom: 50px;"></div>
                    <!-- Checkbox -->
                    <div class="container">
                 
                            <div class="col-lg-8">
                                <form method="POST" action="{{ route('director.review.submit') }}" class="form-inline">
                                    @csrf
                                    <div class="form-group form-group-flex">
                                        <label class="mr-2" for="director_review">Avis du directeur :</label>
                                        <select class="form-control mr-2" style="width: 250px;" name="director_review" id="director_review">
                                            <option value="">Sélectionnez une thèse</option>
                                            @foreach($theses as $these)
                                                <option value="{{ $these->id }}">{{ $these->titreOriginal }}</option>
                                            @endforeach
                                        </select>
                                        <select class="form-control" style="width: 150px;" name="avis" id="avis">
                                            <option value="oui">Oui</option>
                                            <option value="non">Non</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Soumettre</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</main><!-- End #main -->
@endsection

<!-- Add Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
