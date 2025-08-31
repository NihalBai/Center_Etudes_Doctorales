<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Doctorant</title>
    <base href="/public">
</head>
<body>
@extends($layoutFile)

@section('content')

<main id="main" class="main">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Modifier Doctorant</h5>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{route('doctorants.index')}}">Doctorants</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Formulaire de Modification de Doctorant</h5>
                        <form method="POST" action="route('doctorants.update', $doctorant->id) }}">
                            @csrf
                            @method('PUT') 
                            <!-- Afficher les anciennes valeurs -->
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="{{ $doctorant->nom }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ $doctorant->prenom }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="CINE" class="form-label">CIN</label>
                                <input type="text" class="form-control" id="CINE" name="CINE" value="{{ $doctorant->CINE }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sexe</label>
                                <select class="form-select" id="sex" name="sex" required>
                                    <option value="male" {{ $doctorant->sex === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $doctorant->sex === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date_de_naissance" class="form-label">Date de Naissance</label>
                                <input type="date" class="form-control" id="date_de_naissance" name="date_de_naissance" value="{{ $doctorant->date_de_naissance }}" required>
                            </div>

                            <div class="mb-3">
    <label for="photo_path" class="form-label">Ancienne Photo</label><br>
    <img src="{{ asset($doctorant->photo_path) }}" alt="Ancienne photo" style="max-width: 200px; max-height: 200px;"><br>
    <label for="photo_path" class="form-label">Télécharger une Nouvelle Photo</label>
    <input type="file" class="form-control" id="photo_path" name="photo_path">
</div>

                            <div class="mb-3">
                                <label for="dossier" class="form-label">Dossier</label>
                                <input type="text" class="form-control" id="dossier" name="dossier" value="{{ $doctorant->dossier }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="encadrant" class="form-label">Encadrant</label>
                                <select class="form-select" id="encadrant" name="id_encadrant" required>
                                    @foreach($encadrants as $encadrant)
                                        <option value="{{ $encadrant->id }}" {{ $doctorant->id_encadrant == $encadrant->id ? 'selected' : '' }}>{{ $encadrant->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Valider</button>
                            <a href="{{ route('doctorants.index') }}" class="btn btn-secondary">Annuler</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

@endsection  
</body>
</html>
