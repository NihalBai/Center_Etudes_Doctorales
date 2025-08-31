<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membre Profile</title>
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

                    <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
                 <center>
                    <h2>{{$membre->prenom}} {{$membre->nom}} </h2><br>
                    <h3>
                    @php
                                $latestGrade = $membre->latestGrade();
                            @endphp

                            @if($latestGrade)
                                {{ $latestGrade->nom }}
                            @else
                                No grade assigned
                            @endif
                    </h3>
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
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Membre de Jury</button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Rapporteur</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Mise à jour Membre</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                          
                        
                            <!-- Contenu de l'onglet About -->
                            <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nom </div>
                    <div class="col-lg-9 col-md-8">{{$membre->nom}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Prénom </div>
                    <div class="col-lg-9 col-md-8">{{$membre->prenom}}</div>
                  </div>
                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Grade(s)</div>
                    <div class="col-lg-9 col-md-8">
                    @php
                                $latestGrade = $membre->latestGrade();
                            @endphp

                            @if($latestGrade)
                                {{ $latestGrade->nom }}
                            @else
                                No grade assigned
                            @endif
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8">{{$membre->email}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Téléphone</div>
                    <div class="col-lg-9 col-md-8">{{$membre->tele}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Affiliation</div>
                    <div class="col-lg-9 col-md-8">
                    @if ($membre->faculte)
                       {{ $membre->name }}
                    @elseif ($membre->autre)
                       {{ $membre->autre->nom }} , {{ $membre->autre->ville }}
                    @endif
                    </div>
                  </div>

                

                        </div>

                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                            <!-- Contenu de l'onglet Membre de Jury -->

                            


<!-- =============================================================================================================== -->


<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Soutenances Evaluées</h5>
                    <div class="accordion" id="accordionSoutenances">
                        @foreach($soutenances as $index => $soutenance)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSoutenance{{$index}}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSoutenance{{$index}}" aria-expanded="false" aria-controls="collapseSoutenance{{$index}}">
                                    Le {{$soutenance->date}} à {{$soutenance->heure}}
                                </button>
                            </h2>
                            <div id="collapseSoutenance{{$index}}" class="accordion-collapse collapse" aria-labelledby="headingSoutenance{{$index}}" data-bs-parent="#accordionSoutenances">
                                <div class="accordion-body">
                                    <strong>Etat:</strong> {{$soutenance->etat}}
                                    <div class="profile-card pt-4">
                                        <h3><strong>Titre Initial:</strong> {{$soutenance->theses->first()->titreOriginal ?? 'N/A'}}</h3>
                                        <p><strong>Titre Final:</strong> {{$soutenance->theses->first()->titreFinal ?? 'N/A'}}</p>
                                        <p><strong>Formation Doctorale:</strong> {{$soutenance->theses->first()->formation ?? 'N/A'}}</p>
                                        <p><strong>Acceptation du directeur:</strong> {{ $soutenance->theses->first()->acceptationDirecteur ?? 'N/A' }}</p>
                                        <p><strong>Encadrant:</strong>
                                            @if($soutenance->encadrant)
                                                {{ $soutenance->encadrant->nom }} {{ $soutenance->encadrant->prenom }}
                                            @else
                                                - N/A
                                            @endif
                                        </p>
                                        <p><strong>Qualité D'évaluation:</strong> {{ $soutenance->evaluer->first()->qualite }}</p>
                                        <p><strong>Doctorant:</strong>
                                            @foreach($soutenance->doctorants as $doctorant)
                                                {{ $doctorant->prenom }} {{ $doctorant->nom }}
                                            @endforeach
                                        </p>
                                        <p><strong>Localisation:</strong> {{$soutenance->localisation->nom ?? 'N/A'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</div>
<div class="tab-pane fade pt-3" id="profile-settings">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thèses Validées</h5>
                        <div class="accordion" id="accordionTheses">
                            @if(!empty($theses))    
                                @foreach($theses as $index => $these)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThese{{$index}}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThese{{$index}}" aria-expanded="false" aria-controls="collapseThese{{$index}}">
                                            {{$these->titreOriginal}},
                                            {{$these->formation}}
                                        </button>

                                    </h2>
                                    <div id="collapseThese{{$index}}" class="accordion-collapse collapse" aria-labelledby="headingThese{{$index}}" data-bs-parent="#accordionTheses">
                                        <div class="accordion-body">
                                            <strong>Titre Original:</strong> {{$these->titreOriginal}} <br><br>
                                            <p><strong>Titre Final:</strong> {{$these->titreFinal ?? $these->titreOriginal}}</p>
                                            <p><strong>Doctorant:</strong>
                                                @if($these->doctorant)
                                                    {{ $these->doctorant->prenom }} {{ $these->doctorant->nom }}
                                                @else
                                                    - N/A
                                                @endif
                                            </p>
                                            <p><strong>Formation Doctorale:</strong> {{$these->formation}}</p>
                                            <p><strong>Encadrant:</strong>
                                                @if($these->encadrant)
                                                    {{ $these->encadrant->nom }} {{ $these->encadrant->prenom }}
                                                @else
                                                    - N/A
                                                @endif
                                            </p>
                                            <div class="my-2">
                                                <strong>Avis du rapporteur:</strong>
                                                <span>{{ $these->pivot->avis }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <a href="{{ $these->pivot->lien_rapport }}" class="btn btn-primary" download>Télécharger le rapport</a>
                                                <div>
                                                    @if ($these->pivot->status === 'Inactif')
                                                        <button class="btn btn-danger" disabled>{{ $these->pivot->status }}</button>
                                                    @else
                                                        <button class="btn btn-success" disabled>{{ $these->pivot->status }}</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p>Rien à Afficher Pour Le Moment!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>





                    
                       
                <div class="tab-pane fade pt-3" id="profile-change-password">
 <!-- Contenu de l'onglet Update Membre -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mise à jour Membre Informations</h5>
                <form action="{{ route('membres.update', $membre->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Form fields for editing membre information -->
                    <div class="row mb-3">
                        <label for="nom" class="col-md-4 col-lg-3 col-form-label">Nom</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="nom" type="text" class="form-control" id="nom" value="{{ $membre->nom }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="prenom" class="col-md-4 col-lg-3 col-form-label">Prénom</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="prenom" type="text" class="form-control" id="prenom" value="{{ $membre->prenom }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="email" type="email" class="form-control" id="email" value="{{ $membre->email }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="sex" class="col-md-4 col-lg-3 col-form-label">Sex</label>
                        <div class="col-md-8 col-lg-9">
                            <select name="sex" class="form-select" id="sex">
                                <option value="male" {{ $membre->sex == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $membre->sex == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="tele" class="col-md-4 col-lg-3 col-form-label">Tele</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="tele" type="text" class="form-control" id="tele" value="{{ $membre->tele }}">
                        </div>
                    </div>

                    

<div class="row mb-3">
    <label for="faculte" class="col-md-4 col-lg-3 col-form-label">Faculté</label>
    <div class="col-md-8 col-lg-9">
        <input name="faculte" type="text" class="form-control" id="faculte" value="{{ $membre->faculte->nom ?? '' }}">
    </div>
</div>

<div class="row mb-3">
    <label for="ville" class="col-md-4 col-lg-3 col-form-label">Ville</label>
    <div class="col-md-8 col-lg-9">
        <input name="ville" type="text" class="form-control" id="ville" value="{{ $membre->faculte->ville ?? '' }}">
    </div>
</div>

<div class="row mb-3">
    <label for="universite" class="col-md-4 col-lg-3 col-form-label">Université</label>
    <div class="col-md-8 col-lg-9">
        <input name="universite" type="text" class="form-control" id="universite" value="{{ $membre->faculte->universite->nom ?? '' }}">
    </div>
</div>


<div class="row mb-3">
    <label for="autre" class="col-md-4 col-lg-3 col-form-label">Autre</label>
    <div class="col-md-8 col-lg-9">
        <input name="autre" type="text" class="form-control" id="autre" value="{{ $membre->autre->nom ?? ' ' }}">
    </div>
</div>


<div class="row mb-3">
    <label class="col-md-4 col-lg-3 col-form-label">Grades</label>
    <div class="col-md-8 col-lg-9">
        @foreach($membre->grades as $grade)
            <div class="input-group mb-3">
                <input name="grades[{{ $grade->id }}]" type="text" class="form-control" value="{{ $grade->nom }}" readonly>
            </div>
        @endforeach
        <div id="new-grades-container"></div>
        <div class="input-group mb-3">
            <input name="new-grade" type="text" class="form-control" id="new-grade">
            <button type="button" class="btn btn-outline-primary add-grade">Add</button>
        </div>
    </div>
</div>


                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>






       
                    </div><!-- End tab-content -->

                </div><!-- End card-body -->
            </div><!-- End card -->

        </div><!-- End col-xl-8 -->
        
    </div><!-- End row -->
</section><!-- End section profile -->

</main><!-- End #main -->



@endsection
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('.add-grade').click(function() {
            var newGradeName = $('#new-grade').val();
            if (newGradeName) {
                var newGradeInput = $('<div class="input-group mb-3">' +
                                      '<input name="new-grades[]" type="text" class="form-control" value="' + newGradeName + '" readonly>' +
                                      '</div>');
                $('#new-grades-container').append(newGradeInput);
                $('#new-grade').val('').prop('disabled', true); // Clear and disable input field
                $(this).prop('disabled', true); // Disable the "Add" button
            }
        });
    });
</script>


