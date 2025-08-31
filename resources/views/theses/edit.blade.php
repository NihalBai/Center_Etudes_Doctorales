<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification</title>
    <base href="/public">
</head>
<body>
@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
            <div class="card-body" >
              <h5 class="card-title">Modifier Thèse</h5>

              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('theses.index')}}">Thèses</a></li>
                  <li class="breadcrumb-item active">Modifier</li>
                </ol>
              </nav>
              @if(session('success'))
    <div class="col-12">
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
       
    


<div class="col-lg-10">

<div class="card">
  <div class="card-body">
    <h5 class="card-title"><center>Thèse</center></h5>

<!-- Vertical Form -->
<form class="row g-3" id="these-form" method="POST" action="{{ route('theses.update', $these->id) }}">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label for="titreOriginal" class="form-label">Titre Original :</label>
        <input type="text" class="form-control" id="titreOriginal" name="titreOriginal" value="{{ $these->titreOriginal }}" required>
    </div>
    <div class="col-md-6">
        <label for="titreFinal" class="form-label">Titre Final :</label>
        <input type="text" class="form-control" id="titreFinal" name="titreFinal" value="{{ $these->titreFinal }}">
    </div>
    <div class="col-12">
        <label for="formation" class="form-label">Formation Doctorale :</label>
        <input type="text" class="form-control" id="formation" name="formation" value="{{ $these->formation }}" required>
    </div>
    <div class="col-md-6">
        <label for="cine" class="form-label">CIN Doctorant :</label>
        <select class="form-select" id="cine" name="cine" required>
            <option value="{{ $these->doctorant->CINE }}">{{ $these->doctorant->CINE }}</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="acceptationDirecteur" class="form-label">Avis Directeur :</label>
        <select class="form-select" id="acceptationDirecteur" name="acceptationDirecteur" required>
            <option value="Oui" {{ $these->acceptationDirecteur == 'Oui' ? 'selected' : '' }}>Oui</option>
            <option value="Non" {{ $these->acceptationDirecteur == 'Non' ? 'selected' : '' }}>Non</option>
        </select>
    </div>
    <div class="col-12">
        <label for="structure_recherche" class="form-label">Structure de Recherche :</label>
        <select class="form-select" id="structure_recherche" name="structure_recherche" required onchange="toggleOtherStructureField()">
            <option value="">Sélectionner une structure de recherche</option>
            <option value="LMACS" {{ $these->structure_recherche == 'LMACS' ? 'selected' : '' }}>LMACS</option>
            <option value="LGEM" {{ $these->structure_recherche == 'LGEM' ? 'selected' : '' }}>LGEM</option>
            <option value="LC2MC" {{ $these->structure_recherche == 'LC2MC' ? 'selected' : '' }}>LC2MC</option>
            <option value="LGB" {{ $these->structure_recherche == 'LGB' ? 'selected' : '' }}>LGB</option>
            <option value="LGIIS" {{ $these->structure_recherche == 'LGIIS' ? 'selected' : '' }}>LGIIS</option>
            <option value="LICPM" {{ $these->structure_recherche == 'LICPM' ? 'selected' : '' }}>LICPM</option>
            <option value="LBAIM" {{ $these->structure_recherche == 'LBAIM' ? 'selected' : '' }}>LBAIM</option>
            <option value="TIAD" {{ $these->structure_recherche == 'TIAD' ? 'selected' : '' }}>TIAD</option>
            <option value="LGS" {{ $these->structure_recherche == 'LGS' ? 'selected' : '' }}>LGS</option>
            <option value="LGE" {{ $these->structure_recherche == 'LGE' ? 'selected' : '' }}>LGE</option>
            <option value="LGEEA" {{ $these->structure_recherche == 'LGEEA' ? 'selected' : '' }}>LGEEA</option>
            <option value="autre" {{ $these->structure_recherche == 'autre' ? 'selected' : '' }}>Autre</option>
        </select>
    </div>
    <div class="col-12" id="otherStructureField" style="{{ $these->structure_recherche == 'autre' ? '' : 'display:none;' }}">
        <label for="other_structure" class="form-label">Autre Structure :</label>
        <input type="text" class="form-control" id="other_structure" name="other_structure" value="{{ $these->structure_recherche == 'autre' ? $these->other_structure : '' }}">
    </div>
    <div class="col-md-6">
    <label for="date_premiere_inscription" class="form-label">Date de Première Inscription :</label>
    <input type="text" class="form-control" id="date_premiere_inscription" name="date_premiere_inscription"  value="{{$date_premiere->year . '-' . $date_premiere->addYear()->year}}" readOnly>
</div>

    <div class="col-md-6">
    <label for="date_premiere_inscription" class="form-label">Date de Première Inscription :</label>
    <input type="date" class="form-control" id="date_premiere_inscription" name="date_premiere_inscription" required>
</div>


    <div class="col-md-6">
        <label for="nombre_publications_article" class="form-label">Nombre de Publications (Articles) :</label>
        <input type="number" class="form-control" id="nombre_publications_article" name="nombre_publications_article" value="{{ $these->nombre_publications_article }}" min="0" required>
    </div>
    <div class="col-md-6">
        <label for="nombre_publications_conference" class="form-label">Nombre de Publications (Conférences) :</label>
        <input type="number" class="form-control" id="nombre_publications_conference" name="nombre_publications_conference" value="{{ $these->nombre_publications_conference }}" min="0" required>
    </div>
    <div class="col-md-6">
        <label for="nombre_communications" class="form-label">Nombre de Communications :</label>
        <input type="number" class="form-control" id="nombre_communications" name="nombre_communications" value="{{ $these->nombre_communications }}" min="0" required>
    </div>
    <div style="margin-bottom: 20px;"></div>
    <div class="col-12 text-center" style="margin-bottom: 20px;">
        <button type="submit" class="btn btn-primary">Modifier</button>
        <button type="reset" class="btn btn-secondary">Réinitialiser</button>
    </div>
</form>
<!-- Vertical Form -->

<script>
    // Gestion de l'affichage du champ 'Autre Structure' en fonction de la sélection
    function toggleOtherStructureField() {
        var structureSelect = document.getElementById('structure_recherche');
        var otherStructureField = document.getElementById('otherStructureField');

        if (structureSelect.value === 'autre') {
            otherStructureField.style.display = '';
        } else {
            otherStructureField.style.display = 'none';
            document.getElementById('other_structure').value = ''; // Effacer la valeur si une autre structure n'est pas sélectionnée
        }
    }

    // Initialisation de l'état visible/invisible du champ 'Autre Structure'
    toggleOtherStructureField();
</script>

    



 


  </div>
</div>
</div>


          </div>

        </div>
      </div>
    </section>

  </main> 
  <!-- End  main -->














@endsection
</body>
</html>