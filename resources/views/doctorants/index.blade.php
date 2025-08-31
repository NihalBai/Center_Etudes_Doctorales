<!-- resources/views/demandes/index.blade.php -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

@extends($layoutFile)

@section('content')
<main id="main" class="main">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Bienvenue!</h5>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Membres</a></li>
                    <li class="breadcrumb-item active">Doctorants</li>
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
                        <h5 class="card-title">Liste de doctorants</h5>
                        <div style="margin-bottom: 40px;"></div>
                        <a href="{{ route('doctorants.create') }}" class="btn btn-primary">Nouveau Doctorant</a>

                        <!-- Table with stripped rows  -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>CINE</th>
                                    <th>Sexe</th>
                                    <th>Date de naissance</th>
                                    <th>Encadrant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctorants as $doctorant)
                                    <tr class="doctorant-row" data-url="{{ route('doctorants.profil', ['id' => $doctorant->id]) }}" style="cursor: pointer;">
                                        <td>{{$doctorant->id}}</td>
                                        <td>{{$doctorant->nom}}</td>
                                        <td>{{$doctorant->prenom}}</td>
                                        <td>{{$doctorant->CINE}}</td>
                                        <td>
                                            @if ($doctorant->sex === 'male')
                                                Homme
                                            @elseif ($doctorant->sex === 'female')
                                                Femme
                                            @else
                                                Autre
                                            @endif
                                        </td>
                                        <td>{{$doctorant->date_de_naissance}}</td>
                                        <td>{{$doctorant->encadrant->nom}} {{$doctorant->encadrant->prenom}}</td>                                   
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.doctorant-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.url;
            });
        });
    });
</script>
@endsection
