<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Doctorant</title>
</head>
<base href="/public"> 
<body>
@extends($layoutFile)

@section('content')

<main id="main" class="main">

<section class="section profile">
    <div class="row">
        <div class="col-xl-4">

            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                <img src="{{ Storage::url('public/photosdoctorants/' .$doctorant->photo_path)}}" alt="Photo du doctorant" class="rounded-circle">
                <center>
    
    @if($doctorant->sex === 'female')
        
        <h2>Mme&nbsp;&nbsp;{{ $doctorant->nom }} {{ $doctorant->prenom }}</h2><br>
        <h2>  {{ $doctorant->CINE }}</h2>
    @elseif($doctorant->sex === 'male')
        
        <h2>Mr&nbsp;&nbsp;{{ $doctorant->nom }} {{ $doctorant->prenom }}</h2><br>
        <h2>  {{ $doctorant->CINE }}</h2>
    @endif
</center>

                    <!-- A supprimer si necessaire -->
                </div>
            </div> 

        </div>

        <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">About</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Editer</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Parcours Scolaire</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Editer Parcours</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                          
                        
                            <!-- Contenu de l'onglet About -->
                            <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nom </div>
                    <div class="col-lg-9 col-md-8">{{$doctorant->nom}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Prénom </div>
                    <div class="col-lg-9 col-md-8">{{$doctorant->prenom}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Sexe</div>
                    <div class="col-lg-9 col-md-8">
                    @if($doctorant->sex === 'female')
                        Femme
                    @else
                        Homme
                    @endif
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">CINE</div>
                    <div class="col-lg-9 col-md-8">{{$doctorant->CINE}}</div>
                  </div>

                  <div class="row">
                   <div class="col-lg-3 col-md-4 label">Email</div>
                   <div class="col-lg-9 col-md-8">{{ $doctorant->email }}</div>
                 </div>

                 <div class="row">
                  <div class="col-lg-3 col-md-4 label">Téléphone</div>
                  <div class="col-lg-9 col-md-8">{{ $doctorant->tele }}</div>
                 </div>

                 <div class="row">
    <div class="col-lg-3 col-md-4 label">Date de Naissance</div>
    <div class="col-lg-9 col-md-8">
        {{ \Carbon\Carbon::parse($doctorant->date_de_naissance)->format('d-m-Y') }}
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-4 label">Lieu de Naissance</div>
    <div class="col-lg-9 col-md-8">
        {{ $doctorant->lieu }}
    </div>
</div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Endadrant</div>
                    <div class="col-lg-9 col-md-8">{{ $doctorant->encadrant->nom }} {{ $doctorant->encadrant->prenom }}-{{ $doctorant->encadrant->grade }}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Thèse</div>
                    <div class="col-lg-9 col-md-8"> {{ $doctorant->these->titreOriginal }}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Formation Doctorale</div>
                    <div class="col-lg-9 col-md-8">{{ $doctorant->these->formation }}</div>
                  </div>


                  <div class="row">
    <div class="col-lg-3 col-md-3 label">Dossier Doctorant</div>
    <div class="col-lg-9 col-md-8 d-flex align-items-start">
        <div class="col-lg-6 mb-3">
            <label for="documentType" class="form-label">Type de Document</label>
            <select class="form-select" id="documentType">
                <option value="" disabled selected>Choisir le type de document</option>
                <option value="rapport_these">Rapport de Thèse</option>
                <option value="cv">CV</option>
                <option value="dossier_scientifique">Dossier Scientifique</option>
                <option value="demande_soutenance">Demande de Soutenance</option>
                <option value="rapport_encadrant">Rapport de l'Encadrant</option>
                <option value="pv_individuel">PV Individuel</option> <!-- Nouveau type de document -->
            </select>
        </div>
        <div class="col-lg-6 mb-3 ms-lg-3  align-self-end"  >
            <button class="btn btn-primary" onclick="downloadDocument()">Télécharger</button>
        </div>
    </div>
</div>


<script>
    function downloadDocument() {
        var documentType = document.getElementById('documentType').value;
        if (documentType) {
            var link = document.getElementById(documentType);
            if (link) {
                link.click();
            } else {
                alert("Le document n'a pas été trouvé.");
            }
        } else {
            alert("Veuillez choisir un type de fichier.");
        }
    }
</script>

@foreach($doctorant->files as $document)
    @php
        // Récupérer l'extension du fichier
        $extension = pathinfo($document->path, PATHINFO_EXTENSION);
    @endphp

    <a href="{{ asset('storage/' . $document->path) }}"
       id="{{ $document->type }}" 
       style="display:none;" 
       download="{{ $document->type }}_{{$doctorant->nom}} {{$doctorant->prenom}}.{{ $extension }}" download>
       Télécharger {{ $document->type }}
    </a>
@endforeach




                  

                

                        </div>

                     

                            <!-- Contenu de l'onglet edit Profile-->


<!-- =============================================================================================================== -->


  <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
<section>
    <!-- Profile Edit Form -->
    <form action="{{ route('doctorants.update', ['doctorant' => $doctorant]) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

<!-- photo du doctorant -->
<div class="row mb-3">
    <label for="photo_path" class="col-md-4 col-lg-3 col-form-label">Photo</label>
    <div class="col-md-8 col-lg-9">
        <img src="{{ Storage::url('public/photosdoctorants/'.$doctorant->photo_path)}}" alt="Photo du doctorant" title="Photo du doctorant">
        <div class="pt-2">
            <label for="photo_path" class="btn btn-primary btn-sm" title="Télécharger une nouvelle photo">
                <i class="bi bi-upload"></i>Importer
            </label>
            <input type="file" id="photo_path" name="photo_path" class="form-control-file" style="display: none;">
            <!-- Champ caché pour conserver l'ancienne valeur -->
            <input type="hidden" name="old_photo_path" value="{{$doctorant->photo_path}}">
        </div>
    </div>
</div>

      
      <!-- Prénom -->
      <div class="row mb-3">
        <label for="prenom" class="col-md-4 col-lg-3 col-form-label">Prénom</label>
        <div class="col-md-8 col-lg-9">
          <input name="prenom" type="text" class="form-control" id="prenom" value="{{ $doctorant->prenom }}">
        </div>
      </div>
      <!-- Nom -->
      <div class="row mb-3">
        <label for="nom" class="col-md-4 col-lg-3 col-form-label">Nom</label>
        <div class="col-md-8 col-lg-9">
          <input name="nom" type="text" class="form-control" id="nom" value="{{ $doctorant->nom }}">
        </div>
      </div>

      <!-- Sexe -->
<div class="row mb-3">
    <label for="sex" class="col-md-4 col-lg-3 col-form-label">Sexe</label>
    <div class="col-md-8 col-lg-9">
        <select name="sex" class="form-select" id="sex">
            <option value="male" {{ $doctorant->sex === 'male' ? 'selected' : '' }}>Homme</option>
            <option value="female" {{ $doctorant->sex === 'female' ? 'selected' : '' }}>Femme</option>
        </select>
    </div>
</div>
<!-- Email -->
<div class="row mb-3">
    <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
    <div class="col-md-8 col-lg-9">
        <input name="email" type="email" class="form-control" id="email" value="{{ $doctorant->email }}">
    </div>
</div>

<!-- Téléphone -->
<div class="row mb-3">
    <label for="tele" class="col-md-4 col-lg-3 col-form-label">Téléphone</label>
    <div class="col-md-8 col-lg-9">
        <input name="tele" type="text" class="form-control" id="tele" value="{{ $doctorant->tele }}">
    </div>
</div>

      <!-- CINE -->
      <div class="row mb-3">
        <label for="CINE" class="col-md-4 col-lg-3 col-form-label">CINE</label>
        <div class="col-md-8 col-lg-9">
          <input name="CINE" type="text" class="form-control" id="CINE" value="{{ $doctorant->CINE }}">
        </div>
      </div>
      <!-- Date de naissance -->
      <div class="row mb-3">
        <label for="date_de_naissance" class="col-md-4 col-lg-3 col-form-label">Date de Naissance</label>
        <div class="col-md-8 col-lg-9">
          <input name="date_de_naissance" type="date" class="form-control" id="date_de_naissance" value="{{ $doctorant->date_de_naissance }}">
        </div>
      </div>
      <!-- Lieu de naissance -->
<div class="row mb-3">
    <label for="lieu" class="col-md-4 col-lg-3 col-form-label">Lieu de Naissance</label>
    <div class="col-md-8 col-lg-9">
        <input name="lieu" type="text" class="form-control" id="lieu" value="{{ $doctorant->lieu }}">
    </div>
</div>








<!-- Dossier Doctorant -->
<!-- Dossier Doctorant -->
<!-- Dossier Doctorant -->
<div class="row mb-3">
    <label for="dossierType" class="col-md-4 col-lg-3 col-form-label">Dossier Doctorant</label>
    <div class="col-md-8 col-lg-9">
        <div class="pt-2">
            <!-- Menu déroulant pour choisir le type de document -->
            <select class="form-select mb-3" id="dossierType" name="dossierType">
                <option value="" disabled selected>Choisir le type de document</option>
                <option value="rapport_these">Rapport de Thèse</option>
                <option value="cv">CV</option>
                <option value="dossier_scientifique">Dossier Scientifique</option>
                <option value="demande_soutenance">Demande de Soutenance</option>
                <option value="rapport_encadrant">Rapport de l'Encadrant</option>
                <option value="pv_individuel">PV Individuel</option> <!-- Nouveau type de document -->


                <!-- Ajoutez d'autres types de documents avec leurs extensions -->
            </select>

            <!-- Champ pour afficher le nom du fichier actuel (si existant) -->
            <div class="mb-3">
                <label for="currentFile" class="col-md-4 ">Document actuel :</label>
                <input type="text" class="form-control" id="currentFile" value="" readOnly><br>
            </div>

            <!-- Bouton pour télécharger un nouveau fichier -->
            <label for="dossier" class="btn btn-primary btn-sm" title="Télécharger un nouveau dossier">
                <i class="bi bi-upload"></i> Importer
            </label>
            <!-- Champ de téléchargement caché -->
            <input type="file" id="dossier" name="dossier" class="form-control-file" style="display: none;">
            <!-- Champ caché pour l'ancienne valeur -->
            <input type="hidden" name="old_dossier" value="">
        </div>
    </div>
</div>

<script>
    // Define the doctorantFiles object using JavaScript's object literal notation
    var doctorantFiles = {
        @foreach ($doctorant->files as $file)
            '{{ $file->type }}': '{{ $file->path }}',
        @endforeach
    };

    // Event listener for when the document type is changed
    document.getElementById('dossierType').addEventListener('change', function() {
        var documentType = this.value;
        var currentFile = document.getElementById('currentFile');
        var oldDossier = document.querySelector('input[name="old_dossier"]');

        if (doctorantFiles[documentType]) {
            currentFile.value = documentType + " - " + doctorantFiles[documentType];
            oldDossier.value = doctorantFiles[documentType]; // Set the old document value
        } else {
            currentFile.value = " Aucun document actuellement.";
            oldDossier.value = ''; // Reset old document value
        }
    });

    // Event listener for when a file is selected
    document.getElementById('dossier').addEventListener('change', function() {
        var fileName = this.files[0].name;
        var documentType = document.getElementById('dossierType').value;
        if (documentType) {
            document.getElementById('currentFile').value = documentType + " - " + fileName;
        } else {
            alert("Veuillez choisir un type de fichier avant d'importer.");
            this.value = null; // Reset file input if no document type is selected
        }
    });
</script>





      <!-- Encadrant -->
      <div class="row mb-3">
        <label for="id_encadrant" class="col-md-4 col-lg-3 col-form-label">Encadrant</label>
        <div class="col-md-8 col-lg-9">
          <select name="id_encadrant" class="form-select" id="id_encadrant">
            @foreach($encadrants as $encadrant)
              <option value="{{ $encadrant->id }}" {{ $doctorant->id_encadrant == $encadrant->id ? 'selected' : '' }}>
                {{ $encadrant->nom }} {{ $encadrant->prenom }} - {{ $encadrant->grade }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
      <!-- valider -->
      <div style="margin-bottom: 20px;"></div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
      </div>
    </form><!-- End Profile Edit Form -->

    </section>
  </div>


  <!--Parcours Scolaire-->

  <div class="tab-pane fade pt-3" id="profile-settings">

<!-- Settings Form -->
<div class="tab-pane fade show active profile-overview" id="profile-overview">

<!-- Informations de scolarité -->
@foreach($scolarites as $scolarite)
            <div class="row">
                <div class="col-lg-3 col-md-4 label">{{ $scolarite->mois }}-{{ $scolarite->annee }} :</div>
                <div class="col-lg-9 col-md-8">
                {{ $scolarite->niveau }}  en  {{ $scolarite->specialite }}  avec mention  <strong>{{  $scolarite->mention_label }}</strong>
                </div>
            </div>
        @endforeach

    </div>
</div>
<!-- profil.blade.php -->

        
        <div class="tab-pane fade pt-3" id="profile-change-password">
    <div class="tab-pane fade show active profile-overview" id="profile-overview">

        <!-- Edit Parcours Scolaire Form -->
        <form id="editScolariteForm" method="POST" action="{{ route('doctorants.updateScolarite') }}">
            @csrf
            @method('PUT')

            <!-- Champ caché pour l'ID de la scolarité -->
            <input type="hidden" name="scolarite_id" id="scolarite_id" value="{{ $scolarite->id ?? '' }}">

            <!-- Champ caché pour l'ID du doctorant -->
            <input type="hidden" name="doctorant_id" id="doctorant_id" value="{{ $doctorant->id }}">

            <div class="row mb-3">
                <label for="scolariteSelect" class="col-md-4 col-lg-3 col-form-label text-blue">Choisir Parcours</label>
                <div class="col-md-8 col-lg-9">
                    <select class="form-select" id="scolariteSelect" name="parcours">
                        <option value="">Sélectionner un niveau</option>
                        @foreach($scolarites as $item)
                        <option value="{{ $item->id }}"
                                data-niveau="{{ $item->niveau }}"
                                data-mois="{{ $item->mois }}"
                                data-annee="{{ $item->annee }}"
                                data-specialite="{{ $item->specialite }}"
                                data-mention="{{ $item->mention_label }}"
                                @if(isset($scolarite) && $item->id == $scolarite->id) selected @endif>
                            {{ $item->niveau }}
                        </option>
                        @endforeach
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="niveau" class="col-md-4 col-lg-3 col-form-label text-blue">Niveau</label>
                <div class="col-md-8 col-lg-9">
                    <input name="niveau" type="text" class="form-control" id="niveau" value="{{ $scolarite->niveau ?? '' }}">
                </div>
            </div>

            <div class="row mb-3">
                <label for="mois" class="col-md-4 col-lg-3 col-form-label text-blue">Mois</label>
                <div class="col-md-8 col-lg-9">
                    <input name="mois" type="text" class="form-control" id="mois" value="{{ $scolarite->mois ?? '' }}">
                </div>
            </div>

            <div class="row mb-3">
                <label for="annee" class="col-md-4 col-lg-3 col-form-label text-blue">Année</label>
                <div class="col-md-8 col-lg-9">
                    <input name="annee" type="text" class="form-control" id="annee" value="{{ $scolarite->annee ?? '' }}">
                </div>
            </div>

            <div class="row mb-3">
                <label for="specialite" class="col-md-4 col-lg-3 col-form-label text-blue">Spécialité</label>
                <div class="col-md-8 col-lg-9">
                    <input name="specialite" type="text" class="form-control" id="specialite" value="{{ $scolarite->specialite ?? '' }}">
                </div>
            </div>

            <div class="row mb-3">
                <label for="mention" class="col-md-4 col-lg-3 col-form-label text-blue">Mention</label>
                <div class="col-md-8 col-lg-9">
                    <select class="form-select" id="mention" name="mention">
                        <option value="passable" @if(isset($scolarite) && $scolarite->mention == 'passable') selected @endif>Passable</option>
                        <option value="a_bien" @if(isset($scolarite) && $scolarite->mention == 'a_bien') selected @endif>Assez Bien</option>
                        <option value="bien" @if(isset($scolarite) && $scolarite->mention == 'bien') selected @endif>Bien</option>
                        <option value="tr_bien" @if(isset($scolarite) && $scolarite->mention == 'tr_bien') selected @endif>Très Bien</option>
                        <option value="excellent" @if(isset($scolarite) && $scolarite->mention == 'excellent') selected @endif>Excellent</option>
                    </select>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </div>
        </form><!-- End Edit Parcours Scolaire Form -->

    </div>
</div>

<style>
    .text-blue {
        color: rgba(1, 41, 112, 0.6);
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const scolariteSelect = document.getElementById('scolariteSelect');
        const scolariteIdInput = document.getElementById('scolarite_id');
        const niveauInput = document.getElementById('niveau');
        const moisInput = document.getElementById('mois');
        const anneeInput = document.getElementById('annee');
        const specialiteInput = document.getElementById('specialite');
        const mentionSelect = document.getElementById('mention');

        // Remplir les champs avec les informations de la scolarité sélectionnée
        function fillFormFields(selectedOption) {
            if (selectedOption) {
                scolariteIdInput.value = selectedOption.value;
                niveauInput.value = selectedOption.getAttribute('data-niveau') || '';
                moisInput.value = selectedOption.getAttribute('data-mois') || '';
                anneeInput.value = selectedOption.getAttribute('data-annee') || '';
                specialiteInput.value = selectedOption.getAttribute('data-specialite') || '';
                mentionSelect.value = selectedOption.getAttribute('data-mention') || '';
            } else {
                scolariteIdInput.value = '';
                niveauInput.value = '';
                moisInput.value = '';
                anneeInput.value = '';
                specialiteInput.value = '';
                mentionSelect.value = '';
            }
        }

        // Lorsqu'une scolarité est sélectionnée
        scolariteSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value === 'autre') {
                fillFormFields(null);
            } else {
                fillFormFields(selectedOption);
            }
        });

        // Initialiser les champs de formulaire avec la scolarité sélectionnée par défaut
        const initialSelectedOption = scolariteSelect.options[scolariteSelect.selectedIndex];
        if (initialSelectedOption) {
            fillFormFields(initialSelectedOption);
        }
    });
</script>




</section>
</div>







 














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







       
                    </div><!-- End tab-content -->

                </div><!-- End card-body -->
            </div><!-- End card -->

        </div><!-- End col-xl-8 -->
    </div><!-- End row -->
</section><!-- End section profile -->


</main><!-- End #main -->

@endsection
</body>
</html>