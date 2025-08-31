<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<base href="/public"> 
<body>
@extends('layouts.layout')

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
                    <h3>{{$membre->grade}}</h3>
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
                    <div class="col-lg-3 col-md-4 label">Grade</div>
                    <div class="col-lg-9 col-md-8">{{$membre->grade}}</div>
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
                       {{ $membre->faculte->nom }}-{{ $membre->faculte->ville }} , {{ $membre->faculte->universite->nom }}
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
                    <!-- Default Accordion -->
                    <div class="accordion" id="accordionExample">
                        @foreach($soutenances as $index => $soutenance)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{$index}}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$index}}" aria-expanded="true" aria-controls="collapse{{$index}}">
                                   Le&nbsp;&nbsp;&nbsp;&nbsp; {{$soutenance->date}}&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;{{$soutenance->heure}}
                                </button>
                            </h2>
                            <div id="collapse{{$index}}" class="accordion-collapse collapse show" aria-labelledby="heading{{$index}}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <strong>Etat: {{$soutenance->etat}}</strong>
                                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-left">
                                        <!-- Afficher les informations sur la thèse -->
                                       
                                        <h3><strong>Titre Initial:</strong>&nbsp;&nbsp;&nbsp;&nbsp;   {{$soutenance->titreOriginal}}</h3>
                                    
                                     @if(empty($soutenance->titreFinal))
                                       <p><strong>Titre Final: </strong>&nbsp;&nbsp;&nbsp;&nbsp;  {{$soutenance->titreOriginal}}</p>
                                     @else
                                       <p><strong>Titre Final: </strong>&nbsp;&nbsp;&nbsp;&nbsp;   {{$soutenance->titreFinal}}</p>
                                     @endif
                                       <p><strong>Formation Doctorale:</strong>&nbsp;&nbsp;&nbsp;&nbsp;    {{$soutenance->formation}}</p>
                                       <p><strong>Acceptation du directeur:</strong>&nbsp;&nbsp;&nbsp;&nbsp;    {{$soutenance->acceptationDirecteur}}</p>
                                       <p><strong>Encadrant: </strong> &nbsp;&nbsp;&nbsp;&nbsp;   {{$soutenance->nom_e}}  {{$soutenance->nom_e}}-{{$soutenance->grade}}</p>
                                       <p><strong>Qualité D'évaluation:</strong>&nbsp;&nbsp;&nbsp;&nbsp;   {{ $soutenance->qualite }}</p>
                                       <p><strong>Doctorant:</strong>&nbsp;&nbsp;&nbsp;&nbsp;    {{$soutenance->prenom_doc}} {{$soutenance->nom_doc}}</p>
                                       <p><strong>Localisation:</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{$soutenance->localisation}}</p>
                                      


                                        <!-- Afficher les informations sur le doctorant -->
                                        
                                        <!-- Ajouter d'autres informations sur la thèse et le doctorant si nécessaire -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div><!-- End Default Accordion Example -->
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================================================================================================= -->




                        </div>

                        <div class="tab-pane fade pt-3" id="profile-settings">

                            <!-- Contenu de l'onglet Rapporteur -->




                            <section class="section">

<div class="row">
  <div class="col-lg-12">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Thèses Validées </h5>

        <!-- Liste des Thèses Validées -->
        <div class="accordion" id="accordionExample">
      @if(!empty($theses))    
        @foreach($theses as $index => $these)
<div class="accordion-item">
  <h2 class="accordion-header" id="heading{{$index}}">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$index}}" aria-expanded="true" aria-controls="collapse{{$index}}">
      {{$these->titreOriginal}},&nbsp;&nbsp;&nbsp;&nbsp;{{$these->nom_e}}   {{$these->prenom_e}},&nbsp;&nbsp;&nbsp;&nbsp;{{$these->formation}}
     
      </button>
  </h2>
  <div id="collapse{{$index}}" class="accordion-collapse collapse show" aria-labelledby="heading{{$index}}" data-bs-parent="#accordionExample">
      <div class="accordion-body">
          <strong>Titre Original:</strong> &nbsp;&nbsp;&nbsp;&nbsp; {{$these->titreOriginal}} <br><br>
          @if(empty($these->titreFinal))
          <p><strong>Titre Final: </strong>&nbsp;&nbsp;&nbsp;&nbsp;  {{$these->titreOriginal}}</p>
          @else
          <p><strong>Titre Final: </strong>&nbsp;&nbsp;&nbsp;&nbsp;   {{$these->titreFinal}}</p>
          @endif
          <p><strong>Doctorant:</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{$these->prenom}} {{$these->nom}}</p>
          <p><strong>Formation Doctorale:</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{$these->formation}}</p>
          <p><strong>Encadrant: </strong> &nbsp;&nbsp;&nbsp;&nbsp;   {{$these->nom_e}}  {{$these->nom_e}}-{{$these->grade}}</p>
           <!-- Affichage de l'avis du rapporteur -->
           <div class="my-2">
          <strong>Avis du rapporteur:</strong>
          <span>{{ $these->avis }}</span>
          </div>
           <!-- Bouton pour télécharger le rapport -->
         <div class="d-flex justify-content-between">
    <a href="{{ $these->rapport }}" class="btn btn-primary" download>Télécharger le rapport</a>
    <!-- Affichage du statut du compte -->
    <div >
        @if ($these->status === 'Inactif')
            <button class="btn btn-danger" disabled>{{ $these->status }}</button>
        @else
            <button class="btn btn-success" disabled>{{ $these->status }}</button>
        @endif
    </div>
</div>
          
      </div>
  </div>
</div>
@endforeach
@else    <p>Rien à Afficher Pour Le Moment!</p>
@endif
        </div><!-- End Liste des Thèses Validées -->
      </div>
    </div>
  </div>
</div>
</section>




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
