<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande</title>
    <base href="/public">
</head>
<body>
@extends($layoutFile)

@section('content')
<main id="main" class="main">

<div class="card">
            <div class="card-body" >
              <h5 class="card-title">Ajouter Thèse</h5>

              <nav>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('demandes.index')}}">Demandes</a></li>
                  <li class="breadcrumb-item active">Ajouter</li>
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
       
    


<div class="col-lg-12">

<div class="card">
  <div class="card-body">
    <h5 class="card-title"><center>Thèse</center></h5>

<!-- Vertical Form -->
<form class="row g-3" id="these-form" method="POST" action="{{ route('theses.store') }}">
    @csrf
    <div class="col-md-6">
        <label for="titreOriginal" class="form-label">Titre Original :</label>
        <input type="text" class="form-control" id="titreOriginal" name="titreOriginal" required>
    </div>
    <div class="col-md-6">
        <label for="titreFinal" class="form-label">Titre Final :</label>
        <input type="text" class="form-control" id="titreFinal" name="titreFinal" placeholder="Optionnel">
    </div>
    <div class="col-12">
        <label for="formation" class="form-label">Formation Doctorale :</label>
        <input type="text" class="form-control" id="formation" name="formation" required>
    </div>
    <div class="col-md-6">
        <label for="cine" class="form-label">CIN Doctorant :</label>
        <select class="form-select" id="cine" name="cine" required>
            <option value="">CIN du doctorant</option>
            @foreach($doctorantsCIN as $cin)
                <option value="{{ $cin }}">{{ $cin }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="acceptationDirecteur" class="form-label">Avis Directeur :</label>
        <select class="form-select" id="acceptationDirecteur" name="acceptationDirecteur">
            <option value="Oui">Oui</option>
            <option value="Non">Non</option>
        </select>
    </div>
    <div class="col-12">
        <label for="structure_recherche" class="form-label">Structure de Recherche :</label>
        <select class="form-select" id="structure_recherche" name="structure_recherche" required onchange="toggleOtherStructureField()">
            <option value="">Sélectionner une structure de recherche</option>
            <option value="LMACS">LMACS</option>
            <option value="LGEM">LGEM</option>
            <option value="LC2MC">LC2MC</option>
            <option value="LGB">LGB</option>
            <option value="LGIIS">LGIIS</option>
            <option value="LICPM">LICPM</option>
            <option value="LBAIM">LBAIM</option>
            <option value="TIAD">TIAD</option>
            <option value="LGS">LGS</option>
            <option value="LGE">LGE</option>
            <option value="LGEEA">LGEEA</option>
            <option value="autre">Autre</option>
        </select>
    </div>
    <div class="col-12" id="otherStructureField" style="display:none;">
        <label for="other_structure" class="form-label">Autre Structure :</label>
        <input type="text" class="form-control" id="other_structure" name="other_structure">
    </div>
    <div class="col-md-6">
    <label for="date_premiere_inscription" class="form-label">Date de Première Inscription :</label>
    <input type="date" class="form-control" id="date_premiere_inscription" name="date_premiere_inscription" value="{{ date('Y') }}-{{ date('Y') + 1 }}" required>
</div>

    <div class="col-md-6">
        <label for="nombre_publications_article" class="form-label">Nombre de Publications (Articles) :</label>
        <input type="number" class="form-control" id="nombre_publications_article" name="nombre_publications_article" min="0" required>
    </div>
    <div class="col-md-6">
        <label for="nombre_publications_conference" class="form-label">Nombre de Publications (Conférences) :</label>
        <input type="number" class="form-control" id="nombre_publications_conference" name="nombre_publications_conference" min="0" required>
    </div>
    <div class="col-md-6">
        <label for="nombre_communications" class="form-label">Nombre de Communications :</label>
        <input type="number" class="form-control" id="nombre_communications" name="nombre_communications" min="0" required>
    </div>
    
    </div><div style="margin-bottom: 20px;"></div>
    <div class="col-12 text-center" style="margin-bottom: 20px;">
        <button type="submit" class="btn btn-primary">Valider</button>
        <button type="reset" class="btn btn-secondary">Réinitialiser</button>
    </div>
</form>
<!-- Vertical Form -->

<script>
    function toggleOtherStructureField() {
        var select = document.getElementById('structure_recherche');
        var otherField = document.getElementById('otherStructureField');
        if (select.value === 'autre') {
            otherField.style.display = 'block';
        } else {
            otherField.style.display = 'none';
        }
    }
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