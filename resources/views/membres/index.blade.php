@extends('layouts.layout')

@section('content')


    <main id="main" class="main">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bienvenue!</h5>

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="bi bi-house-door"></i></a></li>
                        <li class="breadcrumb-item"><a href="#">Membres</a></li>
                        <li class="breadcrumb-item active">Voir</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-18">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Liste des Membres</h5>
                            <a href="{{route('membres.create')}}" class="btn btn-primary">Membre </a>
                            


                        

                            <!-- Table with stripped rows  -->
                            <table class="table datatable" >
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Affiliation</th>
                                        <th>Sexe</th>
                                        <th>Email</th>
                                        <th>Grade</th>
                                        <th>Profil</th>
                                       
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($membres as $membre)
    <tr class="membre-row" data-id="{{$membre->id}}">
        <td>{{$membre->id}}</td>
        <td>{{$membre->nom}}</td>
        <td>{{$membre->prenom}}</td>
        <td>
            @if ($membre->faculte)
                {{ $membre->faculte->nom }}  {{ $membre->faculte->ville }} , {{ $membre->faculte->universite->nom }}
            @elseif ($membre->autre)
                {{ $membre->autre->nom }} , {{ $membre->autre->ville }}
            @endif
        </td>


        <td>{{$membre->sex}}</td>
        <td>{{$membre->email}}</td>
        <td>{{$membre->grade}}</a></td>
        <td><a href="{{ route('membres.profil', ['id' => $membre->id]) }}">Voir</a></td>

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

@endsection

<style>
   
    .selected-row {
    background-color: bisque; /* Couleur de fond lorsque la ligne est sélectionnée */
}

</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var membreRows = document.querySelectorAll(".membre-row");

        membreRows.forEach(function(row) {
            row.addEventListener("click", function() {
                var membreId = this.getAttribute("data-id");
                // Remplacez 'URL_MODIFICATION' par l'URL de votre page de profil avec l'identifiant du membre
                window.location.href = 'membres/'+ membreId+'/profil' ;
            });
        });
    });

</script>
