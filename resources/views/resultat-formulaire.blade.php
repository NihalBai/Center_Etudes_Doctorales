<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultats</title>
</head>
<body>
<base href="/public">
@extends($layoutFile)


@section('content')

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Resultats</h1>
        
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter Resultat</h5>
                        @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }} <a href="{{ route('autre-information', ['id_soutenance' => $soutenance->id]) }}">Cliquez ici</a> pour compléter les informations.
    </div>
@endif



                       
                        <!-- Multi Columns Form  -->
                        <form id="resultForm" class="row g-3" method="POST" action="{{ route('resultats.store', ['id' => $soutenance->id]) }}">
                            @csrf
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Specialite</label>
                                <input type="text" class="form-control"  name="specialite" required>
                            </div>

                            <div class="col-md-6">
                                <label for="formationDoctorale" class="form-label">Formation Doctorale</label>
                                <input type="text" class="form-control"  name="formationDoctorale" required>
                            </div>

                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Titre final </label>
                                <input type="text" class="form-control"  name="titreFinal" required>
                            </div>
                           
                            <div class="col-md-6">
                                <label for="observation" class="form-label">Observation</label>
                                <select class="form-control" id="observation" name="observation" required>
                                    <option value="Valider">Valider</option>
                                    <option value="Rattraper">Rattraper</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="mention" class="form-label">Mention</label>
                                <select class="form-control" id="mention" name="mention" required>
                                    <option value="Très Honorable">Très Honorable</option>
                                    <option value="Honorable">Honorable</option>
                                </select>
                            </div>
                           
                           
                            <div class="text-center">
                                <button id="submitBtn" type="submit" class="btn btn-primary">Enregistrer</button>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="reset" class="btn btn-secondary">Annuler</button>
                            </div>
                        </form>
                        <!-- End Multi Columns Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->


@endsection
