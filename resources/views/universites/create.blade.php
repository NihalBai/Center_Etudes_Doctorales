<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <base href="/public">
</head>
<body>
@extends('layouts.layout')

@section('content') 
<main id="main" class="main">




<div class="card">
            <div class="card-body" >
              <h5 class="card-title">Bienvenue!</h5>

              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('membres.index')}}">Membres</a></li>
                  <li class="breadcrumb-item active">Ajouter</li>
                </ol>
              </nav>
              @if(session('success'))
    <div class="col-6">
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    </div>
@endif

            </div>
          </div>
          <!-- End Page Title -->


<section class="section">
  <div class="row">
   

<div>


    <div class="col-lg-12">
        
    <div class="card">
    <div class="card-body">
        <h5 class="card-title">Informations de l'université</h5>
        <form method="POST" action="{{ route('universites.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de l'université :</label>
                <select class="form-select" id="nom" name="nom" onchange="toggleNouvelleUniversite()">
                    <option value="">Choisir une université existante</option>
                    @foreach($universites as $universite)
                        <option value="{{ $universite->id }}">{{ $universite->nom }}</option>
                    @endforeach
                    <option value="autre">Ajouter une nouvelle université</option>
                </select>
            </div>
            <div id="nouvelle-universite" style="display: none;">
                <div class="mb-3">
                    <label for="nouvelle_universite" class="form-label">Nom de la nouvelle université :</label>
                    <input type="text" class="form-control" id="nouvelle_universite" name="nouvelle_universite">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
        </form>
    </div>
</div>
<script>
    function toggleNouvelleUniversite() {
        var selectElement = document.getElementById('nom');
        var nouvelleUniversiteDiv = document.getElementById('nouvelle-universite');
        if (selectElement.value === 'autre') {
            nouvelleUniversiteDiv.style.display = 'block';
        } else {
            nouvelleUniversiteDiv.style.display = 'none';
        }
    }
</script>




  <!-- Formulaire de la faculte -->


  <div class="card">
    <div class="card-body">
        <h5 class="card-title">Informations de la faculté</h5>
        <form method="POST" action="{{ route('facultes.store') }}">
            @csrf
            <div class="mb-3">
    <label for="nom" class="form-label">Nom de la faculté :</label>
    <select class="form-select" id="nom" name="nom" onchange="toggleNouvelleFaculte(this)" required>
        <option value="">Choisir une faculté existante</option>
        <!-- Options existantes -->
        @foreach($facultes as $faculte)
            <option value="{{ $faculte->nom }}">{{ $faculte->nom }}</option>
        @endforeach
        <option value="autre">Autre</option>
    </select>
</div>
<div id="nouvelle-faculte" style="display: none;">
    <div class="mb-3">
        <label for="nouvelle_faculte" class="form-label">Nouvelle Faculté :</label>
        <input type="text" class="form-control" id="nouvelle_faculte" name="nouvelle_faculte">
    </div>
</div>
<script>
    function toggleNouvelleFaculte(select) {
        var nouvelleFaculteDiv = document.getElementById('nouvelle-faculte');
        if (select.value === 'autre') {
            nouvelleFaculteDiv.style.display = 'block';
        } else {
            nouvelleFaculteDiv.style.display = 'none';
        }
    }
</script>

            <div class="mb-3">
    <label for="ville" class="form-label">Ville :</label>
    <select class="form-select" id="ville" name="ville" onchange="toggleNouvelleVille(this)" required>
        <option value="">Choisir une ville existante</option>
        <!-- Options existantes -->
        @foreach($villes as $ville)
            <option value="{{ $ville }}">{{ $ville }}</option>
        @endforeach
        <option value="autre">Autre</option>
    </select>
</div>
<div id="nouvelle-ville" style="display: none;">
    <div class="mb-3">
        <label for="nouvelle_ville" class="form-label">Nouvelle Ville :</label>
        <input type="text" class="form-control" id="nouvelle_ville" name="nouvelle_ville">
    </div>
</div>
<script>
    function toggleNouvelleVille(select) {
        var nouvelleVilleDiv = document.getElementById('nouvelle-ville');
        if (select.value === 'autre') {
            nouvelleVilleDiv.style.display = 'block';
        } else {
            nouvelleVilleDiv.style.display = 'none';
        }
    }
</script>

            <!-- Champ de sélection pour choisir une université existante -->
            <div class="mb-3">
                <label for="choix_universite" class="form-label">Choisir une université  :</label>
                <select class="form-select" id="choix_universite" name="id_universite">
                    <option value="">Sélectionner une université </option>
                    <!-- Options existantes -->
                    @foreach($universites as $universite)
                        <option value="{{ $universite->id }}">{{ $universite->nom }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
        </form>
    </div>
</div>


<!-- Autres Informations  -->

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Autre</h5>
        <form method="POST" action="{{ route('autres.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="ville" class="form-label">Ville :</label>
                <select class="form-select" id="ville" name="ville" onchange="toggleAutreVille(this)" required>
                    <option value="">Choisir une ville existante</option>
                    <!-- Options existantes -->
                    @foreach($villesAutres as $ville)
                        <option value="{{ $ville }}">{{ $ville }}</option>
                    @endforeach
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div id="nouvelle-ville-autre" style="display: none;">
                <div class="mb-3">
                    <label for="nouvelle-ville-autre" class="form-label">Nouvelle Ville :</label>
                    <input type="text" class="form-control" id="nouvelle-ville-autre" name="nouvelle-ville-autre">
                </div>
                <script>
                function toggleAutreVille(select) {
                    var nouvelleVilleDiv = document.getElementById('nouvelle-ville-autre');
                    if (select.value === 'autre') {
                        nouvelleVilleDiv.style.display = 'block';
                    } else {
                        nouvelleVilleDiv.style.display = 'none';
                    }
                }
            </script>
            </div>
           
            <!-- Champ de sélection pour choisir une université existante -->
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
        </form>
    </div>
</div>
<!-- Formulaire du membre  -->

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Autre</h5>
        <form method="POST" action="{{ route('membres.store') }}">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="ville" class="form-label">Ville :</label>
                <select class="form-select" id="ville" name="ville" onchange="toggleAutreVille(this)" required>
                    <option value="">Choisir une ville existante</option>
                    <!-- Options existantes -->
                    @foreach($villesAutres as $ville)
                        <option value="{{ $ville }}">{{ $ville }}</option>
                    @endforeach
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div id="nouvelle-ville-autre" style="display: none;">
                <div class="mb-3">
                    <label for="nouvelle-ville-autre" class="form-label">Nouvelle Ville :</label>
                    <input type="text" class="form-control" id="nouvelle-ville-autre" name="nouvelle-ville-autre">
                </div>
                <script>
                function toggleAutreVille(select) {
                    var nouvelleVilleDiv = document.getElementById('nouvelle-ville-autre');
                    if (select.value === 'autre') {
                        nouvelleVilleDiv.style.display = 'block';
                    } else {
                        nouvelleVilleDiv.style.display = 'none';
                    }
                }
            </script>
            </div>
           
            <!-- Champ de sélection pour choisir une université existante -->
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <button type="reset" class="btn btn-secondary">Réinitialiser</button>
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