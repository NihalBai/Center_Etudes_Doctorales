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
                        <h5 class="card-title">Les informations Arabs des membres </h5>
                        @if (session('success'))
                         <div class="alert alert-success">
                         
                             {{ session('success') }} .Pour impimer le Diplome <a href="{{ route('final-page', ['id' => $id_doctorant]) }}">Aller à la page finale</a>



                          </div>
                        @endif

                        <form id="resultForm" class="row g-3" method="POST" action="{{ route('membre-arabe.store') }}">
    @csrf
    @foreach ($membres as $evaluation)
        <input type="hidden" name="id[]" value="{{ $evaluation->membre->id }}">
        <h3>{{ $evaluation->membre->nom }}  {{ $evaluation->membre->prenom }}</h3>
        <!-- Display other member details as needed -->
        <div class="col-md-6">
            <label for="nom" class="form-label">Nom Arab</label>
            <input type="text" name="nom[]" class="form-control" value="{{ $evaluation->membre->membreArabe ? $evaluation->membre->membreArabe->nom : '' }}" required>
        </div>
        <div class="col-md-6">
            <label for="prenom" class="form-label">Prenom Arab</label>
            <input type="text" name="prenom[]" class="form-control" value="{{ $evaluation->membre->membreArabe ? $evaluation->membre->membreArabe->prenom : '' }}" required>
        </div>
        <div class="col-md-6">
            <label for="grade" class="form-label">Grade Arab</label>
            <input type="text" name="grade[]" class="form-control" value="{{ $evaluation->membre->membreArabe ? $evaluation->membre->membreArabe->grade : '' }}" required>
        </div>
        <div class="col-md-6">
            <label for="qualite" class="form-label">Qualite Arab</label>
            <input type="text" name="qualite[]" class="form-control" value="{{ $evaluation->membre->membreArabe ? $evaluation->membre->membreArabe->qualite : '' }}" required>
        </div>
        <br>
    @endforeach

    <div class="text-center">
        <button id="submitBtn" type="submit" class="btn btn-primary">Enregistrer</button>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="reset" class="btn btn-secondary">Annuler</button>
    </div>
</form>


                      <!-- End Multi Columns Form  -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- End #main -->


@endsection
